<?php
namespace Libs;
require_once __DIR__ . '/PHPMailer/PHPMailerAutoload.php';
use PHPMailer;

class Send extends Basement {
    
    private $Mail; // PHPMailerオブジェクト
    private $Body; // 本文格納用
    
    public function __construct() {
        $this->_csrfCheck();
        $this->Mail = new PHPMailer();
        $this->Body = $this->_lfCrToBr(self::h($_SESSION['body']));
    }
    
    /**
     * 改行コード(LFCR)をbrタグに変換
     * nl2brだとGmail上でバグるため
     * @param string $str
     * @return string
     */
    private function _lfCrToBr($str) {
        return preg_replace('/\r\n/', '<br>', $str);
    }
    
    /**
     * CSRFチェック
     */
    private function _csrfCheck() {
        try {
            CsrfValidator::validate($_SESSION['token']);
        } catch (Exception $e) {
            $this->locationToTop($e->getMessage());
        }
    }
    
    public function main() {
        $this->_setMailConfig();
        $this->_sendMailToClient();
        $this->_sendConfirmMailToCustomer();
        session_destroy();
    }
    
    /**
     * PHPMailerの設定
     */
    private function _setMailConfig() {
        $this->Mail->isSMTP();
        $this->Mail->CharSet = 'utf-8';
        $this->Mail->Host = self::SMTP_HOST;
        $this->Mail->SMTPAuth = true;
        $this->Mail->Username = self::SMTP_USER;
        $this->Mail->Password = self::SMTP_PASS;
        $port = (is_int(self::SMTP_PORT)) ? self::SMTP_PORT : (int)self::SMTP_PORT;
        $this->Mail->Port = $port;
        $this->Mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        $this->Mail->isHTML(true);
    }

    /**
     * メール送信
     */
    private function _sendMailToClient() {
        try {
            $this->Mail->setFrom(self::ADDRESS_FROM, self::NAME_FROM); // From
            $this->Mail->addAddress(self::ADDRESS_TO, self::NAME_TO); // TO
            $this->Mail->Subject = mb_encode_mimeheader(self::h($_SESSION['subject']));
            $this->Mail->Body = $this->_getBody();
            if (!$this->Mail->send()) {
                throw new \Exception('メールの送信に失敗しました。処理を中止します。');
            }
        } catch (\Exception $e) {
            $this->locationToTop($e->getMessage());
        }
    }
    
    /**
     * 問い合わせ内容本文を取得する
     * @return string $body 本文
     */
    private function _getBody() {
        $body = self::h($_SESSION['name']) . '　様より、下記の内容でお問い合わせがありました。<br>';
        $body .= 'メールアドレス: ' . self::h($_SESSION['mail']) . '<br>';
        $body .= '------------------------------<br>';
        $body .= $this->Body . '<br>';
        $body .= '------------------------------<br>';
        return $body;
    }
    
    /**
     * 問い合わせたお客様へ、送信確認メールを送信する
     */
    private function _sendConfirmMailToCustomer() {
        try {
            $this->Mail->setFrom(self::CONFIRM_ADDRESS_FROM, self::CONFIRM_NAME_FROM);
            $this->Mail->addAddress(self::h($_SESSION['mail']));
            $this->Mail->Subject = mb_encode_mimeheader('お問い合わせ有難うございます。');
            $this->Mail->Body = $this->_getConfirmBody();
            if (!$this->Mail->send()) {
                throw new \Exception('確認メールの送信に失敗しました。処理を中止します。');
            }
        } catch (\Exception $e) {
            $this->locationToTop($e->getMessage());
        }
    }
    
    /**
     * お問い合わせをした方への確認メールの本文を取得する
     * @return string $body 本文
     */
    private function _getConfirmBody() {
        $body = self::h($_SESSION['name']) . '　様<br><br>';
        $body .= 'お問い合わせ有難うございます。<br>下記の内容で送信させて頂きました。<br>';
        $body .= 'お返事まで数日要することがあります。<br>予めご了承ください。<br><br>';
        $body .= '------------------------------<br>';
        $body .= '件名: ' . self::h($_SESSION['subject']) . '<br>';
        $body .= $this->Body . '<br>';
        $body .= '------------------------------<br>';
        $body .= '※このアドレスは送信専用です。返信を行わないようお願い致します。<br>';
        return $body;
    }
}
