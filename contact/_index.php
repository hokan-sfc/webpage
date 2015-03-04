<header>
    <h1><a href="/">星空観賞サークル</a></h1>
    <nav>
        <ul>
            <li><a href="../about/">ABOUT</a></li>
            <li><a href="http://hokan-sfc.tumblr.com">BLOG</a></li>
            <li class="active">CONTACT</li>
        </ul>
    </nav>
</header>

<section class="catch">
    <h1>C<span>ONTACT</span></h1>
</section>

<?php if(isset($flash)): ?>
<section id="flash" class="<?= $flash['class'] ?>">
    <span class="glyphicon glyphicon-<?= $flash['icon'] ?>"></span>
    <div><?= $flash['message'] ?></div>
</section>
<?php endif; ?>

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
