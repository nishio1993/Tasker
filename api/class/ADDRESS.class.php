<?php

class ADDRESS extends ColumnBase {
    public static function isValid(string $address) {
        return  Validation::isSingleByte($address) &&
                Validation::isIPAddress($address)
                ? true : false;
    }
}