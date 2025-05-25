<?php include_once("modules/login.php")?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Login - Secure Access</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: black;
             background-image: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Elements */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .floating-shapes {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .shape1 {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape2 {
            top: 20%;
            right: 10%;
            width: 80px;
            height: 80px;
            animation-delay: 2s;
        }

        .shape3 {
            bottom: 20%;
            left: 15%;
            width: 120px;
            height: 120px;
            animation-delay: 4s;
        }

        .shape4 {
            bottom: 30%;
            right: 20%;
            width: 60px;
            height: 60px;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Main Container */
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            position: relative;
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ff6b6b, #ffa726);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .login-title {
            color: white;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .login-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            font-weight: 300;
        }

        /* Form Styles */
        .form-group {
            position: relative;
            margin-bottom: 30px;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px 16px 55px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-input:focus {
            outline: none;
            border-color: #ff6b6b;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.2);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .form-group:hover .input-icon,
        .form-input:focus + .input-icon {
            color: #ff6b6b;
            transform: translateY(-50%) scale(1.1);
        }

        .password-toggle {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            color: #ff6b6b;
            transform: translateY(-50%) scale(1.1);
        }

        /* Login Button */
        .login-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #ff6b6b, #ffa726);
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 107, 107, 0.4);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        /* Additional Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }

        .remember-me input {
            margin-right: 8px;
            accent-color: #ff6b6b;
        }

        .forgot-password {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: #ff6b6b;
            text-shadow: 0 0 10px rgba(255, 107, 107, 0.5);
        }

        /* Error Alert */
        .alert {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #fff;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Social Login */
        .social-login {
            margin-top: 30px;
            text-align: center;
        }

        .social-divider {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .social-divider::before,
        .social-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .social-divider span {
            color: rgba(255, 255, 255, 0.7);
            padding: 0 20px;
            font-size: 0.9rem;
        }

        .social-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .social-btn:hover {
            transform: translateY(-3px);
            border-color: #ff6b6b;
            background: rgba(255, 107, 107, 0.2);
            color: white;
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
        }

        /* Loading State */
        .loading {
            position: relative;
            color: transparent;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 20px;
                border-radius: 20px;
            }

            .login-title {
                font-size: 1.8rem;
            }

            .form-input {
                padding: 14px 18px 14px 50px;
            }

            .form-options {
                flex-direction: column;
                text-align: center;
            }

            .social-buttons {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 360px) {
            .login-container {
                padding: 25px 15px;
            }

            .logo {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .login-title {
                font-size: 1.6rem;
            }
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .login-container {
                background: rgba(0, 0, 0, 0.9);
                border: 2px solid white;
            }

            .form-input {
                background: rgba(0, 0, 0, 0.7);
                border-color: white;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="floating-shapes shape1"></div>
        <div class="floating-shapes shape2"></div>
        <div class="floating-shapes shape3"></div>
        <div class="floating-shapes shape4"></div>
    </div>

    <!-- Login Container -->
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-lock"></i>
            </div>
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to your account to continue</p>
        </div>
<?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <!-- Error Alert (PHP integration point) -->
        <div id="error-alert" class="alert" style="display: none;">
            <i class="fas fa-exclamation-triangle"></i>
            Invalid credentials. Please try again.
        </div>

        <!-- Login Form -->
        <form method="POST" id="loginForm">
            <div class="form-group">
                <input 
                    type="text" 
                    name="username" 
                    class="form-input" 
                    placeholder="Username or Email"
                    required
                    autocomplete="username"
                >
                <i class="fas fa-user input-icon"></i>
            </div>

            <div class="form-group">
                <input 
                    type="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="Password"
                    id="password"
                    required
                    autocomplete="current-password"
                >
                <i class="fas fa-lock input-icon"></i>
                <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
                <a href="#" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">
                <span>Sign In</span>
            </button>
        </form>

        <!-- Social Login -->
        <!-- <div class="social-login">
            <div class="social-divider">
                <span>Or continue with</span>
            </div>
            <div class="social-buttons">
                <a href="#" class="social-btn" title="Login with Google">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#" class="social-btn" title="Login with Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-btn" title="Login with Apple">
                    <i class="fab fa-apple"></i>
                </a>
            </div>
        </div> -->
    </div>

    <script>
        // Password visibility toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.disabled = true;
            
            // Remove loading state after 3 seconds (for demo purposes)
            // In real implementation, this would be handled by PHP response
            setTimeout(() => {
                btn.classList.remove('loading');
                btn.disabled = false;
            }, 3000);
        });

        // Input focus animations
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Show error alert (integrate with PHP)
        // This would be controlled by PHP: <?php if (!empty($error)) echo "showError('$error');"; ?>
        function showError(message) {
            const alert = document.getElementById('error-alert');
            alert.style.display = 'block';
            alert.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
        }

        // Keyboard navigation improvements
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                const form = document.getElementById('loginForm');
                const inputs = form.querySelectorAll('input[required]');
                let allFilled = true;
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        allFilled = false;
                        input.focus();
                        return false;
                    }
                });
                
                if (allFilled) {
                    form.submit();
                }
            }
        });

        // Auto-hide error after 5 seconds
        setTimeout(() => {
            const alert = document.getElementById('error-alert');
            if (alert.style.display === 'block') {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                    alert.style.opacity = '1';
                }, 300);
            }
        }, 5000);
    </script>
</body>
</html>