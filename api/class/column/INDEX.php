<?php
require_once('autoloader.php');

class INDEX implements Column
{
    public static function isValid($value): bool
    {
        return  Validation::isSingleByte($value) &&
                Validation::isNumber($value) &&
                Validation::isCorrectRange($value, 0, 1999999)
                ? true
                : false;
    }
}