<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
use Libs\Basement;
use Libs\CsrfValidator;
use Libs\Flash;

$keys = ['name', 'mail', 'subject', 'body'];
foreach ($keys as $key) {
    $$key = (isset($_SESSION[$key])) ? Basement::h($_SESSION[$key]) : '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mailform Example</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Mailform Example</h1>
    <?=Flash::getErrorMsg('other', 'div');?>
    <p>※全項目入力必須です。</p>
    <form action="confirm.php" method="post">
        <table>
            <tr>
                <td class="label"><label for="name">Name: </label></td>
                <td><input type="text" id="name" class="area <?=Flash::getErrorClass('name');?>" name="name" value="<?=$name;?>"></td>
                <td class="error_msg"><?=Flash::getErrorMsg('name');?></td>
            </tr>
            <tr>
                <td class="label"><label for="mail">Your Mail: </label></td>
                <td><input type="mail" id="mail" class="area <?=Flash::getErrorClass('mail');?>" name="mail" value="<?=$mail;?>"></td>
                <td class="error_msg"><?=Flash::getErrorMsg('mail');?></td>
            </tr>
            <tr>
                <td class="label"><label for="subject">Subject: </label></td>
                <td><input type="text" id="subject" class="area <?=Flash::getErrorClass('subject');?>" name="subject" value="<?=$subject;?>"></td>
                <td class="error_msg"><?=Flash::getErrorMsg('subject');?></td>
            </tr>
            <tr>
                <td class="label"><label for="body">Body: </label></td>
                <td><textarea name="body" id="body" class="area <?=Flash::getErrorClass('body');?>" cols="30" rows="10"><?=$body;?></textarea></td>
                <td class="error_msg"><?=Flash::getErrorMsg('body');?></td>
            </tr>
            <input type="hidden" name="token" value="<?=CsrfValidator::generate();?>">
        </table>
        <button type="submit" id="submit" name="action" value="send">確認画面へ</button>
    </form>
</body>
</html>
