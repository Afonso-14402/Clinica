document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.menu-toggle').forEach(item => {
        item.addEventListener('click', function() {
            // Verifique se o código está sendo executado
            console.log('Menu toggle clicado');
            // Toggle a classe 'active' no item pai
            this.closest('.menu-item').classList.toggle('active');
        });
    });
});
