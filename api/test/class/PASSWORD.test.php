<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/column/PASSWORD.class.php');
require_once('class/Security.class.php');

class test extends TestCase {
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function SetHash() {
        $hash = Security::ToHash('password');
        $PASSWORD = new PASSWORD($hash);
    }

    /**
     * 空文字セット不可能
     *
     * @test
     */
    public function SetEmpty() {
        $this->expectException(RuntimeException::class);
        $NAME = new PASSWORD('');
    }

    /**
     * Nullセット不可能
     *
     * @test
     */
    public function SetNull() {
        $this->expectException(RuntimeException::class);
        $NAME = new PASSWORD(null);
    }

    /**
     * 認証可能
     *
     * @test
     */
    public function GetAndVerify() {
        $hash = Security::ToHash('password');
        $PASSWORD = new PASSWORD($hash);
        $this->assertTrue(Security::VerifyHash('password', $PASSWORD->Get()));
    }
}