<?php
namespace Libs;
/**
 * cf. http://qiita.com/mpyw/items/8f8989f8575159ce95fc
 */
class CsrfValidator {
    
    const HASH_ALGO = 'sha256';
    
    /**
     * トークン作成
     * @return string ハッシュ済みセッションID
     * @throws Exception
     */
    public static function generate() {
        if (session_status() === PHP_SESSION_NONE) {
            throw new \Exception('Sessionがアクティブでないため、処理を中止します。');
        }
        return hash(self::HASH_ALGO, session_id());
    }
    
    /**
     * トークンのチェック
     * セッションIDが変わっている場合throw
     * @param string $token トークン
     * @param boolean $throw
     * @return boolean falseの場合throw
     * @throws Exception
     */
    public static function validate($token, $throw = false) {
        $success = self::generate() === $token;
        if (!$success && $throw) {
            throw new \Exception('トークンが一致しないため、処理を中止します。');
        }
        return $success;
    }
}