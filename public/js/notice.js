document.addEventListener('DOMContentLoaded', function() {
    console.log('Notice javascript working');
  
    // Notice seen
    [].forEach.call(document.querySelectorAll('button.btn-notice-seen'), function(el) {
        el.addEventListener('click', function() {
            const path = el.getAttribute('path');

            var httpRequest = new XMLHttpRequest();
            httpRequest.onreadystatechange = function () {
                el.parentNode.remove();

                removeAppContainer();                
            };

            httpRequest.open("POST", path);
            httpRequest.setRequestHeader("Content-Type", "text/json");
            httpRequest.send();
        })
    })

    function removeAppContainer() {
        const notices = document.querySelectorAll('div.notice');

        if (notices.length < 1) {
            let col = document.querySelector('div#notice-container');
            col.remove();
        }
    }
})
