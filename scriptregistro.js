document.getElementById('registerForm').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value;
    const correo = document.getElementById('correo').value;
    const contraseña = document.getElementById('contraseña').value;
    const confirmarContraseña = document.getElementById('confirmar_contraseña').value;
    
    if (!nombre || !correo || !contraseña || !confirmarContraseña) {
        e.preventDefault();
        showError('Por favor, completa todos los campos');
        return;
    }
    
    if (!isValidEmail(correo)) {
        e.preventDefault();
        showError('Por favor, ingresa un correo válido');
        return;
    }
    
    if (contraseña.length < 6) {
        e.preventDefault();
        showError('La contraseña debe tener al menos 6 caracteres');
        return;
    }
    
    if (contraseña !== confirmarContraseña) {
        e.preventDefault();
        showError('Las contraseñas no coinciden');
        return;
    }
});

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showError(message) {
    const existingError = document.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    const form = document.getElementById('registerForm');
    form.parentNode.insertBefore(errorDiv, form);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
}

function showSuccess(message) {
    const existingSuccess = document.querySelector('.success-message');
    if (existingSuccess) {
        existingSuccess.remove();
    }
    
    const successDiv = document.createElement('div');
    successDiv.className = 'success-message';
    successDiv.style.backgroundColor = 'rgba(40, 167, 69, 0.1)';
    successDiv.style.border = '1px solid rgba(40, 167, 69, 0.3)';
    successDiv.style.color = '#28a745';
    successDiv.style.padding = '1rem';
    successDiv.style.borderRadius = '10px';
    successDiv.style.marginBottom = '1rem';
    successDiv.style.textAlign = 'center';
    successDiv.textContent = message;
    
    const form = document.getElementById('registerForm');
    form.parentNode.insertBefore(successDiv, form);
    
    setTimeout(() => {
        window.location.href = 'login.html';
    }, 2000);
}

// Check for messages from PHP
window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    const success = urlParams.get('success');
    
    if (error === 'exists') {
        showError('Este correo ya está registrado');
    } else if (error === 'passwords') {
        showError('Las contraseñas no coinciden');
    } else if (error === 'empty') {
        showError('Por favor, completa todos los campos');
    } else if (success === '1') {
        showSuccess('Registro exitoso. Redirigiendo al login...');
    }
});
