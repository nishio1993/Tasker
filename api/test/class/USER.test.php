<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/record/USER.class.php');
require_once('class/Security.class.php');

class USERtest extends TestCase {
    public function setUp() : void {
        DBFacade::Connect();
    }
    /**
     * 引数無し生成可能
     *
     * @test
     * @doesNotPerformAssertions
     */
    public function Construct() {
        $USER = new USER();
    }

    /**
     * 配列から生成
     *
     * @test
     * @doesNotPerformAssertions
     */
    public function ConstructByClass() {
        $parameter = [];
        $parameter['MAIL'] = 'test@test.com';
        $parameter['NAME'] = 'テスト太郎';
        $parameter['PASSWORD'] = 'password';
        $USER = new USER($parameter);
    }

    /**
     * KeyValueから生成
     *
     * @test
     * @doesNotPerformAssertions
     */
    public function ConstructByArray() {
        $MAIL = 'test@test.com';
        $PASSWORD = Security::ToHash('password');
        $NAME = 'HogeFuga';
        $USER = new USER(['MAIL' => $MAIL, 'PASSWORD' => $PASSWORD, 'NAME' => $NAME]);
    }

    /**
     * 生成して登録
     *
     * @test
     */
    public function Regist() {
        $MAIL = 'sample@sample.com';
        $PASSWORD = Security::ToHash('password');
        $NAME = 'HogeFuga';
        $USER = new USER(['MAIL' => $MAIL, 'PASSWORD' => $PASSWORD, 'NAME' => $NAME]);
        $this->assertNull($USER->CREATE_DATETIME);
        $this->assertNull($USER->UPDATE_DATETIME);
        $this->assertSame($USER->regist(), 1);
        $this->assertNotNull($USER->CREATE_DATETIME);
        $this->assertNotNull($USER->UPDATE_DATETIME);
    }

    /**
     * 登録済ユーザー検索
     *
     * @test
     */
    public function FindByMAIL() {
        $MAIL = 'sample@sample.com';
        $USER = USER::findByMAIL($MAIL);
        $this->assertSame($USER->MAIL, 'sample@sample.com');
        $this->assertSame($USER->NAME, 'HogeFuga');
        $this->assertTrue(Security::VerifyHash('password', $USER->PASSWORD));
        $this->assertNotNull($USER->CREATE_DATETIME);
        $this->assertNotNull($USER->UPDATE_DATETIME);
    }

    /**
     * 登録済ユーザー名前・パスワード更新
     *
     * @test
     */
    public function Save() {
        $MAIL = 'sample@sample.com';
        $USER = USER::FindByMAIL($MAIL);
        $USER->NAME = 'FooBar';
        $USER->PASSWORD = Security::ToHash('newpassword');
        $USER->save();
        $USER2 = USER::findByMAIL($MAIL);
        $this->assertSame($MAIL, $USER2->MAIL);
        $this->assertSame('FooBar', $USER2->NAME);
        $this->assertTrue(Security::VerifyHash('newpassword', $USER2->PASSWORD));
        $this->assertSame($USER->CREATE_DATETIME, $USER2->CREATE_DATETIME);
        $this->assertSame($USER->UPDATE_DATETIME, $USER2->UPDATE_DATETIME);
    }

    /**
     * 主キー無し登録不可能
     *
     * @test
     */
    public function NoKeyRegist() {
        $this->expectException(RuntimeException::class);
        $PASSWORD = Security::ToHash('password');
        $NAME = 'HogeFuga';
        $USER = new USER(['PASSWORD' => $PASSWORD, 'NAME' => $NAME]);
        $USER->regist();
    }

    /**
     * 名前とパスワード無し登録不可能
     *
     * @test
     */
    public function NoValueRegist() {
        $this->expectException(RuntimeException::class);
        $MAIL = new MAIL('example@example.com');
        $USER = new USER(['MAIL' => $MAIL]);
        $USER->regist();
    }

    /**
     * 主キー無し更新不可能
     *
     * @test
     */
    public function NoKeySave() {
        $this->expectException(RuntimeException::class);
        $NAME = new NAME('HOGEHOGE');
        $USER = new USER(['NAME' => $NAME]);
        $USER->save();
    }
    
    /**
     * 登録解除可能
     *
     * @test
     */
    public function Unregist() {
        $MAIL = 'sample@sample.com';
        $USER = USER::FindByMAIL($MAIL);
        $this->assertSame($USER->unregist(), 1);
        $USER2 = USER::findByMAIL($MAIL);
        $this->assertSame($USER2, []);
    }
    
    /**
     * 存在しないユーザー登録解除不可能
     *
     * @test
     */
    public function UnregistNoUSER() {
        $this->expectException(RuntimeException::class);
        $MAIL = 'sample@sample.com';
        $USER = new USER(['MAIL' => $MAIL]);
        $USER->unregist();
    }
}