<?php
require_once('./validation.class.php');
class END_DATETIME extends ColumnBase {
    public static function isCollectValue($value) : bool {
        return  Validation::isSingleByte($value) &&
                Validation::datetimeTryParse($value, 'Y-m-d H:i:s')
                ? true
                : false;
    }
}