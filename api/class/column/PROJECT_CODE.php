<?php
require_once('autoloader.php');

class PROJECT_CODE implements Column {
    public static function isValid($value): bool
    {
        return  Validation::isSingleByte($value) &&
                Validation::isAlphaNum($value) &&
                Validation::isCorrectLength($value, 36, 36)
                ? true
                : false;
    }
}