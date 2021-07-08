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
            <a href="{{route('admin.product.index')}}">
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
                <li><a href="{{route('admin.orders.list','new')}}">طلبات جديدة</a></li>
                <li><a href="{{route('admin.orders.list','pre_paid')}}">طلبات بانتظار الدفع</a></li>
                <li><a href="{{route('admin.orders.list','in_progress')}}">طلبات جارية</a></li>
                <li><a href="{{route('admin.orders.list','completed')}}">طلبات مكتملة</a></li>
                <li><a href="{{route('admin.orders.list','rejected')}}">طلبات مرفوضة</a></li>
                <li><a href="{{route('admin.rate.index')}}">تقييمات العملاء</a></li>
            </ul>
        </li>

        <li>
            <a href="{{route('admin.notification.index')}}">
                <i class="mdi mdi-alert-octagram"></i>
                <span> إدارة الإشعارات الجماعية </span>
            </a>
        </li>

        <li>
            <a href="{{route('admin.wallet-pay.index')}}">
                <i class="mdi mdi-cached"></i>
                <span> إدارة الحوالات البنكية </span>
            </a>
        </li>

        <li>
            <a href="{{route('admin.contact.index')}}">
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
                    <a href="{{route('admin.settings.edit')}}">الإعدادات العامة</a>
                </li>
                <li>
                    <a href="javascript: void(0);" aria-expanded="false">الصفحات
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-third-level nav" aria-expanded="false">
                        <li>
                            <a href="{{route('admin.page.edit',['type'=>'about','for'=>'all'])}}">عن التطبيق</a>
                        </li>
                        <li>
                            <a href="{{route('admin.page.edit',['type'=>'percent','for'=>'all'])}}">عمولة التطبيق</a>
                        </li>
                        <li>
                            <a href="{{route('admin.page.edit',['type'=>'terms','for'=>'user'])}}">الشروط والأحكام للمستخدم</a>
                        </li>
                        <li>
                            <a href="{{route('admin.page.edit',['type'=>'terms','for'=>'provider'])}}">الشروط والأحكام لمقدم الخدمة</a>
                        </li>
                        <li>
                            <a href="{{route('admin.page.edit',['type'=>'terms','for'=>'delivery'])}}">الشروط والأحكام للمندوب</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('admin.contact_type.index')}}">أنواع التواصل</a>
                </li>
                <li>
                    <a href="javascript: void(0);" aria-expanded="false">المدن والأحياء
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-third-level nav" aria-expanded="false">
                        <li>
                            <a href="{{route('admin.city.index')}}">المدن</a>
                        </li>
                        <li>
                            <a href="{{route('admin.district.index')}}">الأحياء</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('admin.bank.index')}}">الحسابات البنكية</a>
                </li>
                <li>
                    <a href="{{route('admin.story_period.index')}}">أسعار إضافة حالات</a>
                </li>
                <li>
                    <a href="{{route('admin.slider.index')}}">الإعلانات</a>
                </li>

            </ul>
        </li>
    </ul>

</div>
