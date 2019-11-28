<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/column/START_DATETIME.class.php');

class START_DATETIMEtest extends TestCase {
    /**
     * Y-m-dOK
     *
     * @test
     */
    public function haifunOK() {
        $this->assertTrue(START_DATETIME::isValid('2020-01-01 00:00:00'));
    }
    
    /**
     * Y/m/dOK
     *
     * @test
     */
    public function slashOK() {
        $this->assertTrue(START_DATETIME::isValid('2020/01/01 00:00:00'));
    }
    
    /**
     * YmdOK
     *
     * @test
     */
    public function noneOK() {
        $this->assertTrue(START_DATETIME::isValid('20200101000000'));
    }
    
    /**
     * 年月日時分秒OK
     *
     * @test
     */
    public function japaneseOK() {
        $this->assertTrue(START_DATETIME::isValid('2020年01月01日00時00分00秒'));
    }
    
    /**
     * YmdOnlyNG
     *
     * @test
     */
    public function YmdNG() {
        $this->assertFalse(START_DATETIME::isValid('2020-01-01'));
    }
    
    /**
     * HisOnlyNG
     *
     * @test
     */
    public function HisNG() {
        $this->assertFalse(START_DATETIME::isValid('12:34:56'));
    }

    /**
     * 日付以外の文字列セット不可能
     *
     * @test
     */
    public function stringNG() {
        $this->assertFalse(START_DATETIME::isValid('yyyymmddhhiiss'));
    }
}