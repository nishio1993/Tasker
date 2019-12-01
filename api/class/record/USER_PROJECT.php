<?php
require_once('autoloader.php');

class USER_PROJECT extends Record
{
    const FIELD = [
        'MAIL',
        'PROJECT_CODE',
        'USER_INDEX',
        'PROJECT_INDEX'
    ];
    const KEY = [
        'MAIL',
        'PROJECT_CODE'
    ];

    public static function findByMail(string $MAIL)
    {
        return parent::select(['PROJECT_CODE', 'PROJECT_INDEX'], ['MAIL' => $MAIL]);
    }

    public static function findByProjectCode(string $PROJECT_CODE)
    {
        return parent::select(['MAIL', 'USER_INDEX'], ['PROJECT_CODE' => $PROJECT_CODE]);
    }

    public function regist(): int
    {
        $result = parent::insert();
        if ($result === 0) {
            throw new RuntimeException('Failure USER_PROJECT->create');
        }
        return $result;
    }

    public function save(): int
    {
        $result = parent::update();
        if ($result === 0) {
            throw new RuntimeException('Failure USER_PROJECT->save');
        }
        return $result;
    }

    public function unregist(): int
    {
        $result = parent::delete();
        if ($result === 0) {
            throw new RuntimeException('Failure USER_PROJECT->unregist');
        }
        return $result;
    }
}