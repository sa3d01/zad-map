<div class="row">
    <div class="col-xl-4">
        <div class="card-box">
            <h4 class="header-title mt-0">الأعضاء</h4>
            <div class="widget-chart text-center">
                <div data-users="{{$all_users_count}}" data-providers="{{$all_providers_count}}" data-deliveries="{{$all_deliveries_count}}" data-families="{{$all_families_count}}" id="morris-donut-users" dir="ltr" style="height: 245px;" class="morris-chart"></div>
                <ul class="list-inline chart-detail-list mb-0">
                    <li class="list-inline-item">
                        <h5 style="color: #f05050;"><i class="fa fa-circle mr-1"></i>المستخدمين</h5>
                    </li>
                    <li class="list-inline-item">
                        <h5 style="color: #648b55;"><i class="fa fa-circle mr-1"></i>مقدمى الخدمات</h5>
                    </li>
                    <li class="list-inline-item">
                        <h5 style="color: #ffbd4a;"><i class="fa fa-circle mr-1"></i>الأسر المنتجة</h5>
                    </li>
                    <li class="list-inline-item">
                        <h5 style="color: #4080ff;"><i class="fa fa-circle mr-1"></i>مندوبى التوصيل</h5>
                    </li>
                </ul>
            </div>
        </div>
    </div><!-- end col -->


    <div class="col-xl-8">
        <div class="card-box">
            <h4 class="header-title mt-0">إحصائيات الطلبات خلال أسبوع ماضى</h4>
            <div hidden id="seven-orders">
                @foreach($seven_orders as $chart_order)
                    <div data-order="{{$chart_order}}"></div>
                @endforeach
            </div>
            <div data-orders="{{$seven_orders}}" id="morris-line-orders" dir="ltr" style="height: 280px;" class="morris-chart"></div>
        </div>
    </div><!-- end col -->
</div>
<!-- end row -->
