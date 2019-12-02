<?php
require_once('autoloader.php');

class ADDRESS implements Column {
    public static function isValid(string $address) {
        return  Validation::isSingleByte($address) &&
                Validation::isIPAddress($address)
                ? true : false;
    }
}