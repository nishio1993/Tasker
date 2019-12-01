<?php
require_once('autoloader.php');

class TASK extends Record
{
    const FIELD = [
        'TASK_CODE',
        'TASK_NAME',
        'CATEGORY_CODE',
        'TEXT',
        'REPRESENTATIVE',
        'LABEL',
        'PROGRESS',
        'START_DATETIME',
        'END_DATETIME',
        'CREATE_USER',
        'CREATE_DATETIME',
        'UPDATE_USER',
        'UPDATE_DATETIME'
    ];
    const KEY = [
        'TASK_CODE'
    ];

    public static function findByTaskCode($taskCode)
    {
        if (is_string($taskCode) || is_array($taskCode)) {
            return parent::select([], ['TASK_CODE' => $taskCode]);
        } else {
            throw new RuntimeException('Failure TASK->findByTaskCode()');
        }
    }

    public function create(): int
    {
        $result = parent::insert();
        if ($result === 0) {
            throw new RuntimeException('Failure TASK->create()');
        }
        return $result;
    }

    public function update(): int
    {
        $result = parent::update();
        if ($result === 0) {
            throw new RuntimeException('Failure TASK->update()');
        }
        return $result;
    }

    public function delete(): int
    {
        $result = parent::delete();
        if ($result === 0) {
            throw new RuntimeException('Failure TASK->delete()');
        }
        return $result;
    }
}