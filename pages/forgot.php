<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Banner Fair Surveying & Mapping Ltd</title>
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
            height: 100vh;
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
            padding: 20px;
        }

        .forgot-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            padding: 40px 30px;
            text-align: center;
        }

        .illustration {
            margin-bottom: 20px;
        }

        .illustration i {
            font-size: 60px;
            color: var(--secondary-color);
            opacity: 0.8;
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
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
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

        .input-group input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
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

        .btn-reset {
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

        .btn-reset:hover {
            background-color: var(--secondary-color);
        }

        .back-to-login {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 14px;
            margin-top: 20px;
        }

        .back-to-login:hover {
            text-decoration: underline;
        }

        .steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .steps::before {
            content: '';
            position: absolute;
            height: 2px;
            background-color: #ddd;
            top: 15px;
            left: 40px;
            right: 40px;
            z-index: 1;
        }

        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            color: white;
            z-index: 2;
            position: relative;
        }

        .step.active {
            background-color: var(--primary-color);
        }

        .step.completed {
            background-color: var(--success-color);
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .verify-code {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .code-input {
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 18px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .code-input:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(46, 134, 193, 0.2);
        }

        .password-requirements {
            margin-top: 15px;
            font-size: 12px;
            color: #777;
            text-align: left;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 5px;
        }

        .requirement i {
            color: #ddd;
        }

        .requirement.met i {
            color: var(--success-color);
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

        @media (max-width: 576px) {
            .forgot-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <div class="forgot-container">
            <div class="illustration">
                <i class="fas fa-key"></i>
            </div>
            
            <div class="form-header">
                <h1>Forgot Your Password?</h1>
                <p>No worries! Enter your email and we'll send you a reset link.</p>
            </div>

            <div class="steps">
                <div class="step active" id="step1">1</div>
                <div class="step" id="step2">2</div>
                <div class="step" id="step3">3</div>
            </div>

            <!-- Alert - initially hidden, shown with JS -->
            <div class="alert alert-error" id="errorAlert" style="display: none">
                <i class="fas fa-exclamation-circle"></i>
                <span id="errorText">Something went wrong!</span>
            </div>

            <!-- Step 1: Email -->
            <div class="form-section active" id="section1">
                <form id="emailForm">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-group">
                            <i class="input-icon fas fa-envelope"></i>
                            <input type="email" id="email" placeholder="Enter your registered email" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-reset">
                        <i class="fas fa-paper-plane"></i> Send Reset Code
                    </button>
                </form>
            </div>

            <!-- Step 2: Verification Code -->
            <div class="form-section" id="section2">
                <form id="verifyForm">
                    <div class="form-group">
                        <label>Enter Verification Code</label>
                        <p style="font-size: 13px; color: #777; margin-bottom: 15px;">
                            We've sent a 6-digit code to your email. Please enter it below.
                        </p>
                        <div class="verify-code">
                            <input type="text" class="code-input" maxlength="1" id="code1" inputmode="numeric" required>
                            <input type="text" class="code-input" maxlength="1" id="code2" inputmode="numeric" required>
                            <input type="text" class="code-input" maxlength="1" id="code3" inputmode="numeric" required>
                            <input type="text" class="code-input" maxlength="1" id="code4" inputmode="numeric" required>
                            <input type="text" class="code-input" maxlength="1" id="code5" inputmode="numeric" required>
                            <input type="text" class="code-input" maxlength="1" id="code6" inputmode="numeric" required>
                        </div>
                        <p style="font-size: 13px; color: #777; text-align: center;">
                            Didn't receive a code? <a href="#" id="resendCode" style="color: var(--secondary-color);">Resend</a>
                        </p>
                    </div>

                    <button type="submit" class="btn-reset">
                        <i class="fas fa-check-circle"></i> Verify Code
                    </button>
                </form>
            </div>

            <!-- Step 3: New Password -->
            <div class="form-section" id="section3">
                <form id="resetForm">
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <div class="input-group">
                            <i class="input-icon fas fa-lock"></i>
                            <input type="password" id="newPassword" placeholder="Create new password" required>
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
                        <label for="confirmPassword">Confirm Password</label>
                        <div class="input-group">
                            <i class="input-icon fas fa-lock"></i>
                            <input type="password" id="confirmPassword" placeholder="Confirm new password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-reset">
                        <i class="fas fa-save"></i> Reset Password
                    </button>
                </form>
            </div>

            <a href="login.php" class="back-to-login">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailForm = document.getElementById('emailForm');
            const verifyForm = document.getElementById('verifyForm');
            const resetForm = document.getElementById('resetForm');
            const errorAlert = document.getElementById('errorAlert');
            const errorText = document.getElementById('errorText');
            const resendCode = document.getElementById('resendCode');
            
            // Step navigation
            function goToStep(step) {
                // Hide all sections
                document.querySelectorAll('.form-section').forEach(section => {
                    section.classList.remove('active');
                });
                
                // Show current section
                document.getElementById('section' + step).classList.add('active');
                
                // Update step indicators
                document.querySelectorAll('.step').forEach((stepEl, index) => {
                    if (index + 1 < step) {
                        stepEl.classList.remove('active');
                        stepEl.classList.add('completed');
                        stepEl.innerphp = '<i class="fas fa-check"></i>';
                    } else if (index + 1 === step) {
                        stepEl.classList.add('active');
                        stepEl.classList.remove('completed');
                        stepEl.innerphp = step;
                    } else {
                        stepEl.classList.remove('active', 'completed');
                        stepEl.innerphp = index + 1;
                    }
                });
            }
            
            // Email submission
            emailForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = document.getElementById('email').value;
                
                if (!email) {
                    showError('Please enter your email address');
                    return;
                }
                
                // Simulate API call
                const button = emailForm.querySelector('.btn-reset');
                simulateLoading(button, 'Sending...', 'Send Reset Code');
                
                // After 2 seconds, go to next step (verification code)
                setTimeout(() => {
                    goToStep(2);
                    // Focus on first code input
                    document.getElementById('code1').focus();
                    setTimeout(() => {
                        goToStep(2);
                        // Focus on first code input
                        document.getElementById('code1').focus();
                    }, 2000);
                });
            });
            
            // Verification code submission
            verifyForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get all code inputs
                const codeInputs = document.querySelectorAll('.code-input');
                let code = '';
                let isValid = true;
                
                // Validate all inputs are filled
                codeInputs.forEach(input => {
                    if (!input.value) {
                        isValid = false;
                    }
                    code += input.value;
                });
                
                if (!isValid) {
                    showError('Please enter the complete verification code');
                    return;
                }
                
                // Simulate API call
                const button = verifyForm.querySelector('.btn-reset');
                simulateLoading(button, 'Verifying...', 'Verify Code');
                
                // After 2 seconds, go to next step (new password)
                setTimeout(() => {
                    goToStep(3);
                    // Focus on new password input
                    document.getElementById('newPassword').focus();
                }, 2000);
            });
            
            // Handle code input auto-advance
            document.querySelectorAll('.code-input').forEach((input, index) => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    if (this.value && index < 5) {
                        document.querySelectorAll('.code-input')[index + 1].focus();
                    }
                });
                
                // Allow backspace to go back
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        document.querySelectorAll('.code-input')[index - 1].focus();
                    }
                });
            });
            
            // Password reset submission
            resetForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const newPassword = document.getElementById('newPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                
                if (!newPassword) {
                    showError('Please enter a new password');
                    return;
                }
                
                if (newPassword !== confirmPassword) {
                    showError('Passwords do not match');
                    return;
                }
                
                // Check password strength
                if (!checkPasswordStrength(newPassword)) {
                    showError('Password does not meet requirements');
                    return;
                }
                
                // Simulate API call
                const button = resetForm.querySelector('.btn-reset');
                simulateLoading(button, 'Resetting...', 'Reset Password');
                
                // After 2 seconds, show success and redirect
                setTimeout(() => {
                    errorAlert.className = 'alert alert-success';
                    errorText.textContent = 'Password reset successful! Redirecting to login...';
                    document.querySelector('#errorAlert i').className = 'fas fa-check-circle';
                    errorAlert.style.display = 'flex';
                    
                    // Redirect to login after 2 more seconds
                    setTimeout(() => {
                        // In production, this would redirect to login
                        // window.location.href = 'login.php';
                    }, 2000);
                }, 2000);
            });
            
            // Password strength checker
            const newPassword = document.getElementById('newPassword');
            newPassword.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });
            
            // Password strength checker
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
            
            // Resend code
            resendCode.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Show loading state
                this.innerphp = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                this.style.pointerEvents = 'none';
                
                // After 2 seconds, reset
                setTimeout(() => {
                    this.innerphp = 'Resend';
                    this.style.pointerEvents = 'auto';
                    
                    // Show success message
                    errorAlert.className = 'alert alert-success';
                    errorText.textContent = 'Verification code resent!';
                    document.querySelector('#errorAlert i').className = 'fas fa-check-circle';
                    errorAlert.style.display = 'flex';
                    
                    // Clear after 3 seconds
                    setTimeout(() => {
                        errorAlert.style.display = 'none';
                    }, 3000);
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
            function simulateLoading(button, loadingText, originalText) {
                const originalContent = button.innerphp;
                
                // Change button to loading state
                button.innerphp = `<i class="fas fa-spinner fa-spin"></i> ${loadingText}`;
                button.disabled = true;
                
                // Return a function to reset the button
                return setTimeout(() => {
                    button.innerphp = originalContent;
                    button.disabled = false;
                }, 2000);
            }
        });
    </script>
</body>
</php>