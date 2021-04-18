@extends('Dashboard.layouts.master')
@section('title', 'بيانات طلب')
@section('styles')
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <div class="panel-body">
                            <div class="clearfix">
                                <div class="float-left">
                                    <h3>{{config('app.name')}}</h3>
                                </div>
                                <div class="float-right">
                                    <h4>فاتورة # </h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="float-left mt-3">
                                        <strong> صاحب الطلب: </strong>
                                        <p>
                                            <a href="{{route('admin.user.show',$order->user_id)}}">{{$order->user->name}}</a>
                                        </p>
                                        <strong> مزود الخدمة: </strong>
                                        <p>
                                            <a href="{{route('admin.provider.show',$order->provider_id)}}">{{$order->provider->name}}</a>
                                        </p>
                                        @if($order->delivery_id!=null)
                                            <strong> مندوب التوصيل : </strong>
                                            <p>
                                                <a href="{{route('admin.delivery.show',$order->delivery_id)}}">{{$order->delivery->name}}</a>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="float-right mt-3">
                                        <strong> تاريخ الطلب: </strong>
                                        <p>
                                            {{\Carbon\Carbon::parse($order->created_at)->format('Y-M-d')}}
                                        </p>
                                        <strong> تاريخ الاستلام: </strong>
                                        <p>
                                            {{\Carbon\Carbon::parse($order->deliver_at)->format('Y-M-d')}}
                                        </p>
                                        <p class="m-t-10"><strong>حالة الطلب: </strong>
                                            <span class="badge @if($order->status=='rejected') badge-danger @elseif($order->status=='completed') badge-success @elseif($order->status=='new') badge-primary @elseif($order->status=='in_progress') badge-purple @else badge-warning @endif">{{$order->getStatusArabic()}}</span>
                                        </p>
                                        <p class="m-t-10"><strong>رقم الطلب: </strong> #{{$order->id}}</p>
                                    </div>
                                </div><!-- end col -->
                            </div>
                            <!-- end row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table mt-4">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>اسم الخدمة</th>
                                                    <th>الكمية</th>
                                                    <th>سعر الوحدة</th>
                                                    <th>المجموع</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($order->orderItems as $orderItem)
                                                <tr>
                                                    <td><a href="{{route('admin.product.show',$orderItem->cartItem->product_id)}}"> {{$orderItem->cartItem->product_id}}</a></td>
                                                    <td>{{$orderItem->cartItem->product->name}}</td>
                                                    <td>{{$orderItem->cartItem->count}}</td>
                                                    <td>{{$orderItem->cartItem->product->price}}</td>
                                                    <td>{{$orderItem->cartItem->product->price*$orderItem->cartItem->count}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if($order->status=='rejected')
                                <div class="col-xl-6 col-6">
                                    <div class="clearfix mt-4">
                                        <h5 class="small text-dark">سبب الالغاء</h5>
                                        <small>
                                            {{\App\Models\CancelOrder::where('order_id',$order->id)->latest()->value('reason')}}
                                        </small>
                                        <h5 class="small text-dark">صاحب عملية الالغاء</h5>
                                        @php
                                            $canceller_id=\App\Models\CancelOrder::where('order_id',$order->id)->latest()->value('user_id');
                                            $canceller=\App\Models\User::find($canceller_id);
                                            if ($canceller->type=='PROVIDER'){
                                                $canceller_show=route('admin.provider.show',$canceller_id);
                                            }elseif ($canceller->type=='USER'){
                                                $canceller_show=route('admin.user.show',$canceller_id);
                                            }else{
                                                $canceller_show=route('admin.delivery.show',$canceller_id);
                                            }
                                        @endphp
                                        <small>
                                            <a href="{{$canceller_show}}">{{$canceller->name}}</a>
                                        </small>
                                    </div>
                                </div>
                                @endif
                                @php
                                    if ($order['deliver_by']=='user')
                                    {
                                        $delivery_price=0;
                                    }elseif ($order['deliver_by']=='delivery')
                                    {
                                        $delivery_price=\App\Models\Setting::value('delivery_price');
                                    }else{
                                        $delivery_price=$order->orderItems->first()->cartItem->product->delivery_price;
                                    }

                                    $promo_code = \App\Models\PromoCode::where('code', $order->promo_code)->first();
                                    $discount=0;
                                    if ($promo_code){
                                        $discount=$promo_code->discount_percent*($order->price()+($delivery_price))/100;
                                    }
                                @endphp
                                <div class="col-xl-3 col-6 offset-xl-3">
                                    <p class="text-right"><b>المجموع:</b> {{$order->price()}}</p>
                                    <p class="text-right">التوصيل: {{$delivery_price}}</p>
                                    <p class="text-right">الخصم: {{$discount}}</p>
                                    <hr>
                                    <h3 class="text-right">ريال {{($order->price()+$delivery_price)-$discount}}</h3>
                                </div>
                            </div>
                            <hr>
                            <div class="d-print-none">
                                <div class="float-right">
                                    <a href="javascript:window.print()" class="btn btn-dark waves-effect waves-light"><i class="fa fa-print"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
