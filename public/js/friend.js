document.addEventListener("DOMContentLoaded", function () {
    console.log('App working');

    // !To rework
    // [].forEach.call(document.querySelectorAll('button.friend-request-response'), function (btn) {
    //     btn.addEventListener('click', function () {
    //         let path = btn.getAttribute('path');

    //         var httpRequest = new XMLHttpRequest();
    //         httpRequest.onreadystatechange = function (data) {
    //             console.log('uu');
    //         };

    //         httpRequest.setRequestHeader(
    //             "Content-Type",
    //             "text/json"
    //         );

    //         httpRequest.open("POST", path);
    //         httpRequest.send();
    //     })
    // });

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