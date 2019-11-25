<?php
require_once('class/Logger.class.php');
require_once('class/DBFacade.class.php');
header("Content-Type: application/json; charset=utf-8");
header("X-Content-Type-Options: nosniff");

$PATH_INFO = (string)filter_input(INPUT_SERVER, 'PATH_INFO');
if (empty($PATH_INFO)) {
    return;
}

if (file_exists('controller'.$PATH_INFO.'.api.php')) {
    try {
        require_once('controller'.$PATH_INFO.'.api.php');
        $method = (string)filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $response = [];
        if (function_exists($method)) {
            DBFacade::Connect();
            DBFacade::Transaction();
            $response = $method();
            DBFacade::Commit();
            DBFacade::DisConnect();
            response(200, $response);
        } else {
            $error = '405 Method Not Allowed';
            response(405, compact('error'));
        }
    } catch(Exception $e) {
        DBFacade::Rollback();
        DBFacade::DisConnect();
        $error = $e->getMessage();
        //Logger::ERROR();
        response(400, compact('error'));
    }
} else {
    $error = '無効なURLです。';
    response(404, compact('error'));
}

/**
 * JSONエンコード後返却する
 *
 * @param integer $code
 * @param array $response
 * @return void
 */
function response(int $code, array $response) : void {
    http_response_code($code);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}