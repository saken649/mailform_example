<?php
namespace Libs;
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

class Flash {
    /**
     * エラーメッセージをセッションから取得する
     * エラーが無ければ空文字
     * @param string $key input.name
     * @param string $tag spanタグor divタグ
     * @return string エラーメッセージ
     */
    public static function getErrorMsg($key, $tag = 'span') {
        if (isset($_SESSION['error'][$key])) {
            $msg = self::_makeErrorMsgs($_SESSION['error'][$key], $tag);
            unset($_SESSION['error'][$key]);
            return $msg;
        } else {
            return '';
        }
    }
    
    /**
     * セッションからエラーメッセージのHTMLタグを生成する
     * @param array $msgs エラーメッセージ
     * @param string $tag span or div
     * @return string HTMLタグ化済みエラーメッセージ
     */
    private static function _makeErrorMsgs($msgs, $tag) {
        $ret = sprintf('<%s class="error">', $tag);
        $ret .= implode('/ ', $msgs);
        $ret .= sprintf('</%s>', $tag);
        return $ret;
    }
    
    /**
     * エラー時テキストボックスにエラークラスを付加する
     * 無ければ空文字を返し、エラークラスを付加しない
     * @param string $key input.name
     * @return string エラークラス
     */
    public static function getErrorClass($key) {
        if (isset($_SESSION['error'][$key])) {
            return 'error_input';
        } else {
            return '';
        }
    }
}
