<?php
require_once('class/validation.class.php');
require_once('class/column/ColumnBase.interface.php');
class ADDRESS implements ColumnBase {
    public static function isValid(string $address) {
        return  Validation::isSingleByte($address) &&
                Validation::isIPAddress($address)
                ? true : false;
    }
}