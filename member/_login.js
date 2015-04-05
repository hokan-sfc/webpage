<script type="text/javascript">
function googleCallback (authResult) {
    if (authResult['access_token']) {
        hideFlash();
        console.log(authResult['access_token']);
        console.log(authResult['id_token']);
        gapi.auth.setToken(authResult);
        gapi.client.load('oauth2', 'v2', function () {
            var r = gapi.client.oauth2.userinfo.get();
            r.execute(function (info) {
                console.log(info);
            });
        });
    } else if (authResult['error']) {
        if (authResult['error'] == 'access_denied') {
            flashWarning('アプリケーションの連携を行わないとログインすることはできません。');
        } else {
            flashError('認証エラーが発生しました。再度ログインをお試しいただき、何度も同様のエラーが出る場合はお手数ですが上部CONTACTからその旨をお問い合わせください。');
        }
    }
}

function flashError (message) {
    hideFlash();
    showFlash('error', 'exclamation-sign', message);
}

function flashWarning (message) {
    hideFlash();
    showFlash('warning', 'info-sign', message);
}

function showFlash(type, icon, message) {
    var flash = document.getElementsByClassName('flash')[0];
    flash.classList.remove('hidden');
    flash.classList.add(type);
    flash.getElementsByTagName('span')[0].classList.add('glyphicon-' + icon);
    flash.getElementsByTagName('div')[0].innerHTML = message;
}

function hideFlash() {
    var flash = document.getElementsByClassName('flash')[0];
    flash.className = 'flash hidden';
    flash.getElementsByTagName('span')[0].className = 'glyphicon';
}

window.yconnectInit = function() {
    YAHOO.JP.yconnect.Authorization.init({
        button: {
            format: 'image',
            type: 'a',
            width: 166,
            height: 44,
            className: 'yconnectLogin'
        },
        authorization: {
            clientId: '<?= $yahoo_client_id ?>',
            redirectUri: '<?= $yahoo_callback ?>',
            scope: 'openid email profile address',
            state: '<?= $state ?>',
            nonce: '<?= $nonce ?>'
        },
        onError: function(res) {
            flashError('認証エラーが発生しました。再度ログインをお試しいただき、何度も同様のエラーが出る場合はお手数ですが上部CONTACTからその旨をお問い合わせください。');
        },
        onCancel: function(){
            flashWarning('アプリケーションの連携を行わないとログインすることはできません。');
        }
    });
};

(function(){
    // Yahoo API
    var fs = document.getElementsByTagName('script')[0], s = document.createElement('script');
    s.setAttribute('src', 'https://s.yimg.jp/images/login/yconnect/auth/1.0.0/auth-min.js');
    fs.parentNode.insertBefore(s, fs);

    // Google API
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/client:plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>
