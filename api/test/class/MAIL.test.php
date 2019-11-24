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
    public function Set_HalfChar() {
        try {
            $MAIL = new MAIL('test@test.com');
            $MAIL->Set('test@test.com');
        } catch(Exception $e) {
            $this->assertTrue(false);
            return;
        }
        $this->assertTrue(true);
    }

    /**
     * 大文字セット可能
     *
     * @test
     */
    public function Set_FullChar() {
        try {
            $MAIL = new MAIL('TEST@TEST.COM');
            $MAIL->Set('TEST@TEST.COM');
        } catch(Exception $e) {
            $this->assertTrue(false);
            return;
        }
        $this->assertTrue(true);
    }

    /**
     * 空文字セット不可能
     *
     * @test
     */
    public function SetEmpty() {
        $this->expectException(RuntimeException::class);
        $NAME = new MAIL('');
    }

    /**
     * Nullセット不可能
     *
     * @test
     */
    public function SetNull() {
        $this->expectException(RuntimeException::class);
        $NAME = new MAIL(null);
    }

    /**
     * @無しセット不可能
     *
     * @test
     */
    public function Set_NoAt() {
        try {
            $MAIL = new MAIL('TESTTEST.COM');
            $MAIL->Set('TESTTEST.COM');
        } catch(Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * 全角セット不可能
     *
     * @test
     */
    public function Set_MultiByte() {
        try {
            $MAIL = new MAIL('ｔｅｓｔ＠ｔｅｓｔ．ｃｏｍ');
            $MAIL->Set('ｔｅｓｔ＠ｔｅｓｔ．ｃｏｍ');
        } catch(Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * ピリオド一連続セット可能
     *
     * @test
     */
    public function Set_OnePeriod() {
        try {
            $MAIL = new MAIL('te.st@test.com');
            $MAIL->Set('te.st@test.com');
        } catch(Exception $e) {
            $this->assertTrue(false);
            return;
        }
        $this->assertTrue(true);
    }

    /**
     * ピリオド二連続セット不可能
     *
     * @test
     */
    public function Set_TwoPeriod() {
        try {
            $MAIL = new MAIL('te..st@test.com');
            $MAIL->Set('te..st@test.com');
        } catch(Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * ピリオド先頭セット不可能
     *
     * @test
     */
    public function Set_FirstPeriod() {
        try {
            $MAIL = new MAIL('.test@test.com');
            $MAIL->Set('.test@test.com');
        } catch(Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * ピリオド＠直前セット不可能
     *
     * @test
     */
    public function Set_LastPeriod() {
        try {
            $MAIL = new MAIL('test.@test.com');
            $MAIL->Set('test.@test.com');
        } catch(Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * 一部の記号セット可能
     *
     * @test
     */
    public function Set_CorrectSymbol() {
        try {
            $MAIL = new MAIL('-._@test.com');
            $MAIL->Set('-._@test.com');
        } catch(Exception $e) {
            $this->assertTrue(false);
            return;
        }
        $this->assertTrue(true);
    }

    /**
     * 一部の記号以外セット不可能
     *
     * @test
     */
    public function Set_NotCorrectSymbol() {
        try {
            $MAIL = new MAIL('\\[,]"\'@test.com');
            $MAIL->Set('\\[,]"\'@test.com');
        } catch(Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }
    
    /**
     * 256文字可能
     *
     * @test
     */
    public function Set_Length256() {
        $MAIL = new MAIL('1234567890abcdefghij123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890@123456789012345678901234567890123456789012345678901.com');
        $this->assertSame(get_class($MAIL), 'MAIL');
    }
    
    /**
     * 257文字不可能
     *
     * @test
     * @expectedException Exception
     */
    public function Set_Length257() {
        $this->expectException(Exception::class);
        $MAIL = new MAIL('12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890@1234567890123456789012345678901234567890123456789012.com');
    }

    /**
     * ゲット可能
     *
     * @test
     */
    public function Get_Correct() {
        $MAIL = new MAIL('test@test.com');
        $this->assertSame($MAIL->Get(), 'test@test.com');
    }
}