(function(){
    'use strict'
    var loadEvent = function(){
        var iPad = /iPad/.test(navigator.userAgent);
        if(iPad){
            document.getElementById('twitter-widget').style.height = '260px';
        }
    };

    if(window.addEventListener){
        window.addEventListener('load', loadEvent);
    } else if(window.attachEvent){
        window.attachEvent('onload', loadEvent);
    } else{
        window.onload = loadEvent;
    }
}());
