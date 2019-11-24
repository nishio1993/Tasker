<?php
require_once('class/column/ColumnBase.interface.php');
require_once('class/Validation.class.php');
class PASSWORD implements ColumnBase {
    public static function isCorrectValue($value) : bool {
        return  Validation::isSingleByte($value) &&
               !Validation::includingPlatformDependentCharacters($value) &&
                Validation::isCorrectLength($value, 8, 16)
                ? true
                : false;
    }
}