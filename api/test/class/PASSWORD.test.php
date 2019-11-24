<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/column/PASSWORD.class.php');
class test extends TestCase {
    /**
     * @test
     */
    public function HashOK() {
        $hash = PASSWORD::toHash('password');
        $this->assertTrue(PASSWORD::isCorrectValue($hash));
    }

    /**
     * 空文字セット不可能
     *
     * @test
     */
    public function EmptyNG() {
        $this->assertFalse(PASSWORD::isCorrectValue(''));
    }

    /**
     * Nullセット不可能
     *
     * @test
     */
    public function NullNG() {
        $this->assertFalse(PASSWORD::isCorrectValue(null));
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