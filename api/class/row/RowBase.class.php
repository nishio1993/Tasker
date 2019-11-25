<?php
include_once('class/DBFacade.class.php');
require_once('class/Base.class.php');
/**
 * ローベースクラス
 */
abstract class RowBase extends Base {
    protected $data = [];
    const FIELD = [];
    const PRIMARY_KEY = [];

    /**
     * コンストラクタ
     * 
     * 継承クラスのプロパティを引数から設定する。
     * 引数はColumnBase継承クラス配列もしくはKeyValue形式であること。
     *
     * @param array $columnList
     */
    public function __construct(array $keyValue = []) {
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
    public function __get(string $key) {
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
    public function __set(string $key, $value) : void {
        $this->data[$key] = $value;
    }

    /**
     * 連想配列化
     * 
     * Key=カラム名、Value=値の連想配列を返却する。
     *
     * @return  array
     */
    public function toArray() : array {
        $tableName = get_called_class();
        return $this->data;
    }

    /**
     * 主キー配列取得
     * 
     * 主キーカラム名の配列を返却する。
     *
     * @return array
     */
    public static function getPrimaryKey() : array {
        return PRIMARY_KEY;
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
    protected static function select(array $select = [], array $where = [], array $orderby = []) {
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

        $placeHolder = DBFacade::CreatePlaceHolder($where);
        $result = DBFacade::Execute(join("\n", $sql), $placeHolder);

        //結果が無い場合[]返却
        if ($result === []) {
            return [];
        //1件の場合KeyValue返却
        } else if (count($result) === 1) {
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
                    $columnList[] = new $key($value);
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
    protected function insert() : int {
        $sql = [];
        $tableName = get_called_class();
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
    protected function update() : int {
        $tableName = get_called_class();
        $propertyList = $this->data;
        $sql = [];
        $sql[] = "UPDATE ".$tableName;
        $sql[] = "SET";
        $tmp = [];
        $propertyList = [];
        foreach($tableName::FIELD as $key){
            if (isset($this->data[$key])) {
                $tmp[] = $key." = :".$key;
                $propertyList[$key] = $this->data[$key];
            }
        }
        $sql[] = join(",", $tmp);
        $sql[] = "WHERE";
        $tmp = [];
        foreach($tableName::PRIMARY_KEY as $primaryKey) {
            $tmp[] = $primaryKey." = :".$primaryKey;
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
    protected function delete() : int {
        $tableName = get_called_class();
        $placeHolder = [];
        $sql = [];
        $sql[] = "DELETE FROM ".$tableName;
        $sql[] = "WHERE\n    ";
        $tmp = [];
        $placeHolder = [];
        foreach($tableName::PRIMARY_KEY as $primaryKey) {
            $tmp[] = $primaryKey." = :".$primaryKey;
            $placeHolder[$primaryKey] = $this->data[$primaryKey];
        }
        $sql[] = join("\nAND ", $tmp);
        $placeHolder = DBFacade::CreatePlaceHolder($placeHolder);
        return DBFacade::Execute(join("\n", $sql), $placeHolder);
    }

    /**
     * テーブルクラス配列返却関数
     * 
     * テーブルクラス配列を返却する。
     *
     * @param   array $result テーブル形式配列
     * @return  array マスタクラス配列
     */
    protected static function returnRowList(array $result) : array {
        $rowList = [];
        $tableName = get_called_class();
        foreach($result as $row){
            $rowList[] = new $tableName($row);
        }
        return $rowList;
    }

    /**
     * カラム一覧返却関数
     * 
     * テーブルクラスをテーブル形式配列に変換して返却する。
     *
     * @return  array テーブル形式配列
     */
    public static function returnColumnList() : array {
        return array_keys(get_class_vars());
    }
}