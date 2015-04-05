<script type="text/javascript">
function googleCallback (authResult) {
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
            format: "image",
            type: "a",
            width: 166,
            height: 44,
            className: "yconnectLogin"
        },
        authorization: {
            clientId: "<?= $yahoo_client_id ?>",
            redirectUri: "<?= $yahoo_callback ?>",
            scope: "openid email profile address",
            state: "<?= $state ?>",
            nonce: "<?= $nonce ?>"
        },
        onError: function(res) {
            window.alert('通信エラーが発生しました。お手数をお掛けしますが、再度ログインをお試しください。');
        },
        onCancel: function(){}
    });
};

(function(){
    // Yahoo API
    var fs = document.getElementsByTagName("script")[0], s = document.createElement("script");
    s.setAttribute("src", "https://s.yimg.jp/images/login/yconnect/auth/1.0.0/auth-min.js");
    fs.parentNode.insertBefore(s, fs);

    // Google API
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/client:plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>
