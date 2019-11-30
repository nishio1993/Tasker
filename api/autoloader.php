<?php
spl_autoload_register(function($className): void
{
    if (file_exists("class/module/{$className}.php")) {
        require_once("class/module/{$className}.php");
    } elseif (file_exists("class/column/{$className}.php")) {
        require_once("class/column/{$className}.php");
    } elseif (file_exists("class/record/{$className}.php")) {
        require_once("class/record/{$className}.php");
    } elseif (file_exists("class/column/{$className}.php")) {
        require_once("class/column/{$className}.php");
    }
});