<?php
require_once('autoloader.php');

class COMMENT extends Record
{
    const FIELD = [
        'TASK_CODE',
        'INDEX',
        'COMMENT_TEXT',
        'CREATE_USER',
        'CREATE_DATETIME',
        'UPDATE_USER',
        'UPDATE_DATETIME'
    ];
    const KEY = [
        'TASK_CODE',
        'INDEX'
    ];

    public static function findByTaskCode($TASK_CODE)
    {
        return parent::select([], ['TASK_CODE' => $TASK_CODE], ['INDEX']);
    }

    public function create(): int
    {
        $result = parent::insert();
        if ($result === 0) {
            throw new RuntimeException('Failure COMMENT->create()');
        }
        return $result;
    }

    public function update(): int
    {
        $result = parent::update();
        if ($result === 0) {
            throw new RuntimeException('Failure COMMENT->update()');
        }
        return $result;
    }

    public function delete(): int
    {
        $result = parent::delete();
        if ($result === 0) {
            throw new RuntimeException('Failure COMMENT->delete()');
        }
        return $result;
    }
}