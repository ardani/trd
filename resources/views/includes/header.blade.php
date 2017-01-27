<header class="site-header">
    <div class="container-fluid">
        <button id="show-hide-sidebar-toggle" class="show-hide-sidebar">
            <span>toggle menu</span>
        </button>
        <button class="hamburger hamburger--htla">
            <span>toggle menu</span>
        </button>
        <div class="site-header-content">
            <div class="site-header-content-in">
                <div class="site-header-shown">
                    <div class="dropdown user-menu">
                        <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('img/avatar-2-64.png') }}">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
                            <a class="dropdown-item" href="{{url('profile')}}"><span class="font-icon glyphicon glyphicon-user"></span>{{ Auth::user()->username }}</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{url('logout')}}"><span class="font-icon glyphicon glyphicon-log-out"></span>Logout</a>
                        </div>
                    </div>
                    <button type="button" class="burger-right">
                        <i class="font-icon-menu-addl"></i>
                    </button>
                </div>
                <div class="mobile-menu-right-overlay"></div>
            </div>
        </div>
    </div>
</header>
