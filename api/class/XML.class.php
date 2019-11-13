<?php
class XML {
    public static function Read(string $filepath){
        $configXML  = simplexml_load_file($filepath);
        $configArr  = json_decode(json_encode($configXML), true);
        return $configArr;
    }
}