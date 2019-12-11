<?php
function GET() {
    if (is_string($_GET['MAIL'])) {
        $MAIL = urldecode((string)filter_input(INPUT_GET, 'MAIL'));
        if (!MAIL::isValid($MAIL)) {
            throw new InvalidArgumentException('MAIL('.$MAIL.') is invalid.');
        }
    
        $USER = USER::findByMAIL($MAIL);
        $response['USER'] = $USER->toArray();
        unset($response['USER']['PASSWORD']);
        return $response;
    } elseif (is_array($_GET['MAIL'])) {
        $MAILList = filter_input(INPUT_GET, 'MAIL', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $errtmp = [];
        foreach($MAILList as $index => $MAIL) {
            $MAILList[$index] = urldecode($MAIL);
            if (!MAIL::isValid(urldecode($MAIL))) {
                $errtmp[] = 'MAIL('.$MAIL.')';
            }
        }
        if ($errtmp !== []) {
            $error = join(" and", $errtmp).' are invalid.';
            throw new InvalidArgumentException($error);
        }
    
        $USERList = USER::findByMAIL($MAILList);
        foreach($USERList as $index => $USER) {
            $response['USER'][$index] = $USER->toArray();
            unset($response['USER'][$index]['PASSWORD']);
        }
        return $response;
    } else {
        throw new InvalidArgumentException('MAIL is required.');
    }
}

function POST() {
    $MAIL = urldecode((string)filter_input(INPUT_POST, 'MAIL'));
    $USER_NAME = urldecode((string)filter_input(INPUT_POST, 'USER_NAME'));
    $PASSWORD = urldecode((string)filter_input(INPUT_POST, 'PASSWORD'));

    $errtmp = [];
    if (!MAIL::isValid($MAIL)) {
        $errtmp[] = 'MAIL('.$MAIL.')';
    }
    if (!USER_NAME::isValid($USER_NAME)) {
        $errtmp[] = 'USER_NAME('.$USER_NAME.')';
    }
    if (!PASSWORD::isValid($PASSWORD)) {
        $errtmp[] = 'PASSWORD('.$PASSWORD.')';
    }
    if ($errtmp !== []) {
        $error = join(' and ', $errtmp).' are invalid.';
        throw new InvalidArgumentException($error);
    }

    $USER = new USER();
    $USER->MAIL = $MAIL;
    $USER->USER_NAME = $USER_NAME;
    $USER->PASSWORD = Security::ToHash($PASSWORD);
    $response['result'] = $USER->Regist();
    return $response;
}

function PUT() {
    $MAIL = urldecode((string)filter_input(INPUT_GET, 'MAIL'));
    $parameter = file_get_contents('php://input');
    $paramArray = explode('=', $parameter);
    $USER_NAME = $paramArray[0] === 'USER_NAME' ? urldecode($paramArray[1]) : '';
    $PASSWORD = $paramArray[0] === 'PASSWORD' ? urldecode($paramArray[1]) : '';

    $USER = new USER();
    $USER->MAIL = $MAIL;
    if (!MAIL::isValid($MAIL)) {
        throw new InvalidArgumentException('MAIL('.$MAIL.') is invalid.');
    }
    if (!empty($USER_NAME)) {
        $USER_NAME = urldecode((string)$USER_NAME);
        if (USER_NAME::isValid($USER_NAME)) {
            $USER->USER_NAME = $USER_NAME;
        } else {
            throw new InvalidArgumentException('USER_NAME('.$USER_NAME.') is invalid.');
        }
    }
    if (!empty($PASSWORD)) {
        $PASSWORD = urldecode((string)$PASSWORD);
        if (PASSWORD::isValid($PASSWORD)) {
            $USER->PASSWORD = Security::toHash($PASSWORD);
        } else {
            throw new InvalidArgumentException('PASSWORD('.$PASSWORD.') is invalid.');
        }
    }

    if (empty($USER_NAME) && empty($PASSWORD)) {
        throw new InvalidArgumentException('Either USER_NAME or PASSWORD is required.');
    } else {
        $response['result'] = $USER->save();
        return $response;
    }
}

function DELETE() {
    $MAIL = urldecode((string)filter_input(INPUT_GET, 'MAIL'));
    if (!MAIL::isValid($MAIL)) {
        throw new InvalidArgumentException('MAIL is required.');
    }

    $USER = new USER;
    $USER->MAIL = $MAIL;
    $response['result'] = $USER->unregist();
    return $response;
}