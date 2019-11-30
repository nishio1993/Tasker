<?php
require_once('autoloader.php');

class TOKEN implements ColumnBase {
    public static function isValid($value) : bool {
        return  Validation::isSingleByte($value) &&
                Validation::isCorrectLength($value, 32, 32) &&
               !Validation::includingPlatformDependentCharacters($value) &&
                Validation::isHexadecimal($value)
                ? true
                : false;
    }
}