<?php

class Security {
    /**
     * トークン生成関数
     * 
     * 32桁のトークンを返却する。
     * $_SESSION['token']に保存する。
     *
     * @return string
     */
    public static function CreateToken() : string {
        $token = bin2hex(random_bytes(16));
        $_SESSION['token'] = $token;
        return $token;
    }

    /**
     * トークン検証関数
     * 
     * $tokenが$_SESSION['token']と一致しているか検証する。
     * $_SESSION['token']を消去する。
     *
     * @param string $token
     * @return boolean
     */
    public static function VerifyToken(string $token) : bool {
        $result = false;
        if ($token === $_SESSION['token']) {
            $result = true;
        }
        $_SESSION['token'] = null;
        return $result;
    }

    /**
     * ハッシュ化関数
     * 
     * $passwordをArgon2iハッシュ化した結果を返却する。
     *
     * @param string $password
     * @return string
     */
    public static function ToHash(string $password) : string {
        $password = str_replace("\0", "", $password);
        return password_hash($password, PASSWORD_ARGON2I);
    }

    /**
     * ハッシュ検証関数
     * 
     * $passwordと$hashが一致しているか検証する。
     *
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    public static function VerifyHash(string $password, string $hash) : bool {
        return password_verify($password, $hash);
    }

    /**
     * UUID生成関数
     * 
     * UUIDを返却する。
     *
     * @return string
     */
    public static function CreateUUID() : string {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * タイムスタンプ生成関数
     * 
     * タイムスタンプを生成する。
     *
     * @param string $format
     * @return string
     */
    public static function CreateTimeStamp(string $format = 'YmdHisu') : string {
        $DateTimeImmutable = new DateTimeImmutable();
        return $DateTimeImmutable->format($format);
    }

    /**
     * HTMLエスケープ関数
     * 
     * $strに対してXSS対策を行う。
     *
     * @param string $str
     * @return string
     */
    public static function EscapeHTML(string $str) : string {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}