@extends('Dashboard.layouts.master')
@section('title', 'الحسابات البنكية')
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
                        <a href="{{route('admin.bank.create')}}">
                            <button type="button" class="btn btn-block btn-sm btn-success waves-effect waves-light">إضافة حساب بنكى</button>
                        </a>
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>الشعار</th>
                                <th>إسم البنك</th>
                                <th>رقم الحساب</th>
                                <th>العمليات المتاحة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $row)
                                <tr>
                                    <td data-toggle="modal" data-target="#imgModal{{$row->id}}">
                                        <img width="50px" height="50px" class="img_preview" src="{{ $row->logo}}">
                                    </td>
                                    <div id="imgModal{{$row->id}}" class="modal fade" role="img">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <img data-toggle="modal" data-target="#imgModal{{$row->id}}" class="img-preview" src="{{ $row->logo}}" style="max-height: 500px">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->account_number}}</td>
                                    <td>
                                        <div class="button-list">
                                            <a href="{{route('admin.bank.edit',$row->id)}}">
                                                <button class="btn btn-warning waves-effect waves-light"> <i class="fa fa-map-pin mr-1"></i> <span>تعديل</span> </button>
                                            </a>
                                            @if($row->status==1)
                                                <form class="ban" data-id="{{$row->id}}" method="POST" action="{{ route('admin.bank.ban',[$row->id]) }}">
                                                    @csrf
                                                    {{ method_field('POST') }}
                                                    <button class="btn btn-danger waves-effect waves-light"> <i class="fa fa-archive mr-1"></i> <span>حظر</span> </button>
                                                </form>
                                            @else
                                                <form class="activate" data-id="{{$row->id}}" method="POST" action="{{ route('admin.bank.activate',[$row->id]) }}">
                                                    @csrf
                                                    {{ method_field('POST') }}
                                                    <button class="btn btn-success waves-effect waves-light"> <i class="fa fa-user-clock mr-1"></i> <span>تفعيل</span> </button>
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
        $(document).on('click', '.ban', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: "تأكيد عملية الحظر ؟",
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
        $(document).on('click', '.activate', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: "تأكيد عملية التفعيل ؟",
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
