<?php

final class Validation {
    /****************************************
    * プロパティ
    * @param $errMsg    targetのvalidaion結果メッセージの配列
    * @param $errVal    不正データのkey、value、reasonの配列
    ****************************************/
    private static $pattern;
    public  static $errMsg;
    public  static $errVal;

    /****************************************
    * 入力内容チェック関数
    * @return 不正な値がある場合true、ない場合false
    ****************************************/
    public static function ExecuteForInput(array $target) : bool{
        self::$errMsg = array();
        if (gettype($target) !== array()){
            
        }
        foreach($target as $key => $value){
            //type="checkbox"など配列で送信される場合があるため
            if (is_array($value)){
                foreach($value as $arrayValue){
                    self::Execute($key, $arrayValue);
                }
            } else {
                self::Execute($key, $value);
            }
        }
        return self::$errMsg !== array();
    }
    
    /****************************************
    * バリデーション実行関数
    * @param    $key    項目名
    * @param    $value  項目内容
    ****************************************/
/*
    public static function Execute(string $key, string $value) : bool{
        if (!compact(self::$errMsg)) {
            self::$errMsg = array();
        }
        self::Prepare();

        //必須項目チェック
        if (self::$pattern[$key]['notnull'] && empty(trim($value)){
            self::$errMsg[] = "{$pattern[$key]['name']}が未入力です。";
            self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => 'notnull');
        }
        //長さチェック
        $length = explode('-', self::$pattern[$key]['length']);
        if (!self::isCorrectLength($value, $length[0], $length[1])){
            self::$errMsg[] = "{$pattern[$key]['name']}が長すぎます。";
            self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => 'length');
        }
        //半角チェック
        if (self::$pattern[$key]['single'] && !self::isSingleByte($value) ){
            self::$errMsg[] = "{$pattern[$key]['name']}に全角が含まれています。";
            self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => 'single');
        }
        //環境依存文字チェック
        if (strcmp(trim($value), '') != 0 && self::includingPlatformDependentCharacters($value)){
            self::$errMsg[] = "{$pattern[$key]['name']}に環境依存文字が含まれています。";
            self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => 'dependency');
        }
        //空白値の場合型チェック無し
        if (empty(trim($value))){
            return self::$errMsg === array();
        }
        //型チェック
        switch (self::$pattern[$key]['type']) {
            case "alphanum":
                if (!self::isAlphaNum($value)){
                    self::$errMsg[] = "{self::$pattern[$key]['name']}に英数字以外の文字が含まれています。";
                    self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => $pattern[$key]['type']);
                }
                break;
            case "alphabet":
                if (!self::isAlphabet($value)){
                    self::$errMsg[] = "{self::$pattern[$key]['name']}に英字以外の文字が含まれています。";
                    self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => $pattern[$key]['type']);
                }
                break;
            case "number":
                if (!self::isNumber($value)){
                    self::$errMsg[] = "{self::$pattern[$key]['name']}に数字以外の文字が含まれています。";
                    self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => $pattern[$key]['type']);
                }
                break;
            case "double":
            
                break;
            case "date":
                if (!self::isDate($value) ){
                    self::$errMsg[] = "{self::$pattern[$key]['name']}が正しい日付ではありません。";
                    self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => $pattern[$key]['type']);
                }
                break;
            case "time":
                if (!self::isTime($value) ){
                    self::$errMsg[] = "{self::$pattern[$key]['name']}が正しい時刻ではありません。";
                    self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => $pattern[$key]['type']);
                }
                break;
            case "datetime":
                if (!self::isDateTime($value) ){
                    self::$errMsg[] = "{self::$pattern[$key]['name']}が正しい年月日時分秒ではありません。";
                    self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => $pattern[$key]['type']);
                }
                break;
            case "mail":
                if (!self::isMail($value)){
                    self::$errMsg[] = "{self::$pattern[$key]['name']}がメールアドレスではありません。";
                    self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => $pattern[$key]['type']);
                }
                break;
            case "tel":
                if (!self::isTel($value)){
                    self::$errMsg[] = "{self::$pattern[$key]['name']}が電話番号ではありません。";
                    self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => $pattern[$key]['type']);
                }
                break;
            case "post":
                if (!self::isPost($value)){
                    self::$errMsg[] = "{self::$pattern[$key]['name']}が郵便番号ではありません。";
                    self::$errVal[] = array('key' => $key, 'value' => $value, 'reason' => $pattern[$key]['type']);
                }
                break;
            case "string":
                break;
            default:
        }

        return self::$errMsg === array();
    }
*/
    /**
     * Validate準備関数
     * 
     * XMLファイルからバリデーションパターンを取得し、プロパティに保存する。
     * 
     * @return void
     */
    private static function Prepare() {
        if (!compact(self::$pattern) || self::$pattern == false){
            $xml   = simplexml_load_file("ini/mstmnt_common.xml");
            $array = json_decode(json_encode($xml), true);
            self::$pattern = array();
            foreach($array as $masterName => $columnName){
                foreach($columnName as $detail){
                    self::$pattern[$columnName] = $detail;
                }
            }
        }
        if (self::$pattern === false){
            throw new RuntimeException("Can't read mstmnt_common.xml");
        }
    }

