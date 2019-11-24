<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/column/NAME.class.php');

class NAMEtest extends TestCase {
    /**
     * 日本語OK
     *
     * @test
     */
    public function JapaneseOK() {
        $this->assertTrue(NAME::isCorrectValue('テスト　太郎'));
    }

    /**
     * 英名OK
     *
     * @test
     */
    public function EnglishOK() {
        $this->assertTrue(NAME::isCorrectValue('Tarou Test'));
    }

    /**
     * 空文字セット不可能
     *
     * @test
     */
    public function EmptyNG() {
        $this->assertFalse(NAME::isCorrectValue(''));
    }

    /**
     * Nullセット不可能
     *
     * @test
     */
    public function NullNG() {
        $this->assertFalse(NAME::isCorrectValue(null));
    }

    /**
     * @test
     */
    public function Half32OK() {
        $this->assertTrue(NAME::isCorrectValue('12345678901234567890123456789012'));
    }

    /**
     * @test
     */
    public function Full32OK() {
        $this->assertTrue(NAME::isCorrectValue('１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２'));
    }

    /**
     * @test
     */
    public function Half33NG() {
        $this->assertFalse(NAME::isCorrectValue('123456789012345678901234567890123'));
    }

    /**
     * @test
     */
    public function Full33NG() {
        $this->assertFalse(NAME::isCorrectValue('１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３'));
    }
}