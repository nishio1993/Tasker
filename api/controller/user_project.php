<?php
function GET() {
    $MAIL = (string)filter_input(INPUT_GET, 'MAIL');
    $PROJECT_CODE = (string)filter_input(INPUT_GET, 'PROJECT_CODE');
    if (!empty($MAIL) && empty($PROJECT_CODE)) {
        $MAIL = new MAIL($MAIL);
        $PROJECT_CODEList = USER_PROJECT::FindByMAIL($MAIL);
        $response = [];
        foreach($PROJECT_CODEList as $PROJECT_CODE) {
            $response[] = PROJECT::FindByPROJECT_CODE($PROJECT_CODE)->ToArray();
        }
        return json_encode($response);
    } elseif (empty($MAIL) && !empty($PROJECT_CODE)) {
        $PROJECT_CODE = new PROJECT_CODE($PROJECT_CODE);
        $MAILList = USER_PROJECT::FindByPROJECT_CODE($PROJECT_CODE);
        $response = [];
        foreach($MAILList as $MAIL) {
            $response[] = USER::FindByMAIL($MAIL)->ToArray();
        }
        return json_encode($response);
    }
}

function POST() {
    $MAIL = new MAIL((string)filter_input(INPUT_POST, 'MAIL'));
    $PROJECT_CODE = new PROJECT_CODE((string)filter_input(INPUT_POST, 'PROJECT_CODE'));
    $USER_PROJECT = new USER_PROJECT([$MAIL, $PROJECT_CODE]);
    return json_encode($USER_PROJECT->Regist());
}

function PUT() {

}

function DELETE() {
    
}