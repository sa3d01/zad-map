@extends('Dashboard.layouts.master')
@section('title', 'الحوالات البنكية')
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
                                <th>المستخدم</th>
                                <th>البنك</th>
                                <th>المبلغ</th>
                                <th>صورة الحوالة</th>
                                <th>الحالة</th>
                                <th>العمليات المتاحة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $row)
                                <tr>
                                    <td>
                                        <a href="{{route('admin.user.show',$row->user_id)}}">
                                            {{$row->user->name}}
                                        </a>
                                    </td>
                                    <td>{{$row->bank->name}}</td>
                                    <td>{{$row->amount}}</td>
                                    <td data-toggle="modal" data-target="#imgModal{{$row->id}}">
                                        <img width="50px" height="50px" class="img_preview" src="{{ $row->image}}">
                                    </td>
                                    <div id="imgModal{{$row->id}}" class="modal fade" role="img">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <img data-toggle="modal" data-target="#imgModal{{$row->id}}" class="img-preview" src="{{ $row->image}}" style="max-height: 500px">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <td>
                                        <span class="badge @if($row->status=='pending') badge-warning @elseif($row->status=='accepted') badge-success @else badge-danger @endif">
                                            @if($row->status=='pending') معلق @elseif($row->status=='accepted') تم التأكيد @else مرفوض @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="button-list">
                                            @if($row->status=='pending')
                                                <form class="reject" data-id="reject-{{$row->id}}" method="POST" action="{{ route('admin.wallet-pay.reject',[$row->id]) }}">
                                                    @csrf
                                                    {{ method_field('POST') }}
                                                    <button class="btn btn-danger waves-effect waves-light"> <i class="fa fa-archive mr-1"></i> <span>رفض</span> </button>
                                                </form>
                                                <form class="accept" data-id="accept-{{$row->id}}" method="POST" action="{{ route('admin.wallet-pay.accept',[$row->id]) }}">
                                                    @csrf
                                                    {{ method_field('POST') }}
                                                    <button class="btn btn-success waves-effect waves-light"> <i class="fa fa-user-clock mr-1"></i> <span>تأكيد</span> </button>
                                                </form>
                                            @else
                                                <form class="delete" data-id="{{$row->id}}" method="POST" action="{{ route('admin.wallet-pay.destroy',[$row->id]) }}">
                                                    @csrf
                                                    {{ method_field('DELETE') }}
                                                    <button class="btn btn-danger waves-effect waves-light"> <i class="fa fa-tractor mr-1"></i> <span>حذف</span> </button>
                                                </form>
                                            @endif
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
        $(document).on('click', '.reject', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: "تأكيد عملية الرفض ؟",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'نعم !',
                cancelButtonText: 'ﻻ , الغى العملية!',
                closeOnConfirm: false,
                closeOnCancel: false,
                preConfirm: () => {
                    $("form[data-id='" + id + "']").submit();
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
        });
        $(document).on('click', '.accept', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: "تأكيد عملية القبول ؟",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'نعم !',
                cancelButtonText: 'ﻻ , الغى العملية!',
                closeOnConfirm: false,
                closeOnCancel: false,
                preConfirm: () => {
                    $("form[data-id='" + id + "']").submit();
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
        });
    </script>
@endsection
