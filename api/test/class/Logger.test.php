<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/Logger.class.php');

class Loggertest extends TestCase {
    /**
     * DEBUGログ生成可能
     *
     * @test
     */
    public function DEBUG() {
        Logger::DEBUG('test');
        $today = date('Ymd');
        $this->assertFileExists("../log/debug/{$today}.log");
    }

    /**
     * INFOログ生成可能
     *
     * @test
     */
    public function INFO() {
        Logger::INFO('test');
        $today = date('Ymd');
        $this->assertFileExists("../log/info/{$today}.log");
    }

    /**
     * ERRORログ生成可能
     *
     * @test
     */
    public function ERROR() {
        Logger::ERROR('test');
        $today = date('Ymd');
        $this->assertFileExists("../log/error/{$today}.log");
    }

    /**
     * SQLログ生成可能
     *
     * @test
     */
    public function SQL() {
        Logger::SQL('test', 1, [0 => ['parameter' => ':test', 'value' => 'test', 'type' => PDO::PARAM_STR]]);
        $today = date('Ymd');
        $this->assertFileExists("../log/sql/{$today}.log");
    }
}