<?php
require_once('autoloader.php');

class TASK_NAME implements Column
{
    public static function isValid($value): bool
    {
        return !Validation::includingPlatformDependentCharacters($value) &&
                Validation::isCorrectLength($value, 1, 64)
                ? true
                : false;
    }
}