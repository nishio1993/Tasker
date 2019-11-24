<?php
use PHPUnit\Framework\TestCase;
require_once('vendor/autoload.php');
require_once('class/Validation.class.php');
const ALPHABET_HALF = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
const ALPHABET_FULL = 'ａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺ';
const NUMBER_HALF   = '1234567890';
const NUMBER_FULL   = '１２３４５６７８９０';
const SYMBOL_HALF   = '!"#$%&\'()=~|`{+*}<>?_\\@[;:],./';
const SYMBOL_FULL   = '！＃＄％＆（）＝￣｜｛＋＊｝＜＞？＿￥＠「；：」、。・';
const JAPANESE      = 'あいうえおカキクケコ他値津手都';
const PLATFORMCHAR  = '№㏍℡㊤㊥㊦㊧㊨㈱㈲㈹㍾㍽㍼㍻㍉㎜㎝㎞㎎㎏㏄㍉㌔㌢㍍㌘㌧㌃㌶㍑㍗㌍㌦㌣㌫㍊㌻①②③④⑤⑥⑦⑧⑨⑩⑪⑫⑬⑭⑮⑯⑰⑱⑲⑳ⅠⅡⅢⅣⅤⅥⅦⅧⅨⅩ①②③④⑤⑥⑦⑧⑨⑩⑪⑫⑬⑭⑮⑯⑰⑱⑲⑳ⅠⅡⅢⅣⅤ∑∮∟⊿纊鍈蓜炻棈兊夋奛奣寬﨑嵂';

class Validationtest extends TestCase {

