<li class="dropdown notification-list">
    <a class="nav-link dropdown-toggle  waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
        <i class="fe-bell noti-icon"></i>
        <span class="badge badge-danger rounded-circle noti-icon-badge">{{$unread_notifications_count}}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-lg">
        <!-- item-->
        <div class="dropdown-item noti-title">
            <h5 class="m-0">
                <span class="float-right">
                    <a href="" class="text-dark">
                        <small>حذف الكل</small>
                    </a>
                </span>الإشعارات
            </h5>
        </div>

        <div class="slimscroll noti-scroll">
            @foreach($notifications as $notification)
                @if($notification->more_details['type']=='contact')
                    <a href="javascript:void(0);" class="dropdown-item notify-item @if($notification->read=='true') active @endif">
                        <div class="notify-icon">
                            @php
                                $contact=\App\Models\Contact::find($notification->more_details['contact_id']);
                            @endphp
                            <img src="{{$contact->user->image}}" class="img-fluid rounded-circle" alt="" /> </div>
                        <p class="notify-details">{{$notification->title}}</p>
                        <p class="text-muted mb-0 user-msg">
                            <small>{{$notification->note}}</small>
                        </p>
                    </a>
                @else
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-primary">
                            <i class="mdi mdi-comment-account-outline"></i>
                        </div>
                        <p class="notify-details">Caleb Flakelar commented on Admin
                            <small class="text-muted">1 min ago</small>
                        </p>
                    </a>
                @endif
            @endforeach
        </div>

        <!-- All-->
{{--        <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">--}}
{{--            View all--}}
{{--            <i class="fi-arrow-right"></i>--}}
{{--        </a>--}}

    </div>
</li>
