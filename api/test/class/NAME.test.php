<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/column/NAME.class.php');

class NAMEtest extends TestCase {
    /**
     * SetGet
     *
     * @test
     */
    public function SetGet() {
        $NAME = new NAME('テスト太郎');
        $this->assertSame('テスト太郎', $NAME->Get());
    }

    /**
     * 空文字セット不可能
     *
     * @test
     */
    public function SetEmpty() {
        $this->expectException(RuntimeException::class);
        $NAME = new NAME('');
    }
    
    /**
     * Nullセット不可能
     *
     * @test
     */
    public function SetNull() {
        $this->expectException(RuntimeException::class);
        $NAME = new NAME(null);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function SetHalf32() {
        $NAME = new NAME('12345678901234567890123456789012');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function SetFull32() {
        $NAME = new NAME('１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２');
    }

    /**
     * @test
     */
    public function SetHalf33() {
        $this->expectException(RuntimeException::class);
        $NAME = new NAME('123456789012345678901234567890123');
    }

    /**
     * @test
     */
    public function SetFull33() {
        $this->expectException(RuntimeException::class);
        $NAME = new NAME('１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３');
    }
}