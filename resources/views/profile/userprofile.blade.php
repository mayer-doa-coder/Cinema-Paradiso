@extends('layouts.app')

@section('title', 'User Profile - Cinema Paradiso')

@push('styles')
<style>
body {
    margin: 0 !important;
    padding: 0 !important;
}
.ht-header {
    margin-top: 0 !important;
    padding-top: 0 !important;
}
.user-hero {
    background: url('{{ asset('images/uploads/user-bg.jpg') }}') no-repeat center;
    background-size: cover;
    padding: 80px 0;
    position: relative;
}
.user-hero:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(11, 26, 42, 0.8);
}
.user-hero .hero-ct {
    position: relative;
    z-index: 2;
}
.user-information {
    background: #0b1a2a;
    padding: 20px;
    border-radius: 5px;
    border: 1px solid #405266;
}
.user-img {
    text-align: center;
    margin-bottom: 30px;
}
.user-img img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 15px;
}
.user-img .redbtn {
    background: #eb70ac;
    color: #fff;
    padding: 8px 20px;
    border-radius: 5px;
    display: inline-block;
    transition: all 0.3s ease;
}
.user-img .redbtn:hover {
    background: #eb70ac;
    color: #0b1a2a;
}
.user-fav {
    margin-bottom: 20px;
}
.user-fav p {
    color: #3e9fd8;
    font-weight: 600;
    font-size: 16px;
    text-align: left !important;
    margin-bottom: 10px;
    padding-bottom: 10px;
    padding-left: 0 !important;
    border-bottom: 1px solid #405266;
}
.user-fav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.user-fav ul li {
    margin-bottom: 8px;
}
.user-fav ul li a {
    color: #abb7c4;
    padding: 8px 15px;
    display: block;
    border-radius: 3px;
    transition: all 0.3s ease;
}
.user-fav ul li.active a,
.user-fav ul li a:hover {
    color: #dcf836;
}
.form-style-1.user-pro {
    padding: 30px;
    border-radius: 5px;
}
.form-style-1 h4 {
    color: #3e9fd8;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #405266;
}
.form-it {
    margin-bottom: 20px;
}
.form-it label {
    color: #ffffff;
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}
.form-it input,
.form-it select {
    width: 100%;
    padding: 10px 15px;
    background: #020d18;
    border: 1px solid #405266;
    border-radius: 3px;
    color: #abb7c4;
    transition: all 0.3s ease;
}
.form-it input:focus,
.form-it select:focus {
    border-color: #dcf836;
    outline: none;
}
.form-it input::placeholder {
    color: #405266;
}
.form-style-1 form.user {
    margin-bottom: 40px;
}
.form-style-1 input.submit {
    background: #eb70ac;
    color: #fff;
    border: none;
    padding: 12px 40px;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    text-align: center;
    display: block;
    width: 150px;
    margin: 0;
}
.form-style-1 input.submit:hover {
    background: #eb70ac;
    color: #0b1a2a;
}
</style>
@endpush

@section('content')
<!-- BEGIN | Header -->
<header class="ht-header">
	<div class="container">
		<nav class="navbar navbar-default navbar-custom">
				<div class="navbar-header logo">
				    <div class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					    <span class="sr-only">Toggle navigation</span>
					    <div id="nav-icon1">
							<span></span>
							<span></span>
							<span></span>
						</div>
				    </div>
				    <a href="{{ route('home') }}"><img class="logo" src="{{ asset('images/cinema_paradiso.png') }}" alt="" width="119" height="58"></a>
			    </div>
				<div class="collapse navbar-collapse flex-parent" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav flex-child-menu menu-left">
						<li class="hidden">
							<a href="#page-top"></a>
						</li>
						<li class="first">
							<a class="btn btn-default lv1" href="{{ route('home') }}">
							Home
							</a>
						</li>
						<li class="first">
							<a class="btn btn-default lv1" href="{{ route('movies.index') }}">
							Movies
							</a>
						</li>
						<li class="first">
							<a class="btn btn-default lv1" href="{{ route('celebrities') }}">
							Celebrities
							</a>
						</li>
						<li class="first">
							<a class="btn btn-default lv1" href="{{ route('blog') }}">
							News
							</a>
						</li>
						<li class="first">
							<a class="btn btn-default lv1" href="{{ route('community') }}">
							Community
							</a>
						</li>
					</ul>
					<ul class="nav navbar-nav flex-child-menu menu-right">               
						<li><a href="{{ route('help') }}">Help</a></li>
						@auth
							<li>
								<a href="{{ route('user.profile') }}" style="color: #dcf836; font-weight: 500;">
									{{ Auth::user()->name }}
								</a>
							</li>
						@else
							<li class="loginLink"><a href="#">LOG In</a></li>
							<li class="btn signupLink"><a href="#">sign up</a></li>
						@endauth
					</ul>
				</div>
	    </nav>
	    
	    @include('partials._search')
	</div>
