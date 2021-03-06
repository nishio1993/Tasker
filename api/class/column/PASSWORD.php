<?php
require_once('autoloader.php');

class PASSWORD implements Column {
    public static function isValid($value) : bool {
        return !Validation::includingPlatformDependentCharacters($value) &&
                Validation::isSingleByte($value) &&
                Validation::isCorrectLength($value, 8, 16)
                ? true
                : false;
    }
}