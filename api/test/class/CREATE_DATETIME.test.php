<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/column/CREATE_DATETIME.class.php');

class CREATE_DATETIMEtest extends TestCase {
    /**
     * Y/m/d→Y-m-d
     *
     * @test
     */
    public function Set_Convert1() {
        $CREATE_DATETIME = new CREATE_DATETIME('2020/01/01 00:00:00');
        $this->assertSame($CREATE_DATETIME->Get(), '2020-01-01 00:00:00');
    }

    /**
     * Ymd→Y-m-d
     *
     * @test
     */
    public function Set_Convert2() {
        $CREATE_DATETIME = new CREATE_DATETIME('20200101000000');
        $this->assertSame($CREATE_DATETIME->Get(), '2020-01-01 00:00:00');
    }

    /**
     * Y年m月d日→Y-m-d
     *
     * @test
     */
    public function Set_Convert3() {
        $CREATE_DATETIME = new CREATE_DATETIME('2020年01月01日 00:00:00');
        $this->assertSame($CREATE_DATETIME->Get(), '2020-01-01 00:00:00');
    }

    /**
     * y-m-d→Y-m-d
     *
     * @test
     */
    public function Set_Convert4() {
        $this->expectException(RuntimeException::class);
        $CREATE_DATETIME = new CREATE_DATETIME('20-01-01 00:00:00');
    }

    /**
     * 日付以外の文字列セット不可能
     *
     * @test
     */
    public function Set_String() {
        $this->expectException(RuntimeException::class);
        $CREATE_DATETIME = new CREATE_DATETIME('二千二十年一月一日');
    }
}