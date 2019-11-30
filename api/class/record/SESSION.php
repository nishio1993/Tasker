<?php
require_once('autoloader.php');

class SESSION extends Record {
    private $USER_ID, $TOKEN, $START_DATETIME, $END_DATETIME;

    public function Insert() {
        self::SetTime();
        parent::Insert();
    }

    public function Update() {
        self::SetTime();
        parent::Update();
    }

    private static function SetTime(SESSION $SESSION) {
        $start = date('Y-m-d H:i:s', strtotime());
        $end   = date('Y-m-d H:i:s', strtotime('+7 day'));;
        $SESSION->Set('START_DATETIME', $start);
        $SESSION->Set('END_DATETIME',   $end);
    }
}