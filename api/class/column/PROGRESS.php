<?php
require_once('autoloader.php');

class PROGRESS implements Column
{
    public static function isValid($value): bool
    {
        return  Validation::isSingleByte($value) &&
                Validation::isNumber($value) &&
                Validation::isCorrectRange($value, 0, 100)
                ? true
                : false;
    }
}