@extends('Dashboard.layouts.master')
@section('title', 'بيانات مندوب الجديدة')
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
                            <h4 class="card-title">{{$user->data_for_update['data']['name']}}</h4>
                            <p class="card-text">ID : {{$user->id}}</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>الهاتف : </strong><span>{{$user->data_for_update['data']['phone']}}</span></li>
                            <li class="list-group-item"><strong>المدينة : </strong><span>{{\App\Models\DropDown::where('id',$user->data_for_update['data']['city_id'])->value('name')}}</span></li>
                            <li class="list-group-item"><strong>الحى : </strong><span>{{\App\Models\DropDown::where('id',$user->data_for_update['data']['district_id'])->value('name')}}</span></li>
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
                        <div id="map" class="gmaps" style="position: relative; overflow: hidden;" data-lat="{{$user->data_for_update['data']['location']['lat']}}" data-lng="{{$user->data_for_update['data']['location']['lng']}}"></div>
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
                                @foreach($user->data_for_update['banks'] as $key=>$bank)
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td>{{$bank['name']}}</td>
                                        <td>{{$bank['account_number']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--                car--}}
                    <div class="card-box">
                        <h4 class="header-title mt-0 mb-3">تفاصيل السيارة</h4>
                        @if($user->data_for_update['car'])
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
                                        <td>{{$user->data_for_update['car']['brand']}}</td>
                                    </tr>
                                    <tr>
                                        <td>سنة الصنع</td>
                                        <td>{{$user->data_for_update['car']['year']}}</td>
                                    </tr>
                                    <tr>
                                        <td>اللون</td>
                                        <td>{{$user->data_for_update['car']['color']}}</td>
                                    </tr>
                                    <tr>
                                        <td>رقم لوحة السيارة</td>
                                        <td>{{$user->data_for_update['car']['identity']}}</td>
                                    </tr>
                                    <tr>
                                        <td>تاريخ انتهاء التأمين</td>
                                        <td>{{$user->data_for_update['car']['end_insurance_date']}}</td>
                                    </tr>
                                    <tr>
                                        <td>صورة التأمين</td>
                                        <td data-toggle="modal" data-target="#insuranceModal{{$user->id}}">
                                            <img width="50px" height="50px" class="img_preview" src="{{$user->data_for_update['car']['insurance_image']}}">
                                        </td>
                                        <div id="insuranceModal{{$user->id}}" class="modal fade" role="img">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <img data-toggle="modal" data-target="#insuranceModal{{$user->id}}" class="img-preview" src="{{$user->data_for_update['car']['insurance_image']}}" style="max-height: 500px">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    <tr>
                                        <td>صورة رخصة القيادة</td>
                                        <td data-toggle="modal" data-target="#driveModal{{$user->id}}">
                                            <img width="50px" height="50px" class="img_preview" src="{{$user->data_for_update['car']['drive_image']}}">
                                        </td>
                                        <div id="driveModal{{$user->id}}" class="modal fade" role="img">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <img data-toggle="modal" data-target="#driveModal{{$user->id}}" class="img-preview" src="{{$user->data_for_update['car']['drive_image']}}" style="max-height: 500px">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    <tr>
                                        <td>صورة رخصة السيارة</td>
                                        <td data-toggle="modal" data-target="#identityModal{{$user->id}}">
                                            <img width="50px" height="50px" class="img_preview" src="{{$user->data_for_update['car']['identity_image']}}">
                                        </td>
                                        <div id="identityModal{{$user->id}}" class="modal fade" role="img">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <img data-toggle="modal" data-target="#identityModal{{$user->id}}" class="img-preview" src="{{$user->data_for_update['car']['identity_image']}}" style="max-height: 500px">
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
