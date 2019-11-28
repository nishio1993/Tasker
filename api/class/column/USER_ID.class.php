<?php
require_once('class/validation.class.php');
require_once('class/column/ColumnBase.interface.php');
class USER_ID implements ColumnBase {
    public static function isValid($value) : bool {
        return  Validation::isSingleByte($value) &&
                Validation::isCorrectLength($value, 8, 16) &&
               !Validation::includingPlatformDependentCharacters($value)
                ? true
                : false;
    }
}