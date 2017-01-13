<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
use Libs\Basement;
use Libs\Confirm;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instance = new Confirm();
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
    <link rel="stylesheet" href="css/style.css">
    <title>確認</title>
</head>
<body>
    <h1>Confirm</h1>
    <p>下記内容で送信します。よろしいですか？</p>
    <table class="confirm">
        <tr>
            <td class="label">Name: </td>
            <td class="area"><?=Basement::h($_SESSION['name']); ?></td>
        </tr>
        <tr>
            <td class="label">Mail: </td>
            <td class="area"><?=Basement::h($_SESSION['mail']); ?></td>
        </tr>
        <tr>
            <td class="label">Subject: </td>
            <td class="area"><?=Basement::h($_SESSION['subject']); ?></td>
        </tr>
        <tr>
            <td class="label">Body: </td>
            <td class="area"><?=nl2br(Basement::h($_SESSION['body'])); ?></td>
        </tr>
    </table>
    <form action="send.php" method="post">
        <button type="button" name="action" onclick="javascript:window.history.back(-1);return false;">修正</button>
        <button type="submit" name="action" value="send">送信</button>
    </form>
</body>
</html>