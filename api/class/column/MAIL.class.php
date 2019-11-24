<?php
require_once('class/validation.class.php');
require_once('class/column/ColumnBase.interface.php');
class MAIL implements ColumnBase {
    public static function isCorrectValue($value) : bool {
        return  Validation::isSingleByte($value) &&
                Validation::isCorrectLength($value, 1, 256) &&
                Validation::isMail($value)
                ? true
                : false;
    }
}