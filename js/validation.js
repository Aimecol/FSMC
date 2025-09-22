/**
 * Client-side validation functions for FSMC project
 */

// Email validation
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Phone validation (Rwanda format)
function validatePhone(phone) {
    // Remove spaces and special characters
    const cleanPhone = phone.replace(/[^0-9+]/g, '');
    
    // Check Rwanda phone number patterns
    const patterns = [
        /^(\+250|250)?[0-9]{9}$/,  // +250xxxxxxxxx or 250xxxxxxxxx or xxxxxxxxx
        /^07[0-9]{8}$/,            // 07xxxxxxxx
        /^08[0-9]{8}$/,            // 08xxxxxxxx
    ];
    
    return patterns.some(pattern => pattern.test(cleanPhone));
}

// Password strength validation
function validatePassword(password) {
    const errors = [];
    
    if (password.length < 6) {
        errors.push('Password must be at least 6 characters long');
    }
    
    if (!/[A-Z]/.test(password)) {
        errors.push('Password must contain at least one uppercase letter');
    }
    
    if (!/[a-z]/.test(password)) {
        errors.push('Password must contain at least one lowercase letter');
    }
    
    if (!/[0-9]/.test(password)) {
        errors.push('Password must contain at least one number');
    }
    
    return {
        valid: errors.length === 0,
        errors: errors
    };
}

// Real-time form validation
function setupFormValidation(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    // Email fields
    const emailFields = form.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        field.addEventListener('blur', function() {
            validateEmailField(this);
        });
        
        field.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
    
    // Phone fields
    const phoneFields = form.querySelectorAll('input[name*="phone"], input[id*="phone"]');
    phoneFields.forEach(field => {
        field.addEventListener('blur', function() {
            validatePhoneField(this);
        });
        
        field.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
    
    // Password fields
    const passwordFields = form.querySelectorAll('input[type="password"]');
    passwordFields.forEach(field => {
        field.addEventListener('blur', function() {
            validatePasswordField(this);
        });
        
        field.addEventListener('input', function() {
            clearFieldError(this);
            if (this.name === 'password' || this.id === 'password') {
                updatePasswordStrengthIndicator(this);
            }
        });
    });
    
    // Required fields
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            validateRequiredField(this);
        });
        
        field.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        if (!validateForm(this)) {
            e.preventDefault();
            return false;
        }
    });
}

// Validate individual email field
function validateEmailField(field) {
    const email = field.value.trim();
    if (email && !validateEmail(email)) {
        showFieldError(field, 'Please enter a valid email address');
        return false;
    }
    clearFieldError(field);
    return true;
}

// Validate individual phone field
function validatePhoneField(field) {
    const phone = field.value.trim();
    if (phone && !validatePhone(phone)) {
        showFieldError(field, 'Please enter a valid phone number');
        return false;
    }
    clearFieldError(field);
    return true;
}

// Validate individual password field
function validatePasswordField(field) {
    const password = field.value;
    if (password) {
        const validation = validatePassword(password);
        if (!validation.valid) {
            showFieldError(field, validation.errors.join('<br>'));
            return false;
        }
    }
    clearFieldError(field);
    return true;
}

// Validate required field
function validateRequiredField(field) {
    const value = field.value.trim();
    if (!value) {
        showFieldError(field, 'This field is required');
        return false;
    }
    clearFieldError(field);
    return true;
}

// Show field error
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('is-invalid');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.innerHTML = message;
    
    field.parentNode.appendChild(errorDiv);
}

// Clear field error
function clearFieldError(field) {
    field.classList.remove('is-invalid');
    
    const errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Validate entire form
function validateForm(form) {
    let isValid = true;
    
    // Clear all previous errors
    form.querySelectorAll('.is-invalid').forEach(field => {
        clearFieldError(field);
    });
    
    // Validate required fields
    form.querySelectorAll('[required]').forEach(field => {
        if (!validateRequiredField(field)) {
            isValid = false;
        }
    });
    
    // Validate email fields
    form.querySelectorAll('input[type="email"]').forEach(field => {
        if (!validateEmailField(field)) {
            isValid = false;
        }
    });
    
    // Validate phone fields
    form.querySelectorAll('input[name*="phone"], input[id*="phone"]').forEach(field => {
        if (!validatePhoneField(field)) {
            isValid = false;
        }
    });
    
    // Validate password fields
    form.querySelectorAll('input[type="password"]').forEach(field => {
        if (!validatePasswordField(field)) {
            isValid = false;
        }
    });
    
    // Scroll to first error
    if (!isValid) {
        const firstError = form.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }
    
    return isValid;
}

// Password strength indicator
function updatePasswordStrengthIndicator(field) {
    const password = field.value;
    const strengthIndicator = document.getElementById('password-strength');
    
    if (!strengthIndicator) return;
    
    let strength = 0;
    let strengthText = '';
    let strengthClass = '';
    
    if (password.length >= 6) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    switch (strength) {
        case 0:
        case 1:
            strengthText = 'Very Weak';
            strengthClass = 'text-danger';
            break;
        case 2:
            strengthText = 'Weak';
            strengthClass = 'text-warning';
            break;
        case 3:
            strengthText = 'Fair';
            strengthClass = 'text-info';
            break;
        case 4:
            strengthText = 'Good';
            strengthClass = 'text-success';
            break;
        case 5:
            strengthText = 'Strong';
            strengthClass = 'text-success';
            break;
    }
    
    strengthIndicator.textContent = strengthText;
    strengthIndicator.className = `password-strength ${strengthClass}`;
}

// File upload validation
function validateFileUpload(input, allowedTypes = ['image/jpeg', 'image/png', 'image/gif'], maxSize = 5242880) {
    const file = input.files[0];
    if (!file) return true;
    
    // Check file type
    if (!allowedTypes.includes(file.type)) {
        showFieldError(input, 'Invalid file type. Only JPG, PNG, and GIF files are allowed.');
        return false;
    }
    
    // Check file size
    if (file.size > maxSize) {
        const maxSizeMB = (maxSize / 1024 / 1024).toFixed(1);
        showFieldError(input, `File size too large. Maximum size is ${maxSizeMB}MB.`);
        return false;
    }
    
    clearFieldError(input);
    return true;
}

// Initialize validation on page load
document.addEventListener('DOMContentLoaded', function() {
    // Setup validation for common forms
    const forms = ['loginForm', 'registrationForm', 'userForm', 'productForm', 'serviceForm', 'researchForm', 'trainingForm'];
    forms.forEach(formId => {
        setupFormValidation(formId);
    });
    
    // Setup file upload validation
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            validateFileUpload(this);
        });
    });
});
