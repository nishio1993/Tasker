<?php
require_once('autoloader.php');

class TEXT implements Column
{
    public static function isValid($value): bool
    {
        return !Validation::includingPlatformDependentCharacters($value) &&
                Validation::isCorrectLength($value, 1, 1024)
                ? true
                : false;
    }
}