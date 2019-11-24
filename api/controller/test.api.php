<?php
function GET() {
    $encode = urlencode('test@test.com');
    $decode = urldecode('test@test.com');
    $response['encode'] = $encode;
    $response['decode'] = $decode;
    return $response;
}