<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/column/UPDATE_DATETIME.class.php');

class UPDATE_DATETIMEtest extends TestCase {
    /**
     * Y/m/d→Y-m-d
     *
     * @test
     */
    public function Set_Convert1() {
        $UPDATE_DATETIME = new UPDATE_DATETIME('2020/01/01 00:00:00');
        $this->assertSame($UPDATE_DATETIME->Get(), '2020-01-01 00:00:00');
    }

    /**
     * Ymd→Y-m-d
     *
     * @test
     */
    public function Set_Convert2() {
        $UPDATE_DATETIME = new UPDATE_DATETIME('20200101000000');
        $this->assertSame($UPDATE_DATETIME->Get(), '2020-01-01 00:00:00');
    }

    /**
     * Y年m月d日→Y-m-d
     *
     * @test
     */
    public function Set_Convert3() {
        $UPDATE_DATETIME = new UPDATE_DATETIME('2020年01月01日 00:00:00');
        $this->assertSame($UPDATE_DATETIME->Get(), '2020-01-01 00:00:00');
    }

    /**
     * y-m-d→Y-m-d
     *
     * @test
     */
    public function Set_Convert4() {
        $this->expectException(RuntimeException::class);
        $UPDATE_DATETIME = new UPDATE_DATETIME('20-01-01 00:00:00');
    }

    /**
     * 日付以外の文字列セット不可能
     *
     * @test
     */
    public function Set_String() {
        $this->expectException(RuntimeException::class);
        $UPDATE_DATETIME = new UPDATE_DATETIME('二千二十年一月一日');
    }
}