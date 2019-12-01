<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('autoloader.php');

class MAILtest extends TestCase {
    /**
     * 小文字セット可能
     *
     * @test
     */
    public function HalfCharOK() {
        $this->assertTrue(MAIL::isValid('test@test.com'));
    }

    /**
     * 大文字セット可能
     *
     * @test
     */
    public function FullCharOK() {
        $this->assertTrue(MAIL::isValid('TEST@TEST.COM'));
    }

    /**
     * 空文字セット不可能
     *
     * @test
     */
    public function EmptyNG() {
        $this->assertFalse(MAIL::isValid(''));
    }

    /**
     * Nullセット不可能
     *
     * @test
     */
    public function NullNG() {
        $this->assertFalse(MAIL::isValid(null));
    }

    /**
     * @無しセット不可能
     *
     * @test
     */
    public function NoAtNG() {
        $this->assertFalse(MAIL::isValid('TESTTEST.COM'));
    }

    /**
     * 全角セット不可能
     *
     * @test
     */
    public function MultiByteNG() {
        $this->assertFalse(MAIL::isValid('ｔｅｓｔ＠ｔｅｓｔ．ｃｏｍ'));
    }

    /**
     * ピリオド一連続セット可能
     *
     * @test
     */
    public function OnePeriodOK() {
        $this->assertTrue(MAIL::isValid('TE.ST@TEST.COM'));
    }

    /**
     * ピリオド二連続セット不可能
     *
     * @test
     */
    public function TwoPeriodNG() {
        $this->assertFalse(MAIL::isValid('TE..ST@TEST.COM'));
    }

    /**
     * ピリオド先頭セット不可能
     *
     * @test
     */
    public function FirstPeriodNG() {
        $this->assertFalse(MAIL::isValid('.test@test.com'));
    }

    /**
     * ピリオド＠直前セット不可能
     *
     * @test
     */
    public function LastPeriodNG() {
        $this->assertFalse(MAIL::isValid('test.@test.com'));
    }

    /**
     * 一部の記号セット可能
     *
     * @test
     */
    public function CorrectSymbolOK() {
        $this->assertTrue(MAIL::isValid('-._@test.com'));
    }

    /**
     * 一部の記号以外セット不可能
     *
     * @test
     */
    public function NotCorrectSymbolNG() {
        $this->assertFalse(MAIL::isValid('\\[,]"\'@test.com'));
    }
    
    /**
     * 256文字可能
     *
     * @test
     */
    public function Length256OK() {
        $this->assertTrue(MAIL::isValid('1234567890abcdefghij123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890@123456789012345678901234567890123456789012345678901.com'));
    }
    
    /**
     * 257文字不可能
     *
     * @test
     * @expectedException Exception
     */
    public function Length257NG() {
        $this->assertFalse(MAIL::isValid('12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890@1234567890123456789012345678901234567890123456789012.com'));
    }
}