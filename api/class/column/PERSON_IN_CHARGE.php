<?php
require_once('autoloader.php');

class PERSON_IN_CHARGE implements Column
{
    public static function isValid($value): bool
    {
        return  Validation::isSingleByte($value) &&
                Validation::isCorrectLength($value, 0, 256) &&
                Validation::isMail($value)
                ? true
                : false;
    }
}