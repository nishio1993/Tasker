<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('autoloader.php');

class Securitytest extends TestCase {
    /**
     * トークン生成可能
     *
     * @test
     */
    public function CreateToken() {
        $this->assertSame(strlen(Security::CreateToken()), 32);
    }

    /**
     * トークン検証可能
     *
     * @test
     */
    public function VerifyToken() {
        $this->assertTrue(Security::VerifyToken(Security::CreateToken()));
    }

    /**
     * ハッシュ生成可能
     *
     * @test
     */
    public function ToHash() {
        $this->assertNotSame('password', Security::ToHash('password'));
    }

    /**
     * ハッシュランダム生成可能
     *
     * @test
     */
    public function ToHashWhetherRandomOrNot() {
        $this->assertNotSame(Security::ToHash('password'), Security::ToHash('password'));
    }

    /**
     * ハッシュ検証可能
     *
     * @test
     */
    public function VerifyHash() {
        $this->assertTrue(Security::VerifyHash('password', Security::ToHash('password')));
    }

    /**
     * ハッシュ検証NULLバイト対策済
     *
     * @test
     */
    public function VerifyHashSolvedNullByte() {
        $this->assertFalse(Security::VerifyHash("\n", Security::ToHash("\npassword")));
    }

    /**
     * UUID生成可能
     *
     * @test
     */
    public function CreateUUID() {
        $this->assertNotNull(Security::CreateUUID());
    }

    /**
     * UUIDランダム生成可能
     *
     * @test
     */
    public function CreateUUIDWhetherRandomOrNot() {
        $this->assertNotSame(Security::CreateUUID(), Security::CreateUUID());
    }

    /**
     * タイムスタンプ生成可能
     *
     * @test
     */
    public function CreateTimeStamp() {
        $this->assertNotNull(Security::CreateTimeStamp());
    }

    /**
     * タイムスタンプ一意
     *
     * @test
     */
    public function CreateUniqueTimeStamp() {
        $this->assertNotSame(Security::CreateTimeStamp(), Security::CreateTimeStamp());
    }

    /**
     * タイムスタンプフォーマット指定可能
     *
     * @test
     */
    public function CreateTimeStampSpecifiedFormat() {
        $this->assertSame(strlen(Security::CreateTimeStamp('Y-m-dH:i:s.u')), 25);
    }

    /**
     * HTMLエスケープ可能
     *
     * @test
     */
    public function EscapeHTML() {
        $this->assertSame(Security::EscapeHTML('<>&'), '&lt;&gt;&amp;');
    }

    /**
     * HTMLエスケープ可能
     *
     * @test
     */
    public function EscapeHTMLForNormal() {
        $this->assertSame(Security::EscapeHTML('testTESTあいうえお'), 'testTESTあいうえお');
    }
}