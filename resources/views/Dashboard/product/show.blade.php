@extends('Dashboard.layouts.master')
@section('title', 'بيانات خدمة')
@section('style')
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card-box">
                        <h4 class="header-title mt-0 mb-3">البيانات الرئيسية</h4>

                        <div id="carouselExampleIndicators" class="card-img-top carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @foreach($product->images as $key=>$image)
                                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$key}}" class="@if($loop->first) active @endif"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach($product->images as $image)
                                <div class="carousel-item @if($loop->first) active @endif">
                                    <img style="height: 400px" class="d-block w-100" src="{{$image}}" alt="First slide">
                                </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">{{$product->name}}</h4>
                            <p class="card-text">الوصف : {{$product->note}}</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>مزود الخدمة : </strong><span><a href="{{route('admin.provider.show',$product->user_id)}}"> {{$product->user->name}}</a></span></li>
                            <li class="list-group-item"><strong>التصنيف : </strong><span>{{$product->category->name}}</span></li>
                            <li class="list-group-item"><strong>السعر : </strong><span>{{$product->price}}</span></li>
                            @if($product->has_delivery==1)
                                <li class="list-group-item"><strong>امكانية التوصيل : </strong><span>متاح</span></li>
                                <li class="list-group-item"><strong>سعر التوصيل : </strong><span>{{$product->delivery_price}}</span></li>
                            @else
                                <li class="list-group-item"><strong>امكانية التوصيل : </strong><span>ﻻ يوجد</span></li>
                            @endif
                            <li class="list-group-item"><strong>تاريخ الإضافة : </strong><span>{{$product->created_at}}</span></li>
                        </ul>
                    </div>
                </div>
{{--                credit--}}
            </div>

        </div>
    </div>
@endsection
@section('script')
@endsection
