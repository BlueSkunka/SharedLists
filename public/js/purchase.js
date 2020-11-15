document.addEventListener('DOMContentLoaded', function() {
    console.log('Purchase javascript working');

    resetPurchaseBuyEvent();
    resetPurchaseCancelEvent();

    // Purchase - Buy / refund to done
    function resetPurchaseBuyEvent() {
        [].forEach.call(document.querySelectorAll('button.btn-purchase-done'), function(el) {
            el.addEventListener('click', function() {
                const path = el.getAttribute('path');

                var httpRequest = new XMLHttpRequest();
                httpRequest.onreadystatechange = function () {
                    const json = JSON.parse(httpRequest.response);

                    let container = document.querySelector(el.getAttribute('container'));
                    container.remove();

                    let newContainer = document.querySelector('div.purchase-' + (json['isMain'] ? 'bought' : 'refunded') + '-container');
                    newContainer.insertAdjacentHTML('beforeend', json['html']);

                    resetPurchaseCancelEvent();
                };

                httpRequest.open("POST", path);
                httpRequest.setRequestHeader("Content-Type", "text/json");
                httpRequest.send();
            });
        });
    }

    // Purchase - Done to buy / refund
    function resetPurchaseCancelEvent() {
        [].forEach.call(document.querySelectorAll('button.btn-purchase-cancel'), function(el) {
            el.addEventListener('click', function() {
                const path = el.getAttribute('path');

                var httpRequest = new XMLHttpRequest();
                httpRequest.onreadystatechange = function() {
                    const json = JSON.parse(httpRequest.response);

                    let container = document.querySelector(el.getAttribute('container'));
                    container.remove();

                    let newContainer = document.querySelector('div.purchase-to-' + (json['isMain'] ? 'buy' : 'refund') + '-container');
                    newContainer.insertAdjacentHTML('beforeend', json['html']);

                    resetPurchaseBuyEvent();
                };

                httpRequest.open("POST", path);
                httpRequest.setRequestHeader("Content-Type", "text/json");
                httpRequest.send();
            });
        });
    }
    
    
})