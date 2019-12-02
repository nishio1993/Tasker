<?php

class Validation {
    /**
     * 環境依存文字チェック関数
     * 
     * 引数に環境依存文字が含まれている場合true、含まれていなければfalse
     *
     * @param string $str
     * @return boolean
     */
    public static function includingPlatformDependentCharacters(string $str) : bool{
        return strlen($str) !== strlen(mb_convert_encoding(mb_convert_encoding($str,'SJIS','UTF-8'),'UTF-8','SJIS'));
    }

    /**
     * 英字チェック関数
     * 
     * 引数が英字のみの場合true、そうでなければfalse
     *
     * @param string $str
     * @return boolean
     */
    public static function isAlphabet(string $str) : bool{
        return preg_match("/^[a-zA-Z]+$/", mb_convert_kana($str, 'a'));
    }

    /**
     * 数字チェック関数
     * 
     * 引数が数字のみの場合true、そうでなければfalse
     *
     * @param string $str
     * @return boolean
     */
    public static function isNumber(string $str) : bool{
        return preg_match("/^[0-9]+$/", mb_convert_kana($str, 'a'));
    }

    /**
     * 英数字チェック関数
     * 
     * 引数が英数字のみの場合true、そうでなければfalse
     *
     * @param string $str
     * @return boolean
     */
    public static function isAlphaNum(string $str) : bool{
        return preg_match("/^[a-zA-Z0-9]+$/", mb_convert_kana($str, 'a'));
    }

    /**
     * 半角チェック関数
     * 
     * 引数が半角のみの場合true、そうでなければfalse
     *
     * @param string $str
     * @return boolean
     */
    public static function isSingleByte(string $str) : bool{
        return mb_strlen($str) == mb_strwidth($str);
    }

    /**
     * 日付型チェック関数
     * 
     * 引数が日付型の場合true、そうでなければfalse
     *
     * @param string $str
     * @return boolean
     */
    public static function isDate(string $str) : bool{
        return  self::datetimeTryParse($str) &&
                strlen(str_replace(['-', '/', '年', '月', '日', '時', '分', '秒', '.'], '', $str)) === 8;
    }

    /**
     * 時刻型チェック関数
     * 
     * 引数が時刻型の場合true、そうでなければfalse
     *
     * @param string $str
     * @return boolean
     */
    public static function isTime(string $str) : bool{
        return  self::datetimeTryParse($str) &&
                strlen(str_replace(['-', '/', '年', '月', '日', '時', '分', '秒', '.', ' '], '', $str)) === 6;
    }

    /**
     * 日時型チェック関数
     * 
     * 引数が日時型の場合true、そうでなければfalse
     *
     * @param string $str
     * @return boolean
     */
    public static function isDateTime(string $str) : bool{
        return  self::datetimeTryParse($str) &&
                strlen(str_replace(['-', '/', '年', '月', '日', '時', '分', '秒', '.', ' '], '', $str)) === 14;
    }

    /**
     * 日時型変換関数
     * 
     * $dateがdate型・time型・datetime型の場合true、そうでなければfalse
     * $formatに合わせて$dateを変換
     *
     * @param string $date
     * @param string $format
     * @return boolean
     */
    public static function datetimeTryParse(string &$date, string $format = "Y-m-d H:i:s") : bool{
        $str  = strtotime(str_replace(['-', '/', '年', '月', '日', '時', '分', '秒', '.', ' '], '', $date));
        $date = $str !== false ? date($format, $str) : $date;
        return  $str !== false;
    }

    /**
     * メールアドレスチェック関数
     * 
     * 引数がメールアドレスの場合true、そうでなければfalse
     *
     * @param string $str
     * @return boolean
     */
    public static function isMail(string $str) : bool{
        return  filter_var($str, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE) && 
                preg_match('/@([^@\[]++)\z/', $str);
    }

    /**
     * 電話番号チェック関数
     * 
     * 引数が電話番号の場合true、そうでなければfalse
     *
     * @param string $str
     * @return boolean
     */
    public static function isTel(string $str) : bool{
        if (strlen($str) > 11 && !preg_match("/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/", $str) ){
            return false;
        }
        $tmpstr = str_replace('-', '', $str);
        return (strlen($tmpstr) === 10 || strlen($tmpstr) === 11) && self::isNumber($tmpstr);
    }

    /**
     * 郵便番号チェック関数
     * 
     * 引数が郵便番号の場合true、そうでなければfalse
     *
     * @param string $str
     * @return boolean
     */
    public static function isPost(string $str) : bool{
        if (strlen($str) > 7 && !preg_match("/^[0-9]{3}-[0-9]{4}$/", $str) ){
            return false;
        }
        $tmpstr = str_replace('-', '', $str);
        return strlen($tmpstr) === 7 && self::isNumber($tmpstr);
    }

    /**
     * 文字列長さチェック関数
     * 
     * $strの長さが$min以上かつ$max以下の場合trueを返却
     * $min未満または$max超の場合falseを返却
     * 
     * @param   string  $str
     * @param   integer $min
     * @param   integer $max
     * @return  boolean
     */
    public static function isCorrectLength(string $str, int $min, int $max) : bool {
        return $min <= $max && $min <= mb_strlen($str) && mb_strlen($str) <= $max;
    }

    /**
     * 文字列バイト数チェック関数
     * 
     * $strのバイト数が$min以上かつ$max以下の場合trueを返却
     * $min未満または$max超の場合falseを返却
     * 
     * @param   string  $str
     * @param   integer $min
     * @param   integer $max
     * @return  boolean
     */
    public static function isCorrectByte(string $str, int $min, int $max) : bool {
        return $min <= $max && $min <= strlen($str) && strlen($str) <= $max;
    }

    /**
     * 数値範囲チェック関数
     * 
     * $strのが$min以上かつ$max以下の場合trueを返却
     * $min未満または$max超の場合falseを返却
     * 
     * @param   string  $str
     * @param   integer $min
     * @param   integer $max
     * @return  boolean
     */
    public static function isCorrectRange($str, int $min, int $max): bool
    {
        return $min <= $max && $min <= (int)$str && (int)$str <= $max;
    }

    /**
     * 16進数チェック関数
     * 
     * $strが16進数の場合trueを返却
     * 16進数ではない場合falseを返却
     * 
     * @param   string  $str    チェック対象文字列
     * @return  boolean
     */
    public static function isHexadecimal(string $str) : bool {
        return preg_match("/^[a-fA-F0-9]+$/", mb_convert_kana($str, 'a'));
    }

    /**
     * TINYINTチェック関数
     * 
     * $strが0以上255以下の場合trueを返却
     * そうではない場合Falseを返却
     *
     * @param string $value
     * @return boolean
     */
    public static function isTinyInt(string $value): bool
    {
        return 0 <= $value && $value <= 255;
    }
}