<?php
require_once('class/row/RowBase.class.php');
require_once('class/column/MAIL.class.php');
require_once('class/column/PASSWORD.class.php');
require_once('class/column/NAME.class.php');
require_once('class/column/CREATE_DATETIME.class.php');
require_once('class/column/UPDATE_DATETIME.class.php');
require_once('class/Security.class.php');
class USER extends RowBase {
    const FIELD = ['MAIL', 'NAME', 'PASSWORD', 'CREATE_DATETIME', 'UPDATE_DATETIME'];
    const PRIMARY_KEY = ['MAIL'];

    public static function findByMAIL(string $MAIL) : array {
        return parent::select([], ['MAIL' => $MAIL], []);
    }

    public function regist() : int {
        $datetime = new DateTimeImmutable();
        $this->CREATE_DATETIME = $datetime->format('Y-m-d H:i:s');
        $this->UPDATE_DATETIME = $datetime->format('Y-m-d H:i:s');
        $result = parent::insert();
        if ($result === 0) {
            throw new RuntimeException('Failure USER->Regist()');
        }
        return $result;
    }

    public function save() : int {
        $datetime = new DateTimeImmutable();
        $this->UPDATE_DATETIME = new UPDATE_DATETIME($datetime->format('Y-m-d H:i:s'));
        $result = parent::update();
        if ($result === 0) {
            throw new RuntimeException('Failure USER->Save()');
        }
        return $result;
    }

    public function unregist() : int {
        $result = parent::delete();
        if ($result === 0) {
            throw new RuntimeException('Failure USER->Unregist()');
        }
        return $result;
    }
}