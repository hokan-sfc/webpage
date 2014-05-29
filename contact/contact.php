<?php
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $to = 'hokan@sfc.keio.ac.jp';
    $subject = 'Webフォームからのお問い合わせ';
    $body = 'お名前: '.$name.'\n\nメールアドレス: '.$email.'\n\nお問い合せ内容: \n'.$message;
    $header = 'From:'.$email;

    mb_language('japanese');
    mb_internal_encoding('EUC-JP');
    mail($to, $subject, $body, $header);

    header('Location: index.html');
?>
