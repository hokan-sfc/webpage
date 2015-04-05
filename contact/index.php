<?php

require_once __DIR__.'/../lib/internal/template/rest_handler.php';

class Handler extends RESTHandler {
    private $name;
    private $email;
    private $message;
    private $flash;

    function __construct() {
        parent::__construct(array(self::METHOD_GET, self::METHOD_POST));
    }

    protected function get(Parameter $params) {
        $this->render(
            '_index.php',
            $this->create_css_include_tag('index.css'),
            NULL,
            array(
                'name' => $this->name,
                'email' => $this->email,
                'message' => $this->message,
                'flash' => $this->flash
            )
        );
    }

    protected function post(Parameter $params) {
        $this->name = $params->get_string('name');
        $this->email = $params->get_string('email');
        $this->message = $params->get_string('message');
        if (!$this->name) {
            $this->render_get_with_error_flash('<p>お名前が入力されていないため、送信を中止しました。<br />お名前を入力された上で、もう一度送信をお試しください。</p>');
        } else if (!$this->email) {
            $this->render_get_with_error_flash('<p>メールアドレスが入力されていないため、送信を中止しました。<br />メールアドレスを入力された上で、もう一度送信をお試しください。</p>');
        } else if (!$this->message) {
            $this->render_get_with_error_flash('<p>お問い合せ内容が入力されていないため、送信を中止しました。<br />お問い合せ内容を入力された上で、もう一度送信をお試しください。</p>');
        } else {
            // HTML5のinput[type='email']と同じ正規表現
            $html5_email_reg = '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/i';
            $email = trim($this->email);
            if(!preg_match($html5_email_reg, $email)){
                $this->render_get_with_error_flash('<p>メールアドレスの形式が正しくないため、送信を中止しました。<br />入力されたアドレスを再度ご確認の上、もう一度送信をお試しください。</p>');
            } else {
                $this->send($this->name, $email, $this->message);
                $this->name = NULL;
                $this->email = NULL;
                $this->message = NULL;
                $this->render_get_with_flash(
                    'success',
                    'ok-sign',
                    '<p>お問い合わせを承りました。<br />返信のあるまで今しばらくお待ちください。'
                );
            }
        }
    }

    private function render_get_with_error_flash($message) {
        $this->render_get_with_flash('error', 'exclamation-sign', $message);
    }

    private function render_get_with_flash($class, $icon, $message) {
        $this->flash = array(
            'class' => $class,
            'icon' => $icon,
            'message' => $message
        );
        $this->get(new Parameter(NULL));
    }

    private function send($name, $email, $message) {
        $to = 'hokan@sfc.keio.ac.jp';
        $subject = 'Webフォームからのお問い合わせ';
        $body = "お名前: $name\n\nメールアドレス: $email\n\nお問い合せ内容: \n$message";
        $header = "From: $email";
        mb_language('japanese');
        mb_internal_encoding('UTF-8');
        mb_send_mail($to, $subject, $body, $header);
    }
}

$h = new Handler();
$h->handle();

?>