    /**
     * 環境依存文字無し
     *
     * @test
     */
    public function includingPlatformDependentCharactersFalse() {
        $charList = preg_split("//u", ALPHABET_HALF.ALPHABET_FULL.NUMBER_HALF.NUMBER_FULL.SYMBOL_HALF.SYMBOL_FULL.JAPANESE, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::includingPlatformDependentCharacters($char);
            if ($tmp === true) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 環境依存文字有り
     *
     * @test
     */
    public function includingPlatformDependentCharactersTrue() {
        $charList = preg_split("//u", PLATFORMCHAR, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::includingPlatformDependentCharacters($char);
            if ($tmp === false) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 英字のみ
     *
     * @test
     */
    public function isAlphabet() {
        $charList = preg_split("//u", ALPHABET_HALF.ALPHABET_FULL, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isAlphabet($char);
            if ($tmp === false) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 数値は弾く
     *
     * @test
     */
    public function isNotAlphabetBecauseNumber() {
        $charList = preg_split("//u", NUMBER_HALF.NUMBER_FULL, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isAlphabet($char);
            if ($tmp === true) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 記号は弾く
     *
     * @test
     */
    public function isNotAlphabetBecauseSymbol() {
        $charList = preg_split("//u", SYMBOL_HALF.SYMBOL_FULL, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isAlphabet($char);
            if ($tmp === true) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 日本語は弾く
     *
     * @test
     */
    public function isNotAlphabetBecauseJapanese() {
        $charList = preg_split("//u", JAPANESE, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isAlphabet($char);
            if ($tmp === true) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 数字のみ
     *
     * @test
     */
    public function isNumber() {
        $charList = preg_split("//u", NUMBER_HALF.NUMBER_FULL, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isNumber($char);
            if ($tmp === false) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 英字は弾く
     *
     * @test
     */
    public function isNotNumberBecauseAlphabet() {
        $charList = preg_split("//u", ALPHABET_HALF.ALPHABET_FULL, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isNumber($char);
            if ($tmp === true) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 記号は弾く
     *
     * @test
     */
    public function isNotNumberBecauseSymbol() {
        $charList = preg_split("//u", SYMBOL_HALF.SYMBOL_FULL, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isNumber($char);
            if ($tmp === true) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
    * 日本語は弾く
    *
    * @test
    */
    public function isNotNumberBecauseJapanese() {
        $charList = preg_split("//u", JAPANESE, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isNumber($char);
            if ($tmp === true) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 数字のみ
     *
     * @test
     */
    public function isAlphaNumOnlyNumber() {
        $charList = preg_split("//u", NUMBER_HALF.NUMBER_FULL, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isAlphaNum($char);
            if ($tmp === false) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 英字のみ
     *
     * @test
     */
    public function isAlphaNumOnlyAlphabet() {
        $charList = preg_split("//u", ALPHABET_HALF.ALPHABET_FULL, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isAlphaNum($char);
            if ($tmp === false) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 英数字のみ
     *
     * @test
     */
    public function isAlphaNum() {
        $charList = preg_split("//u", ALPHABET_HALF.ALPHABET_FULL.NUMBER_HALF.NUMBER_FULL, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isAlphaNum($char);
            if ($tmp === false) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
     * 記号は弾く
     *
     * @test
     */
    public function isNotAlphaNumBecauseSymbol() {
        $charList = preg_split("//u", SYMBOL_HALF.SYMBOL_FULL, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isAlphaNum($char);
            if ($tmp === true) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
    * 日本語は弾く
    *
    * @test
    */
    public function isNotAlphaNumBecauseJapanese() {
        $charList = preg_split("//u", JAPANESE, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isAlphaNum($char);
            if ($tmp === true) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
    * シングルバイトのみ
    *
    * @test
    */
    public function isSingleByte() {
        $charList = preg_split("//u", ALPHABET_HALF.NUMBER_HALF.SYMBOL_HALF, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isSingleByte($char);
            if ($tmp === false) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
    * マルチバイトは弾く
    *
    * @test
    */
    public function isNotSingleByte() {
        $charList = preg_split("//u", ALPHABET_FULL.NUMBER_FULL.SYMBOL_FULL.JAPANESE, -1, PREG_SPLIT_NO_EMPTY);
        $result = true;
        foreach($charList as $char) {
            $tmp = Validation::isSingleByte($char);
            if ($tmp === true) {
                $result = $char;
                break;
            }
        }
        $this->assertTrue($result);
    }

    /**
    * 文字列長さ
    *
    * @test
    */
    public function isCorrectLength1() {
        $this->assertTrue(Validation::isCorrectLength('AbＣｄえ', 0, 5));
    }

    /**
    * 文字列長さ
    *
    * @test
    */
    public function isCorrectLength2() {
        $this->assertTrue(Validation::isCorrectLength('AbＣｄえ', 5, 5));
    }

    /**
    * 文字列長さ
    *
    * @test
    */
    public function isCorrectLength3() {
        $this->assertTrue(Validation::isCorrectLength('AbＣｄえ', 5, 10));
    }
    
    /**
    * 文字列長さ
    *
    * @test
    */
    public function isCorrectLength4() {
        $this->assertTrue(Validation::isCorrectLength('', 0, 0));
    }
    
    /**
    * 文字列長さ
    *
    * @test
    */
    public function isCorrectLength5() {
        $this->assertTrue(Validation::isCorrectLength("\0", 0, 0));
    }

    /**
    * 文字列長さ
    *
    * @test
    */
    public function isNotCorrectLength1() {
        $this->assertFalse(Validation::isCorrectLength('AbＣｄえ', 0, 4));
    }

    /**
    * 文字列長さ
    *
    * @test
    */
    public function isNotCorrectLength2() {
        $this->assertFalse(Validation::isCorrectLength('AbＣｄえ', 6, 6));
    }

    /**
    * 文字列長さ
    *
    * @test
    */
    public function isNotCorrectLength3() {
        $this->assertFalse(Validation::isCorrectLength('AbＣｄえ', 10, 0));
    }

    

    /**
    * 文字列バイト
    *
    * @test
    */
    public function isCorrectByte1() {
        $this->assertTrue(Validation::isCorrectByte('AbＣｄえ', 0, 11));
    }

    /**
    * 文字列バイト
    *
    * @test
    */
    public function isCorrectByte2() {
        $this->assertTrue(Validation::isCorrectByte('AbＣｄえ', 11, 11));
    }

    /**
    * 文字列バイト
    *
    * @test
    */
    public function isCorrectByte3() {
        $this->assertTrue(Validation::isCorrectByte('AbＣｄえ', 11, 22));
    }

    /**
    * 文字列長さ
    *
    * @test
    */
    public function isCorrectByte4() {
        $this->assertTrue(Validation::isCorrectByte('', 0, 0));
    }

    /**
    * 文字列長さ
    *
    * @test
    */
    public function isCorrectByte5() {
        $this->assertTrue(Validation::isCorrectByte("\0", 0, 0));
    }

    /**
    * 文字列バイト
    *
    * @test
    */
    public function isNotCorrectByte1() {
        $this->assertFalse(Validation::isCorrectByte('AbＣｄえ', 0, 10));
    }

    /**
    * 文字列バイト
    *
    * @test
    */
    public function isNotCorrectByte2() {
        $this->assertFalse(Validation::isCorrectByte('AbＣｄえ', 12, 24));
    }

    /**
    * 文字列バイト
    *
    * @test
    */
    public function isNotCorrectByte3() {
        $this->assertFalse(Validation::isCorrectByte('AbＣｄえ', 22, 0));
    }

    /**
     * 小文字可能
     *
     * @test
     */
    public function isMail1() {
        $this->assertTrue(Validation::isMail('test@test.com'));
    }

    /**
     * 大文字可能
     *
     * @test
     */
    public function isMail2() {
        $this->assertTrue(Validation::isMail('TEST@TEST.COM'));
    }

    /**
     * ピリオド一つ可能
     *
     * @test
     */
    public function isMail3() {
        $this->assertTrue(Validation::isMail('te.st@test.com'));
    }

    /**
     * 一部記号可能
     *
     * @test
     */
    public function isMail4() {
        $this->assertTrue(Validation::isMail('-._@test.com'));
    }

    /**
     * @抜き不可能
     *
     * @test
     */
    public function isNotMail1() {
        $this->assertFalse(Validation::isMail('TESTTEST.COM'));
    }

    /**
     * 全角不可能
     *
     * @test
     */
    public function isNotMail2() {
        $this->assertFalse(Validation::isMail('ｔｅｓｔ＠ｔｅｓｔ．ｃｏｍ'));
    }

    /**
     * ピリオド二つ不可能
     *
     * @test
     */
    public function isNotMail3() {
        $this->assertFalse(Validation::isMail('te..st@test.com'));
    }

    /**
     * ピリオド先頭不可能
     *
     * @test
     */
    public function isNotMail4() {
        $this->assertFalse(Validation::isMail('.test@test.com'));
    }

    /**
     * ピリオド@直前不可能
     *
     * @test
     */
    public function isNotMail5() {
        $this->assertFalse(Validation::isMail('test.@test.com'));
    }

    /**
     * 一部記号可能
     *
     * @test
     */
    public function isNotMail6() {
        $this->assertFalse(Validation::isMail('test.@test.com'));
    }

    /**
     * 一部記号不可能
     *
     * @test
     */
    public function isNotMail7() {
        $this->assertFalse(Validation::isMail('\\[,]"\'@test.com'));
    }
}