<?php

abstract class Base {
    public function GetClass() {
        return get_called_class();
    }
}