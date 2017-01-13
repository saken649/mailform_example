<?php
namespace Libs;
session_start();
require_once __DIR__ . '/vendor/autoload.php';
use Libs\UserBasement;
use Libs\Basement;
use Libs\Send;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instance = new Send();
    $instance->main();
} else {
    // POSTによるリクエストで無ければ強制遷移
    Basement::locationToTopStatic();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>お問い合わせ有難うございました。</title>
    <script language="JavaScript">
        function locate() {
            location.href = '<?=UserBasement::LOCATION_AFTER_SEND;?>';
        }
        setTimeout("locate()", 3000);
    </script>
</head>
<body>
    <p>お問い合わせ有難うございます。</p>
    <p>確認メールを送信させて頂きましたので、ご確認ください。</p>
</body>
</html>