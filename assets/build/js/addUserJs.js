var instance = $('form').parsley();

(function() {
    'use strict';
    window.addEventListener('load', function() {
        var form = document.querySelector('form');
        var validation = function() {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        }

    }, false);
})();