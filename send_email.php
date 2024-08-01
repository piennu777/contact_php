<?php
session_start();
session_regenerate_id(true);
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

// ブラックリスト
$blacklist = [
    ''
];

// ユーザーのIPアドレスを取得
$ip_address = $_SERVER['REMOTE_ADDR'];

// IPアドレスがブラックリストに含まれているかチェック
if (in_array($ip_address, $blacklist)) {
    echo "あなたのIPアドレスからのお問い合わせはブロックされました。";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['form_submitted'])) {
        $_SESSION['form_submitted'] = true;
        $to = "受信先メアド（ようはサーバーからこのメアドへお問い合わせの内容が送られます）";
        $subject = "お問い合わせ";
        function isHeaderInjection($str) {
            return preg_match("/[\r\n]/", $str);
        }
        $name = htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $message = htmlspecialchars($_POST["message"], ENT_QUOTES, 'UTF-8');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "無効なEメールアドレスです。";
            header("refresh:3;url=https://contact.piennu777.jp/");
            exit;
        }
        if (isHeaderInjection($name) || isHeaderInjection($email) || isHeaderInjection($message)) {
            die('不正な入力が検出されました。');
            header("refresh:3;url=https://contact.piennu777.jp/");
            exit;
        }
        $email_message = "名前: " . $name . "\n";
        $email_message .= "Eメール: " . $email . "\n";
        $email_message .= "メッセージ: " . $message . "\n";
        $email_message .= "IPアドレス: " . $ip_address;
        
        if (mail($to, $subject, $email_message)) {
            echo "お問い合わせありがとうございました。";
            echo "<br/>";
            echo ("3秒後にリダイレクトされます...");
            header("refresh:3;url=https://contact.piennu777.jp/");
        } else {
            echo "メールの送信に失敗しました。:(";
            header("refresh:3;url=https://contact.piennu777.jp/");
        }
    } else {
        echo "フォームはすでに送信されています。再度送信することはできません。";
        header("refresh:3;url=https://contact.piennu777.jp/");
    }
} else {
    header("refresh:1;url=https://contact.piennu777.jp/");
}
?>
