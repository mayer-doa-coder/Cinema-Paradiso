@extends('layouts.app')

@section('title', 'Followers - Cinema Paradiso')

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
    background: #0b1a2a !important;
    padding: 20px !important;
    border-radius: 5px !important;
    border: 1px solid #405266 !important;
    margin-top: 0 !important;
}
.user-img {
    text-align: center !important;
    margin-bottom: 30px !important;
    padding: 0 !important;
}
.user-img img {
    width: 150px !important;
    height: 150px !important;
    border-radius: 50% !important;
    margin-bottom: 15px !important;
    object-fit: cover !important;
    border: 3px solid #e9d736 !important;
}
.user-img .redbtn {
    background: #eb70ac !important;
    color: #fff !important;
    padding: 8px 20px !important;
    border-radius: 5px !important;
    display: inline-block !important;
    transition: all 0.3s ease !important;
    border: none !important;
    cursor: pointer !important;
}
.user-img .redbtn:hover {
    background: #eb70ac !important;
    color: #0b1a2a !important;
}
.user-information ul {
    padding: 0 !important;
}
.user-fav {
    margin-bottom: 20px !important;
    border-top: none !important;
    padding: 0 !important;
}
.user-fav p {
    color: #3e9fd8 !important;
    font-weight: 600 !important;
    font-size: 16px !important;
    text-align: left !important;
    margin-bottom: 10px !important;
    padding-bottom: 10px !important;
    padding-left: 0 !important;
    border-bottom: 1px solid #405266 !important;
    text-transform: none !important;
}
.user-fav ul {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}
.user-fav ul li {
    margin-bottom: 8px !important;
}
.user-fav ul li a {
    color: #abb7c4 !important;
    padding: 8px 15px !important;
    display: block !important;
    border-radius: 3px !important;
    transition: all 0.3s ease !important;
    text-transform: uppercase !important;
    font-weight: 600 !important;
    letter-spacing: 0.5px !important;
    font-size: 13px !important;
}
.user-fav ul li.active a {
    color: #e9d736 !important;
    background: transparent !important;
}
.user-fav ul li a:hover {
    color: #e9d736 !important;
    background: transparent !important;
}

.followers-container {
    background: #0b1a2a;
    padding: 30px;
    border-radius: 5px;
    border: 1px solid #405266;
}

.followers-container h4 {
    color: #3e9fd8;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #405266;
}

.user-card {
    background: #020d18;
    border: 1px solid #405266;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.user-card:hover {
    border-color: #e9d736;
    transform: translateY(-2px);
}

.user-card img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9d736;
}

.user-card-info {
    flex: 1;
}

.user-card-info h5 {
    color: #fff;
    font-size: 16px;
    margin: 0 0 8px 0;
    font-weight: 600;
}

.user-card-info p {
    color: #abb7c4;
    font-size: 14px;
    margin: 0 0 5px 0;
}

.user-card-info .user-stats {
    display: flex;
    gap: 15px;
    margin-top: 8px;
}

.user-card-info .user-stats span {
    color: #3e9fd8;
    font-size: 13px;
}

.user-card-info .following-badge {
    background: #3e9fd8;
    color: #fff;
    padding: 4px 10px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    margin-top: 8px;
}

.user-card-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-follow {
    background: #eb70ac;
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: 3px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 13px;
}

.btn-follow:hover {
    background: #d65a96;
}

.btn-following {
    background: #405266;
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: 3px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 13px;
}

.btn-following:hover {
    background: #eb70ac;
}

