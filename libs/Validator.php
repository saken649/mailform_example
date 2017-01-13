<?php
namespace Libs;
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
use Libs\CsrfValidator;

class Validator {
    
    private static $isRequiredErrorMsg = '入力必須です。';
    private static $isValidMailErrorMsg = '有効なメールアドレスではありません。';
    private static $rules = ['CsrfSafe', 'Required', 'ValidMail'];
    /**
     * エラーメッセージ取得getter
     * @param string $property プロパティ名
     * @return string エラーメッセージプロパティ
     */
    public static function getErrorMsg($property) {
        return self::$$property;
    }
    
    /**
     * 必須項目入力チェック
     * Falseの場合エラー
     * @param string $str 入力文字
     * @param boolean 入力されていない場合false
     */
    public static function isRequired($str) {
        if (empty($str) && $str !== 0) {
            return false;
        }
        return true;
    }
    
    /**
     * メールアドレスチェック
     * 有効なメールアドレスでない場合false
     * cf. http://qiita.com/mpyw/items/346f1789ad0e1b969ebc
     * @param type $mail
     * @return type
     */
    public static function isValidMail($mail) {
        switch (true) {
            case !filter_var($mail, FILTER_VALIDATE_EMAIL):
            case !preg_match('/@([\w.-]++)\z/', $mail, $m):
                return false;
            case checkdnsrr($m[1], 'MX'):
            case checkdnsrr($m[1], 'A'):
            case checkdnsrr($m[1], 'AAAA'):
                return true;
            default:
                return false;
        }
    }
    
    /**
     * バリデーション
     * @param array $conditions バリデーションルール
     * @param string $post POSTされたデータ
     * @param string $name Input.name
     * @return boolean $isValid バリデーションOKならtrue
     */
    public static function validate($conditions, $post, $name) {
        $isValid = true;
        foreach ($conditions as $key => $val) {
            list($condition, $message) = self::_setConditionAndMessage($key, $val);
            if (array_search($condition, self::$rules) === false) {
                throw new \Exception($condition . ' は正しいバリデーションルールではありません。');
            }
            if ($condition === 'CsrfSafe') {
                CsrfValidator::validate($post);
            } else {
                $method = 'is' . $condition;
                if (!self::$method($post)) {
                    $property = $method . 'ErrorMsg';
                    $_SESSION['error'][$name][] = (!empty($message)) ? $message : self::getErrorMsg($property);
                    $isValid = false;
                }
            }
        }
        return $isValid;
    }
    
    /**
     * バリデーションルール及びエラーメッセージを取得する
     * ルールの書き方によって型が変動するため注意
     * @param string / int $key
     * @param array / string $val
     * @return array
     */
    private static function _setConditionAndMessage($key, $val) {
        if (is_int($key)) {
            $condition = $val;
            $message = ($condition === 'CsrfSafe') ? '' : self::getErrorMsg(sprintf('is%sErrorMsg', $condition));
        } else {
            $condition = $key;
            $message = $val['Message'];
        }
        return [$condition, $message];
    }
}
