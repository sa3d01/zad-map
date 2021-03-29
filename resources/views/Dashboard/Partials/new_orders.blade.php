<div class="row">
    <div class="col-xl-4">
        <div class="card-box">
            <div class="dropdown float-right">
                <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-dots-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="javascript:void(0);" class="dropdown-item">المزيد</a>
                </div>
            </div>

            <h4 class="header-title mb-3">رسائل تواصل الأعضاء</h4>

            <div class="inbox-widget">
                @foreach(\App\Models\Contact::where('read',false)->get() as $contact)
                    <div class="inbox-item">
                    <a href="#">
                        <div class="inbox-item-img"><img src="{{$contact->user->image}}" class="rounded-circle" alt="{{$contact->user->name}}"></div>
                        <h5 class="inbox-item-author mt-0 mb-1">{{$contact->user->name}}</h5>
                        <p class="inbox-item-text">{{$contact->message}}</p>
                        <p class="inbox-item-date">{{\Carbon\Carbon::parse($contact->created_at)->diffForHumans()}}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-xl-8">
        <div class="card-box">
            <div class="dropdown float-right">
                <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-dots-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="javascript:void(0);" class="dropdown-item">المزيد</a>
                </div>
            </div>

            <h4 class="header-title mt-0 mb-3">آخر الطلبات</h4>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Project Name</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Assign</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Adminto Admin v1</td>
                        <td>01/01/2017</td>
                        <td>26/04/2017</td>
                        <td><span class="badge badge-danger">Released</span></td>
                        <td>Coderthemes</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Adminto Frontend v1</td>
                        <td>01/01/2017</td>
                        <td>26/04/2017</td>
                        <td><span class="badge badge-success">Released</span></td>
                        <td>Adminto admin</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Adminto Admin v1.1</td>
                        <td>01/05/2017</td>
                        <td>10/05/2017</td>
                        <td><span class="badge badge-pink">Pending</span></td>
                        <td>Coderthemes</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Adminto Frontend v1.1</td>
                        <td>01/01/2017</td>
                        <td>31/05/2017</td>
                        <td><span class="badge badge-purple">Work in Progress</span>
                        </td>
                        <td>Adminto admin</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Adminto Admin v1.3</td>
                        <td>01/01/2017</td>
                        <td>31/05/2017</td>
                        <td><span class="badge badge-warning">Coming soon</span></td>
                        <td>Coderthemes</td>
                    </tr>

                    <tr>
                        <td>6</td>
                        <td>Adminto Admin v1.3</td>
                        <td>01/01/2017</td>
                        <td>31/05/2017</td>
                        <td><span class="badge badge-primary">Coming soon</span></td>
                        <td>Adminto admin</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- end col -->

</div>
