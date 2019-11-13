<?php

abstract class ColumnBase {
    protected $property;

    public function __construct($value = null) {
        if (!is_null($value)) {
            $this->Set($value);
        }
    }

    public function Get() : mixed {
        return $this->property;
    }

    public function Set(string $value) : void {
        $trace = debug_backtrace();
        $ref = new ReflectionClass($trace[0]['object']);
        $file = basename($ref->getFilename());
        $inheritedClassName = str_replace('.class.php', '', $file);
        if ($inheritedClassName::isCollectValue($value)) {
            $this->property = $value;
        } else {
            throw new RuntimeException("{$value} is not {$inheritedClassName}");
        }
    }

    abstract public static function isCollectValue($value) : bool;
}