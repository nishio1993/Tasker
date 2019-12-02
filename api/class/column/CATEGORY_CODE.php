<?php
require_once('autoloader.php');

class CATEGORY_CODE implements Column
{
    public static function isValid($value): bool
    {
        return  Validation::isSingleByte($value) &&
                Validation::isCorrectLength($value, 54, 54)
                ? true
                : false;
    }
}