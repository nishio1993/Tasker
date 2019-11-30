<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('autoloader.php');

class USER_IDtest extends TestCase {
    protected $USER_ID;

    /**
     * 8文字セット可能確認
     *
     * @test
     */
    public function Set_length8() {
        try {
            $USER_ID = new USER_ID('12345678');
        } catch(Exception $e) {
            $this->assertTrue(false);
            return;
        }
        $this->assertTrue(true);
    }

    /**
     * 16文字セット可能確認
     *
     * @test
     */
    public function Set_length16() {
        try {
            $USER_ID = new USER_ID('1234567890123456');
        } catch(Exception $e) {
            $this->assertTrue(false);
            return;
        }
        $this->assertTrue(true);
    }

    /**
     * 7文字セット不可能確認
     *
     * @test
     */
    public function Set_length7() {
        try {
            $USER_ID = new USER_ID('1234567');
        } catch(Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * 17文字セット不可能確認
     *
     * @test
     */
    public function Set_length17() {
        try {
            $USER_ID = new USER_ID('12345678901234567');
        } catch(Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }
}