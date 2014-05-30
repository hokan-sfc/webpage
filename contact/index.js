(function(){
    'use strict'
    function getQueries(){
        var urls = location.href.split('?');
        var queryString = urls[1].split('&');
        var queries = {};

        for(var i = 0; i < queryString.length; i++){
            var query = queryString.split('=');
            queries[query[0]] = query[1];
        }

        return queries;
    }

    var flash = document.getElementById('flash');
}());
