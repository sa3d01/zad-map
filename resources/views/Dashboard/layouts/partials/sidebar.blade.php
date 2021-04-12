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
                <i class="mdi mdi-human"></i>
                <span> إدارة المستخدمين </span>
            </a>
        </li>

        <li>
            <a href="javascript: void(0);">
                <i class="mdi mdi-home-currency-usd"></i>
                <span> إدارة مزودى الخدمات </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="{{route('admin.provider.binned')}}">طلبات الإنضمام</a></li>
                <li><a href="{{route('admin.provider.index')}}">مزودى الخدمات بالتطبيق</a></li>
                <li><a href="{{route('admin.story.binned')}}">طلبات إضافة إستورى</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);">
                <i class="mdi mdi-car"></i>
                <span> إدارة المندوبيين </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="{{route('admin.delivery.binned')}}">طلبات الإنضمام</a></li>
                <li><a href="{{route('admin.delivery.index')}}"> عرض الكل</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);">
                <i class="mdi mdi-battlenet"></i>
                <span> إدارة الخدمات </span>
            </a>
        </li>

        <li>
            <a href="{{route('admin.category.index')}}">
                <i class="mdi mdi-box-shadow"></i>
                <span> إدارة التصنيفات </span>
            </a>
        </li>

        <li>
            <a href="javascript: void(0);">
                <i class="mdi mdi-cart-plus"></i>
                <span> إدارة الطلبات </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="javascript: void(0);">طلبات جديدة</a></li>
                <li><a href="javascript: void(0);">طلبات بانتظار الدفع</a></li>
                <li><a href="javascript: void(0);">طلبات جارية</a></li>
                <li><a href="javascript: void(0);">طلبات مكتملة</a></li>
                <li><a href="javascript: void(0);">طلبات مرفوضة</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);">
                <i class="mdi mdi-alert-octagram"></i>
                <span> إدارة الإشعارات الجماعية </span>
            </a>
        </li>

        <li>
            <a href="javascript: void(0);">
                <i class="mdi mdi-cached"></i>
                <span> إدارة الحوالات البنكية </span>
            </a>
        </li>

        <li>
            <a href="javascript: void(0);">
                <i class="mdi mdi-mailbox"></i>
                <span> إدارة رسائل التواصل </span>
            </a>
        </li>

        <li>
            <a href="javascript: void(0);">
                <i class="mdi mdi-share-variant"></i>
                <span> إعدادات أخرى </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level nav" aria-expanded="false">
                <li>
                    <a href="javascript: void(0);">الإعدادات العامة</a>
                </li>
                <li>
                    <a href="javascript: void(0);" aria-expanded="false">الصفحات
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-third-level nav" aria-expanded="false">
                        <li>
                            <a href="javascript: void(0);">عن التطبيق</a>
                        </li>
                        <li>
                            <a href="javascript: void(0);">الشروط والأحكام للمستخدم</a>
                        </li>
                        <li>
                            <a href="javascript: void(0);">الشروط والأحكام للمندوب ومقدم الخدمة</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);">أنواع التواصل</a>
                </li>
                <li>
                    <a href="javascript: void(0);">المدن والأحياء</a>
                </li>
                <li>
                    <a href="javascript: void(0);">الحسابات البنكية</a>
                </li>
                <li>
                    <a href="javascript: void(0);">الإعلانات</a>
                </li>

            </ul>
        </li>
    </ul>

</div>
