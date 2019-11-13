<?php
require_once('./interface/Logger.interface.php');
class Logger implements LoggerInterface {
    private static function Log(string $log, string $type) : void {
        if (!file_exists("/log")) {
            mkdir("/log");
        }
        if (!file_exists("/log/{$type}")) {
            mkdir("/log/{$type}");
        }

        $now = date('Y/m/d');
        $microtime = microtime();

        $logfile = new SplFileObject("/log/{$type}/{$now}.log", 'a');
        $logfile->fwrite("{$microtime} : {$log}");
        $logfile = null;
    }

    public static function DEBUG(string $log) : void {
        self::Log($log, 'debug');
    }

    public static function INFO(string $log) : void {
        self::Log($log, 'info');
    }

    public static function ERROR(string $log) : void {
        self::Log($log, 'error');
    }

    public static function SQL(string $sql, int $count, array $placeHolder = []) : void {
        $tmp[] = "影響件数 = {$count}";
        $tmp[] = $sql;
        if ($placeHolder !== []) {
            foreach($placeHolder as $row) {
                $tmp[] = "Parameter = {$row['parameter']}, Value = {$row['value']}, Type = {$row['type']}";
            }
        }
        self::Log(join("\n", $tmp), 'sql');
    }
}