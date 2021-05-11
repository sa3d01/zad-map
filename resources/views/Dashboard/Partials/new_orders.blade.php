<div class="row">
    <div class="col-xl-4">
        <div class="card-box">
            <div class="dropdown float-right">
                <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-dots-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{route('admin.contact.index')}}" class="dropdown-item">المزيد</a>
                </div>
            </div>
            <h4 class="header-title mb-3">رسائل تواصل الأعضاء</h4>
            <div class="inbox-widget">
                @foreach(\App\Models\Contact::where('read',false)->get() as $contact)
                    <div class="inbox-item">
                    <a href="#">
                        <div class="inbox-item-img"><img style="height: 40px;width: 40px" src="{{$contact->user->image}}" class="rounded-circle" alt="{{$contact->user->name}}"></div>
                        <h5 class="inbox-item-author mt-0 mb-1">{{$contact->user->name}}</h5>
                        <p class="inbox-item-text">{{\Illuminate\Support\Str::limit($contact->message,100)}}</p>
                        <p class="inbox-item-date">{{\Carbon\Carbon::parse($contact->created_at)->diffForHumans()}}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-xl-8">
        <div class="card-box">
            <h4 class="header-title mt-0 mb-3">آخر الطلبات</h4>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>تاريخ الطلب</th>
                        <th>تاريخ الإستلام</th>
                        <th>حالة الطلب</th>
                        <th>مزود الخدمة</th>
                        <th>المزيد</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\Models\Order::latest()->take(7)->get() as $last_order)
                    <tr>
                        <td>{{$last_order->id}}</td>
                        <td>{{$last_order->user->name}}</td>
                        <td>{{\Carbon\Carbon::parse($last_order->created_at)->format('Y-M-d')}}</td>
                        <td>{{\Carbon\Carbon::parse($last_order->deliver_at)->format('Y-M-d')}}</td>
                        <td><span class="badge @if($last_order->status=='rejected') badge-danger @elseif($last_order->status=='completed') badge-success @elseif($last_order->status=='new') badge-primary @elseif($last_order->status=='in_progress') badge-purple @else badge-warning @endif">{{$last_order->getStatusArabic()}}</span></td>
                        <td>{{$last_order->provider->name}}</td>
                        <td>
                            <a href="{{route('admin.order.show',$last_order->id)}}">
                                 <i class="fa fa-eye mr-1"></i> <span>عرض</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- end col -->

</div>
