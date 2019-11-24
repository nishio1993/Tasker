<?php
require_once('class/validation.class.php');
require_once('class/column/ColumnBase.interface.php');
class END_DATETIME implements ColumnBase {
    public static function isCorrectValue($value) : bool {
        return  Validation::isSingleByte($value) &&
                Validation::datetimeTryParse($value, 'Y-m-d H:i:s')
                ? true
                : false;
    }
}