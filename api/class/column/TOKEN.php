<?php
require_once('autoloader.php');

class TOKEN implements Column {
    public static function isValid($value) : bool {
        return !Validation::includingPlatformDependentCharacters($value) &&
                Validation::isCorrectLength($value, 32, 32) &&
                Validation::isSingleByte($value) &&
                Validation::isHexadecimal($value)
                ? true
                : false;
    }
}