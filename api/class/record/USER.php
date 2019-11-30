<?php
require_once('autoloader.php');

class USER extends Record {
    const FIELD = ['MAIL', 'NAME', 'PASSWORD', 'CREATE_DATETIME', 'UPDATE_DATETIME'];
    const KEY = ['MAIL'];

    public static function findByMAIL($MAIL) {
        if (is_string($MAIL) || is_array($MAIL)) {
            return parent::select([], ['MAIL' => $MAIL], []);
        } else {
            throw new RuntimeException('Failure USER->findByMAIL()');
        }
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
        $this->UPDATE_DATETIME = $datetime->format('Y-m-d H:i:s');
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