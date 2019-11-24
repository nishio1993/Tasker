<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/column/TOKEN.class.php');
require_once('class/Security.class.php');

class TOKENtest extends TestCase {
    /**
     * トークンセット可能
     *
     * @test
     */
    public function Set_Token() {
        try {
            $TOKEN = new TOKEN(Security::CreateToken());
        } catch (Exception $e) {
            $this->assertTrue(false);
            return;
        }
        $this->assertTrue(true);
    }

    /**
     * 全角セット不可能
     *
     * @test
     */
    public function Set_FullChar() {
        try {
            $TOKEN = new TOKEN(mb_convert_kana(Security::CreateToken(), 'A'));
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * ハッシュセット不可能
     *
     * @test
     */
    public function Set_Hash() {
        try {
            $TOKEN = new TOKEN(Security::ToHash('token'));
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * 31文字以下セット不可能
     *
     * @test
     */
    public function Set_31() {
        try {
            $TOKEN = new TOKEN('1234567890123456789012345678901');
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * 33文字以上セット不可能
     *
     * @test
     */
    public function Set_33() {
        try {
            $TOKEN = new TOKEN('123456789012345678901234567890123');
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * 日本語セット不可能
     *
     * @test
     */
    public function Set_Japanese() {
        try {
            $TOKEN = new TOKEN('あいうえおかきくけこさしすせそたちつてとなにぬねのはひふへほまみ');
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * 記号セット不可能
     *
     * @test
     */
    public function Set_Symbol() {
        try {
            $TOKEN = new TOKEN('!!!!!!!!!!??????????##########%%');
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }
}