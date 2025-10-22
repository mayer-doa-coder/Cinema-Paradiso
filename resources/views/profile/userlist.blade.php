@extends('layouts.app')

@section('title', 'My Lists - Cinema Paradiso')

@push('styles')
<style>
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
    object-fit: cover;
    border: 3px solid #e9d736;
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
    color: #e9d736;
}
.list-container {
    background: #0b1a2a;
    padding: 30px;
    border-radius: 5px;
    margin-bottom: 30px;
}
.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #405266;
}
.list-header h4 {
    color: #3e9fd8;
    font-size: 20px;
    margin: 0;
}
.create-list-btn {
    background: #eb70ac;
    color: #fff;
    padding: 10px 25px;
    border-radius: 5px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    border: none;
    cursor: pointer;
}
.create-list-btn:hover {
    background: #d55a92;
    color: #fff;
}
.list-item {
    background: rgba(15, 33, 51, 0.5);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}
.list-item:hover {
    background: rgba(15, 33, 51, 0.8);
    transform: translateX(5px);
}
.list-item-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 10px;
}
.list-item-title {
    color: #fff;
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 5px 0;
}
.list-item-meta {
    color: #abb7c4;
    font-size: 13px;
    margin: 0;
}
.list-item-description {
    color: #ccc;
    font-size: 14px;
    line-height: 1.6;
    margin: 10px 0;
}
.list-item-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}
.list-item-actions a,
.list-item-actions button {
    padding: 6px 15px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}
.btn-view {
    background: #3e9fd8;
    color: #fff;
}
.btn-view:hover {
    background: #2e8fc8;
    color: #fff;
}
.btn-edit {
    background: #405266;
    color: #fff;
}
.btn-edit:hover {
    background: #506376;
    color: #fff;
}
.btn-delete {
    background: #eb70ac;
    color: #fff;
}
.btn-delete:hover {
    background: #d55a92;
    color: #fff;
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #abb7c4;
}
.empty-state i {
    font-size: 80px;
    color: #405266;
    margin-bottom: 20px;
    display: block;
}
.empty-state h3 {
    color: #e9d736;
    margin-bottom: 10px;
}
.empty-state p {
    margin-bottom: 30px;
}
</style>
@endpush

@section('content')
<div class="hero user-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="hero-ct">
					<h1>{{ Auth::user()->name }}'s Lists</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li> <span class="ion-ios-arrow-right"></span>Lists</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="page-single">
	<div class="container">
		<div class="row ipad-width2">
			<div class="col-md-3 col-sm-12 col-xs-12">
				@include('profile.partials.sidebar')
			</div>
			<div class="col-md-9 col-sm-12 col-xs-12">
				<div class="list-container">
					<div class="list-header">
						<h4>My Lists</h4>
						<button class="create-list-btn" onclick="alert('Create list functionality coming soon!')">
							<i class="ion-plus"></i> Create New List
						</button>
					</div>

					<!-- Placeholder for future lists functionality -->
					<div class="empty-state">
						<i class="ion-clipboard"></i>
						<h3>No Lists Created Yet</h3>
						<p>Create custom lists to organize your favorite movies and share them with others!</p>
						<button class="create-list-btn" onclick="alert('Create list functionality coming soon!')">
							Create Your First List
						</button>
					</div>

					<!-- Example of how lists will look when implemented -->
					<!-- 
					<div class="list-item">
						<div class="list-item-header">
							<div>
								<h5 class="list-item-title">My Favorite Action Movies</h5>
								<p class="list-item-meta">15 movies • Created 2 weeks ago • Public</p>
							</div>
						</div>
						<p class="list-item-description">
							A collection of the best action movies that keep you on the edge of your seat with intense sequences and amazing stunts.
						</p>
						<div class="list-item-actions">
							<a href="#" class="btn-view">View List</a>
							<a href="#" class="btn-edit">Edit</a>
							<button class="btn-delete" onclick="return confirm('Are you sure you want to delete this list?')">Delete</button>
						</div>
					</div>
					-->
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
