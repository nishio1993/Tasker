<?php
require_once('autoloader.php');

class Logger {
    private static function Log(string $log, string $type, string $user) : void {
        $today     = date('Ymd');
        $timestamp = Security::CreateTimeStamp('Y-m-d H:i:s.u');

        if (!file_exists("../log")) {
            mkdir("../log");
        }
        if (!file_exists("../log/{$type}")) {
            mkdir("../log/{$type}");
        }
        if (!file_exists("../log/{$type}/{$today}.log")) {
            touch("../log/{$type}/{$today}.log");
        }
        $logFile = new SplFileObject("../log/{$type}/{$today}.log", 'a');
        $logFile->fwrite("{$timestamp} : {$user} : {$log}\n");
        unset($logFile);
    }

    public static function DEBUG(string $log, string $user = 'unknown') : void {
        self::Log($log, 'debug', $user);
    }

    public static function INFO(string $log, string $user = 'unknown') : void {
        self::Log($log, 'info', $user);
    }

    public static function ERROR(string $log, string $user = 'unknown') : void {
        self::Log($log, 'error', $user);
    }

    public static function SQL(string $sql, int $count, array $placeHolder = [], string $user = 'unknown') : void {
        $tmp[] = "影響件数 = {$count}";
        $tmp[] = $sql;
        if ($placeHolder !== []) {
            foreach($placeHolder as $row) {
                $tmp[] = "Parameter = {$row['parameter']}, Value = {$row['value']}, Type = {$row['type']}";
            }
        }
        self::Log(join("\n", $tmp), 'sql', $user);
    }
}