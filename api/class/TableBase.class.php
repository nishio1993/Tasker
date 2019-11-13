<?php
include_once('./DBFacade.class.php');

/**
 * テーブルベースクラス
 */
abstract class TableBase {
    /**
     * コンストラクタ
     * 
     * TableBaseクラスと継承クラスのプロパティを引数から設定する。
     * 引数は列名・値のKeyValue形式であること。
     * 引数のValueはクラスでもプリミティブでもどちらでもよい。
     *
     * @param array $row 列名・値のKeyValue
     */
    public function __construct(array $keyValue) {
        foreach($keyValue as $key => $value) {
            if (gettype($value) === 'object') {
                $this->$key->Set($value);
            } else {
                $this->$key->Set(new $key($value));
            }
        }
    }

    /**
     * Getter
     * 
     * プロパティ名を指定して取得する。
     *
     * @param   string  $name   プロパティ名
     * @return  mixed   プロパティ
     */
    public function Get(string $name){
        return  property_exists($this, $name)
                ? $this->$name
                : null;
    }

    /**
     * Setter
     * 
     * プロパティ名と値を指定して設定する。
     *
     * @param   string    $name     プロパティ名
     * @param   mixed     $value    値
     * @return  void
     */
    public function Set(string $name, $value){
        if (!property_exists($this, $name)) {
            throw new RuntimeException('{$name}は存在しません。');
        } else if (isset($this->$name)) {
            $this->$name->Set($value);
        } else {
            $this->$name = new $name($value);
        }
    }

    /**
     * 連想配列化
     * 
     * Key=カラム名、Value=値の連想配列にして自身を返却する。
     *
     * @return  array
     */
    public function ToArray() : array {
        return get_class_vars();
    }

    /**
     * Select
     * 
     * DBFacadeを用いて、継承クラスのテーブルデータを取得する。
     * 第一引数が空配列の場合全列取得する。
     * 第二引数が空配列の場合全取得する。
     * 第三引数がtrueの場合未削除のみ取得する。初期値はtrue。
     *
     * @param array $select
     * @param array $where
     * @param boolean $onlyNotDeleted
     * @return void
     */
    public abstract static function Select(array $select = [], array $where = [], array $orderby = []){
        $tableName = get_called_class();
        $placeHolder = DBFacade::CreatePlaceHolder($where);
        $sql = [];
        $sql[] = "SELECT";
        if ($select !== []) {
            $tmp = [];
            foreach($select as $col) {
                $tmp[] = $col;
            }
            $sql[] = join(",\n", $tmp);
        } else {
            $sql[] = "    *";
        }
        $sql[] = "FROM";
        $sql[] = "    ".$tableName;
        $sql[] = "WHERE";
        $sql[] = "1 = 1";
        if ($where !== []) {
            $tmp = [];
            foreach($where as $col => $val) {
                $tmp[] = $col." = :".$col;
            }
            $sql[] = join("\nAND ", $tmp);
        }
        if ($orderby !== []) {
            $tmp = [];
            foreach($orderby as $col => $order) {
                $tmp[] = "{$col} {$order}";
            }
            $sql[] = join("\n,", $tmp);
        }
        return DBFacade::Execute(join("\n", $sql), $placeHolder);
    }

    /**
     * Insert
     * 
     * プロパティの値を継承クラスのテーブルに追加する。
     *
     * @return void
     */
    public function Insert() {
        $tableName = get_called_class();
        $propertyList = get_class_vars();
        $placeHolder = DBFacade::CreatePlaceHolder($propertyList);
        $sql = [];
        $col = [];
        $val = [];
        $sql[] = "INSERT INTO ".$tableName;
        $sql[] = "(";
        foreach($propertyList as $col => $val){
            $col[] = $col;
            $val[] = ":".$col;
        }
        $sql[] = join(",", $col);
        $sql[] = ")";
        $sql[] = "VALUES";
        $sql[] = "(";
        $sql[] = join(",", $val);
        $sql[] = ")";
        return DBFacade::Execute(join("\n", $sql), $placeHolder);
    }

    /**
     * Update
     * 
     * プロパティの値で継承クラスのテーブルを更新する。
     * 第一引数で指定したプロパティを条件に更新する。
     * 第一引数の指定がない場合、主キーを条件に更新する。
     *
     * @param array $where  列名配列
     * @return void
     */
    public function Update(array $where = []) {
        $tableName = get_called_class();
        $propertyList = get_class_vars();
        $placeHolder = DBFacade::CreatePlaceHolder($propertyList);
        $sql = [];
        $sql[] = "UPDATE ".$tableName;
        $sql[] = "SET";
        $tmp = [];
        foreach($propertyList as $col => $val){
            $tmp[] = $col." = :".$col;
        }
        $sql[] = join(",", $tmp);
        $sql[] = "WHERE";
        $tmp = [];
        if ($where !== [] ) {
            foreach($where as $col){
                $type = gettype($this->$col);
                switch ($type) {
                    case "integer" :
                    case "boolean" :
                        $tmp[] = $col." = ".$this->$col;
                        break;
                    case "string" :
                    case "double" :
                        $tmp[] = $col." = '".$this->$col."'";
                        break;
                    case "NULL" :
                        $tmp[] = $col." = NULL";
                        break;
                    default :
                        $tmp[] = $col." = '".$this->$col."'";
                }
            }
        } else {
            $config = XML::Read('ini/mstmnt_common.xml');
            $table
        }
        $sql[] = join("\nAND ", $tmp);
        return DBFacade::Execute(join("\n", $sql), $placeHolder);
    }

    /**
     * Delete
     * 
     * 削除フラグを1に更新する。
     * 第一引数で指定したプロパティの値を持つ行が対象。
     *
     * @param array $key    列名配列
     * @return void
     */
    public function Delete(array $key) {
        $tableName = get_called_class();
        $placeHolder = [];
        $sql = [];
        $sql[] = "UPDATE ".$tableName;
        $sql[] = "SET";
        $sql[] = "    DELFLG  = 1";
        $sql[] = "   ,DELDATE = ".$DELDATE;
        $sql[] = "WHERE";
        $tmp = [];
        foreach($key as $col){
            $tmp[] = $col." = :".$col;
            $placeHolder[$col]['parameter'] = ":".$col;
            $placeHolder[$col]['value'] = $this->$col;
            $placeHolder[$col]['type'] = DBFacade::GetPDOParam($this->$col);
        }
        $sql[] = join("\nAND ", $tmp);
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