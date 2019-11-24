<?php

$unittestList = glob('unittest/*.php');
foreach($unittestList as $unittest) {
    $cmd = 'vendor/bin/phpunit '.$unittest;
    $file = new SplFileObject('phpunit.cmd', 'w');
    $file->fwrite($cmd);
    $file = null;
    exec("cmd.exe phpunit.cmd", $result);
    echo(join("\n", $result));
}