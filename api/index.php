<?php
require_once('autoloader.php');

$PATH_INFO = (string)filter_input(INPUT_SERVER, 'PATH_INFO');
if (empty($PATH_INFO)) {
    Logger::ERROR('PathInfo is null');
    return;
}

if (file_exists('controller'.$PATH_INFO.'.api.php')) {
    try {
        require_once('controller'.$PATH_INFO.'.api.php');
        $method = (string)filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $response = [];
        Logger::INFO('PATH_INFO is '.$PATH_INFO.', method is '.$method);
        if (function_exists($method)) {
            DBFacade::Connect();
            DBFacade::Transaction();
            $response = $method();
            DBFacade::Commit();
            DBFacade::DisConnect();
            if (isset($response['error'])) {
                response(400, $response);
            } else {
                response(200, $response);
            }
        } else {
            $error = 'Method is invalid';
            Logger::ERROR('method is invalid');
            response(405, compact('error'));
        }
    } catch(Exception $e) {
        DBFacade::Rollback();
        DBFacade::DisConnect();
        $error = $e->getMessage();
        Logger::ERROR($error);
        response(400, compact('error'));
    }
} else {
    $error = '無効なURLです。';
    Logger::ERROR('PATH_INFO is invalid');
    response(404, compact('error'));
}

/**
 * JSONエンコードを返却する
 *
 * @param integer $code
 * @param array $response
 * @return void
 */
function response(int $code, array $response) : void
{
    header("Content-Type: application/json; charset=utf-8");
    header("X-Content-Type-Options: nosniff");
    http_response_code($code);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}