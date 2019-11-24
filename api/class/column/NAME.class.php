<?php
require_once('class/column/ColumnBase.interface.php');
require_once('class/Validation.class.php');
class NAME implements ColumnBase {
    public static function isCollectValue($value) : bool {
        return !Validation::includingPlatformDependentCharacters($value) &&
                Validation::isCorrectLength($value, 1, 32)
                ? true
                : false;
    }
}