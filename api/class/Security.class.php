<?php

class Security {
    public static function CreateToken() : string {
        $token = bin2hex(random_bytes(16));
        $_SESSION['token'] = $token;
        return $token;
    }

    public static function VerifyToken(string $token) : bool {
        $result = false;
        if ($token === $_SESSION['token']) {
            $result = true;
        }
        $_SESSION['token'] = null;
        return $result;
    }

    public static function CreatePasswordHash(string $password) : string {
        $password = str_replace("\0", "", $password);
        return password_hash($password, PASSWORD_ARGON2I);
    }

    public static function VerifyPasswordHash(string $password, string $hash) : bool {
        return password_verify($password, $hash);
    }

    public static function EscapeHTML(string $str) : string {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    public static function CreateGUID() : string {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public static function CreateTimeStamp(string $format = 'YmdHisu') : string {
        $DateTimeImmutable = new DateTimeImmutable();
        return $DateTimeImmutable->format($format);
    }
}