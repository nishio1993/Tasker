<?php
require_once('autoloader.php');

/**
 * 抽象レコードクラス
 * 
 * テーブル名のクラスで継承する
 * FIELD定数にカラム一覧、KEY定数に主キーを記載する
 */
abstract class Record
{
    protected $data = [];
    const FIELD = [];
    const KEY = [];

    /**
     * コンストラクタ
     * 
     * 継承クラスのプロパティを引数から設定する。
     * 引数はColumnBase継承クラス配列もしくはKeyValue形式であること。
     *
     * @param array $columnList
     */
    public function __construct(array $keyValue = [])
    {
        if ($keyValue !== []) {
            foreach($keyValue as $key => $value) {
                $this->data[$key] = $value;
            }
        }
    }

    /**
     * Getter
     * 
     * プロパティ名を指定して取得する。
     *
     * @param   string  $key     プロパティ名
     * @return  mixed
     */
    public function __get(string $key)
    {
        return  isset($this->data[$key])
                ? $this->data[$key]
                : null;
    }

    /**
     * Setter
     * 
     * プロパティ名と値を指定して設定する。
     *
     * @param   string    $key      プロパティ名
     * @param   mixed     $value    値
     * @return  void
     */
    public function __set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * 連想配列化
     * 
     * Key=カラム名、Value=値の連想配列を返却する。
     *
     * @return  array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * カラム配列取得
     * 
     * カラム名の配列を返却する。
     *
     * @return array
     */
    public static function getField(): array
    {
        $tableName = get_called_class();
        return $tableName::FIELD;
    }

    /**
     * 主キー配列取得
     * 
     * 主キーの配列を返却する。
     *
     * @return array
     */
    public static function getPrimaryKey(): array
    {
        $tableName = get_called_class();
        return $tableName::KEY;
    }

    /**
     * Select
     * 
     * DBFacadeを用いて、継承クラスのテーブルデータを取得する。
     * $selectは列名配列。空配列の場合全列取得する。
     * $whereはKeyValue配列。空配列の場合全取得する。Valueが配列ならIN句、違うなら＝。
     * $orderbyはKeyValue配列。Keyに列名、ValueにASC・DESC指定。空配列の場合指定無し。
     * 返り値は結果が0件の場合空配列、1件の場合継承クラス、2件以上の場合継承クラス配列。
     *
     * @param array $select
     * @param array $where
     * @param boolean $orderby
     * @return array
     */
    protected static function select(array $select = [], array $where = [], array $orderby = [])
    {
        $tableName = get_called_class();
        $sql = [];
        $sql[] = "SELECT";
        //SELECT
        if ($select !== []) {
            $tmp = [];
            foreach($select as $col) {
                $tmp[] = "    ".$col;
            }
            $sql[] = join(",\n", $tmp);
        } else {
            $sql[] = "    *";
        }
        //FROM
        $sql[] = "FROM";
        $sql[] = "    {$tableName}";
        //WHERE
        if ($where !== []) {
            $sql[] = "WHERE";
            $tmp = [];
            foreach($where as $col => $val) {
                if (is_array($val)) {
                    $array = [];
                    $count = count($val);
                    for ($i = 0; $i < $count; ++$i) {
                        $array[] = ":{$col}$i";
                    }
                    $tmp[] = str_replace('ele', join(',', $array), "{$col} IN ( ele )");
                } else {
                    $tmp[] = "{$col} = :{$col}";
                }
            }
            $sql[] = join("\nAND ", $tmp);
        }
        //ORDER BY
        if ($orderby !== []) {
            $tmp = [];
            foreach($orderby as $col => $order) {
                $tmp[] = "{$col} {$order}";
            }
            $sql[] = join("\n,", $tmp);
        }

        //SQL実行
        $placeHolder = DBFacade::CreatePlaceHolder($where);
        $result = DBFacade::Execute(join("\n", $sql), $placeHolder);

        //結果が無い場合[]返却
        if ($result === []) {
            return [];
        //1件の場合KeyValue返却
        } elseif (count($result) === 1) {
            $columnList = [];
            foreach($result[0] as $key => $value) {
                $columnList[$key] = ($value);
            }
            return new $tableName($columnList);
        //2件以上の場合テーブル配列返却
        } else {
            $rowList = [];
            foreach($result as $row) {
                $columnList = [];
                foreach($row as $key => $value) {
                    $columnList[$key] = $value;
                }
                $rowList[] = new $tableName($columnList);
            }
            return $rowList;
        }
    }

