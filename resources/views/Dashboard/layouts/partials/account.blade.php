<div class="user-box text-center">
    <img src="{{auth()->user()->image}}" alt="user-img" title="Mat Helme" class="rounded-circle img-thumbnail avatar-lg">
    <div class="dropdown">
        <a href="#" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block" data-toggle="dropdown">{{auth()->user()->name}}</a>
        <div class="dropdown-menu user-pro-dropdown">
            <!-- item-->
            <a href="{{route('admin.profile')}}" class="dropdown-item notify-item">
                <i class="fe-user mr-1"></i>
                <span>حسابى</span>
            </a>
            <!-- item-->
            <a href="{{ route('admin.logout') }}" class="dropdown-item notify-item" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                <i class="fe-log-out mr-1"></i>
                <span>خروج</span>
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

        </div>
    </div>
    <p class="text-muted">{{auth()->user()->getRoleNames()->first()}}</p>
</div>
