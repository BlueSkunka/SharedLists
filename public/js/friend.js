document.addEventListener("DOMContentLoaded", function () {
    console.log('Friend javascript working');

    // Friend Request - Cancel request
    [].forEach.call(document.querySelectorAll('.btn-remove-friend-request'), function(el) {
        el.addEventListener('click', function() {
            const path = el.getAttribute('path');
            const container = document.querySelector(el.getAttribute('container'));

            var httpRequest = new XMLHttpRequest();
            httpRequest.onreadystatechange = function() {
                // const json = JSON.parse(httpRequest.response);

                container.remove();
            };

            httpRequest.open("POST", path);
            httpRequest.setRequestHeader("Content-Type", "text/json");
            httpRequest.send();
        })
    });

    // Friend Request - Accept request
    [].forEach.call(document.querySelectorAll('.friend-request-accept'), function(el) {
        el.addEventListener('click', function() {
            const path = el.getAttribute('path');
            const container = document.querySelector(el.getAttribute('container'));

            var httpRequest = new XMLHttpRequest();
            httpRequest.onreadystatechange = function() {
                const json = JSON.parse(httpRequest.response);

                container.remove();

                let friendList = document.querySelector('div#friends-list');
                friendList.insertAdjacentHTML('beforeend', json['username'] + '<br>');
            };

            httpRequest.open("POST", path);
            httpRequest.setRequestHeader("Content-Type", "text/json");
            httpRequest.send();
        });
    });

    // Friend Request - Refuse request
    [].forEach.call(document.querySelectorAll('.friend-request-refuse'), function(el) {
        el.addEventListener('click', function() {
            const path = el.getAttribute('path');
            const container = document.querySelector(el.getAttribute('container'));

            var httpRequest = new XMLHttpRequest();
            httpRequest.onreadystatechange = function() {
                container.remove();
            };

            httpRequest.open("POST", path);
            httpRequest.setRequestHeader("Content-Type", "text/json");
            httpRequest.send();
        });
    })

});