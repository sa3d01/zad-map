<div id="sidebar-menu">
    <ul class="metismenu" id="side-menu">
        <li class="menu-title">محتويات النظام</li>
        <li>
            <a href="{{route('admin.home')}}">
                <i class="mdi mdi-view-dashboard"></i>
                <span> الرئيسية </span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.user.index')}}">
                <i class="mdi mdi-account-card-details"></i>
                <span> إدارة المستخدمين </span>
            </a>
        </li>
        <li>
            <a href="javascript: void(0);">
                <i class="mdi mdi-human"></i>
                <span> إدارة مزودى الخدمات </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="ui-buttons.html">طلبات الإنضمام</a></li>
                <li><a href="ui-cards.html">مزودى الخدمات</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript: void(0);">
                <i class="mdi mdi-car"></i>
                <span> إدارة المندوبيين </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="ui-buttons.html">طلبات الإنضمام</a></li>
                <li><a href="ui-cards.html">المندوبيين</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);">
                <i class="mdi mdi-share-variant"></i>
                <span> Multi Level </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level nav" aria-expanded="false">
                <li>
                    <a href="javascript: void(0);">Level 1.1</a>
                </li>
                <li>
                    <a href="javascript: void(0);" aria-expanded="false">Level 1.2
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-third-level nav" aria-expanded="false">
                        <li>
                            <a href="javascript: void(0);">Level 2.1</a>
                        </li>
                        <li>
                            <a href="javascript: void(0);">Level 2.2</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>

</div>
