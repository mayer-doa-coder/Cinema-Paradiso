<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>@yield('title', 'Cinema Paradiso')</title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="referrer" content="no-referrer-when-downgrade">
    <link rel="profile" href="#">

    <!--Google Font-->
    <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Dosis:400,700,500|Nunito:300,400,600' />
    <!-- Mobile specific meta -->
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone-no">

    <link rel="stylesheet" href="{{ asset('css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>
<body>
<!--preloading-->
<div id="preloader">
    <img class="logo" src="{{ asset('images/cinema_paradiso.png') }}" alt="" width="350" height="150">
    <div id="status">
        <span></span>
        <span></span>
    </div>
</div>
<!--end of preloading-->

<!--login form popup-->
<div class="login-wrapper" id="login-content">
    <div class="login-content">
        <a href="#" class="close">x</a>
        <h3>Login</h3>
        <form method="post" id="login-form">
            @csrf
            <div class="row">
                 <label for="login-username">
                    Username or Email:
                    <input type="text" name="login" id="login-username" placeholder="Hugh Jackman or email@example.com" required="required" />
                </label>
            </div>
           
            <div class="row">
                <label for="login-password">
                    Password:
                    <input type="password" name="password" id="login-password" placeholder="******" required="required" />
                </label>
            </div>
            <div class="row">
                <div class="remember">
                    <div>
                        <input type="checkbox" name="remember" id="remember-me" value="1"><span>Remember me</span>
                    </div>
                    <a href="#" id="forgot-password-link">Forget password ?</a>
                </div>
            </div>
           <div class="row">
             <button type="submit">Login</button>
           </div>
           <div class="row">
               <div class="error-message" id="login-error" style="display: none; color: red; margin-top: 10px;"></div>
           </div>
        </form>
    </div>
</div>
<!--end of login form popup-->

<!--signup form popup-->
<div class="login-wrapper"  id="signup-content">
    <div class="login-content">
        <a href="#" class="close">x</a>
        <h3>sign up</h3>
        <form method="post" id="signup-form">
            @csrf
            <div class="row">
                 <label for="signup-name">
                    Full Name:
                    <input type="text" name="name" id="signup-name" placeholder="Hugh Jackman" required="required" />
                </label>
            </div>
            <div class="row">
                 <label for="signup-username">
                    Username:
                    <input type="text" name="username" id="signup-username" placeholder="hughjackman" pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{3,20}$" required="required" />
                </label>
            </div>
           
            <div class="row">
                <label for="signup-email">
                    your email:
                    <input type="email" name="email" id="signup-email" placeholder="your@email.com" required="required" />
                </label>
            </div>
             <div class="row">
                <label for="signup-password">
                    Password:
                    <input type="password" name="password" id="signup-password" placeholder="" required="required" />
                </label>
                <p class="password-requirements">Password must contain at least 8 characters.</p>

            </div>
             <div class="row">
                <label for="signup-password-confirmation">
                    re-type Password:
                    <input type="password" name="password_confirmation" id="signup-password-confirmation" placeholder="" required="required" />
                </label>
            </div>
            <div class="row">
                <label for="signup-bio">
                    Bio (optional):
                    <textarea name="bio" id="signup-bio" placeholder="Tell us about yourself..." maxlength="500" rows="3"></textarea>
                </label>
            </div>
           <div class="row">
             <button type="submit">sign up</button>
           </div>
           <div class="row">
               <div class="error-message" id="signup-error" style="display: none; color: red; margin-top: 10px;"></div>
           </div>
        </form>
    </div>
</div>
<!--end of signup form popup-->

<!--forgot password form popup-->
<div class="login-wrapper" id="forgot-password-content">
    <div class="login-content">
        <a href="#" class="close">x</a>
        <h3>Forgot Password</h3>
        <p style="color: #abb7c4; margin-bottom: 20px;">Enter your email address and we'll send you a temporary password.</p>
        <form method="post" id="forgot-password-form">
            @csrf
            <div class="row">
                 <label for="forgot-email">
                    Email Address:
                    <input type="email" name="email" id="forgot-email" placeholder="email@example.com" required="required" />
                </label>
            </div>
           <div class="row">
             <button type="submit">Send Temporary Password</button>
           </div>
           <div class="row">
               <div class="message" id="forgot-message" style="display: none; margin-top: 10px;"></div>
           </div>
           <div class="row" style="text-align: center; margin-top: 15px;">
               <a href="#" id="back-to-login" style="color: #dd003f; text-decoration: underline;">Back to Login</a>
           </div>
        </form>
    </div>
