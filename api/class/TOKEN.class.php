<?php
require_once('./validation.class.php');
require_once('./ColumnBase.class.php');
class TOKEN extends ColumnBase {
    public static function isCollectValue($value) : bool {
        return  Validation::isSingleByte($value) &&
                Validation::isCorrectLength($value, 36, 36) &&
                !Validation::includingPlatformDependentCharacters($value)
                ? true
                : false;
    }
}