<?php
require_once('autoloader.php');

class CATEGORY extends Record
{
    const FIELD = [
        'PROJECT_CODE',
        'COLUMN_CODE',
        'INDEX',
        'COLUMN_NAME',
        'CREATE_USER',
        'CREATE_DATETIME',
        'UPDATE_USER',
        'UPDATE_DATETIME'];
    const KEY = [
        'PROJECT_CODE',
        'COLUMN_CODE'
    ];

    public static function findByProjectCode(string $projectCode)
    {
        return parent::select([], ['PROJECT_CODE' => $projectCode], []);
    }

    public function create(): int
    {
        $result = parent::insert();
        if ($result === 0) {
            throw new RuntimeException('Failure CATEGORY->create()');
        }
        return $result;
    }

    public function save(): int
    {
        $result = parent::update();
        if ($result === 0) {
            throw new RuntimeException('Failure CATEGORY->save()');
        }
        return $result;
    }

    public function delete(): int
    {
        $result = parent::delete();
        if ($result === 0) {
            throw new RuntimeException('Failure CATEGORY->delete()');
        }
        return $result;
    }
}