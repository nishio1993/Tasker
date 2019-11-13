<?php
final class DBFacade {
    private static $pdo;

    /**
     *  DBコネクション獲得関数
     *
     *  PDOクラスを返却する。
     *
     *  @param  array   $setting    接続設定
     *  @return PDO
     */
    public static function Connect(array $setting = []) : PDO{
        if (isset(self::$pdo)) {
            return self::$pdo;
        }

        //接続情報取得
        $ini_array = parse_ini_file("../../.env");
        $db        = $ini_array['connect']['rdb'];
        $dbname    = $ini_array['connect']['dbname'];
        $host      = $ini_array['connect']['host'];
        $port      = $ini_array['connect']['port'];
        $charset   = $ini_array['connect']['charset'];
        $user      = $ini_array['connect']['user'];
        $password  = $ini_array['connect']['password'];

        //コネクション取得
        $dsn = sprintf("%s:dbname=%s;host=%s;port=%s;charset=%s", $db, $dbname, $host, $port, $charset);
        self::$pdo = new PDO($dsn, $user, $password);
        if ($setting === []){
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } else {
            foreach($setting as $key => $value){
                self::$pdo->setAttribute($key, $value);
            }
        }

        return self::$pdo;
    }

    /*************
    * DB切断
    **************/
    public static function DisConnect(){
        self::$pdo = null;
    }

    /*************
    * SQL実行共通関数
    * $sql  :実行SQL文
    * return:SELECTの場合取得結果、INSERT・UPDATE・DELETEの場合影響件数、SQL実行に失敗した場合False
    **************/
    public static function Execute(string $sql, array $bindValue = array()){
        if ($bindValue === array() ){
            return self::ExecuteByQuery($sql);
        } else {
            return self::ExecuteByPrepareStatement($sql, $bindValue);
        }
    }

    /*************
    * ユーザー入力値がないSQL実行
    * $sql  :実行SQL文
    * return:SELECTの場合取得結果、INSERT・UPDATE・DELETEの場合影響件数、SQL実行に失敗した場合False
    **************/
    private static function ExecuteByQuery(string $sql){
        try {
            $statement = self::Connect()->query($sql);
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
            Logger::ERROR("ExecuteByQuery error: ".$statement->getMessage()." : {$sql}");
            throw new RuntimeException($e->getMessage());
        }
    }

    /*************
    * ユーザー入力値があるSQL実行、手動クオートの必要なし
    * $sql      :実行SQL文
    * $bindValue:プレースホルダ
    * return    :SELECTの場合取得結果、INSERT・UPDATE・DELETEの場合影響行数、SQL実行に失敗した場合False
    **************/
    private static function ExecuteByPrepareStatement(string $sql, array $placeHolder){
        try {
            $statement = self::Connect()->prepare($sql);
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
            Logger::ERROR("ExecuteByPrepareStatement error: ".$statement->getMessage()." : {$sql}");
            return false;
        }
    }

    /**
     *  プレースホルダ取得関数
     *
     *  KeyValue形式の引数から作成したプレースホルダを返却する。
     *
     *  @param  array   $keyValue
     *  @return PDO
     */
    public static function CreatePlaceHolder(array $keyValue) : array {
        $placeHolder = array();
        foreach($keyValue as $key => $value){
            if (is_array($value)) {
                foreach($value as $associativeKey => $associativeValue) {
                    $placeHolder[] = [
                        'parameter' => ":".$associativeKey,
                        'value'     => $associativeValue,
                        'type'      => self::GetPDOParam($associativeValue)
                    ];
                }
            } else {
                $placeHolder[] = [
                    'parameter' => ":".$key,
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
     *  引数の型に応じたPDOパラメータタイプを返却する。
     *
     *  @param  mixed   $value
     *  @return PDO::PARAM
     */
    public static function GetPDOParam(mixed $value) : int{
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

    /*************
    * トランザクション開始
    * return:トランザクション開始に成功した場合True、失敗した場合False
    **************/
    public static function Transaction() : bool{
        self::Connect()->beginTransaction();
    }

    /*************
    * コミット
    * return:コミットに成功した場合True、失敗した場合False
    **************/
    public static function Commit() : bool{
        self::Connect()->commit();
    }

    /*************
    * ロールバック
    * return:ロールバックに成功した場合True、失敗した場合False
    **************/
    public static function Rollback() : bool{
        self::Connect()->rollback();
    }
}