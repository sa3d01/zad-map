@extends('Dashboard.layouts.master')
@section('title', 'المندوبيين الطالبين لتعديل ملفاتهم الشخصية')
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
                                <th>الإسم</th>
                                <th>رقم الجوال</th>
                                <th>المدينة</th>
                                <th>العمليات المتاحة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $row)
                                <tr>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->user->phone}}</td>
                                    <td>{{$row->city?$row->city->name:''}}</td>
                                    <td>
                                        <div class="button-list">
                                            <a href="{{route('admin.delivery.show_request',$row->id)}}">
                                                <button class="btn btn-info waves-effect waves-light"> <i class="fa fa-eye mr-1"></i> <span>عرض</span> </button>
                                            </a>
                                            <form class="reject" data-href="{{ route('admin.delivery.reject_request',[$row->id]) }}" data-id="{{$row->id}}">
                                                @csrf
                                                <button class="btn btn-danger waves-effect waves-light"> <i class="fa fa-archive mr-1"></i> <span>رفض</span> </button>
                                            </form>
                                            <form class="accept" data-id="{{$row->id}}" data-href="{{ route('admin.delivery.accept_request',[$row->id]) }}">
                                                @csrf
                                                <button class="btn btn-success waves-effect waves-light"> <i class="fa fa-user-clock mr-1"></i> <span>قبول</span> </button>
                                            </form>
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
            Swal.fire({
                title: 'من فضلك اذكر سبب الرفض',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'رفض',
                cancelButtonText: 'الغاء',
                showLoaderOnConfirm: true,
                preConfirm: (reject_reason) => {
                    $.ajax({
                        url: $(this).data('href'),
                        type:'GET',
                        data: {reject_reason}
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(() => {
                location.reload(true);
            })
        });
        $(document).on('click', '.accept', function (e) {
            e.preventDefault();
            Swal.fire({
                title: "هل انت متأكد من القبول ؟",
                text: "تأكد من اجابتك قبل التأكيد!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-info',
                confirmButtonText: 'نعم , قم بالقبول!',
                cancelButtonText: 'ﻻ , الغى عملية القبول!',
                closeOnConfirm: false,
                closeOnCancel: false,
                preConfirm: () => {
                    $.ajax({
                        url: $(this).data('href'),
                        type:'GET',
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(() => {
                location.reload(true);
            })
        });
    </script>
@endsection
