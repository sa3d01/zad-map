<li class="dropdown notification-list">
    <a class="nav-link dropdown-toggle  waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
        <i class="fe-bell noti-icon"></i>
        <span class="badge badge-danger rounded-circle noti-icon-badge">{{$unread_notifications_count}}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-lg">
        <!-- item-->
        @if(count($notifications)>0)
        <div class="dropdown-item noti-title">
            <h5 class="m-0">
                <span class="float-right">
                    <a href="{{route('admin.clear-all-notifications')}}" class="text-dark">
                        <small>حذف الكل</small>
                    </a>
                </span>الإشعارات
            </h5>
        </div>
        @endif
        @if(count($notifications)>0)
        <div class="slimscroll noti-scroll">
            @foreach($notifications as $notification)
                @if($notification->more_details['type']=='contact')
                    <a href="{{route('admin.contact.index')}}" class="dropdown-item notify-item @if($notification->read=='true') active @endif">
                        <div class="notify-icon bg-primary">
                            @php
                                $contact=\App\Models\Contact::find($notification->more_details['contact_id']);
                            @endphp
                            <i class="mdi mdi-mailbox"></i>
                        </div>
                        <p class="notify-details">{{$notification->title}}</p>
                        <p class="text-muted mb-0 user-msg">
                            <small>{{\Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}</small>
                        </p>
                    </a>
                @else
                    <a href="{{route('admin.wallet-pay.index')}}" class="dropdown-item notify-item @if($notification->read=='true') active @endif">
                        <div class="notify-icon bg-primary">
                            <i class="mdi mdi-bank-transfer"></i>
                        </div>
                        <p class="notify-details">{{$notification->note}}
                            <small>{{\Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}</small>
                        </p>
                    </a>
                @endif
            @endforeach
        </div>
        @else
            <div class="slimscroll noti-scroll">
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <div class="notify-icon bg-primary">
                        <i class="mdi mdi-bed-empty"></i>
                    </div>
                    <p class="notify-details"> ! ﻻ يوجد اشعارات جديدة</p>
                </a>
            </div>
        @endif
    </div>
</li>