    /****************************************
    * 環境依存文字チェック関数
    * @param    $str    チェック対象文字列
    * @return   環境文字が含まれている場合True、含まれていなければFalse
    ****************************************/
    public static function includingPlatformDependentCharacters(string $str) : bool{
        return strlen($str) !== strlen(mb_convert_encoding(mb_convert_encoding($str,'SJIS','UTF-8'),'UTF-8','SJIS'));
    }
    
    /****************************************
    * 英字チェック関数
    * @param    $str    チェック対象文字列
    * @return   全て英字であればTrue、英字以外が含まれていればFalse
    ****************************************/
    public static function isAlphabet(string $str) : bool{
        return preg_match("/^[a-zA-Z]+$/", mb_convert_kana($str, 'a'));
    }
    
    /****************************************
    * 数字チェック関数
    * @param    $str    チェック対象文字列
    * @return   全て数字であればTrue、数字以外が含まれていればFalse
    ****************************************/
    public static function isNumber(string $str) : bool{
        return preg_match("/^[0-9]+$/", mb_convert_kana($str, 'a'));
    }
    
    /****************************************
    * 英数字チェック関数
    * @param    $str    チェック対象文字列
    * @return   全て英数字であればTrue、英数字以外が含まれていればFalse
    ****************************************/
    public static function isAlphaNum(string $str) : bool{
        return preg_match("/^[a-zA-Z0-9]+$/", mb_convert_kana($str, 'a'));
    }
    
    /****************************************
    * 半角チェック関数
    * @param    $str    チェック対象文字列
    * @return   全て半角であればTrue、全角が含まれていればFalse
    ****************************************/
    public static function isSingleByte(string $str) : bool{
        return mb_strlen($str) == mb_strwidth($str);
    }
    
    /****************************************
    * DateTimeチェック関数
    * @param    $str    チェック対象文字列
    * @return   日付変換可能であればTrue、不可能ならばFalse
    ****************************************/
    public static function isDate(string $str) : bool{
        return self::datetimeTryParse($str);
    }
    
    /****************************************
    * DateTimeチェック関数
    * @param    $str    チェック対象文字列
    * @return   日付変換可能であればTrue、不可能ならばFalse
    ****************************************/
    public static function isTime(string $str) : bool{
        return self::datetimeTryParse($str);
    }
    
    /****************************************
    * DateTime変換関数
    * @param    $str    チェック対象文字列
    * @param    &$date  変換結果
    * @param    $format 変換フォーマット
    * @return   日付変換可能であればTrue、不可能ならばFalse
    ****************************************/
    public static function datetimeTryParse(string &$date = "", string $format = "Y-m-d H:i:s") : bool{
        $str  = strtotime($date);
        $date = $str !== false
                ? date($format, $tmp) : $date;
        return  $str !== false;
    }
    
    /****************************************
    * メールアドレスチェック関数
    * @param    $str    チェック対象文字列
    * @return   メールアドレスであればTrue、メールアドレスでなければFalse
    ****************************************/
    public static function isMail(string $str) : bool{
        return filter_var($str, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE) !== false;
    }
    
    /****************************************
    * 電話番号チェック関数
    * @param    $str    チェック対象文字列
    * @return   電話番号であればTrue、電話番号でなければFalse
    ****************************************/
    public static function isTel(string $str) : bool{
        if (strlen($str) > 11 && !preg_match("/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/", $str) ){
            return false;
        }
        $tmpstr = str_replace('-', '', $str);
        return (strlen($tmpstr) === 10 || strlen($tmpstr) === 11) && self::isNumber($tmpstr);
    }
    
    /****************************************
    * 郵便番号チェック関数
    * @param    $str    チェック対象文字列
    * @return   郵便番号であればTrue、電話番号でなければFalse
    ****************************************/
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
     * @param   string  $str    チェック対象文字列
     * @param   integer $min    最小文字数
     * @param   integer $max    最大文字数
     * @return  boolean
     */
    public static function isCorrectLength(string $str, int $min, int $max) : bool {
        $tmpstr = self::conv_encode($str,'EUC-JP','UTF-8');
        return $min <= strlen($tmpstr) && strlen($tmpstr) <= $max;
    }

    /**
     * 文字列バイト数チェック関数
     * 
     * $strのバイト数が$min以上かつ$max以下の場合trueを返却
     * $min未満または$max超の場合falseを返却
     * 
     * @param   string  $str    チェック対象文字列
     * @param   integer $min    最小文字数
     * @param   integer $max    最大文字数
     * @return  boolean
     */
    public static function isCorrectByte(string $str, int $min, int $max) : bool {
        $tmpstr = self::conv_encode($str,'EUC-JP','UTF-8');
        return $min <= strlen($tmpstr) && strlen($tmpstr) <= $max;
    }

