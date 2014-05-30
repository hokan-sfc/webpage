<?php
    function check($name, $email, $message){
        $success = array(
            'permit' => true,
            'glyphicon' => 'ok-sign',
            'classname' => 'success',
            'message' => '<p>お問い合わせを承りました。<br />返信のあるまで今しばらくお待ちください。'
        );
        $warning = array(
            'permit' => true,
            'glyphicon' => 'info-sign',
            'classname' => 'warning'
        );
        $error = array(
            'permit' => false,
            'glyphicon' => 'exclamation-sign',
            'classname' => 'error'
        );

        /*
         * 存在のバリデーション
         */
        $trimmed = trim($name);
        if(empty($trimmed) && $name != '0'){
            $error;
            $error['message'] = '<p>お名前が入力されていないため、送信を中止しました。<br />お名前を入力された上で、もう一度送信をお試しください。</p>';
            return $error;
        }
        $trimmed = trim($email);
        if(empty($trimmed) && $message != '0'){
            $error;
            $error['message'] = '<p>メールアドレスが入力されていないため、送信を中止しました。<br />メールアドレスを入力された上で、もう一度送信をお試しください。</p>';
            return $error;
        }
        $trimmed = trim($message);
        if(empty($trimmed) && $message != '0'){
            $error;
            $error['message'] = '<p>お問い合せ内容が入力されていないため、送信を中止しました。<br />お問い合せ内容を入力された上で、もう一度送信をお試しください。</p>';
            return $error;
        }

        /*
         * メールアドレスのバリデーション
         */
        // HTML5のinput[type='email']と同じ正規表現
        $html5_email_reg = '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/i';
        // 最初と最後のスペースを無視
        $email = trim($email);
        if(!preg_match($html5_email_reg, $email)){
            $error;
            $error['message'] = '<p>メールアドレスの形式が正しくないため、送信を中止しました。<br />入力されたアドレスを再度ご確認の上、もう一度送信をお試しください。</p>';
            return $error;
        }

        return $success;
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $to = 'hokan@sfc.keio.ac.jp';
        $subject = 'Webフォームからのお問い合わせ';
        $body = 'お名前: '.$name."\n\nメールアドレス: ".$email."\n\nお問い合せ内容: \n".$message;
        $header = 'From:'.$email;

        $result = check($name, $email, $message);
        if($result['permit']){
            mb_language('japanese');
            mb_internal_encoding('UTF-8');
            mb_send_mail($to, $subject, $body, $header);
            unset($name);
            unset($email);
            unset($message);
        }

        $glyphicon = $result['glyphicon'];
        $class = $result['classname'];
        $flash = $result['message'];
    } else{
        $class = 'hide';
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="keywords" content="星空観賞サークル,ほかん,hokan,SFC,天文" />
        <meta name="description" content="慶応義塾大学SFC 星空観賞サークル オフィシャルサイト" />
        <meta name="copyright" content="慶応義塾大学 星空観賞サークル" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>Contact | 星空観賞サークル</title>

        <!-- Fabicon -->
        <link rel="icon" type="image/vnd.microsoft.icon" href="../shared/img/favicon.ico">

        <!-- Style sheets -->
        <link rel="stylesheet" type="text/css" href="../lib/normalize.css">
        <link rel="stylesheet" type="text/css" href="../lib/glyphicons/css/glyphicons.css">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Nunito:300|Open+Sans:600">
        <link rel="stylesheet" type="text/css" href="../shared/css/common.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/navigation.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/contents.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/footer.css">
        <link rel="stylesheet" type="text/css" href="index.css">

        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!--[if (gte IE 6)&(lte IE 8)]>
            <script src="http://cdnjs.cloudflare.com/ajax/libs/selectivizr/1.0.2/selectivizr-min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="main">

            <header>
                <h1><a href="/">星空観賞サークル</a></h1>
                <nav>
                    <ul>
                        <li><a href="../about">ABOUT</a></li>
                        <li><a href="http://hokan-sfc.tumblr.com">BLOG</a></li>
                        <li class="active">CONTACT</li>
                    </ul>
                </nav>
            </header>

            <section class="catch">
                <h1>C<span>ONTACT</span></h1>
            </section>

            <section id="flash" class="<?= $class ?>">
                <span class="glyphicon glyphicon-<?= $glyphicon ?>"></span>
                <div><?= isset($flash) ? $flash : '' ?></div>
            </section>

            <article class="contents form">
                <h1>お問い合わせフォーム</h1>
                <p>星空観賞サークルへの入会希望やご質問、その他お問い合せには以下のフォームをご利用ください。</p>
                <p>メンバーが内容を確認次第、メールにて返答いたします。</p>
                <p>また、星空観賞サークルの公式ツイッターアカウントでも質問への返答を行っていますので、都合の良い方法でお気軽にお問い合わせください。</p>
                <form action="." method="POST">
                    <label>お名前</label>
                    <input type="text" name="name" value="<?= isset($name) ? $name : '' ?>" />
                    <label>メールアドレス</label>
                    <input type="text" name="email" value="<?= isset($email) ? $email : '' ?>" />
                    <label>お問い合せ内容</label>
                    <textarea name="message"><?= isset($message) ? $message : '' ?></textarea>
                    <input type="submit" />
                </form>
            </article>

        </div>
        <footer>
            <p>&copy; 2014 Starlit sky appreciation circle</p>
        </footer>
    </body>
</html>
