<?php
require_once('class/validation.class.php');
require_once('class/column/ColumnBase.interface.php');
class PROJECT_CODE implements ColumnBase {
    public __construct() {
        $this->property = Security::GetGUID();
    }
}