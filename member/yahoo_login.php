<?php

require_once __DIR__.'/../lib/internal/config/config_handler.php';
require_once __DIR__.'/../lib/internal/template/rest_handler.php';

class Handler extends RESTHandler {
    function __construct() {
        parent::__construct(array(self::METHOD_GET));
    }

    protected function get(Parameter $params) {
        $c = new Config();
        $client = $c->yahoo_client();
        session_start();
        $state = $_SESSION['YAHOO_STATE'];
        $nonce = $_SESSION['YAHOO_NONCE'];

        $tokens = $this->get_tokens($client, $state);
        if (!$tokens) {
            // TODO error
            echo 'error with get_tokens';
        }
        $id_token = $this->get_id_token($client, $nonce);
        if (!$id_token) {
            // TODO error
            echo 'error with get_id_token';
        }
        $user_info = $this->get_user_info($client, $tokens['access_token']);
        if (!$user_info) {
            $rat = $this->refresh_access_token($client, $tokens['refresh_token']);
            if (!$rat) {
                // TODO error
                echo 'error with refresh_access_token';
            }
            $user_info = $this->get_user_info($client, $rat);
            if (!$user_info) {
                // TODO error
                echo 'error with get_user_info';
            }
        }
        var_dump($id_token);
        var_dump($user_info);
    }

    private function get_tokens($client, $state) {
        try {
            $code = $client->getAuthorizationCode($state);
            if (!$code) {
                return FALSE;
            }
            $client->requestAccessToken($c->yahoo_callback(), $code);
            return array(
                'access_token' => $client->getAccessToken(),
                'refresh_token' => $client->getRefreshToken()
            );
        } catch (TokenException $e) {
            return FALSE;
        }
    }

    private function get_id_token($client, $nonce) {
        try {
            if (!$client->verifyIdToken($nonce) {
                return FALSE;
            }
            return $client->getIdToken();
        } catch (Exception $e) {
            return FALSE;
        }
    }

    private function get_user_info($client, $access_token) {
        try {
            $client->requestUserInfo($access_token);
            return $client->getUserInfo();
        } catch (ApiException $e) {
            return FALSE;
        }
    }

    private function refresh_access_token($client, $refresh_token) {
        try {
            $client->refreshAccessToken($refresh_token);
            return $client->getAccessToken();
        } catch (OAuth2TokenException $e) {
            return FALSE;
        }
    }
}

$h = new Handler();
$h->handle();

?>
