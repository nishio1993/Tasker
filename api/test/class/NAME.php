<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('autoloader.php');

class NAMEtest extends TestCase {
    /**
     * 日本語OK
     *
     * @test
     */
    public function JapaneseOK() {
        $this->assertTrue(NAME::isValid('テスト　太郎'));
    }

    /**
     * 英名OK
     *
     * @test
     */
    public function EnglishOK() {
        $this->assertTrue(NAME::isValid('Tarou Test'));
    }

    /**
     * 空文字セット不可能
     *
     * @test
     */
    public function EmptyNG() {
        $this->assertFalse(NAME::isValid(''));
    }

    /**
     * Nullセット不可能
     *
     * @test
     */
    public function NullNG() {
        $this->assertFalse(NAME::isValid(null));
    }

    /**
     * @test
     */
    public function Half32OK() {
        $this->assertTrue(NAME::isValid('12345678901234567890123456789012'));
    }

    /**
     * @test
     */
    public function Full32OK() {
        $this->assertTrue(NAME::isValid('１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２'));
    }

    /**
     * @test
     */
    public function Half33NG() {
        $this->assertFalse(NAME::isValid('123456789012345678901234567890123'));
    }

    /**
     * @test
     */
    public function Full33NG() {
        $this->assertFalse(NAME::isValid('１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３'));
    }
}