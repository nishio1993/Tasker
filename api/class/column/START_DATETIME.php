<?php
require_once('autoloader.php');

class START_DATETIME implements Column
{
    public static function isValid($value): bool
    {
        return  Validation::isSingleByte($value) &&
                Validation::isDateTime($value)
                ? true
                : false;
    }
}