</div>
<!--end of forgot password form popup-->

@yield('content')

<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/plugins.js') }}"></script>
<script src="{{ asset('js/plugins2.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/review-management.js') }}"></script>

<script>
// CSRF token setup for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Login form handler
document.getElementById('login-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const errorDiv = document.getElementById('login-error');
    
    try {
        const response = await fetch('/auth/login', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Login successful!');
            window.location.reload(); // Or redirect to dashboard
        } else {
            errorDiv.textContent = data.message || 'Login failed';
            errorDiv.style.display = 'block';
        }
    } catch (error) {
        errorDiv.textContent = 'An error occurred. Please try again.';
        errorDiv.style.display = 'block';
    }
});

// Signup form handler
document.getElementById('signup-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const errorDiv = document.getElementById('signup-error');
    
    try {
        const response = await fetch('/auth/register', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Registration successful! You are now logged in.');
            window.location.reload(); // Or redirect to dashboard
        } else {
            if (data.errors) {
                let errorMessages = '';
                Object.values(data.errors).forEach(error => {
                    errorMessages += error.join(' ') + ' ';
                });
                errorDiv.textContent = errorMessages;
            } else {
                errorDiv.textContent = data.message || 'Registration failed';
            }
            errorDiv.style.display = 'block';
        }
    } catch (error) {
        errorDiv.textContent = 'An error occurred. Please try again.';
        errorDiv.style.display = 'block';
    }
});

// Check if user is logged in
async function checkAuthStatus() {
    try {
        const response = await fetch('/auth/user', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                console.log('User is logged in:', data.user);
                // You can update UI here based on login status
            }
        }
    } catch (error) {
        console.log('User is not logged in');
    }
}

// Check auth status on page load
document.addEventListener('DOMContentLoaded', checkAuthStatus);

// Wait for DOM to be ready before attaching event listeners
$(document).ready(function() {
    // Forgot password link handler
    $('#forgot-password-link').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Close login popup using the existing system
        $('#login-content').parents('.overlay').removeClass('openform');
        
        // Open forgot password popup
        setTimeout(function() {
            $('#forgot-password-content').parents('.overlay').addClass('openform');
        }, 100);
    });

    // Back to login link handler
    $('#back-to-login').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Close forgot password popup
        $('#forgot-password-content').parents('.overlay').removeClass('openform');
        
        // Open login popup
        setTimeout(function() {
            $('#login-content').parents('.overlay').addClass('openform');
        }, 100);
    });
});

// Forgot password form handler
$(document).ready(function() {
    $('#forgot-password-form').on('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('forgot-message');
            const submitButton = this.querySelector('button[type="submit"]');
            
            // Disable button during request
            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';
            
            try {
                const response = await fetch('/auth/forgot-password', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                messageDiv.style.display = 'block';
                
                if (data.success) {
                    messageDiv.style.color = '#4caf50';
                    messageDiv.innerHTML = data.message;
                    
                    // If temp password is returned (development mode)
                    if (data.temp_password) {
                        messageDiv.innerHTML += '<br><strong>Temporary Password: ' + data.temp_password + '</strong>';
                        messageDiv.innerHTML += '<br><small style="color: #ff9800;">' + data.warning + '</small>';
                    }
                    
                    // Clear form
                    this.reset();
                    
                    // Show success and redirect to login after 5 seconds
                    setTimeout(function() {
                        $('#forgot-password-content').parents('.overlay').removeClass('openform');
                        setTimeout(function() {
                            $('#login-content').parents('.overlay').addClass('openform');
                        }, 100);
                        messageDiv.style.display = 'none';
                    }, 5000);
                } else {
                    messageDiv.style.color = '#f44336';
                    messageDiv.textContent = data.message || 'Failed to send temporary password';
                }
            } catch (error) {
                messageDiv.style.color = '#f44336';
                messageDiv.textContent = 'An error occurred. Please try again.';
                messageDiv.style.display = 'block';
            } finally {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.textContent = 'Send Temporary Password';
            }
    });
});

// Handle password change - show login popup
@if(session('password_changed'))
document.addEventListener('DOMContentLoaded', function() {
    // Show success message
    alert('{{ session('message') }}');
    
    // Trigger login popup
    setTimeout(function() {
        const loginLink = document.querySelector('.loginLink a');
        if (loginLink) {
            loginLink.click();
        }
    }, 500);
});
@endif
</script>

@yield('scripts')
@stack('scripts')

</body>
</html>