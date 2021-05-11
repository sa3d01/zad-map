@extends('Dashboard.layouts.master')
@section('title', 'المدن')
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
                        <a href="{{route('admin.city.create')}}">
                            <button type="button" class="btn btn-block btn-sm btn-success waves-effect waves-light">إضافة مدينة</button>
                        </a>
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>الإسم</th>
                                <th>العمليات المتاحة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $row)
                                <tr>
                                    <td>{{$row->name}}</td>
                                    <td>
                                        <div class="button-list">
                                            <a href="{{route('admin.city.edit',$row->id)}}">
                                                <button class="btn btn-warning waves-effect waves-light"> <i class="fa fa-map-pin mr-1"></i> <span>تعديل</span> </button>
                                            </a>
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
