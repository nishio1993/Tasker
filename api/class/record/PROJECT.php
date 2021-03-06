<?php
require_once('autoloader.php');

class PROJECT extends Record
{
    const FIELD = [
        'PROJECT_CODE',
        'PROJECT_NAME',
        'CREATE_USER',
        'CREATE_DATETIME',
        'UPDATE_USER',
        'UPDATE_DATETIME'
    ];
    const KEY = [
        'PROJECT_CODE'
    ];

    public static function findByProjectCode($projectCode)
    {
        if (is_string($projectCode) || is_array($projectCode)) {
            return parent::select([], ['PROJECT_CODE' => $projectCode]);
        } else {
            throw new RuntimeException('Failure PROJECT->findByProjectCode()');
        }
    }

    public function create(): int
    {
        $result = parent::insert();
        if ($result === 0) {
            throw new RuntimeException('Failure PROJECT->create()');
        }
        return $result;
    }

    public function save(): int
    {
        $result = parent::update();
        if ($result === 0) {
            throw new RuntimeException('Failure PROJECT->save()');
        }
        return $result;
    }

    public function delete(): int
    {
        $result = parent::delete();
        if ($result === 0) {
            throw new RuntimeException('Failure PROJECT->delete()');
        }
        return $result;
    }
}