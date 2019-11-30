<?php
require_once('autoloader.php');

class MAIL implements ColumnBase {
    public static function isValid($value) : bool {
        return  Validation::isSingleByte($value) &&
                Validation::isCorrectLength($value, 1, 256) &&
                Validation::isMail($value)
                ? true
                : false;
    }
}