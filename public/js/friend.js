document.addEventListener("DOMContentLoaded", function () {
    console.log('App working');

    [].forEach.call(document.querySelectorAll('button.friend-request-response'), function (btn) {
        btn.addEventListener('click', function () {
            let path = btn.getAttribute('path');

            var httpRequest = new XMLHttpRequest();
            httpRequest.onreadystatechange = function (data) {
                console.log('uu');
            };

            httpRequest.setRequestHeader(
                "Content-Type",
                "text/json"
            );

            httpRequest.open("POST", path);
            httpRequest.send();
        })
    })
});