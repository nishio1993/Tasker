<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('controller/user.api.php');

class usertest extends TestCase {
    protected $client;
    protected $env;
    protected $uri;

    public function setUp() : void {
        $this->client = new GuzzleHttp\Client();
        $this->env    = parse_ini_file('../.env');
        $this->uri    = "{$this->env['api_host']}:{$this->env['api_port']}/user";
    }

    /**
     * @test
     */
    public function correctPOST() {
        $res = $this->client->post($this->uri, [
            'form_params' => [
                'MAIL'     => urlencode('test@test.com'),
                'NAME'     => urlencode('テスト太郎'),
                'PASSWORD' => urlencode('password')
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertFalse(isset($response->error));
        $this->assertSame($response->result, 1);
    }

    /**
     * 重複不可能
     * 
     * @test
     */
    public function incorrectPOST1() {
        $res = $this->client->post($this->uri, [
            'form_params' => [
                'MAIL'     => urlencode('test@test.com'),
                'NAME'     => urlencode('テスト太郎'),
                'PASSWORD' => urlencode('password')
            ],
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue(isset($response->error));
        $this->assertFalse(isset($response->result));
    }

    /**
     * MAIL無し不可能
     * 
     * @test
     */
    public function incorrectPOST2() {
        $res = $this->client->post($this->uri, [
            'form_params' => [
                'NAME'     => urlencode('テスト太郎'),
                'PASSWORD' => urlencode('password')
            ],
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->AssertTrue(isset($response->error['MAIL']));
        $this->assertFalse(isset($response->result));
    }

    /**
     * MAIL空欄不可能
     * 
     * @test
     */
    public function incorrectPOST3() {
        $res = $this->client->post($this->uri, [
            'form_params' => [
                'MAIL'     => urlencode(''),
                'NAME'     => urlencode('テスト太郎'),
                'PASSWORD' => urlencode('password')
            ],
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue(isset($response->error));
        $this->assertFalse(isset($response->result));
    }

    /**
     * NAMEPASSWORD空欄不可能
     * 
     * @test
     */
    public function incorrectPOST4() {
        $res = $this->client->post($this->uri, [
            'form_params' => [
                'MAIL'     => urlencode('sample@sample.com')
            ],
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue(isset($response->error));
        $this->assertFalse(isset($response->result));
    }

    /**
     * @test
     */
    public function correctGET() {
        $res = $this->client->get($this->uri, [
            'query' => [
                'MAIL' => urlencode('test@test.com')
            ],
        ]);

        $object = json_decode($res->getBody());
        $this->assertSame($object->USER['MAIL'], 'test@test.com');
        $this->assertSame($object->USER['NAME'], 'テスト太郎');
        $this->assertNull($object->USER['PASSWORD']);
        $this->assertNotNull($object->USER['CREATE_DATETIME']);
        $this->assertNotNull($object->USER['UPDATE_DATETIME']);
        $this->assertNull($object->error);
    }

    /**
     * MAIL空欄不可能
     * 
     * @test
     */
    public function incorrectGET() {
        $res = $this->client->get($this->uri, [
            'query' => [
                'MAIL' => urlencode('')
            ],
        ],[
            'http_errors' => false
        ]);

        $object = json_decode($res->getBody());
        $this->assertTrue(isset($response->error));
        $this->assertFalse(isset($response->result));
    }

    /**
     * @test
     */
    public function correctPUT() {
        $res = $this->client->put($this->uri, [
            'query' => [
                'MAIL'     => urlencode('test@test.com'),
                'NAME'     => urlencode('太郎テスト'),
                'PASSWORD' => urlencode('pass')
            ],
        ],[
            'http_errors' => false
        ]);

        $object = json_decode($res->getBody());
        $this->assertNull($response->error);
        $this->assertSame($response->result, 1);
    }

    /**
     * MAIL空欄不可能
     * 
     * @test
     */
    public function incorrectPUT1() {
        $res = $this->client->get($this->uri, [
            'query' => [
                'NAME'     => urlencode('太郎テスト'),
                'PASSWORD' => urlencode('pass')
            ],
        ],[
            'http_errors' => false
        ]);

        $object = json_decode($res->getBody());
        $this->assertTrue(isset($response->error));
        $this->assertFalse(isset($response->result));
    }

    /**
     * NAMEPASSWORD空欄不可能
     * 
     * @test
     */
    public function incorrectPUT2() {
        $res = $this->client->get($this->uri, [
            'query' => [
                'MAIL'     => urlencode('test@test.com')
            ],
        ],[
            'http_errors' => false
        ]);

        $object = json_decode($res->getBody());
        $this->assertTrue(isset($response->error));
        $this->assertFalse(isset($response->result));
    }

    /**
     * @test
     */
    public function correctDELETE() {
        $res = $this->client->delete($this->uri, [
            'query' => [
                'MAIL' => urlencode('test@test.com')
            ],
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->assertSame($response->result, 1);
        $this->assertNull($response->error);
    }
}