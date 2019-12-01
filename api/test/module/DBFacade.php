<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('autoloader.php');

class DBFacadetest extends TestCase {
    /**
     *  接続可能
     *
     *  @test
     */
    public function Connect() {
        DBFacade::Connect();
        $this->assertTrue(true);
    }

    /**
     *  切断可能
     *
     *  @test
     */
    public function Disconnect() {
        DBFacade::Connect();
        DBFacade::DisConnect();
        $this->assertTrue(true);
    }

    /**
     *  二回接続可能
     *
     *  @test
     */
    public function ConnectConnect() {
        DBFacade::Connect();
        DBFacade::Connect();
        $this->assertTrue(true);
    }

    /**
     *  二回切断可能
     *
     *  @test
     */
    public function DisConnectDisConnect() {
        DBFacade::Connect();
        DBFacade::DisConnect();
        DBFacade::DisConnect();
        $this->assertTrue(true);
    }

    /**
     *  接続切断接続切断可能
     *
     *  @test
     */
    public function ConnectDisConnectConnectDisConnect() {
        DBFacade::Connect();
        DBFacade::DisConnect();
        DBFacade::Connect();
        DBFacade::DisConnect();
        $this->assertTrue(true);
    }

    /**
     *  接続前トランザクション不可能
     *
     *  @test
     */
    public function Transaction() {
        $this->expectException(Error::class);
        DBFacade::Transaction();
    }

    /**
     *  接続後トランザクション可能
     *
     *  @test
     */
    public function ConnectTransaction() {
        DBFacade::Connect();
        DBFacade::Transaction();
        $this->assertTrue(true);
    }

    /**
     *  トランザクション後トランザクション不可能
     *
     *  @test
     */
    public function ConnectTransactionTransaction() {
        $this->expectException(PDOException::class);
        DBFacade::DisConnect();
        DBFacade::Connect();
        DBFacade::Transaction();
        DBFacade::Transaction();
    }

    /**
     *  接続前コミット不可能
     *
     *  @test
     */
    public function Commit() {
        $this->expectException(Error::class);
        DBFacade::DisConnect();
        DBFacade::Commit();
    }

    /**
     *  トランザクション前コミット不可能
     *
     *  @test
     */
    public function ConnectCommit() {
        $this->expectException(PDOException::class);
        DBFacade::DisConnect();
        DBFacade::Connect();
        DBFacade::Commit();
    }

    /**
     *  トランザクション後コミット可能
     *
     *  @test
     */
    public function TransactionCommit() {
        DBFacade::DisConnect();
        DBFacade::Connect();
        DBFacade::Transaction();
        DBFacade::Commit();
        $this->assertTrue(true);
    }

    /**
     *  コミット後コミット不可能
     *
     *  @test
     */
    public function CommitCommit() {
        $this->expectException(PDOException::class);
        DBFacade::DisConnect();
        DBFacade::Connect();
        DBFacade::Transaction();
        DBFacade::Commit();
        DBFacade::Commit();
    }

    /**
     *  接続前ロールバック不可能
     *
     *  @test
     */
    public function Rollback() {
        $this->expectException(Error::class);
        DBFacade::DisConnect();
        DBFacade::Rollback();
    }

    /**
     *  トランザクション前ロールバック不可能
     *
     *  @test
     */
    public function ConnectRollback() {
        $this->expectException(PDOException::class);
        DBFacade::DisConnect();
        DBFacade::Connect();
        DBFacade::Rollback();
    }

    /**
     *  トランザクション後ロールバック可能
     *
     *  @test
     */
    public function TransactionRollback() {
        DBFacade::DisConnect();
        DBFacade::Connect();
        DBFacade::Transaction();
        DBFacade::Rollback();
        $this->assertTrue(true);
    }

    /**
     *  ロールバック後ロールバック不可能
     *
     *  @test
     */
    public function RollbackRollback() {
        $this->expectException(PDOException::class);
        DBFacade::DisConnect();
        DBFacade::Connect();
        DBFacade::Transaction();
        DBFacade::Rollback();
        DBFacade::Rollback();
    }

