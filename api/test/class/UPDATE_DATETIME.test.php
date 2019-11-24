<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/column/UPDATE_DATETIME.class.php');

class UPDATE_DATETIMEtest extends TestCase {
    /**
     * Y-m-dOK
     *
     * @test
     */
    public function haifunOK() {
        $this->assertTrue(UPDATE_DATETIME::isCorrectValue('2020-01-01 00:00:00'));
    }
    
    /**
     * Y/m/dOK
     *
     * @test
     */
    public function slashOK() {
        $this->assertTrue(UPDATE_DATETIME::isCorrectValue('2020/01/01 00:00:00'));
    }
    
    /**
     * YmdOK
     *
     * @test
     */
    public function noneOK() {
        $this->assertTrue(UPDATE_DATETIME::isCorrectValue('20200101000000'));
    }
    
    /**
     * 年月日時分秒OK
     *
     * @test
     */
    public function japaneseOK() {
        $this->assertTrue(UPDATE_DATETIME::isCorrectValue('2020年01月01日00時00分00秒'));
    }
    
    /**
     * YmdOnlyNG
     *
     * @test
     */
    public function YmdNG() {
        $this->assertFalse(UPDATE_DATETIME::isCorrectValue('2020-01-01'));
    }
    
    /**
     * HisOnlyNG
     *
     * @test
     */
    public function HisNG() {
        $this->assertFalse(UPDATE_DATETIME::isCorrectValue('12:34:56'));
    }

    /**
     * 日付以外の文字列セット不可能
     *
     * @test
     */
    public function stringNG() {
        $this->assertFalse(UPDATE_DATETIME::isCorrectValue('yyyymmddhhiiss'));
    }
}