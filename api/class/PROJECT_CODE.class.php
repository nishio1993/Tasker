<?php
require_once('api/utility/Security.class.php');
class PROJECT_CODE extends ColumnBase {
    public __construct() {
        $this->property = Security::GetGUID();
    }
}