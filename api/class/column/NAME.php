<?php
require_once('autoloader.php');

class NAME implements ColumnBase {
    public static function isValid($value) : bool {
        return !Validation::includingPlatformDependentCharacters($value) &&
                Validation::isCorrectLength($value, 1, 32)
                ? true
                : false;
    }
}