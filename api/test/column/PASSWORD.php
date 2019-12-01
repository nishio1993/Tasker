<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('autoloader.php');

class test extends TestCase {
    /**
     * @test
     */
    public function HashOK() {
        $hash = PASSWORD::toHash('password');
        $this->assertTrue(PASSWORD::isValid($hash));
    }

    /**
     * 空文字セット不可能
     *
     * @test
     */
    public function EmptyNG() {
        $this->assertFalse(PASSWORD::isValid(''));
    }

    /**
     * Nullセット不可能
     *
     * @test
     */
    public function NullNG() {
        $this->assertFalse(PASSWORD::isValid(null));
    }

    /**
     * 認証可能
     *
     * @test
     */
    public function VerifyOK() {
        $hash = PASSWORD::toHash('password');
        $this->assertTrue(PASSWORD::verify('password', $hash));
    }
}