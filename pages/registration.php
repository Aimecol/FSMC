<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Banner Fair Surveying & Mapping Ltd</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a5276;
            --secondary-color: #2e86c1;
            --accent-color: #f39c12;
            --light-color: #f4f6f7;
            --dark-color: #2c3e50;
            --error-color: #e74c3c;
            --success-color: #27ae60;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            padding: 15px 0;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            font-size: 24px;
            color: var(--primary-color);
        }

        .logo-text {
            line-height: 1.2;
        }

        .logo-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .logo-tagline {
            font-size: 12px;
            color: var(--dark-color);
            font-style: italic;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .register-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            overflow: hidden;
            display: flex;
            flex-direction: row;
        }

        .register-sidebar {
            flex: 0 0 40%;
            background-color: var(--primary-color);
            color: white;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .register-sidebar-content {
            flex: 1;
        }

        .sidebar-header {
            margin-bottom: 30px;
        }

        .sidebar-header h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .sidebar-header p {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .benefits {
            margin-top: 30px;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .benefit-icon {
            font-size: 18px;
            color: var(--accent-color);
        }

        .benefit-text {
            font-size: 14px;
            opacity: 0.9;
        }

        .register-form {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h1 {
            font-size: 24px;
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .form-header p {
            color: #777;
            font-size: 14px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-color);
            font-weight: 500;
            font-size: 14px;
        }

        .input-group {
            position: relative;
        }

        .input-group input, .input-group select {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .input-group input:focus, .input-group select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(46, 134, 193, 0.2);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #aaa;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #aaa;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 14px;
        }

        .password-requirements {
            margin-top: 10px;
            font-size: 12px;
            color: #777;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 5px;
        }

        .requirement i {
            color: #ddd;
            font-size: 10px;
        }

        .requirement.met i {
            color: var(--success-color);
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
        }

        .checkbox-group input {
            margin-top: 2px;
        }

        .checkbox-group label {
            font-size: 14px;
            color: #777;
            line-height: 1.4;
        }

        .checkbox-group a {
            color: var(--secondary-color);
            text-decoration: none;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        .btn-register {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .btn-register:hover {
            background-color: var(--secondary-color);
        }

        .alternate-auth {
            position: relative;
            margin: 30px 0 20px;
            text-align: center;
        }

        .alternate-auth::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #ddd;
        }

        .alternate-auth span {
            position: relative;
            background-color: white;
            padding: 0 10px;
            color: #777;
            font-size: 14px;
        }

        .social-register {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .social-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .social-btn i {
            font-size: 16px;
        }

        .google i {
            color: #DB4437;
        }

        .facebook i {
            color: #4267B2;
        }

        .twitter i {
            color: #1DA1F2;
        }

        .login-link {
            text-align: center;
            font-size: 14px;
            color: #777;
        }

        .login-link a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error-color);
            border-left: 3px solid var(--error-color);
        }

        .alert-success {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            border-left: 3px solid var(--success-color);
        }

        .alert i {
            font-size: 16px;
        }

        .sidebar-footer {
            margin-top: 40px;
            font-size: 12px;
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
                max-width: 500px;
            }

            .register-sidebar {
                padding: 30px 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            .register-form {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <div class="register-container">
            <div class="register-sidebar">
                <div class="register-sidebar-content">
                    <div class="sidebar-header">
                        <h2>Join Our Community</h2>
                        <p>Create an account to access our professional land surveying services and resources.</p>
                    </div>
                    
                    <div class="benefits">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="benefit-text">
                                Track your surveying projects in real-time
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="benefit-text">
                                Access exclusive technical resources
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="benefit-text">
                                Receive updates on your property boundaries
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="benefit-text">
                                Store and access your surveying documents
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-footer">
                    <p>HATANGIMANA Fulgence, Surveyor code: LS00280</p>
                </div>
            </div>
            
            <div class="register-form">
                <div class="form-header">
                    <h1>Create an Account</h1>
                    <p>Fill in your details to get started</p>
                </div>
                
                <!-- Alert - initially hidden, shown with JS -->
                <div class="alert alert-error" id="errorAlert" style="display: none">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="errorText">Please fill in all required fields!</span>
                </div>
                
                <form id="registerForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="firstName">First Name *</label>
                            <div class="input-group">
                                <i class="input-icon fas fa-user"></i>
                                <input type="text" id="firstName" placeholder="Enter your first name" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="lastName">Last Name *</label>
                            <div class="input-group">
                                <i class="input-icon fas fa-user"></i>
                                <input type="text" id="lastName" placeholder="Enter your last name" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <div class="input-group">
                                <i class="input-icon fas fa-envelope"></i>
                                <input type="email" id="email" placeholder="Enter your email address" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <div class="input-group">
                                <i class="input-icon fas fa-phone"></i>
                                <input type="tel" id="phone" placeholder="Enter your phone number" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <div class="input-group">
                                <i class="input-icon fas fa-lock"></i>
                                <input type="password" id="password" placeholder="Create a password" required>
                                <button type="button" class="toggle-password" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-requirements">
                                <div class="requirement" id="req-length">
                                    <i class="fas fa-circle"></i>
                                    <span>At least 8 characters</span>
                                </div>
                                <div class="requirement" id="req-uppercase">
                                    <i class="fas fa-circle"></i>
                                    <span>At least 1 uppercase letter</span>
                                </div>
                                <div class="requirement" id="req-lowercase">
                                    <i class="fas fa-circle"></i>
                                    <span>At least 1 lowercase letter</span>
                                </div>
                                <div class="requirement" id="req-number">
                                    <i class="fas fa-circle"></i>
                                    <span>At least 1 number</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password *</label>
                            <div class="input-group">
                                <i class="input-icon fas fa-lock"></i>
                                <input type="password" id="confirmPassword" placeholder="Confirm your password" required>
                                <button type="button" class="toggle-password" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="userType">Account Type *</label>
                            <div class="input-group">
                                <i class="input-icon fas fa-users"></i>
                                <select id="userType" required>
                                    <option value="" disabled selected>Select account type</option>
                                    <option value="individual">Individual</option>
                                    <option value="business">Business</option>
                                    <option value="government">Government</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <div class="input-group">
                                <i class="input-icon fas fa-map-marker-alt"></i>
                                <input type="text" id="address" placeholder="Enter your address">
                            </div>
                        </div>
                        
                        <div class="form-group full-width">
                            <div class="checkbox-group">
                                <input type="checkbox" id="termsAgree" required>
                                <label for="termsAgree">
                                    I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                                </label>
                            </div>
                            
                            <div class="checkbox-group">
                                <input type="checkbox" id="newsletter">
                                <label for="newsletter">
                                    Subscribe to our newsletter to receive updates on new services and promotions
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>
                </form>
                
                <div class="alternate-auth">
                    <span>Or sign up with</span>
                </div>
                
                <div class="social-register">
                    <div class="social-btn google">
                        <i class="fab fa-google"></i>
                    </div>
                    <div class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <div class="social-btn twitter">
                        <i class="fab fa-twitter"></i>
                    </div>
                </div>
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Sign In</a>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const errorAlert = document.getElementById('errorAlert');
            const errorText = document.getElementById('errorText');
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');
            
            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                togglePasswordVisibility(password, this);
            });
            
            toggleConfirmPassword.addEventListener('click', function() {
                togglePasswordVisibility(confirmPassword, this);
            });
            
            function togglePasswordVisibility(inputField, button) {
                const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
                inputField.setAttribute('type', type);
                
                // Change eye icon
                button.querySelector('i').classList.toggle('fa-eye');
                button.querySelector('i').classList.toggle('fa-eye-slash');
            }
            
            // Password strength checker
            password.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });
            
            function checkPasswordStrength(password) {
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password)
                };
                
                document.getElementById('req-length').classList.toggle('met', requirements.length);
                document.getElementById('req-uppercase').classList.toggle('met', requirements.uppercase);
                document.getElementById('req-lowercase').classList.toggle('met', requirements.lowercase);
                document.getElementById('req-number').classList.toggle('met', requirements.number);
                
                return requirements.length && requirements.uppercase && 
                       requirements.lowercase && requirements.number;
            }
            
            // Form submission
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Basic validation
                const firstName = document.getElementById('firstName').value;
                const lastName = document.getElementById('lastName').value;
                const email = document.getElementById('email').value;
                const phone = document.getElementById('phone').value;
                const passwordValue = password.value;
                const confirmPasswordValue = confirmPassword.value;
                const userType = document.getElementById('userType').value;
                const termsAgree = document.getElementById('termsAgree').checked;
                
                // Check required fields
                if (!firstName || !lastName || !email || !phone || !passwordValue || !confirmPasswordValue || !userType || !termsAgree) {
                    showError('Please fill in all required fields');
                    return;
                }
                
                // Check password match
                if (passwordValue !== confirmPasswordValue) {
                    showError('Passwords do not match');
                    return;
                }
                
                // Check password strength
                if (!checkPasswordStrength(passwordValue)) {
                    showError('Password does not meet the requirements');
                    return;
                }
                
                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showError('Please enter a valid email address');
                    return;
                }
                
                // Simulate form submission
                const button = document.querySelector('.btn-register');
                simulateLoading(button, 'Creating Account...', 'Create Account');
                
                // After 2 seconds, show success
                setTimeout(() => {
                    errorAlert.className = 'alert alert-success';
                    errorText.textContent = 'Account created successfully! Redirecting...';
                    document.querySelector('#errorAlert i').className = 'fas fa-check-circle';
                    errorAlert.style.display = 'flex';
                    
                    // Redirect after 2 more seconds
                    setTimeout(() => {
                        // In production, this would redirect to dashboard
                        window.location.href = './login.php';
                    }, 2000);
                }, 2000);
            });
            
            // Helper function to show error
            function showError(message) {
                errorAlert.className = 'alert alert-error';
                errorText.textContent = message;
                document.querySelector('#errorAlert i').className = 'fas fa-exclamation-circle';
                errorAlert.style.display = 'flex';
                
                // Hide after 5 seconds
                setTimeout(() => {
                    errorAlert.style.display = 'none';
                }, 5000);
            }
            
            // Helper function to simulate loading
            // Helper function to simulate loading
            function simulateLoading(button, loadingText, originalText) {
                const originalContent = button.innerphp;
                
                // Change button to loading state
                button.innerphp = `<i class="fas fa-spinner fa-spin"></i> ${loadingText}`;
                button.disabled = true;
                
                // Return function to restore button
                return function() {
                    button.innerphp = originalContent;
                    button.disabled = false;
                };
            }
        });
    </script>
</body>
</php>