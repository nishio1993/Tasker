<?php
require_once('class/record/USER.class.php');
require_once('class/column/MAIL.class.php');
require_once('class/column/NAME.class.php');
require_once('class/column/PASSWORD.class.php');
require_once('class/Security.class.php');
require_once('class/Logger.class.php');

function GET() {
    if (is_string($_GET['MAIL'])) {
        $MAIL = urldecode((string)filter_input(INPUT_GET, 'MAIL'));
        if (!MAIL::isValid($MAIL)) {
            Logger::ERROR("{$MAIL} is not MAIL");
            $response['error']['reason'][] = 'MAIL';
            $response['error']['message']['MAIL'] = 'メールアドレスが正しい形式ではありません。';
            return $response;
        }
        $USER = USER::findByMAIL($MAIL);
        $response['USER'] = $USER->toArray();
        unset($response['USER']['PASSWORD']);
        return $response;
    } elseif (is_array($_GET['MAIL'])) {
        $MAILList = filter_input(INPUT_GET, 'MAIL', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach($MAILList as $index => $MAIL) {
            $MAILList[$index] = urldecode($MAIL);
            if (!MAIL::isValid(urldecode($MAIL))) {
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
        throw new InvalidArgumentException('MAIL is required');
    }
}

function POST() {
    $MAIL = urldecode((string)filter_input(INPUT_POST, 'MAIL'));
    $NAME = urldecode((string)filter_input(INPUT_POST, 'NAME'));
    $PASSWORD = urldecode((string)filter_input(INPUT_POST, 'PASSWORD'));
    if (!MAIL::isValid($MAIL)) {
        $response['error']['reason'][] = 'MAIL('.$MAIL.')';
    }
    if (!NAME::isValid($NAME)) {
        $response['error']['reason'][] = 'NAME('.$NAME.')';
    }
    if (!PASSWORD::isValid($PASSWORD)) {
        $response['error']['reason'][] = 'PASSWORD('.$PASSWORD.')';
    }
    if (isset($response['error'])) {
        $error = join(' and ', $response['error']['reason']);
        $error = $error.' are required';
        throw new InvalidArgumentException($error);
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
    if (!MAIL::isValid($MAIL)) {
        throw new InvalidArgumentException('MAIL('.$MAIL.') is invalid');
    }
    $parameter = file_get_contents('php://input');
    $paramArray = explode('=', $parameter);
    $NAME = $paramArray[0] === 'NAME' ? $paramArray[1] : '';
    $PASSWORD = $paramArray[0] === 'PASSWORD' ? $paramArray[1] : '';
    $USER = new USER();
    $USER->MAIL = $MAIL;
    $response = [];
    if (!empty($NAME)) {
        $NAME = urldecode((string)$NAME);
        if (NAME::isValid($NAME)) {
            $USER->NAME = $NAME;
        } else {
            throw new InvalidArgumentException('NAME('.$NAME.') is invalid');
        }
    }
    if (!empty($PASSWORD)) {
        $PASSWORD = urldecode((string)$PASSWORD);
        if (PASSWORD::isValid($PASSWORD)) {
            $USER->PASSWORD = Security::toHash($PASSWORD);
        } else {
            throw new InvalidArgumentException('PASSWORD('.$PASSWORD.' is invalid');
        }
    }
    if (isset($response['error'])) {
        return $response;
    } elseif (!empty($NAME) || !empty($PASSWORD)) {
        $response['result'] = $USER->regist();
        return $response;
    } else {
        throw new InvalidArgumentException('Either NAME or PASSWORD is required');
    }
}

function DELETE() {
    $MAIL = urldecode((string)filter_input(INPUT_GET, 'MAIL'));
    if (MAIL::isValid($MAIL)) {
        $USER = new USER;
        $USER->MAIL = $MAIL;
        $response['result'] = $USER->unregist();
        return $response;
    }
    throw new InvalidArgumentException('MAIL is required');
}