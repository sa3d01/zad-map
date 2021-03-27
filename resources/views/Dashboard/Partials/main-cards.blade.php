{{--                CARDS--}}
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card-box">
            <h4 class="header-title mt-0 mb-4">المستخدمين</h4>
            <div class="widget-chart-1">
                <div class="widget-chart-box-1 float-left" dir="ltr">
                    <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#f05050 "
                           data-bgColor="#eb346e" value="{{round(($new_users_count/$all_users_count)*100)}}"
                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                           data-thickness=".15"/>
                </div>
                <div class="widget-detail-1 text-right">
                    <h2 class="font-weight-normal pt-2 mb-1"> {{$new_users_count}} </h2>
                    <p class="text-muted mb-1">خلال هذا الأسبوع</p>
                </div>
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <div class="card-box">
            <h4 class="header-title mt-0 mb-4">مقدمى الخدمات</h4>
            <div class="widget-chart-1">
                <div class="widget-chart-box-1 float-left" dir="ltr">
                    <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="green"
                           data-bgColor="#49ba07" value="{{round(($new_providers_count/$all_providers_count)*100)}}"
                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                           data-thickness=".15"/>
                </div>
                <div class="widget-detail-1 text-right">
                    <h2 class="font-weight-normal pt-2 mb-1"> {{$new_providers_count}} </h2>
                    <p class="text-muted mb-1">خلال هذا الأسبوع</p>
                </div>
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <div class="card-box">
            <h4 class="header-title mt-0 mb-4"> الأسر المنتجة</h4>
            <div class="widget-chart-1">
                <div class="widget-chart-box-1 float-left" dir="ltr">
                    <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#ffbd4a"
                           data-bgColor="#eec591" value="{{round(($new_families_count/$all_families_count)*100)}}"
                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                           data-thickness=".15"/>
                </div>
                <div class="widget-detail-1 text-right">
                    <h2 class="font-weight-normal pt-2 mb-1"> {{$new_families_count}} </h2>
                    <p class="text-muted mb-1">خلال هذا الأسبوع</p>
                </div>
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <div class="card-box">
            <h4 class="header-title mt-0 mb-4"> مندوبى التوصيل</h4>
            <div class="widget-chart-1">
                <div class="widget-chart-box-1 float-left" dir="ltr">
                    <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="blue"
                           data-bgColor="#cdd6f2" value="{{round(($new_deliveries_count/$all_deliveries_count)*100)}}"
                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                           data-thickness=".15"/>
                </div>
                <div class="widget-detail-1 text-right">
                    <h2 class="font-weight-normal pt-2 mb-1"> {{$new_deliveries_count}} </h2>
                    <p class="text-muted mb-1">خلال هذا الأسبوع</p>
                </div>
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <div class="card-box">
            <h4 class="header-title mt-0 mb-4"> المنتجات</h4>
            <div class="widget-chart-1">
                <div class="widget-chart-box-1 float-left" dir="ltr">
                    <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#81106b"
                           data-bgColor="#deaaff" value="{{round(($new_products_count/$all_products_count)*100)}}"
                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                           data-thickness=".15"/>
                </div>
                <div class="widget-detail-1 text-right">
                    <h2 class="font-weight-normal pt-2 mb-1"> {{$new_products_count}} </h2>
                    <p class="text-muted mb-1">خلال هذا الأسبوع</p>
                </div>
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <div class="card-box">
            <h4 class="header-title mt-0 mb-4"> الطلبات</h4>
            <div class="widget-chart-1">
                <div class="widget-chart-box-1 float-left" dir="ltr">
                    <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#01011a"
                           data-bgColor="#1a1a30" value="{{round(($new_deliveries_count/$all_deliveries_count)*100)}}"
                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                           data-thickness=".15"/>
                </div>
                <div class="widget-detail-1 text-right">
                    <h2 class="font-weight-normal pt-2 mb-1"> {{$new_deliveries_count}} </h2>
                    <p class="text-muted mb-1">خلال هذا الأسبوع</p>
                </div>
            </div>
        </div>
    </div><!-- end col -->

</div>
<!-- end row -->
