(function(){
    var loadEvent = function(){
        var iPad = /iPad/.test(navigator.userAgent);
        if(iPad){
            document.getElementById('twitter-widget-0').style.height = '300px';
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
