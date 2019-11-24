<?php
require_once('class/validation.class.php');
require_once('class/column/ColumnBase.interface.php');
class TOKEN implements ColumnBase {
    public static function isCollectValue($value) : bool {
        return  Validation::isSingleByte($value) &&
                Validation::isCorrectLength($value, 32, 32) &&
               !Validation::includingPlatformDependentCharacters($value) &&
                Validation::isHexadecimal($value)
                ? true
                : false;
    }
}