    /**
     * Insert
     * 
     * プロパティの値を継承クラスのテーブルに追加する。
     * 返り値は影響件数。
     *
     * @return int
     */
    protected function insert(): int
    {
        $tableName = get_called_class();
        $datetime = new DateTimeImmutable();
        if (in_array('CREATE_DATETIME', $tableName::FIELD)) {
            $this->CREATE_DATETIME = $datetime->format('Y-m-d H:i:s');
        }
        if (in_array('UPDATE_DATETIME', $tableName::FIELD)) {
            $this->UPDATE_DATETIME = $datetime->format('Y-m-d H:i:s');
        }
        if (in_array('CREATE_USER', $tableName::FIELD)) {
            $this->CREATE_USER = isset($_SESSION['USER']['MAIL'])? $_SESSION['USER']['MAIL']: 'unknown';
        }
        if (in_array('UPDATE_USER', $tableName::FIELD)) {
            $this->UPDATE_USER = isset($_SESSION['USER']['MAIL'])? $_SESSION['USER']['MAIL']: 'unknown';
        }

        $sql = [];
        $sql[] = "INSERT INTO {$tableName}";
        $sql[] = "(";
        $col = [];
        $val = [];
        $propertyList = [];
        foreach($tableName::FIELD as $key) {
            if (isset($this->data[$key])) {
                $col[] = $key;
                $val[] = ":".$key;
                $propertyList[$key] = $this->data[$key];
            }
        }
        $sql[] = join(",", $col);
        $sql[] = ")";
        $sql[] = "VALUES";
        $sql[] = "(";
        $sql[] = join(",", $val);
        $sql[] = ")";
        $placeHolder = DBFacade::CreatePlaceHolder($propertyList);
        return DBFacade::Execute(join("\n", $sql), $placeHolder);
    }

    /**
     * Update
     * 
     * プロパティの値で、PrimaryKeyを条件に継承クラスのテーブルを更新する。
     * 返り値は影響件数。
     *
     * @param array $where  列名配列
     * @return int
     */
    protected function update(): int
    {
        $tableName = get_called_class();
        $datetime = new DateTimeImmutable();
        if (in_array('UPDATE_DATETIME', $tableName::FIELD)) {
            $this->UPDATE_DATETIME = $datetime->format('Y-m-d H:i:s');
        }
        if (in_array('UPDATE_USER', $tableName::FIELD)) {
            $this->UPDATE_USER = isset($_SESSION['USER']['MAIL'])? $_SESSION['USER']['MAIL']: 'unknown';
        }

        $sql = [];
        $sql[] = "UPDATE ".$tableName;
        $sql[] = "SET";
        $tmp = [];
        $propertyList = [];
        foreach($tableName::FIELD as $key){
            if (isset($this->data[$key]) && !in_array($key, $tableName::KEY)) {
                $tmp[] = $key." = :".$key;
                $propertyList[$key] = $this->data[$key];
            }
        }
        $sql[] = join(",", $tmp);
        $sql[] = "WHERE";
        $tmp = [];
        foreach($tableName::KEY as $primaryKey) {
            $tmp[] = $primaryKey." = :".$primaryKey;
            $propertyList[$primaryKey] = $this->data[$primaryKey];
        }
        $sql[] = join("\nAND ", $tmp);
        $placeHolder = DBFacade::CreatePlaceHolder($propertyList);
        return DBFacade::Execute(join("\n", $sql), $placeHolder);
    }

    /**
     * Delete
     * 
     * PrimaryKeyを条件に継承クラスのテーブルを削除する。
     * 返り値は影響件数。
     *
     * @param array $key    列名配列
     * @return void
     */
    protected function delete(): int
    {
        $tableName = get_called_class();
        $placeHolder = [];
        $sql = [];
        $sql[] = "DELETE FROM ".$tableName;
        $sql[] = "WHERE\n    ";
        $tmp = [];
        $placeHolder = [];
        foreach($tableName::KEY as $primaryKey) {
            $tmp[] = $primaryKey." = :".$primaryKey;
            $placeHolder[$primaryKey] = $this->data[$primaryKey];
        }
        $sql[] = join("\nAND ", $tmp);
        $placeHolder = DBFacade::CreatePlaceHolder($placeHolder);
        return DBFacade::Execute(join("\n", $sql), $placeHolder);
    }
}