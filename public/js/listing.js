document.addEventListener("DOMContentLoaded", function () {
    console.log('Listing javascript working');

    // Ajouer un item dans un listing
    document.querySelector('button.listing-add-item').addEventListener('click', function() {
        let btn = document.querySelector('button.listing-add-item');

        let list = document.querySelector(btn.getAttribute('data-list-selector'));

        let counter = list.getAttribute('data-widget-counter');

        let newWidget = list.getAttribute('data-prototype');
        newWidget = '<li>' + newWidget.replace('/__name__/g', counter) + '</li>';

        counter++;

        list.setAttribute('data-widget-counter', counter);
        
        list.insertAdjacentHTML('beforeend', newWidget);

        console.log(newWidget);

        return false;
    });
    
})