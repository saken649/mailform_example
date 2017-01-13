<?php
namespace Libs;

/**
 * ユーザー定数設定用クラス
 */
class UserBasement {
    
    // ロケーション
    const LOCATION = 'index.php'; // エラーなどでのリダイレクト先
    const LOCATION_AFTER_SEND = 'index.php'; // 送信完了後のリダイレクト先
    // 接続設定
    const SMTP_HOST = ''; // SMTPサーバーHOST名
    const SMTP_USER = ''; // SMTPサーバーユーザー名
    const SMTP_PASS = ''; // SMTPサーバーパスワード
    const SMTP_PORT = 587; // int SMTPポート番号
    // 宛先・差出人
    const ADDRESS_FROM = ''; // Fromのメールアドレス
    const NAME_FROM = ''; // Fromの差出人名
    const ADDRESS_TO = ''; // Toのメールアドレス
    const NAME_TO = ''; // Toの宛名名
    // 確認メール用 宛先・差出人
    // 問い合わせた方へ、確認メールを送信する
    const CONFIRM_ADDRESS_FROM = ''; // Fromのメールアドレス
    const CONFIRM_NAME_FROM = ''; // Fromの差出人名
    
    /**
     * バリデーションを行うinput.nameとそのバリデーションルールを定義
     * CsrfSafe => トークンの整合性確認を行う(CSRF対策)
     * Required => 入力必須である
     * ValidMail => 不正メールアドレス対策
     * 
     * RequiredとValidMailについては、エラー時のユーザーメッセージを指定可能
     * →ValidMailのvalueとしてMessageを指定すること
     */
    protected $validation = [
        'token' => ['CsrfSafe'],
        'name' => [
            'Required'
        ],
        'mail' => [
            'Required',
            'ValidMail' => [
                'Message' => '有効なメールアドレスではありません。'
            ]
        ],
        'subject' => [
            'Required' => [
                'Message' => '入力必須です。'
            ]
        ],
        'body' => [
            'Required' => [
                'Message' => '入力必須です。'
            ]
        ]
    ];
}
