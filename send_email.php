<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームがすでに送信されたかどうかを確認
    if (!isset($_SESSION['form_submitted'])) {
        $_SESSION['form_submitted'] = true; // フォームが送信されたことを記録

        $to = "ここにメアド"; // 送信先のメールアドレスを指定
        $subject = "件名"; // メールの件名

        // フォームからの入力データを収集
        $name = $_POST["name"];
        $email = $_POST["email"];
        $message = $_POST["message"];

        // メール本文を構築
        $email_message = "名前: " . $name . "\n";
        $email_message .= "Eメール: " . $email . "\n";
        $email_message .= "メッセージ:" . $message;

        // メールを送信
        if (mail($to, $subject, $email_message)) {
            echo "お問い合わせありがとうございました。";
            echo "<br/>";
            echo ("3秒後にリダイレクトされます...");
            // 3秒後に別のページにリダイレクト
            header("refresh:3;url=http://ここにURL");
        } else {
            echo "メールの送信に失敗しました。:(";
        }
    } else {
        echo "フォームはすでに送信されています。再度送信することはできません。";
    }
}
?>
