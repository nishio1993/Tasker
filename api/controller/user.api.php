<?php
require_once('class/row/USER.class.php');
require_once('class/column/MAIL.class.php');
require_once('class/column/NAME.class.php');
require_once('class/column/PASSWORD.class.php');
require_once('class/Security.class.php');
require_once('class/Logger.class.php');

function GET() {
    if (is_string($_GET['MAIL'])) {
        $MAIL = urldecode((string)filter_input(INPUT_GET, 'MAIL'));
        if (!MAIL::isCorrectValue($MAIL)) {
            Logger::ERROR("{$MAIL} is not MAIL");
            $response['error']['reason'][] = 'MAIL';
            $response['error']['message']['MAIL'] = 'メールアドレスが正しい形式ではありません。';
            return $response;
        }
        $USER = USER::findByMAIL($MAIL);
        $response['USER'] = $USER->toArray();
        unset($response['USER']['PASSWORD']);
        return $response;
    } else if (is_array($_GET['MAIL'])) {
        $MAILList = filter_input(INPUT_GET, 'MAIL', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach($MAILList as $index => $MAIL) {
            $MAILList[$index] = urldecode($MAIL);
            if (!MAIL::isCorrectValue(urldecode($MAIL))) {
                $response['error']['reason'][] = $MAIL;
            }
        }
        if (isset($response['error']['reason'])) {
            return $response;
        }
        $USERList = USER::findByMAIL($MAILList);
        foreach($USERList as $index => $USER) {
            $response['USER'][$index] = $USER->toArray();
            unset($response['USER'][$index]['PASSWORD']);
        }
        return $response;
    } else {
        $response['error']['reason'][] = 'MAIL';
        $response['error']['message']['MAIL'] = 'メールアドレスが送信されていません。';
        return $response;
    }
}

function POST() {
    $MAIL = urldecode((string)filter_input(INPUT_POST, 'MAIL'));
    $NAME = urldecode((string)filter_input(INPUT_POST, 'NAME'));
    $PASSWORD = urldecode((string)filter_input(INPUT_POST, 'PASSWORD'));
    if (!MAIL::isCorrectValue($MAIL)) {
        $response['error']['reason'][] = 'MAIL';
    }
    if (!NAME::isCorrectValue($NAME)) {
        $response['error']['reason'][] = 'NAME';
    }
    if (!PASSWORD::isCorrectValue($PASSWORD)) {
        $response['error']['reason'][] = 'PASSWORD';
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
    $MAIL = urldecode((string)filter_input(INPUT_GET, 'MAIL'));
    $parameter = file_get_contents('php://input');
    $USER = new USER();
    $USER->MAIL = $MAIL;
    $NAME = urldecode((string)$parameter['NAME']);
    if (NAME::isCorrectValue($NAME)) {
        $USER->NAME = $NAME;
    }
    $PASSWORD = urldecode((string)$parameter['PASSWORD']);
    if (PASSWORD::isCorrectValue($PASSWORD)) {
        $USER->PASSWORD = Security::toHash($PASSWORD);
    }
    $response['result'] = $USER->regist();
    return $response;
}

function DELETE() {
    $MAIL = urldecode((string)filter_input(INPUT_GET, 'MAIL'));
    if (MAIL::isCorrectValue($MAIL)) {
        $USER = new USER;
        $USER->MAIL = $MAIL;
        $response['result'] = $USER->unregist();
        return $response;
    }
    $response['error']['reason'][] = 'MAIL';
    $response['error']['message']['MAIL'] = 'メールアドレスが正しい形式ではありません。';
    return $response;
}