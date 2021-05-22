@extends('Dashboard.layouts.master')
@section('title', 'بيانات تصنيف')
@section('styles')
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card-box">
                        <h4 class="header-title mt-0 mb-3">البيانات الرئيسية</h4>
                        <img class="card-img-top img-fluid" style="max-height: 400px" src="{{$category->image}}" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title">{{$category->name}}</h4>
                            <p class="card-text">ID : {{$category->id}}</p>
                        </div>
                    </div>
                </div>
            </div>
            {{--                products--}}
            <div class="row">
                <div class="col-xl-12">
                    <div class="card-box">
                        <h4 class="header-title mt-0 mb-3">المنتجات</h4>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>مقدم الخدمة</th>
                                    <th>الاسم</th>
                                    <th>الوصف</th>
                                    <th>السعر</th>
                                    <th>سعر التوصيل المبدأى</th>
                                    <th>صورة</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($category->products as $key=>$product)
                                    <tr>
                                        <td>{{$product->id}}</td>
                                        <td><a href="{{route('admin.provider.show',$product->user_id)}}">{{$product->user->name}}</a></td>
                                        <td>{{$product->name}}</td>
                                        <td>{{\Illuminate\Support\Str::limit($product->note,20)}}</td>
                                        <td>{{$product->price}}</td>
                                        <td>{{$product->user->delivery_price??'ﻻ يوجد توصيل'}}</td>
                                        <td>
                                            <img class="card-img-top img-fluid" style="max-height: 100px;max-width: 100px" src="{{$product->images[0]}}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        let map;
        let marker;
        function initMap() {
            // show map
            let lat_str = document.getElementById('map').getAttribute("data-lat");
            let long_str = document.getElementById('map').getAttribute("data-lng");
            let uluru = {lat:parseFloat(lat_str), lng: parseFloat(long_str)};
            let centerOfOldMap = new google.maps.LatLng(uluru);
            let oldMapOptions = {
                center: centerOfOldMap,
                zoom: 10
            };
            map = new google.maps.Map(document.getElementById('map'), oldMapOptions);
            marker = new google.maps.Marker({position: centerOfOldMap,animation:google.maps.Animation.BOUNCE});
            marker.setMap(map);
        }
        google.maps.event.addDomListener(window, 'load', initMap);
    </script>
@endsection