    /****************************************
    * 文字コード変換関数
    * @param    $str            変換対象文字列
    * @param    $to_encoding    変換元文字コード
    * @param    $from_encoding  変換先文字コード
    * @return   変換結果
    ****************************************/
    public static function conv_encode(string $str, string $to_encoding, string $from_encoding) : string {
        $escape_patterns = array(
            '/\xE2\x80\xBE/' => "\xEF\xBF\xA3",	// オーバーライン(￣)
            '/\xE2\x80\x94/' => "\xE2\x80\x95",	// 全角ダッシュ(―)
            '/\xE3\x80\x9C/' => "\xEF\xBD\x9E",	// 全角波形(～)
            '/\xE2\x80\x96/' => "\xE2\x88\xA5",	// 双柱・平行記号(∥)
            '/\xE2\x88\x92/' => "\xEF\xBC\x8D",	// 全角マイナス(－)
            '/\xC2\xA2/'     => "\xEF\xBF\xA0",		// セント(￠)
            '/\xC2\xA3/'     => "\xEF\xBF\xA1",		// ポンド(￡)
            '/\xC2\xAC/'     => "\xEF\xBF\xA2",		// 否定記号(￢)
        );

        $s_chars_utf8_euc = array(
            '/\x8F\xF3\xF3/' => "\xFC\xF1",	//ⅰ
            '/\x8F\xF3\xF4/' => "\xFC\xF2",	//ⅱ
            '/\x8F\xF3\xF5/' => "\xFC\xF3",	//ⅲ
            '/\x8F\xF3\xF6/' => "\xFC\xF4",	//ⅳ
            '/\x8F\xF3\xF7/' => "\xFC\xF5",	//ⅴ
            '/\x8F\xF3\xF8/' => "\xFC\xF6",	//ⅵ
            '/\x8F\xF3\xF9/' => "\xFC\xF7",	//ⅶ
            '/\x8F\xF3\xFA/' => "\xFC\xF8",	//ⅷ
            '/\x8F\xF3\xFB/' => "\xFC\xF9",	//ⅸ
            '/\x8F\xF3\xFC/' => "\xFC\xFA",	//ⅹ
            '/\r\n/'         => "\n"
        );

        $s_chars_euc_utf8 = array(
            '/\xEE\x8B\xA2/' => "\xE2\x85\xB0",	//ⅰ
            '/\xEE\x8B\xA3/' => "\xE2\x85\xB1",	//ⅱ
            '/\xEE\x8B\xA4/' => "\xE2\x85\xB2",	//ⅲ
            '/\xEE\x8B\xA5/' => "\xE2\x85\xB3",	//ⅳ
            '/\xEE\x8B\xA6/' => "\xE2\x85\xB4",	//ⅴ
            '/\xEE\x8B\xA7/' => "\xE2\x85\xB5",	//ⅵ
            '/\xEE\x8B\xA8/' => "\xE2\x85\xB6",	//ⅶ
            '/\xEE\x8B\xA9/' => "\xE2\x85\xB7",	//ⅷ
            '/\xEE\x8B\xAA/' => "\xE2\x85\xB8",	//ⅸ
            '/\xEE\x8B\xAB/' => "\xE2\x85\xB9"	//ⅹ
        );

        //ホワイトリスト外の特殊文字を事前に置換する
        //①②③④⑤⑥⑦⑧⑨⑩⑪⑫⑬⑭⑮⑯⑰⑱⑲⑳ⅠⅡⅢⅣⅤⅥⅦⅧⅨⅩ㈱㎜㎝㎞㎡㎎㎏㏄
        if ($to_encoding == 'EUC-JP') {
            $to_encoding = 'eucJP-win';
        }
        if ($from_encoding == 'EUC-JP') {
            $from_encoding = 'eucJP-win';
        }
        if ($to_encoding == 'SJIS') {
            $to_encoding = 'SJIS-win';
        }
        if ($from_encoding == 'SJIS') {
            $from_encoding = 'SJIS-win';
        }

        //UTF8からの場合は、事前に置換
        if($from_encoding == 'UTF-8' ) {
            $convstr = preg_replace(array_keys($escape_patterns),array_values($escape_patterns),$str);
        } else {
            $convstr = $str;
        }

        //文字エンコーディング変換
        if ($to_encoding != $from_encoding) {
            $convstr = mb_convert_encoding($convstr,$to_encoding,$from_encoding);
        }

        //UTF8に変換する場合は、後に置換
        if($to_encoding == 'UTF-8' ) {
            $convstr = preg_replace(array_keys($escape_patterns),array_values($escape_patterns),$convstr);
        }

        //ローマ数字小文字の置換
        //ⅰⅱⅲⅳⅴⅵⅶⅷⅸⅹ
        if ($to_encoding == 'eucJP-win') {
            $convstr = preg_replace(array_keys($s_chars_utf8_euc), array_values($s_chars_utf8_euc), $convstr);
        } elseif ($to_encoding == 'UTF-8') {
            $convstr = preg_replace(array_keys($s_chars_euc_utf8), array_values($s_chars_euc_utf8), $convstr);
        }

        return $convstr;
    }
}
?>