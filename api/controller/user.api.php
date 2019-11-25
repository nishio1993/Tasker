<?php
require_once('class/row/USER.class.php');
require_once('class/column/MAIL.class.php');
require_once('class/column/NAME.class.php');
require_once('class/column/PASSWORD.class.php');
require_once('class/Security.class.php');

function GET() {
    if (is_string($_GET['MAIL'])) {
        $MAIL = urldecode((string)filter_input(INPUT_GET, 'MAIL'));
        if (!MAIL::isCorrectValue($MAIL)) {
            $response['error']['message'] = 'メールアドレスが正しい形式ではありません。';
            return $response;
        }
        $USER = USER::findByMAIL($MAIL);
        $response['result']['USER'] = $USER->toArray();
        return $response;
    } else if (is_array($_GET['MAIL'])) {
        $MAILList = filter_input(INPUT_GET, 'MAIL', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach($MAILList as $MAIL) {
            if (!MAIL::isCorrectValue($MAIL)) {
                $response['error']['reason'][] = $MAIL;
            }
        }
        if (isset($response['error']['reason'])) {
            return $response;
        }
        $USERList = USER::findByMAIL($MAIL);
        foreach($USERList as $USER) {
            $response['result']['USER'][] = $USER->toArray();
        }
        return $response;
    } else {
        $response['error']['message'] = 'メールアドレスが送信されていません。';
        return $response;
    }
}

function POST() {
    $MAIL = urldecode((string)filter_input(INPUT_POST, 'MAIL'));
    $NAME = urldecode((string)filter_input(INPUT_POST, 'NAME'));
    $PASSWORD = urldecode((string)filter_input(INPUT_POST, 'PASSWORD'));
    if (!MAIL::isCorrectValue($MAIL)) {
        $response['error'][] = 'MAIL';
    }
    if (!NAME::isCorrectValue($NAME)) {
        $response['error'][] = 'NAME';
    }
    if (!PASSWORD::isCorrectValue($PASSWORD)) {
        $response['error'][] = 'PASSWORD';
    }
    if (isset($response['error'])) {
        return $response;
    }
    $USER = new USER();
    $USER->MAIL = $MAIL;
    $USER->NAME = $NAME;
    $USER->PASSWORD = Security::ToHash($PASSWORD);
    $response['result'] = $USER->Regist();
    return $response;
}

function PUT() {
    parse_str(file_get_contents('php://input'), $parameter);
    $MAIL = urldecode((string)$parameter['MAIL']);
    $USER = USER::findByMAIL($MAIL);
    $response = [];
    if (isset($parameter['NAME'])) {
        $NAME = urldecode((string)$parameter['NAME']);
        if (NAME::isCorrectValue($NAME)) {
            $USER->NAME = $NAME;
            $response['result']['NAME'] = $USER->regist();
        }
    }
    if (isset($parameter['PASSWORD'])) {
        $PASSWORD = urldecode((string)$parameter['PASSWORD']);
        if (PASSWORD::isCorrectValue($PASSWORD)) {
            $USER->PASSWORD = Security::toHash($PASSWORD);
            $response['result']['PASSWORD'] = $USER->regist();
        }
    }
    return $response;
}

function DELETE() {
    parse_str(file_get_contents('php://input'), $parameter);
    $MAIL = urldecode((string)$parameter['MAIL']);
    if (MAIL::isCorrectValue($NAME)) {
        $USER = USER::findByMAIL($MAIL);
        $response['result'] = $USER->unregist();
        return $response;
    }
    $response['error'] = 'MAIL';
    return $response;
}