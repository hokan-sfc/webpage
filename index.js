(function(){
    var loadEvent = function(){
        var iPad = /iPad/.test(navigator.userAgent);
        if(iPad){
            document.getElementsByTagId('twitter-widget-0').style.height = '18.8em';
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
