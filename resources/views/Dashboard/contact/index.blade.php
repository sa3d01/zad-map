@extends('Dashboard.layouts.master')
@section('title', 'رسائل الأعضاء')
@section('styles')
    <link href="{{asset('assets/libs/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/datatables/responsive.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/datatables/buttons.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/datatables/select.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>صاحب الرسالة</th>
                                <th>نوع الرسالة</th>
                                <th>الرسالة</th>
                                <th>تاريخ الرسالة</th>
                                <th>العمليات المتاحة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $row)
                                <tr>
                                    <td>
                                        @if($row->user->type=='USER')
                                            <a href="{{route('admin.user.show',$row->user_id)}}">
                                                {{$row->user->name}}
                                            </a>
                                        @elseif($row->user->type=='PROVIDER')
                                            <a href="{{route('admin.provider.show',$row->user_id)}}">
                                                {{$row->user->name}}
                                            </a>
                                        @else
                                            <a href="{{route('admin.delivery.show',$row->user_id)}}">
                                                {{$row->user->name}}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{$row->contactType->name}}</td>
                                    <td data-toggle="modal" data-target="#msgModal{{$row->id}}">
                                        {{\Illuminate\Support\Str::limit($row->message,50)}}
                                    </td>
                                    <div class="modal fade" id="msgModal{{$row->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="msgModalTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="msgModalTitle">النص الكامل للرسالة</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{$row->message}}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <td>{{$row->created_at}}</td>
                                    <td>
                                        <div class="button-list">
                                            <a href="#" data-toggle="modal" data-target="#replyModal{{$row->id}}">
                                                 <i class="fa fa-pen mr-1"></i>
                                            </a>
                                            <a href="#" data-toggle="modal" data-target="#repliesModal{{$row->id}}">
                                                 <i class="fa fa-mail-bulk mr-1"></i>
                                            </a>
                                            <form class="delete" data-destroy="{{$row->id}}" data-id="{{$row->id}}" method="POST" action="{{ route('admin.contact.destroy',[$row->id]) }}">
                                                @csrf
                                                {{ method_field('DELETE') }}
                                                <i class="fa fa-archive mr-1"></i>
                                            </form>
                                        </div>
                                        <div aria-labelledby="replyModalLabel" class="modal fade show" id="replyModal{{$row->id}}" role="dialog" tabindex="-1">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="replyModalLabel">
                                                            الرد الإداري
                                                        </h5>
                                                        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> ×</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form data-id="{{$row->id}}" method="POST" action="{{ route('admin.contact.reply',[$row->id]) }}">
                                                            @csrf
                                                            {{ method_field('POST') }}
                                                            <div class="form-group">
                                                                <label for="replyMsg">
                                                                    نص الرسالة
                                                                </label>
                                                                <textarea required class="col-md-8" id="replyMsg" name="note"></textarea>
                                                            </div>
                                                            <button class="btn btn-primary" type="submit"> إرسال </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div aria-labelledby="repliesModal" class="modal fade show" id="repliesModal{{$row->id}}" role="dialog" tabindex="-1">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="repliesModal">
                                                            الردود الإدارية
                                                        </h5>
                                                        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> ×</span></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <table class="table table-bordered dt-responsive">
                                                            <thead>
                                                            <tr>
                                                                <th>الرسالة</th>
                                                                <th>تاريخ الرسالة</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach(\App\Models\Notification::where('more_details->type','admin_reply')->where('more_details->contact_id',$row->id)->latest()->get() as $reply)
                                                                <tr>
                                                                    <td>
                                                                        {{$reply->note}}
                                                                    </td>
                                                                    <td>{{\Carbon\Carbon::parse($reply->created_at)->format('Y-m-d H:i')}}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
@endsection
@section('script')
    <script src="{{asset('assets/libs/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/dataTables.keyTable.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/dataTables.select.min.js')}}"></script>
    <script src="{{asset('assets/libs/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/libs/pdfmake/vfs_fonts.js')}}"></script>
    <!-- third party js ends -->
    <!-- Datatables init -->
    <script src="{{asset('assets/js/pages/datatables.init.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
        $(document).on('click', '.delete', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: "تأكيد عملية الحذف ؟",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'نعم !',
                cancelButtonText: 'ﻻ , الغى العملية!',
                closeOnConfirm: false,
                closeOnCancel: false,
                preConfirm: () => {
                    $("form[data-destroy='" + id + "']").submit();
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
        });
    </script>
@endsection
