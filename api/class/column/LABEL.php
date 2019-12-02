<?php
require_once('autoloader.php');

class LABEL implements Column
{
    public static function isValid($value): bool
    {
        return  Validation::isSingleByte($value) &&
                Validation::isNumber($value) &&
                Validation::isCorrectLength($value, 1, 1)
                ? true
                : false;
    }
}