<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/row/USER.class.php');
require_once('class/Security.class.php');

class USERtest extends TestCase {
    /**
     * 引数無し生成不可能
     *
     * @test
     */
    public function Construct() {
        $this->expectException(Error::class);
        $USER = new USER();
    }

    /**
     * クラス配列から生成
     *
     * @test
     * @doesNotPerformAssertions
     */
    public function ConstructByClass() {
        $MAIL = new MAIL('test@test.com');
        $PASSWORD = new PASSWORD(Security::ToHash('password'));
        $NAME = new NAME('HogeFuga');
        $USER = new USER([$MAIL, $PASSWORD, $NAME]);
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
        $MAIL = new MAIL('test@test.com');
        $PASSWORD = new PASSWORD(Security::ToHash('password'));
        $NAME = new NAME('HogeFuga');
        $USER = new USER([$MAIL, $PASSWORD, $NAME]);
        $this->assertNull($USER->Get('CREATE_DATETIME'));
        $this->assertNull($USER->Get('UPDATE_DATETIME'));
        $USER->Regist();
        $this->assertNotNull($USER->Get('CREATE_DATETIME'));
        $this->assertNotNull($USER->Get('UPDATE_DATETIME'));
    }

    /**
     * 登録済ユーザー検索
     *
     * @test
     */
    public function FindByMAIL() {
        $MAIL = new MAIL('test@test.com');
        $USER = USER::FindByMAIL($MAIL);
        $this->assertSame($USER->Get('MAIL'), $MAIL->Get('test@test.com'));
        $this->assertSame($USER->Get('NAME'), 'HogeFuga');
        $this->assertTrue(VerifyHash('password', $USER->Get('PASSWORD')));
        $this->assertNotNull($USER->Get('CREATE_DATETIME'));
        $this->assertNotNull($USER->Get('UPDATE_DATETIME'));
    }

    /**
     * 登録済ユーザー名前・パスワード更新
     *
     * @test
     */
    public function Save() {
        $MAIL = new MAIL('test@test.com');
        $USER = USER::FindByMAIL($MAIL);
        $USER->Set('NAME', 'FooBar');
        $USER->Set('PASSWORD', Security::ToHash('newpassword'));
        $USER->Save();
        $USER2 = USER::FindByMAIL($MAIL);
        $this->assertSame($MAIL->Get(), $USER2->Get('MAIL'));
        $this->assertSame('FooBar', $USER2->Get('NAME'));
        $this->assertTrue(Security::VerifyHash('newpassword', $USER2->Get('PASSWORD')));
        $this->assertSame($USER->Get('CREATE_DATETIME'), $USER2->Get('CREATE_DATETIME'));
        $this->assertSame($USER->Get('UPDATE_DATETIME'), $USER2->Get('UPDATE_DATETIME'));
    }

    /**
     * 主キー無し登録不可能
     *
     * @test
     */
    public function NoKeyRegist() {
        $this->expectException(RuntimeException::class);
        $PASSWORD = new PASSWORD(Security::ToHash('password'));
        $NAME = new NAME('HogeFuga');
        $USER = new USER([$PASSWORD, $NAME]);
        $USER->Regist();
    }

    /**
     * 主キーさえあれば登録可能
     *
     * @test
     * @doesNotPerformAssertions
     */
    public function NoValueRegist() {
        $MAIL = new MAIL('example@example.com');
        $USER = new USER([$MAIL]);
        $USER->Regist();
    }

    /**
     * 主キー無し更新不可能
     *
     * @test
     */
    public function NoKeySave() {
        $this->expectException(RuntimeException::class);
        $NAME = new NAME('HOGEHOGE');
        $USER = new USER([$NAME]);
        $USER->Save();
    }
    
    /**
     * 登録解除可能
     *
     * @test
     */
    public function Unregist() {
        $MAIL = new MAIL('test@test.com');
        $USER = USER::FindByMAIL($MAIL);
        $USER->Unregist();
        $USER = USER::FindByMAIL($MAIL);
        $this->assertSame($USER, []);
        $USER->Set('MAIL', new MAIL('example@example.com'));
        $USER->Unregist();
    }
    
    /**
     * 存在しないユーザー登録解除不可能
     *
     * @test
     */
    public function UnregistNoUSER() {
        $this->expectException(RuntimeException::class);
        $MAIL = new MAIL('test@test.com');
        $USER = new USER([$MAIL]);
        $USER->Unregist();
    }
}