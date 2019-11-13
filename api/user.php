<?php

require_once('./class/USER_ID.class.php');

//Content-Type指定
header('Content-Type: application/json');

//パラメータ取得
$method = (string)filter_input(INPUT_SERVER, 'REQUEST_METHOD');
$id     = (string)filter_input(INPUT_.$method, 'id');
$token  = (string)filter_input(INPUT_.$method, 'token');
if (empty($method) || empty($id) || empty($token)) {
    http_response_code(400);
    echo json_encode(compact('error'));
    return;
} else 

if ($method === 'GET') {

} else ($method === 'POST') {

} else ($method === 'PUT') {

} else ($method === 'DELETE') {

} else {
    $error = ''
    http_response_code(400);
    echo json_encode(compact('error'));
    return;
}