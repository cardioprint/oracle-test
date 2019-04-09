preventXHR = function() {
    XMLHttpRequest.prototype.send = function () {
        alert('You cheater! Go and find some ways without XHR.');
    };

};

preventXHR();
