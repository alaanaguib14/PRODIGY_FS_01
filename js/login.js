document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const errorElements = {
        email: document.createElement('div'),
        password: document.createElement('div')
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
            case 'email':
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!value) errorMessage = 'Email is required';
                else if (!emailPattern.test(value)) errorMessage = 'Invalid email format';
                break;
            case 'password':
                if (!value) errorMessage = 'Password is required';
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