.btn-profile {
    background: transparent;
    color: #3e9fd8;
    border: 1px solid #3e9fd8;
    padding: 8px 20px;
    border-radius: 3px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-profile:hover {
    background: #3e9fd8;
    color: #fff;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #abb7c4;
}

.empty-state i {
    font-size: 64px;
    color: #405266;
    margin-bottom: 20px;
}

.empty-state h5 {
    color: #fff;
    font-size: 18px;
    margin-bottom: 10px;
}

.empty-state p {
    color: #abb7c4;
    font-size: 14px;
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
								<a href="{{ route('user.profile') }}" style="color: #e9d736; font-weight: 500;">
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
					<h1>Followers</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li><span class="ion-ios-arrow-right"></span><a href="{{ route('user.profile') }}">Profile</a></li>
						<li><span class="ion-ios-arrow-right"></span>Followers</li>
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
				@include('profile.partials.sidebar')
			</div>
			<div class="col-md-9 col-sm-12 col-xs-12">
				<div class="followers-container">
					<h4>Your Followers ({{ $followers->count() }})</h4>
					
					@if($followers->count() > 0)
						@foreach($followers as $follower)
							<div class="user-card" data-user-id="{{ $follower->id }}">
								<img src="{{ $follower->avatar ? asset('storage/' . $follower->avatar) : asset('images/uploads/user-img.png') }}" 
								     alt="{{ $follower->name }}">
								
								<div class="user-card-info">
									<h5>
										{{ $follower->name }}
										@if(in_array($follower->id, $followingIds))
											<span class="following-badge">Following</span>
										@endif
									</h5>
									<p>{{ '@' . ($follower->username ?? strtolower(str_replace(' ', '', $follower->name))) }}</p>
									@if($follower->bio)
										<p style="font-size: 13px; margin-top: 5px;">{{ Str::limit($follower->bio, 100) }}</p>
									@endif
									<div class="user-stats">
										<span><i class="ion-android-star"></i> {{ $follower->followers()->count() }} Followers</span>
										<span><i class="ion-android-person"></i> {{ $follower->following()->count() }} Following</span>
									</div>
								</div>
								
								<div class="user-card-actions">
									@if(in_array($follower->id, $followingIds))
										<button class="btn-following" 
										        data-user-id="{{ $follower->id }}" 
										        data-user-name="{{ $follower->name }}"
										        data-action="unfollow">
											Following
										</button>
									@else
										<button class="btn-follow" 
										        data-user-id="{{ $follower->id }}" 
										        data-user-name="{{ $follower->name }}"
										        data-action="follow">
											Follow Back
										</button>
									@endif
									<a href="#" class="btn-profile">View Profile</a>
								</div>
							</div>
						@endforeach
					@else
						<div class="empty-state">
							<i class="ion-android-people"></i>
							<h5>No followers yet</h5>
							<p>When people follow you, they'll appear here!</p>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Follow/Unfollow functionality
    $('.btn-follow, .btn-following').on('click', function() {
        const button = $(this);
        const userId = button.data('user-id');
        const userName = button.data('user-name');
        const action = button.data('action');
        
        if (action === 'unfollow') {
            if (!confirm('Are you sure you want to unfollow ' + userName + '?')) {
                return;
            }
        }
        
        button.prop('disabled', true);
        const originalText = button.text();
        button.text(action === 'follow' ? 'Following...' : 'Unfollowing...');
        
        $.ajax({
            url: '/profile/' + action + '/' + userId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    if (action === 'follow') {
                        // Change to unfollow button
                        button.removeClass('btn-follow').addClass('btn-following');
                        button.text('Following');
                        button.data('action', 'unfollow');
                        
                        // Add "Following" badge
                        const userCard = button.closest('.user-card');
                        const userName = userCard.find('h5');
                        if (!userName.find('.following-badge').length) {
                            userName.append('<span class="following-badge">Following</span>');
                        }
                    } else {
                        // Change to follow button
                        button.removeClass('btn-following').addClass('btn-follow');
                        button.text('Follow Back');
                        button.data('action', 'follow');
                        
                        // Remove "Following" badge
                        const userCard = button.closest('.user-card');
                        userCard.find('.following-badge').remove();
                    }
                    
                    button.prop('disabled', false);
                    
                    // Show success message
                    showNotification('success', response.message);
                } else {
                    button.prop('disabled', false);
                    button.text(originalText);
                    alert(response.message || 'An error occurred. Please try again.');
                }
            },
            error: function(xhr) {
                button.prop('disabled', false);
                button.text(originalText);
                
                let errorMsg = 'Failed to ' + action + ' user. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            }
        });
    });
    
    // Notification helper
    function showNotification(type, message) {
        // You can customize this to use your own notification system
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }
});
</script>
@endpush
