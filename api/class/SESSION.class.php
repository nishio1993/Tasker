<?php

require_once('./TableBase.class.php');
require_once('./USER_ID.class.php');
require_once('./TOKEN.class.php');
require_once('./START_DATETIME.class.php');
require_once('./END_DATETIME.class.php');

class SESSION extends TableBase {
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