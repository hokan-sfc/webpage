<?php

return array(
    'default' => array(
        'db' => array(
            'file' => '/db/database.data'
        ),
        'yahoo' => array(
            'client_id' => '',
            'client_secret' => '',
            'callback' => 'http://hokan.sfc.keio.ac.jp/member/yahoo_login.php'
        ),
        'google' => array(
            'client_id' => '',
            'client_secret' => ''
        )
    ),
    'test' => array(
        'db' => array(
            'file' => '/db/test_database.data'
        )
    )
);

?>
