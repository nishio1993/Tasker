<?php
require_once('./validation.class.php');
require_once('./ColumnBase.class.php');
class USER_ID extends ColumnBase {
    public static function isCollectValue($value) : bool {
        return  Validation::isSingleByte($value) &&
                Validation::isCorrectLength($value, 8, 16) &&
                !Validation::includingPlatformDependentCharacters($value)
                ? true
                : false;
    }
}