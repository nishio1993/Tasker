<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/column/MAIL.class.php');

class MAILtest extends TestCase {
    /**
     * 小文字セット可能
     *
     * @test
     */
    public function HalfCharOK() {
        $this->assertTrue(MAIL::isCorrectValue('test@test.com'));
    }

    /**
     * 大文字セット可能
     *
     * @test
     */
    public function FullCharOK() {
        $this->assertTrue(MAIL::isCorrectValue('TEST@TEST.COM'));
    }

    /**
     * 空文字セット不可能
     *
     * @test
     */
    public function EmptyNG() {
        $this->assertFalse(MAIL::isCorrectValue(''));
    }

    /**
     * Nullセット不可能
     *
     * @test
     */
    public function NullNG() {
        $this->assertFalse(MAIL::isCorrectValue(null));
    }

    /**
     * @無しセット不可能
     *
     * @test
     */
    public function NoAtNG() {
        $this->assertFalse(MAIL::isCorrectValue('TESTTEST.COM'));
    }

    /**
     * 全角セット不可能
     *
     * @test
     */
    public function MultiByteNG() {
        $this->assertFalse(MAIL::isCorrectValue('ｔｅｓｔ＠ｔｅｓｔ．ｃｏｍ'));
    }

    /**
     * ピリオド一連続セット可能
     *
     * @test
     */
    public function OnePeriodOK() {
        $this->assertTrue(MAIL::isCorrectValue('TE.ST@TEST.COM'));
    }

    /**
     * ピリオド二連続セット不可能
     *
     * @test
     */
    public function TwoPeriodNG() {
        $this->assertFalse(MAIL::isCorrectValue('TE..ST@TEST.COM'));
    }

    /**
     * ピリオド先頭セット不可能
     *
     * @test
     */
    public function FirstPeriodNG() {
        $this->assertFalse(MAIL::isCorrectValue('.test@test.com'));
    }

    /**
     * ピリオド＠直前セット不可能
     *
     * @test
     */
    public function LastPeriodNG() {
        $this->assertFalse(MAIL::isCorrectValue('test.@test.com'));
    }

    /**
     * 一部の記号セット可能
     *
     * @test
     */
    public function CorrectSymbolOK() {
        $this->assertTrue(MAIL::isCorrectValue('-._@test.com'));
    }

    /**
     * 一部の記号以外セット不可能
     *
     * @test
     */
    public function NotCorrectSymbolNG() {
        $this->assertFalse(MAIL::isCorrectValue('\\[,]"\'@test.com'));
    }
    
    /**
     * 256文字可能
     *
     * @test
     */
    public function Length256OK() {
        $this->assertTrue(MAIL::isCorrectValue('1234567890abcdefghij123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890@123456789012345678901234567890123456789012345678901.com'));
    }
    
    /**
     * 257文字不可能
     *
     * @test
     * @expectedException Exception
     */
    public function Length257NG() {
        $this->assertFalse(MAIL::isCorrectValue('12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890@1234567890123456789012345678901234567890123456789012.com'));
    }
}