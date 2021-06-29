@extends('Dashboard.layouts.master')
@section('title', 'حالات الإستوري المعلقة')
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
                                <th>صاحب الحالة</th>
                                <th>مدة العرض الطلوبة</th>
                                <th>سعر الإضافة</th>
                                <th>تاريخ الطلب</th>
                                <th>الحالة</th>
                                <th>العمليات المتاحة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $row)
                                <tr>
                                    <td><a href="{{route('admin.provider.show',['provider'=>$row->user_id])}}">{{$row->user->name}}</a></td>
                                    <td>{{$row->storyPeriod->story_period}} أيام </td>
                                    <td>{{$row->storyPeriod->story_price}} ريال </td>
                                    <td>{{$row->created_at}}</td>
                                    <td data-toggle="modal" data-target="#imgModal{{$row->id}}">
                                        <img width="50px" height="50px" class="img_preview" src="{{ $row->media}}">
                                    </td>
                                    <div id="imgModal{{$row->id}}" class="modal fade" role="img">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <img data-toggle="modal" data-target="#imgModal{{$row->id}}" class="img-preview" src="{{ $row->media}}" style="max-height: 500px">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <td>
                                        <div class="button-list">
                                           <a class="reject" href="" data-href="{{ route('admin.story.reject',[$row->id]) }}" data-id="{{$row->id}}">
                                                <button class="btn btn-danger waves-effect waves-light"> <i class="fa fa-archive mr-1"></i> <span>رفض</span> </button>
                                           </a>
                                            <form method="POST" class="accept" data-id="{{$row->id}}" action="{{ route('admin.story.accept',[$row->id]) }}">
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
                        sync:true,
                        type:'GET',
                        data: {reject_reason},
                        success: function(){
                            location.reload(true);
                        }
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
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
                    $(this).submit();
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
        });
    </script>
@endsection
