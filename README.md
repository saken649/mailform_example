# Mailform Example

自社でコーポレートサイト制作の案件が増えてきたため、メールフォームのテンプレートを制作しました。  

セキュリティ周りの対策は正直自信無いので、  
バグを見つけたり、セキュリティホールを見つけた方は  
Issueを投げて頂けると非常に有難いです。

送信先のメールアドレスやバリデーションなどのユーザー設定は、  
全てLibs/UserBasement.phpに定数としてまとめてあります。  
この定数群の各値を環境に合わせて変更してください。  
定数群の修正で、動くようになるはずです。  

というより、テンプレート化する都合上メールサーバーの設定を全て初期化しているため、  
定数群は要修正です。

### 機能
- SMTPサーバー経由によるメール送信(要SMTPサーバー)
- CSRF対策
- 申し訳程度のバリデーション

### 使用ライブラリ
PHPMailer 5.2.22

### 変更履歴
2017/01/12 ver.1 初稿

### バリデーション
`CsrfSafe` : CSRFチェックを行う  
`Required` : 入力されていることをチェックする(入力必須である)  
`ValidMail` : 有効なメールアドレスであることをチェックする

### バリデーション入力例
libs/UserBasement.phpの$validationに、バリデーションを行いたいinputのname属性名を指定する。  
エラーメッセージはデフォルト文も用意しているが、「Required」「ValidMail」については、ユーザーメッセージも指定可能。  

### バリデーション デフォルトエラーメッセージ
`Required` : 「入力必須です。」  
`ValidMail` : 「有効なメールアドレスではありません。」  

### バリデーション入力例
```
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
```