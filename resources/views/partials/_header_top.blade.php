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
                <li><a href="{{ route('chat.index') }}">Messages</a></li>
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
