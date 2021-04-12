<!-- Topbar Start -->
<div class="navbar-custom">
    <ul class="list-unstyled topnav-menu float-right mb-0">
        @include('Dashboard.layouts.partials.notifications')
    </ul>
    <div class="logo-box">
        <a href="{{route('admin.home')}}" class="logo text-center">
            <span class="logo-lg">
                <img src="{{asset('media/images/logo.jpeg')}}" alt="" height="60">
            </span>
            <span class="logo-sm">
                <img src="{{asset('media/images/logo.jpeg')}}" alt="" height="24">
            </span>
        </a>
    </div>
    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
        <li>
            <button class="button-menu-mobile disable-btn waves-effect">
                <i class="fe-menu"></i>
            </button>
        </li>
        <li>
            <h4 class="page-title-main">@yield('title', 'Dashboard')</h4>
        </li>

    </ul>
</div>
<!-- end Topbar -->
