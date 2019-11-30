<?php
require_once('autoloader.php');

class PROJECT_CODE implements ColumnBase {
    public __construct() {
        $this->property = Security::GetGUID();
    }
}