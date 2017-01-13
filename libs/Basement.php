<?php
namespace Libs;
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';

class Basement extends UserBasement {
    
    /**
     * バリデーションルールを取得する
     * @return array
     */
    public function getValidationRules() {
        return $this->validation;
    }
    
    /**
     * トップページへのロケーションを行う
     * @param type $msg
     */
    public function locationToTop($msg) {
        $_SESSION['error']['other'][] = $msg;
        header('Location: ' . self::LOCATION);
        exit;
    }
    
    /**
     * トップページへのロケーションを行う
     */
    public static function locationToTopStatic() {
        session_destroy();
        header('Location: ' . self::LOCATION);
        exit;
    }
    
    /**
     * htmlspecialcharsラッパー
     * @param string $str
     * @return string
     */
    public static function h($str) {
        return htmlspecialchars($str, ENT_QUOTES);
    }
}
