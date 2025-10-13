<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>@yield('title', 'Cinema Paradiso')</title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <input type="checkbox" name="remember" value="Remember me"><span>Remember me</span>
                    </div>
                    <a href="#">Forget password ?</a>
                </div>
            </div>
           <div class="row">
             <button type="submit">Login</button>
           </div>
           <div class="row">
               <div class="error-message" id="login-error" style="display: none; color: red; margin-top: 10px;"></div>
           </div>
        </form>
        <div class="row">
            <p>Or via social</p>
            <div class="social-btn-2">
                <a class="fb" href="#"><i class="ion-social-facebook"></i>Facebook</a>
                <a class="tw" href="#"><i class="ion-social-twitter"></i>twitter</a>
            </div>
        </div>
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

@yield('content')

<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/plugins.js') }}"></script>
<script src="{{ asset('js/plugins2.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>

@stack('scripts')

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
</script>

@yield('scripts')
@stack('scripts')

</body>
</html>