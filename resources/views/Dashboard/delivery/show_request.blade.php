@extends('Dashboard.layouts.master')
@section('title', 'بيانات مندوب')
@section('styles')
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card-box">
                        <h4 class="header-title mt-0 mb-3">البيانات الرئيسية</h4>
                        <img class="card-img-top img-fluid" style="max-height: 400px" src="{{$user->image}}" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title">{{$user->name}}</h4>
                            <p class="card-text">ID : {{$user->id}}</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>الهاتف : </strong><span>{{$user->user->phone}}</span></li>
                            <li class="list-group-item"><strong>المدينة : </strong><span>{{$user->city?$user->city->name:''}}</span></li>
                            <li class="list-group-item"><strong>الحى : </strong><span>{{$user->district?$user->district->name:''}}</span></li>
                            <li class="list-group-item"><strong>الرمز الخاص بالمسوق : </strong><span>{{$user->marketer_id??'ﻻ يوجد'}}</span></li>
                            <li class="list-group-item"><strong>تاريخ الانضمام : </strong><span>{{$user->created_at}}</span></li>
                            @if($user->approved==1)
                                <li class="list-group-item"><strong>تاريخ القبول : </strong><span>{{$user->approved_at}}</span></li>
                            @elseif($user->approved==-1)
                                <li class="list-group-item"><strong>سبب الرفض : </strong><span>{{$user->reject_reason}}</span></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-xl-6">
                    {{--                location--}}
                    <div class="card-box">
                        <h4 class="header-title mt-0 mb-3">الموقع</h4>
                        <script async defer
                                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjBZsq9Q11itd0Vjz_05CtBmnxoQIEGK8&&callback=initMap" type="text/javascript">
                        </script>
                        <div id="map" class="gmaps" style="position: relative; overflow: hidden;" data-lat="{{$user->location['lat']}}" data-lng="{{$user->location['lng']}}"></div>
                    </div>
                    {{--                banks--}}
                    <div class="card-box">
                        <h4 class="header-title mt-0 mb-3">الحسابات البنكية</h4>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>البنك</th>
                                    <th>رقم الحساب</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->user->banks as $key=>$bank)
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td>{{$bank->name}}</td>
                                        <td>{{$bank->account_number}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--                car--}}
                    <div class="card-box">
                        <h4 class="header-title mt-0 mb-3">تفاصيل السيارة</h4>
                        @if($user->car)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>#</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>نوع السيارة</td>
                                        <td>{{$user->user->car->brand}}</td>
                                    </tr>
                                    <tr>
                                        <td>سنة الصنع</td>
                                        <td>{{$user->user->car->year}}</td>
                                    </tr>
                                    <tr>
                                        <td>اللون</td>
                                        <td>{{$user->user->car->color}}</td>
                                    </tr>
                                    <tr>
                                        <td>رقم لوحة السيارة</td>
                                        <td>{{$user->user->car->identity}}</td>
                                    </tr>
                                    <tr>
                                        <td>تاريخ انتهاء التأمين</td>
                                        <td>{{$user->user->car->end_insurance_date}}</td>
                                    </tr>
                                    <tr>
                                        <td>صورة التأمين</td>
                                        <td data-toggle="modal" data-target="#insuranceModal{{$user->user->car->id}}">
                                            <img width="50px" height="50px" class="img_preview" src="{{$user->user->car->insurance_image}}">
                                        </td>
                                        <div id="insuranceModal{{$user->user->car->id}}" class="modal fade" role="img">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <img data-toggle="modal" data-target="#insuranceModal{{$user->user->car->id}}" class="img-preview" src="{{$user->user->car->insurance_image}}" style="max-height: 500px">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    <tr>
                                        <td>صورة رخصة القيادة</td>
                                        <td data-toggle="modal" data-target="#driveModal{{$user->user->car->id}}">
                                            <img width="50px" height="50px" class="img_preview" src="{{$user->user->car->drive_image}}">
                                        </td>
                                        <div id="driveModal{{$user->user->car->id}}" class="modal fade" role="img">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <img data-toggle="modal" data-target="#driveModal{{$user->user->car->id}}" class="img-preview" src="{{$user->user->car->drive_image}}" style="max-height: 500px">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    <tr>
                                        <td>صورة رخصة السيارة</td>
                                        <td data-toggle="modal" data-target="#identityModal{{$user->user->car->id}}">
                                            <img width="50px" height="50px" class="img_preview" src="{{$user->user->car->identity_image}}">
                                        </td>
                                        <div id="identityModal{{$user->user->car->id}}" class="modal fade" role="img">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <img data-toggle="modal" data-target="#identityModal{{$user->user->car->id}}" class="img-preview" src="{{$user->user->car->identity_image}}" style="max-height: 500px">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="card-text">لم يتم تحديد بيانات بعد</p>
                        @endif
                    </div>
                </div>
                {{--                credit--}}
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
