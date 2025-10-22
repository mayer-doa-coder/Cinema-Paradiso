<div class="user-information">
	<div class="user-img">
		<img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) . '?v=' . time() : asset('images/uploads/user-img.png') }}" alt="User Avatar" id="avatar-preview"><br>
		
		<form action="{{ route('user.avatar.update') }}" method="POST" enctype="multipart/form-data" id="avatar-form" class="avatar-upload-form">
			@csrf
			<input type="file" name="avatar" id="avatar-input" accept="image/jpeg,image/png,image/jpg,image/gif" style="display: none;">
			<button type="button" class="redbtn" id="change-avatar-btn">Change avatar</button>
		</form>
		
		@if(Auth::user()->avatar)
			<form action="{{ route('user.avatar.delete') }}" method="POST" style="margin-top: 10px;" id="avatar-delete-form" class="avatar-delete-form">
				@csrf
				@method('DELETE')
				<button type="button" class="redbtn" id="delete-avatar-btn" style="background: #405266; border: none; cursor: pointer;">Remove avatar</button>
			</form>
		@endif
	</div>
	<div class="user-fav">
		<p>Account Details</p>
		<ul>
			<li class="{{ Request::routeIs('user.profile') ? 'active' : '' }}"><a href="{{ route('user.profile') }}">PROFILE</a></li>
			<li class="{{ Request::routeIs('user.watchlist') ? 'active' : '' }}"><a href="{{ route('user.watchlist') }}">WATCHLIST</a></li>
			<li class="{{ Request::routeIs('user.reviews') ? 'active' : '' }}"><a href="{{ route('user.reviews') }}">REVIEWS</a></li>
			<li class="{{ Request::routeIs('user.movies') ? 'active' : '' }}"><a href="{{ route('user.movies') }}">MOVIES</a></li>
			<li class="{{ Request::routeIs('user.list') ? 'active' : '' }}"><a href="{{ route('user.list') }}">LIST</a></li>
		</ul>
	</div>
	<div class="user-fav">
		<p>Others</p>
		<ul>
			<li><a href="#change-password" onclick="if(document.querySelector('.password')) { document.querySelector('.password').scrollIntoView({behavior: 'smooth'}); }">CHANGE PASSWORD</a></li>
			<li>
				<form action="{{ route('auth.logout') }}" method="POST" style="display: inline;">
					@csrf
					<a href="#" onclick="event.preventDefault(); this.closest('form').submit();" style="color: #abb7c4; text-transform: uppercase;">LOG OUT</a>
				</form>
			</li>
		</ul>
	</div>
</div>

@push('scripts')
<script>
(function() {
    // Use IIFE to avoid conflicts and ensure single execution
    if (window.avatarUploadInitialized) {
        return; // Already initialized, don't run again
    }
    window.avatarUploadInitialized = true;
    
    document.addEventListener('DOMContentLoaded', function() {
        // ========== Avatar Upload Functionality ==========
        const avatarInput = document.getElementById('avatar-input');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarForm = document.getElementById('avatar-form');
        const changeAvatarBtn = document.getElementById('change-avatar-btn');
        const deleteAvatarBtn = document.getElementById('delete-avatar-btn');
        const avatarDeleteForm = document.getElementById('avatar-delete-form');
        
        let isUploading = false;
        
        // Handle change avatar button click
        if (changeAvatarBtn && avatarInput) {
            changeAvatarBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (!isUploading) {
                    avatarInput.click();
                }
            }, { once: false });
        }
        
        // Handle file selection and auto-submit
        if (avatarInput && avatarForm) {
            avatarInput.addEventListener('change', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Exit if already uploading or no file selected
                if (isUploading || !this.files || this.files.length === 0) {
                    return;
                }
                
                const file = this.files[0];
                
                // Validate file size (2MB max)
                if (file.size > 2048 * 1024) {
                    alert('Image size must not exceed 2MB');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Only JPEG, PNG, JPG, and GIF images are allowed');
                    this.value = '';
                    return;
                }
                
                // Set uploading flag
                isUploading = true;
                
                // Disable button
                if (changeAvatarBtn) {
                    changeAvatarBtn.disabled = true;
                    changeAvatarBtn.textContent = 'Uploading...';
                    changeAvatarBtn.style.opacity = '0.6';
                    changeAvatarBtn.style.cursor = 'not-allowed';
                }
                
                // Preview the image
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (avatarPreview) {
                        avatarPreview.src = event.target.result;
                    }
                };
                reader.readAsDataURL(file);
                
                // Submit form after a short delay
                setTimeout(function() {
                    avatarForm.submit();
                }, 300);
                
            }, { once: false });
        }
        
        // Handle delete avatar button
        if (deleteAvatarBtn && avatarDeleteForm) {
            deleteAvatarBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!this.disabled && confirm('Are you sure you want to delete your avatar?')) {
                    // Disable button immediately
                    this.disabled = true;
                    this.textContent = 'Deleting...';
                    this.style.opacity = '0.6';
                    this.style.cursor = 'not-allowed';
                    
                    // Submit form
                    setTimeout(function() {
                        avatarDeleteForm.submit();
                    }, 200);
                }
            });
        }
    });
})(); // End of IIFE
</script>
@endpush