</header>
<!-- END | Header -->

<div class="hero user-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="hero-ct">
					<h1>{{ Auth::user()->name }}'s Profile</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li> <span class="ion-ios-arrow-right"></span>Profile</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="page-single">
	<div class="container">
		<div class="row ipad-width">
			<div class="col-md-3 col-sm-12 col-xs-12">
				<div class="user-information">
					<div class="user-img">
						<a href="#"><img src="{{ Auth::user()->avatar ?? asset('images/uploads/user-img.png') }}" alt=""><br></a>
						<a href="#" class="redbtn">Change avatar</a>
					</div>
					<div class="user-fav">
						<p>Account Details</p>
						<ul>
							<li class="active"><a href="{{ route('user.profile') }}">Profile</a></li>
							<li><a href="{{ route('user.watchlist') }}">Watchlist</a></li>
							<li><a href="{{ route('user.reviews') }}">Reviews</a></li>
							<li><a href="{{ route('user.movies') }}">Movies</a></li>
							<li><a href="{{ route('user.list') }}">List</a></li>
						</ul>
					</div>
					<div class="user-fav">
						<p>Others</p>
						<ul>
							<li><a href="#change-password" onclick="document.querySelector('.password').scrollIntoView({behavior: 'smooth'})">Change password</a></li>
							<li>
								<form action="{{ route('auth.logout') }}" method="POST" style="display: inline;">
									@csrf
									<a href="#" onclick="event.preventDefault(); this.closest('form').submit();" style="color: #abb7c4;">Log out</a>
								</form>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9 col-sm-12 col-xs-12">
				<div class="form-style-1 user-pro">
					@if(session('success'))
						<div class="alert alert-success" style="background: #28a745; color: #fff; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
							{{ session('success') }}
						</div>
					@endif
					
					@if($errors->any())
						<div class="alert alert-danger" style="background: #dc3545; color: #fff; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
							<ul style="margin: 0; padding-left: 20px;">
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					<form action="{{ route('user.profile.update') }}" method="POST" class="user">
						@csrf
						<h4>Profile details</h4>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>Username</label>
								<input type="text" name="username" value="{{ Auth::user()->username ?? Auth::user()->name }}" placeholder="Username">
							</div>
							<div class="col-md-6 form-it">
								<label>Email Address</label>
								<input type="email" name="email" value="{{ Auth::user()->email }}" placeholder="Email Address">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>First Name</label>
								<input type="text" name="first_name" value="{{ explode(' ', Auth::user()->name)[0] ?? '' }}" placeholder="First Name">
							</div>
							<div class="col-md-6 form-it">
								<label>Last Name</label>
								<input type="text" name="last_name" value="{{ implode(' ', array_slice(explode(' ', Auth::user()->name), 1)) ?? '' }}" placeholder="Last Name">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>Country</label>
								<select name="country">
									<option value="">Select Country</option>
									<option value="united_states">United States</option>
									<option value="canada">Canada</option>
									<option value="uk">United Kingdom</option>
									<option value="others">Others</option>
								</select>
							</div>
							<div class="col-md-6 form-it">
								<label>State</label>
								<select name="state">
									<option value="">Select State</option>
									<option value="new_york">New York</option>
									<option value="california">California</option>
									<option value="texas">Texas</option>
									<option value="others">Others</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input class="submit" type="submit" value="Save">
							</div>
						</div>	
					</form>
					<form action="{{ route('user.password.update') }}" method="POST" class="password" id="change-password">
						@csrf
						<h4>Change password</h4>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>Old Password</label>
								<input type="password" name="old_password" placeholder="**********">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>New Password</label>
								<input type="password" name="new_password" placeholder="***************">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>Confirm New Password</label>
								<input type="password" name="new_password_confirmation" placeholder="***************">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input class="submit" type="submit" value="Change">
							</div>
						</div>	
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
