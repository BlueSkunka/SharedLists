document.addEventListener("DOMContentLoaded", function () {
    console.log('Listing javascript working');

    // Ajouer un item dans un listing
    document.querySelector('button.listing-add-item').addEventListener('click', function() {
        let btn = document.querySelector('button.listing-add-item');

        let list = document.querySelector(btn.getAttribute('data-list-selector'));

        let counter = list.getAttribute('data-widget-counter');

        const regex = /__name__/g;

        let newWidget = list.getAttribute('data-prototype');
        newWidget = '<div>' + newWidget.replace(regex, counter) + '</div>';

        counter++;

        list.setAttribute('data-widget-counter', counter);
        
        list.insertAdjacentHTML('beforeend', newWidget);

        return false;
    });

    // Gestion affichage modifier listing - Update
    document.querySelector('button#listing-update-btn-update').addEventListener('click', function() {
        let btnUpdate = document.querySelector('button#listing-update-btn-update');
        btnUpdate.classList.add('hidden');

        let btnValidate = document.querySelector('button#listing-update-btn-submit');
        btnValidate.classList.remove('hidden');

        let btnCancel = document.querySelector('button#listing-update-btn-cancel');
        btnCancel.classList.remove('hidden');

        let btnAddItem = document.querySelector('button.listing-add-item');
        btnAddItem.classList.remove('hidden');

        listingFormReadonly(false);

        return false;
    });

    // Gestion affichage modifier listing - Cancel
    document.querySelector('button#listing-update-btn-cancel').addEventListener('click', function() {
        let btnUpdate = document.querySelector('button#listing-update-btn-update');
        btnUpdate.classList.remove('hidden');

        let btnValidate = document.querySelector('button#listing-update-btn-submit');
        btnValidate.classList.add('hidden');

        let btnCancel = document.querySelector('button#listing-update-btn-cancel');
        btnCancel.classList.add('hidden');

        let btnAddItem = document.querySelector('button.listing-add-item');
        btnAddItem.classList.add('hidden');

        listingFormReadonly(true);

        return false;
    });

    // Gestion affichage modifier listing - Readonly
    listingFormReadonly(true);

    function listingFormReadonly(state) {
        [].forEach.call(document.forms["listing"].getElementsByTagName("input"), function(input) {
            input.setAttribute('readonly', state == true ? 'readonly' : '');
            if (state == true)
                input.setAttribute('readonly', 'readonly');
            else
                input.removeAttribute('readonly');
        });

        [].forEach.call(document.forms["listing"].getElementsByTagName("textarea"), function(textarea) {
            textarea.setAttribute('readonly', state == true ? 'readonly' : '');
            if (state == true)
                textarea.setAttribute('readonly', 'readonly');
            else
                textarea.removeAttribute('readonly');
        });

        return true;
    }       
    
    // Gestion affichage listing - UserList
    [].forEach.call(document.querySelectorAll('div.listing-user'), function(el) {
        el.addEventListener('click', function() {
            let path = el.getAttribute('path');

            var httpRequest = new XMLHttpRequest();
            httpRequest.onreadystatechange = function () {
                // console.log(httpRequest);
                // console.log(httpRequest.response);

                const json = JSON.parse(httpRequest.response);

                let listContainer = document.querySelector('div.list-container');
                listContainer.innerHTML = '';
                listContainer.insertAdjacentHTML('beforeend', json['html']);

                let listUser = document.querySelector('h2#listing-username');
                listUser.innerHTML = '';
                listUser.insertAdjacentHTML('beforeend', 'Liste de ' + json['username']);

                resetPurchaseButonEvent();
            };

            httpRequest.open("POST", path);
            httpRequest.setRequestHeader("Content-Type", "text/json");
            httpRequest.send();
        })
    });

    // Gestion choix item dans listing -  Show modal
    function resetPurchaseButonEvent() {
        [].forEach.call(document.querySelectorAll('.purchase-item'), function(el) {
            el.addEventListener('click', function() {
                const path = el.getAttribute('path'); // to define

                var httpRequest = new XMLHttpRequest();
                httpRequest.onreadystatechange = function() {
                    const json = JSON.parse(httpRequest.response);

                    let formContainer = document.querySelector('.modal-body');
                    formContainer.innerHTML = '';
                    formContainer.insertAdjacentHTML('beforeend', json['html']);

                    let titleContainer = document.querySelector('.modal-title');
                    titleContainer.innerHTML = '';
                    titleContainer.insertAdjacentHTML('beforeend', 'Choisir "' + json['itemName'] + '"');
                }

                httpRequest.open("POST", path);
                httpRequest.setRequestHeader("Content-Type", "text/json");
                httpRequest.send();
            })
        })
    }

    // Gestion choix item dans listing - Validate choice
    document.querySelector('#purchase-item-validate').addEventListener('click', function() {
        console.log('submitting');
        document.forms["purchase"].submit();
    })
    
})