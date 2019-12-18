<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('autoloader.php');

class USERtest extends TestCase
{
    protected $uuid;

    public function setUp() : void {
        DBFacade::Connect();
        $this->uuid = Security::createUUID();
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

    /**
     * @test
     */
    public function 生成して登録() {
        $parameter = [];
        $parameter['PROJECT_CODE'] = $this->uuid;
        $parameter['PROJECT_NAME'] = 'テストプロジェクト';
        $PROJECT = new PROJECT($parameter);

        $PROJECT->create();
        $this->assertTrue(isset($PROJECT->CREATE_DATETIME));
        $this->assertTrue(isset($PROJECT->CREATE_USER));
        $this->assertTrue(isset($PROJECT->UPDATE_DATETIME));
        $this->assertTrue(isset($PROJECT->UPDATE_USER));
    }

    /**
     * @test
     */
    public function CODEが被ると登録不可能() {
        $this->expectException(RuntimeException::class);
        $parameter = [];
        $parameter['PROJECT_CODE'] = $this->uuid;
        $parameter['PROJECT_NAME'] = 'テストプロジェクト';
        $PROJECT = new PROJECT($parameter);

        $PROJECT->create();
    }
}