    /**
     *  ExecuteByQueryInsert可能
     *
     *  @test
     */
    public function ExecuteByQueryInsert() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "INSERT INTO TEST";
        $sql[] = "( MAIL )";
        $sql[] = "VALUES";
        $sql[] = "( 'test@test.com' )";
        $this->assertSame(DBFacade::Execute(join("\n", $sql)), 1);
    }

    /**
     *  ExecuteByQuery不正Insert不可能
     *
     *  @test
     */
    public function ExecuteByQueryInsertException() {
        $this->expectException(RuntimeException::class);
        DBFacade::Connect();
        $sql = [];
        $sql[] = "INSERT INTO TEST";
        $sql[] = "( MAIL )";
        $sql[] = "VALUES";
        $sql[] = "( 'test@test.com' )";
        DBFacade::Execute(join("\n", $sql));
    }

    /**
     *  ExecuteByQuerySelect可能
     *
     *  @test
     */
    public function ExecuteByQuerySelectKey() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "SELECT";
        $sql[] = "    MAIL";
        $sql[] = "FROM";
        $sql[] = "    TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = 'test@test.com'";
        $result = DBFacade::Execute(join("\n", $sql));
        $this->assertArrayHasKey('MAIL', $result[0]);
    }

    /**
     *  ExecuteByQuerySelect可能
     *
     *  @test
     */
    public function ExecuteByQuerySelectValue() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "SELECT";
        $sql[] = "    MAIL";
        $sql[] = "FROM";
        $sql[] = "    TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = 'test@test.com'";
        $result = DBFacade::Execute(join("\n", $sql));
        $this->assertContains('test@test.com', $result[0]);
    }

    /**
     *  ExecuteByQuery対象無しSelect空配列
     *
     *  @test
     */
    public function ExecuteByQuerySelectNoTarget() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "SELECT";
        $sql[] = "    MAIL";
        $sql[] = "FROM";
        $sql[] = "    TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = 'hoge'";
        $result = DBFacade::Execute(join("\n", $sql));
        $this->assertSame($result, []);
    }

    /**
     *  ExecuteByQuery不正Select不可能
     *
     *  @test
     */
    public function ExecuteByQuerySelectException() {
        $this->expectException(RuntimeException::class);
        DBFacade::Connect();
        $sql = [];
        $sql[] = "SELECT";
        $sql[] = "    MAIL";
        $sql[] = "FROM";
        $sql[] = "    TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = hoge";
        DBFacade::Execute(join("\n", $sql));
    }

    /**
     *  ExecuteByQueryUpdate可能
     *
     *  @test
     */
    public function ExecuteByQueryUpdate() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "UPDATE TEST SET";
        $sql[] = "    MAIL = 'TEST@TEST.COM'";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = 'test@test.com'";
        $this->assertSame(DBFacade::Execute(join("\n", $sql)), 1);
    }

    /**
     *  ExecuteByQuery対象無しUpdate可能
     *
     *  @test
     */
    public function ExecuteByQueryUpdateNoTarget() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "UPDATE TEST SET";
        $sql[] = "    MAIL = 'TEST@TEST.COM'";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = 'test@test.com'";
        $this->assertSame(DBFacade::Execute(join("\n", $sql)), 0);
    }

    /**
     *  ExecuteByQuery不正Update不可能
     *
     *  @test
     */
    public function ExecuteByQueryUpdateException() {
        $this->expectException(RuntimeException::class);
        DBFacade::Connect();
        $sql = [];
        $sql[] = "UPDATE TEST SET";
        $sql[] = "    MAIL = NULL";
        DBFacade::Execute(join("\n", $sql));
    }

    /**
     *  ExecuteByQueryDelete可能
     *
     *  @test
     */
    public function ExecuteByQueryDelete() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "DELETE FROM TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = 'TEST@TEST.COM'";
        $this->assertSame(DBFacade::Execute(join("\n", $sql)), 1);
    }

    /**
     *  ExecuteByQuery対象無しDelete可能
     *
     *  @test
     */
    public function ExecuteByQueryDeleteNoTarget() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "DELETE FROM TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = 'TEST@TEST.COM'";
        $this->assertSame(DBFacade::Execute(join("\n", $sql)), 0);
    }

    /**
     *  ExecuteByQuery不正Delete不可能
     *
     *  @test
     */
    public function ExecuteByQueryDeleteException() {
        $this->expectException(RuntimeException::class);
        DBFacade::Connect();
        $sql = [];
        $sql[] = "DELETE FROM TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = TEST@TEST.COM";
        DBFacade::Execute(join("\n", $sql));
    }

    /**
     *  文字列可能
     *  
     *  @test
     */
    public function GetPDOParamByString() {
        $this->assertSame(DBFacade::GetPDOParam('test'), PDO::PARAM_STR);
    }

    /**
     *  数値可能
     *  
     *  @test
     */
    public function GetPDOParamByInteger() {
        $this->assertSame(DBFacade::GetPDOParam(123), PDO::PARAM_INT);
    }

    /**
     *  浮動小数点付き可能
     *  
     *  @test
     */
    public function GetPDOParamByDouble() {
        $this->assertSame(DBFacade::GetPDOParam(12.3), PDO::PARAM_STR);
    }

    /**
     *  真偽値可能
     *  
     *  @test
     */
    public function GetPDOParamByBoolean() {
        $this->assertSame(DBFacade::GetPDOParam(false), PDO::PARAM_BOOL);
    }

    /**
     *  NULL可能
     *  
     *  @test
     */
    public function GetPDOParamByNULL() {
        $this->assertSame(DBFacade::GetPDOParam(null), PDO::PARAM_NULL);
    }

    /**
     *  KeyValue配列→プレースホルダ生成可能
     *  
     *  @test
     */
    public function CreatePlaceHolderByArray() {
        $placeHolderMaterial = [
            'key1' => 'value1',
            'key2' => 1234,
            'key3' => 12.34,
            'key4' => true,
            'key5' => null
        ];
        $placeHolder = DBFacade::CreatePlaceHolder($placeHolderMaterial);
        $this->assertSame($placeHolder[0]['parameter'], ':key1');
        $this->assertSame($placeHolder[0]['value'],     'value1');
        $this->assertSame($placeHolder[0]['type'],      PDO::PARAM_STR);
        $this->assertSame($placeHolder[1]['parameter'], ':key2');
        $this->assertSame($placeHolder[1]['value'],     1234);
        $this->assertSame($placeHolder[1]['type'],      PDO::PARAM_INT);
        $this->assertSame($placeHolder[2]['parameter'], ':key3');
        $this->assertSame($placeHolder[2]['value'],     12.34);
        $this->assertSame($placeHolder[2]['type'],      PDO::PARAM_STR);
        $this->assertSame($placeHolder[3]['parameter'], ':key4');
        $this->assertSame($placeHolder[3]['value'],     true);
        $this->assertSame($placeHolder[3]['type'],      PDO::PARAM_BOOL);
        $this->assertSame($placeHolder[4]['parameter'], ':key5');
        $this->assertSame($placeHolder[4]['value'],     null);
        $this->assertSame($placeHolder[4]['type'],      PDO::PARAM_NULL);
    }

    /**
     *  テーブル形式配列→プレースホルダ生成可能
     *  
     *  @test
     */
    public function CreatePlaceHolderByAssociativeArray() {
        $placeHolderMaterial = [
            0 => [
                'key1' => 'value1'
            ],
            1 => [
                'key2' => 1234
            ],
            2 => [
                'key3' => 12.34
            ],
            3 => [
                'key4' => true
            ],
            4 => [
                'key5' => null
            ]
        ];
        $placeHolder = DBFacade::CreatePlaceHolder($placeHolderMaterial);
        $this->assertSame($placeHolder[0]['parameter'], ':key1');
        $this->assertSame($placeHolder[0]['value'],     'value1');
        $this->assertSame($placeHolder[0]['type'],      PDO::PARAM_STR);
        $this->assertSame($placeHolder[1]['parameter'], ':key2');
        $this->assertSame($placeHolder[1]['value'],     1234);
        $this->assertSame($placeHolder[1]['type'],      PDO::PARAM_INT);
        $this->assertSame($placeHolder[2]['parameter'], ':key3');
        $this->assertSame($placeHolder[2]['value'],     12.34);
        $this->assertSame($placeHolder[2]['type'],      PDO::PARAM_STR);
        $this->assertSame($placeHolder[3]['parameter'], ':key4');
        $this->assertSame($placeHolder[3]['value'],     true);
        $this->assertSame($placeHolder[3]['type'],      PDO::PARAM_BOOL);
        $this->assertSame($placeHolder[4]['parameter'], ':key5');
        $this->assertSame($placeHolder[4]['value'],     null);
        $this->assertSame($placeHolder[4]['type'],      PDO::PARAM_NULL);
    }
    
    /**
     *  WhereIn句対応プレースホルダ生成可能
     *  
     *  @test
     */
    public function createPlaceHolderForWhereIn() {
        $material = [
            'key1' => 'value1',
            'key2' => [1234, 12.34, true],
            'key3' => null
        ];
        $placeHolder = DBFacade::createPlaceHolder($material);
        $this->assertSame($placeHolder[0]['parameter'], ':key1');
        $this->assertSame($placeHolder[0]['value'],     'value1');
        $this->assertSame($placeHolder[0]['type'],      PDO::PARAM_STR);
        $this->assertSame($placeHolder[1]['parameter'], ':key20');
        $this->assertSame($placeHolder[1]['value'],     1234);
        $this->assertSame($placeHolder[1]['type'],      PDO::PARAM_INT);
        $this->assertSame($placeHolder[2]['parameter'], ':key21');
        $this->assertSame($placeHolder[2]['value'],     12.34);
        $this->assertSame($placeHolder[2]['type'],      PDO::PARAM_STR);
        $this->assertSame($placeHolder[3]['parameter'], ':key22');
        $this->assertSame($placeHolder[3]['value'],     true);
        $this->assertSame($placeHolder[3]['type'],      PDO::PARAM_BOOL);
        $this->assertSame($placeHolder[4]['parameter'], ':key3');
        $this->assertSame($placeHolder[4]['value'],     null);
        $this->assertSame($placeHolder[4]['type'],      PDO::PARAM_NULL);
    }
    
    /**
     *  ExecuteByPrepareStatementInsert可能
     *
     *  @test
     */
    public function ExecuteByPrepareStatementInsert() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "INSERT INTO TEST";
        $sql[] = "( MAIL )";
        $sql[] = "VALUES";
        $sql[] = "( :MAIL )";
        $placeHolder = [':MAIL' => 'test@test.com'];
        $this->assertSame(DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder)), 1);
    }
    
    /**
     *  ExecuteByPrepareStatement不正Insert不可能
     *
     *  @test
     */
    public function ExecuteByPrepareStatementInsertException() {
        //$this->expectException(RuntimeException::class);
        DBFacade::Connect();
        $sql = [];
        $sql[] = "INSERT INTO TEST";
        $sql[] = "( MAIL )";
        $sql[] = "VALUES";
        $sql[] = "( :MAIL )";
        $placeHolder = ['MAIL' => 'test@test.com'];
        DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder));
    }
    
    /**
     *  ExecuteByPrepareStatementSelect可能
     *
     *  @test
     */
    public function ExecuteByPrepareStatementSelectKey() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "SELECT";
        $sql[] = "    MAIL";
        $sql[] = "FROM";
        $sql[] = "    TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = :MAIL";
        $placeHolder = ['MAIL' => 'test@test.com'];
        $result = DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder));
        $this->assertArrayHasKey('MAIL', $result[0]);
    }
    
    /**
     *  ExecuteByPrepareStatementSelect可能
     *
     *  @test
     */
    public function ExecuteByPrepareStatementSelectValue() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "SELECT";
        $sql[] = "    MAIL";
        $sql[] = "FROM";
        $sql[] = "    TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = :MAIL";
        $placeHolder = ['MAIL' => 'test@test.com'];
        $result = DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder));
        $this->assertContains('test@test.com', $result[0]);
    }
    
    /**
     *  ExecuteByPrepareStatement対象無しSelect空配列
     *
     *  @test
     */
    public function ExecuteByPrepareStatementSelectNoTarget() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "SELECT";
        $sql[] = "    MAIL";
        $sql[] = "FROM";
        $sql[] = "    TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = :MAIL";
        $placeHolder = ['MAIL' => null];
        $result = DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder));
        $this->assertSame($result, []);
    }
    
    /**
     *  ExecuteByPrepareStatement不正Select不可能
     *
     *  @test
     */
    public function ExecuteByPrepareStatementSelectException() {
        //$this->expectException(PDOException::class);
        DBFacade::Connect();
        $sql = [];
        $sql[] = "SELECT";
        $sql[] = "    MAIL";
        $sql[] = "FROM";
        $sql[] = "    TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = :MAIL";
        $placeHolder = ['hoge' => 'fuga'];
        $result = DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder));
    }
    
    /**
     *  ExecuteByPrepareStatementUpdate可能
     *
     *  @test
     */
    public function ExecuteByPrepareStatementUpdate() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "UPDATE TEST SET";
        $sql[] = "    MAIL = 'TEST@TEST.COM'";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = :MAIL";
        $placeHolder = ['MAIL' => 'test@test.com'];
        $this->assertSame(DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder)), 1);
    }
    
    /**
     *  ExecuteByPrepareStatement対象無しUpdate可能
     *
     *  @test
     */
    public function ExecuteByPrepareStatementUpdateNoTarget() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "UPDATE TEST SET";
        $sql[] = "    MAIL = 'TEST@TEST.COM'";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = :MAIL";
        $placeHolder = ['MAIL' => 'test@test.com'];
        $this->assertSame(DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder)), 0);
    }
    
    /**
     *  ExecuteByPrepareStatement不正Update不可能
     *
     *  @test
     */
    public function ExecuteByPrepareStatementUpdateException() {
        //$this->expectException(Exception::class);
        DBFacade::Connect();
        $sql = [];
        $sql[] = "UPDATE TEST SET";
        $sql[] = "    MAIL = 'TEST@TEST.COM'";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = :MAIL";
        $placeHolder = ['hoge' => 'fuga'];
        DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder));
    }
    
    /**
     *  ExecuteByPrepareStatementDelete可能
     *
     *  @test
     */
    public function ExecuteByPrepareStatementDelete() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "DELETE FROM TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = :MAIL";
        $placeHolder = ['MAIL' => 'TEST@TEST.COM'];
        $this->assertSame(DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder)), 1);
    }
    
    /**
     *  ExecuteByPrepareStatement対象無しDelete可能
     *
     *  @test
     */
    public function ExecuteByPrepareStatementDeleteNoTarget() {
        DBFacade::Connect();
        $sql = [];
        $sql[] = "DELETE FROM TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = :MAIL";
        $placeHolder = ['MAIL' => 'TEST@TEST.COM'];
        $this->assertSame(DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder)), 0);
    }

    /**
     *  ExecuteByPrepareStatement不正Delete不可能
     *
     *  @test
     */
    public function ExecuteByPrepareStatementDeleteException() {
        //$this->expectException(RuntimeException::class);
        DBFacade::Connect();
        $sql = [];
        $sql[] = "DELETE FROM TEST";
        $sql[] = "WHERE";
        $sql[] = "    MAIL = :MAIL";
        $placeHolder = ['hoge' => 'fuga'];
        DBFacade::Execute(join("\n", $sql), DBFacade::CreatePlaceHolder($placeHolder));
    }
}