<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');

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
    public function MAILとUSER_NAMEとPASSWORDが揃えば登録可能() {
        $res = $this->client->post($this->uri, [
            'form_params' => [
                'MAIL'     => urlencode('test@test.com'),
                'USER_NAME'     => urlencode('テスト太郎'),
                'PASSWORD' => urlencode('password')
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertFalse(isset($response->error));
        $this->assertSame($response->result, 1);
    }

    /**
     * @test
     */
    public function MAILが被らなければ登録可能() {
        $res = $this->client->post($this->uri, [
            'form_params' => [
                'MAIL'     => urlencode('sample@sample.com'),
                'USER_NAME'     => urlencode('テスト太郎'),
                'PASSWORD' => urlencode('password')
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertFalse(isset($response->error));
        $this->assertSame($response->result, 1);
    }

    /**
     * @test
     */
    public function MAILが被ると登録不可能() {
        $this->expectException(GuzzleHttp\Exception\ClientException::class);
        $res = $this->client->post($this->uri, [
            'form_params' => [
                'MAIL'     => urlencode('test@test.com'),
                'USER_NAME'     => urlencode('テスト太郎'),
                'PASSWORD' => urlencode('password')
            ]
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue(isset($response->error));
        $this->assertFalse(isset($response->result));
    }

    /**
     * @test
     */
    public function MAILが無いと登録不可能() {
        $this->expectException(GuzzleHttp\Exception\ClientException::class);
        $res = $this->client->post($this->uri, [
            'form_params' => [
                'USER_NAME'     => urlencode('テスト太郎'),
                'PASSWORD' => urlencode('password')
            ],
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->AssertSame($response->error->reason[0], 'MAIL');
        $this->assertFalse(isset($response->result));
    }

    /**
     * @test
     */
    public function MAILが空欄だと登録不可能() {
        $this->expectException(GuzzleHttp\Exception\ClientException::class);
        $res = $this->client->post($this->uri, [
            'form_params' => [
                'MAIL'     => urlencode(''),
                'USER_NAME'     => urlencode('テスト太郎'),
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
     * @test
     */
    public function USER_NAMEかPASSWORDが空欄だと登録不可能() {
        $this->expectException(GuzzleHttp\Exception\ClientException::class);
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
    public function MAIL単一指定でUSER一つ取得() {
        $res = $this->client->get($this->uri, [
            'query' => [
                'MAIL' => urlencode('test@test.com')
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertSame($response->USER->MAIL, 'test@test.com');
        $this->assertSame($response->USER->USER_NAME, 'テスト太郎');
        $this->assertFalse(isset($response->USER->PASSWORD));
        $this->assertTrue(isset($response->USER->CREATE_DATETIME));
        $this->assertTrue(isset($response->USER->UPDATE_DATETIME));
        $this->assertFalse(isset($response->error));
    }

    /**
     * @test
     */
    public function MAIL複数指定でUSER複数取得() {
        $res = $this->client->get($this->uri, [
            'query' => [
                'MAIL' => [
                    urlencode('test@test.com'),
                    urlencode('sample@sample.com')
                ]
            ]
        ]);

        $response = json_decode($res->getBody());
        $this->assertSame($response->USER[0]->MAIL, 'sample@sample.com');
        $this->assertSame($response->USER[0]->USER_NAME, 'テスト太郎');
        $this->assertFalse(isset($response->USER[0]->PASSWORD));
        $this->assertTrue(isset($response->USER[0]->CREATE_DATETIME));
        $this->assertTrue(isset($response->USER[0]->UPDATE_DATETIME));
        $this->assertSame($response->USER[1]->MAIL, 'test@test.com');
        $this->assertSame($response->USER[1]->USER_NAME, 'テスト太郎');
        $this->assertFalse(isset($response->USER[1]->PASSWORD));
        $this->assertTrue(isset($response->USER[1]->CREATE_DATETIME));
        $this->assertTrue(isset($response->USER[1]->UPDATE_DATETIME));
    }

    /**
     * @test
     */
    public function MAIL空欄だと取得不可能() {
        $this->expectException(GuzzleHttp\Exception\ClientException::class);
        $res = $this->client->get($this->uri, [
            'query' => [
                'MAIL' => urlencode('')
            ],
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->assertSame($response->error->reason[0], 'MAIL');
        $this->assertTrue(isset($response->error->message));
        $this->assertFalse(isset($response->result));
    }

    /**
     * @test
     */
    public function QueryにMAIL、BodyにUSER_NAMEで更新可能() {
        $res = $this->client->put($this->uri, [
            'query' => [
                'MAIL'     => 'test@test.com'
            ],
            'form_params' => [
                'USER_NAME' => '太郎テスト'
            ]
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->assertFalse(isset($response->error));
        $this->assertSame($response->result, 1);
    }

    /**
     * @test
     */
    public function QueryにMAIL、BodyにPASSWORDで更新可能() {
        $res = $this->client->put($this->uri, [
            'query' => [
                'MAIL'     => 'test@test.com'
            ],
            'form_params' => [
                'PASSWORD' => 'newpassword'
            ]
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->assertFalse(isset($response->error));
        $this->assertSame($response->result, 1);
    }

    /**
     * @test
     */
    public function QueryにMAILが無ければ更新不可能() {
        $this->expectException(GuzzleHttp\Exception\ClientException::class);
        $res = $this->client->put($this->uri, [
            'form_params' => [
                'USER_NAME'     => urlencode('太郎テスト'),
                'PASSWORD' => urlencode('password')
            ],
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->assertSame($response->error->reason[0], 'MAIL');
        $this->assertTrue(isset($response->error->message));
        $this->assertFalse(isset($response->result));
    }

    /**
     * @test
     */
    public function BodyにUSER_NAMEかPASSWORDが無ければ更新不可能() {
        $this->expectException(GuzzleHttp\Exception\ClientException::class);
        $res = $this->client->put($this->uri, [
            'query' => [
                'MAIL'     => urlencode('test@test.com')
            ],
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->assertSame($response->error->reason[0], 'USER_NAME');
        $this->assertSame($response->error->reason[1], 'PASSWORD');
        $this->assertTrue(isset($response->error->message));
        $this->assertFalse(isset($response->result));
    }

    /**
     * @test
     */
    public function QueryにMAILがあれば削除可能() {
        $res = $this->client->delete($this->uri, [
            'query' => [
                'MAIL' => urlencode('test@test.com')
            ],
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->assertSame($response->result, 1);
        $this->assertFalse(isset($response->error));
    }

    /**
     * @test
     */
    public function QueryにMAILがあれば削除可能2() {
        $res = $this->client->delete($this->uri, [
            'query' => [
                'MAIL' => urlencode('sample@sample.com')
            ],
        ],[
            'http_errors' => false
        ]);

        $response = json_decode($res->getBody());
        $this->assertSame($response->result, 1);
        $this->assertFalse(isset($response->error));
    }
}