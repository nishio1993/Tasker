<?php
require_once('autoloader.php');

final class DBFacade {
    private static $pdo;

    /**
     *  DBコネクション生成関数
     *
     * @param  array   $setting
     * @return void
     */
    public static function Connect(array $setting = []): void
    {
        if (isset(self::$pdo)) {
            return;
        }

        //接続情報取得
        $ini_array  = parse_ini_file("../.env");
        $rdb        = $ini_array['rdb'];
        $dbname     = $ini_array['db_name'];
        $host       = $ini_array['db_host'];
        $port       = $ini_array['db_port'];
        $charset    = $ini_array['db_charset'];
        $user       = $ini_array['db_user'];
        $password   = $ini_array['db_password'];

        //コネクション取得
        $dsn = sprintf("%s:dbname=%s;host=%s;port=%s;charset=%s", $rdb, $dbname, $host, $port, $charset);
        self::$pdo = new PDO($dsn, $user, $password);
        if ($setting === []){
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } else {
            foreach($setting as $key => $value){
                self::$pdo->setAttribute($key, $value);
            }
        }

        return;
    }

    /**
     * DBコネクション破棄関数
     *
     * @return void
     */
    public static function DisConnect(): void
    {
        self::$pdo = null;
    }

    /**
     * SQL実行共通関数
     *
     * @param string $sql
     * @param array $placeHolder
     * @return mixed
     */
    public static function Execute(string $sql, array $placeHolder = [])
    {
        if ($placeHolder === [] ){
            return self::ExecuteByQuery($sql);
        } else {
            return self::ExecuteByPrepareStatement($sql, $placeHolder);
        }
    }

    /**
     * Query実行関数
     * 
     * SELECTを実行した場合はテーブル形式配列を返却する。
     * INSERT・UPDATE・DELETEを実行した場合は影響件数を返却する。
     * 
     * @param  string $sql
     * @return mixed
     */
    private static function ExecuteByQuery(string $sql)
    {
        try {
            $statement = self::$pdo->query($sql);
            switch (strtoupper(substr(trim($sql),0,6))){
                case "SELECT":
                    $result = $statement->fetchAll();
                    $count  = count($result);
                    Logger::SQL($sql, $count);
                    return $result;
                case "INSERT":
                case "UPDATE":
                case "DELETE":
                    $result = $statement->rowCount();
                    Logger::SQL($sql, $result);
                    return $result;
                default:
                    throw new RuntimeException('Not correct SQL');
            }
        } catch (Exception $e) {
            Logger::ERROR("ExecuteByQuery error: ".$e->getMessage()." : {$sql}");
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * PrepareStatement実行関数
     * 
     * SELECTを実行した場合はテーブル形式配列を返却する。
     * INSERT・UPDATE・DELETEを実行した場合は影響件数を返却する。
     * 
     * @param string $sql
     * @param array $pladeHolder
     * @return mixed
     */
    private static function ExecuteByPrepareStatement(string $sql, array $placeHolder)
    {
        try {
            $statement = self::$pdo->prepare($sql);
            foreach($placeHolder as $value){
                $statement->bindValue($value['parameter'], $value['value'], $value['type']);
            }

            $statement->execute();
            //$sql = $statement->activeQueryString();

            switch (strtoupper(substr(trim($sql),0,6))){
                case "SELECT":
                    $result = $statement->fetchAll();
                    $count  = count($result);
                    Logger::SQL($sql, $count, $placeHolder);
                    return $result;
                case "INSERT":
                case "UPDATE":
                case "DELETE":
                    $result = $statement->rowCount();
                    Logger::SQL($sql, $result, $placeHolder);
                    return $result;
                default:
                    throw new RuntimeException('Not correct SQL');
            }
        } catch (Exception $e) {
            Logger::ERROR("ExecuteByPrepareStatement error: ".$e->getMessage()." : {$sql}");
            return false;
        }
    }

    /**
     *  プレースホルダ生成関数
     *
     *  KeyValue形式またはテーブル形式配列の$arrayから作成したプレースホルダを返却する。
     *
     *  @param  array   $array
     *  @return array
     */
    public static function CreatePlaceHolder(array $array): array
    {
        $placeHolder = [];
        foreach($array as $key => $value){
            if (is_array($value)) {
                if (is_int($key)) {
                    foreach($value as $associativeKey => $associativeValue) {
                        if ($associativeKey[0] !== ':') {
                            $associativeKey = ":{$associativeKey}";
                        }
                        $placeHolder[] = [
                            'parameter' => $associativeKey,
                            'value'     => $associativeValue,
                            'type'      => self::GetPDOParam($associativeValue)
                        ];
                    }
                } else {
                    $count = count($value);
                    if ($key[0] !== ':') {
                        $key = ":{$key}";
                    }
                    for($i = 0; $i < $count; ++$i) {
                        $placeHolder[] = [
                            'parameter' => "{$key}{$i}",
                            'value'     => $value[$i],
                            'type'      => self::getPDOParam($value[$i])
                        ];
                    }
                }
            } else {
                if ($key[0] !== ':') {
                    $key = ":{$key}";
                }
                $placeHolder[] = [
                    'parameter' => $key,
                    'value'     => $value,
                    'type'      => self::GetPDOParam($value)
                ];
            }
        }
        return $placeHolder;
    }

    /**
     *  PDOパラメータタイプ取得関数
     *
     *  $valueの型に応じたPDOパラメータタイプを返却する。
     *
     *  @param  mixed   $value
     *  @return PDO::PARAM
     */
    public static function GetPDOParam($value): int
    {
        $type = gettype($value);
        switch ($type) {
            case "string" :
            case "double" :
                return PDO::PARAM_STR;
            case "integer" :
                return PDO::PARAM_INT;
            case "boolean" :
                return PDO::PARAM_BOOL;
            case "NULL" :
                return PDO::PARAM_NULL;
            default :
                return PDO::PARAM_STR;
        }
    }

    /**
     * トランザクション開始
     *
     * @return void
     */
    public static function Transaction(): void
    {
        self::$pdo->beginTransaction();
    }

    /**
     * コミット実行
     *
     * @return void
     */
    public static function Commit(): void
    {
        self::$pdo->commit();
    }

    /**
     * ロールバック実行
     *
     * @return void
     */
    public static function Rollback(): void
    {
        self::$pdo->rollback();
    }
}