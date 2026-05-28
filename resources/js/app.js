import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {

    window.Echo.channel('category')
        .listen('.category.created', (e) => {
            console.log('Kategori baru:', e.category);
        });

});