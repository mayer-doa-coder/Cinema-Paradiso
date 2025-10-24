<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Authentication Test - Cinema Paradiso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .test-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #dd003f;
            text-align: center;
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #dd003f;
            padding-bottom: 10px;
        }
        .form-group {
            margin: 15px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="checkbox"] {
            margin-right: 5px;
        }
        button {
            background-color: #dd003f;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #b8002f;
        }
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .result {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            display: none;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .feature-list {
            background-color: #e7f3ff;
            padding: 15px;
            border-left: 4px solid #2196F3;
            margin: 20px 0;
        }
        .feature-list ul {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>🎬 Cinema Paradiso - Authentication Test</h1>
    
    <div class="feature-list">
        <h3>✅ Implemented Features:</h3>
        <ul>
            <li><strong>Remember Me:</strong> Login sessions will persist across browser sessions</li>
            <li><strong>Forgot Password:</strong> Auto-generate and email temporary passwords</li>
            <li><strong>Secure Password Reset:</strong> Temporary passwords replace old ones immediately</li>
        </ul>
    </div>

    <!-- Login Test -->
    <div class="test-section">
        <h2>1. Login Test (with Remember Me)</h2>
        <form id="login-test-form">
            <div class="form-group">
                <label for="login-username">Username or Email:</label>
                <input type="text" id="login-username" name="login" placeholder="Enter username or email" required>
            </div>
            <div class="form-group">
                <label for="login-password">Password:</label>
                <input type="password" id="login-password" name="password" placeholder="Enter password" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" id="remember-check" name="remember" value="1">
                    Remember Me (Stay logged in)
                </label>
            </div>
            <button type="submit">Login</button>
            <div class="result" id="login-result"></div>
        </form>
    </div>

    <!-- Forgot Password Test -->
    <div class="test-section">
        <h2>2. Forgot Password Test</h2>
        <form id="forgot-test-form">
            <div class="form-group">
                <label for="forgot-email">Email Address:</label>
                <input type="email" id="forgot-email" name="email" placeholder="Enter your registered email" required>
            </div>
            <button type="submit">Send Temporary Password</button>
            <div class="result" id="forgot-result"></div>
        </form>
    </div>

    <!-- Check Auth Status -->
    <div class="test-section">
        <h2>3. Check Authentication Status</h2>
        <button id="check-auth-btn">Check If Logged In</button>
        <div class="result" id="auth-result"></div>
    </div>

    <!-- Logout Test -->
    <div class="test-section">
        <h2>4. Logout Test</h2>
        <button id="logout-btn">Logout</button>
        <div class="result" id="logout-result"></div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Login Test
        document.getElementById('login-test-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const resultDiv = document.getElementById('login-result');
            const formData = new FormData(this);
            
            resultDiv.style.display = 'block';
            resultDiv.className = 'result info';
            resultDiv.textContent = 'Logging in...';

            try {
                const response = await fetch('/auth/login', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    resultDiv.className = 'result success';
                    resultDiv.innerHTML = `
                        <strong>✅ Login Successful!</strong><br>
                        Welcome ${data.user.name} (@${data.user.username})<br>
                        Remember Me: ${formData.get('remember') ? 'Enabled ✓' : 'Disabled'}
                    `;
                } else {
                    resultDiv.className = 'result error';
                    resultDiv.textContent = '❌ ' + (data.message || 'Login failed');
                }
            } catch (error) {
                resultDiv.className = 'result error';
                resultDiv.textContent = '❌ Error: ' + error.message;
            }
        });

        // Forgot Password Test
        document.getElementById('forgot-test-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const resultDiv = document.getElementById('forgot-result');
            const formData = new FormData(this);
            const button = this.querySelector('button');
            
            button.disabled = true;
            button.textContent = 'Sending...';
            
            resultDiv.style.display = 'block';
            resultDiv.className = 'result info';
            resultDiv.textContent = 'Processing request...';

            try {
                const response = await fetch('/auth/forgot-password', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    resultDiv.className = 'result success';
                    let message = `<strong>✅ ${data.message}</strong>`;
                    
                    if (data.temp_password) {
                        message += `<br><br><strong>🔑 Temporary Password:</strong> <code style="background: #ffe; padding: 5px; font-size: 18px;">${data.temp_password}</code>`;
                        message += `<br><small style="color: #ff9800;">⚠️ ${data.warning}</small>`;
                    }
                    
                    resultDiv.innerHTML = message;
                    this.reset();
                } else {
                    resultDiv.className = 'result error';
                    resultDiv.textContent = '❌ ' + (data.message || 'Failed to send temporary password');
                }
            } catch (error) {
                resultDiv.className = 'result error';
                resultDiv.textContent = '❌ Error: ' + error.message;
            } finally {
                button.disabled = false;
                button.textContent = 'Send Temporary Password';
            }
        });

        // Check Auth Status
        document.getElementById('check-auth-btn').addEventListener('click', async function() {
            const resultDiv = document.getElementById('auth-result');
            
            resultDiv.style.display = 'block';
            resultDiv.className = 'result info';
            resultDiv.textContent = 'Checking authentication...';

            try {
                const response = await fetch('/auth/user', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    resultDiv.className = 'result success';
                    resultDiv.innerHTML = `
                        <strong>✅ You are logged in!</strong><br>
                        <strong>Name:</strong> ${data.user.name}<br>
                        <strong>Username:</strong> @${data.user.username}<br>
                        <strong>Email:</strong> ${data.user.email}
                    `;
                } else {
                    resultDiv.className = 'result error';
                    resultDiv.textContent = '❌ Not logged in';
                }
            } catch (error) {
                resultDiv.className = 'result error';
                resultDiv.textContent = '❌ Not logged in or session expired';
            }
        });

        // Logout Test
        document.getElementById('logout-btn').addEventListener('click', async function() {
            const resultDiv = document.getElementById('logout-result');
            
            resultDiv.style.display = 'block';
            resultDiv.className = 'result info';
            resultDiv.textContent = 'Logging out...';

            try {
                const response = await fetch('/auth/logout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (response.ok) {
                    resultDiv.className = 'result success';
                    resultDiv.textContent = '✅ Logged out successfully!';
                } else {
                    resultDiv.className = 'result error';
                    resultDiv.textContent = '❌ Logout failed';
                }
            } catch (error) {
                resultDiv.className = 'result error';
                resultDiv.textContent = '❌ Error: ' + error.message;
            }
        });
    </script>
</body>
</html>
