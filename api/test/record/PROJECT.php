<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('autoloader.php');

class USERtest extends TestCase
{
    public function setUp() : void {
        DBFacade::Connect();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function 引数無し生成可能() {
        $PROJECT = new PROJECT();
        $PROJECT->PROJECT_CODE = Security::createUUID();
        $PROJECT->PROJECT_NAME ='テストプロジェクト';
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function KeyValueから生成可能() {
        $parameter = [];
        $parameter['PROJECT_CODE'] = Security::createUUID();
        $parameter['PROJECT_NAME'] = 'テストプロジェクト';
        $PROJECT = new PROJECT($parameter);
    }

    public function
}