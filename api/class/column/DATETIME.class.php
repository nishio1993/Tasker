<?php
require_once('class/column/ColumnBase.interface.php');
abstract class DATETIME implements ColumnBase {
    public function isValid($value) : bool {
        return  Validation::isSingleByte($value) &&
                Validation::datetimeTryParse($value, 'Y-m-d H:i:s')
                ? true
                : false;
    }
}