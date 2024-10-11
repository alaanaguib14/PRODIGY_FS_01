document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const errorElements = {
      password: document.createElement('div'),
      confirm_pass: document.createElement('div')
    };

    for (const field in errorElements) {
        const inputElement = document.querySelector(`[name="${field}"]`);
        const errorElement = errorElements[field];
        errorElement.className = 'error-message';
        errorElement.style.display = 'none';
        inputElement.parentNode.appendChild(errorElement);
        
        inputElement.addEventListener('blur', function() {
            validateField(field);
        });

        inputElement.addEventListener('input', function() {
            validateField(field);
        });
    }

    function validateField(field) {
        const inputElement = document.querySelector(`[name="${field}"]`);
        const value = inputElement.value.trim();
        let errorMessage = '';

        switch (field) {
        case 'password':
            const lowerCase = /[a-z]/.test(value);
            const upperCase = /[A-Z]/.test(value);
            const number = /[0-9]/.test(value);
            if (!value) errorMessage = '';
            else if (!lowerCase || !upperCase || !number) errorMessage = 'Password must contain 1 uppercase, 1 lowercase, and 1 number';
            break;
        case 'confirm_pass':
            if (value !== document.querySelector('[name="password"]').value) errorMessage = 'Passwords do not match';
            break;
        }

        // Show or hide error message
        if (errorMessage) {
            errorElements[field].textContent = errorMessage;
            errorElements[field].style.display = 'block';
        } else {
            errorElements[field].textContent = '';
            errorElements[field].style.display = 'none';
        }

        return !errorMessage;
    }

    form.addEventListener('submit', function(event) {
        let isValid = true;
        for (const field in errorElements) {
        if (!validateField(field)) {
            isValid = false;
        }
        }

        if (!isValid) {
        event.preventDefault();
        }
    });

});