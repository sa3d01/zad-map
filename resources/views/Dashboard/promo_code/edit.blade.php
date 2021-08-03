@extends('Dashboard.layouts.master')
@section('title', 'تعديل كود خصم')
@section('styles')
    <link href="{{asset('assets/libs/bootstrap-timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/libs/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                    <div class="card-box">
                        <form method="POST" action="{{route('admin.promo_code.update',$promo_code->id)}}" enctype="multipart/form-data" data-parsley-validate novalidate>
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="marketer_id">رمز المسوق*</label>
                                <input type="text" value="{{$promo_code->marketer_id}}" name="marketer_id" required class="form-control" id="marketer_id">
                            </div>
                            <div class="form-group">
                                <label for="code">كود الخصم*</label>
                                <input type="text" value="{{$promo_code->code}}" name="code" required class="form-control" id="code">
                            </div>
                            <div class="form-group">
                                <label for="discount_percent">نسبة الخصم*</label>
                                <input type="number" value="{{$promo_code->discount_percent}}" min="1" max="100" name="discount_percent" required class="form-control" id="discount_percent">
                            </div>
                            <div class="form-group">
                                <label for="count_of_uses">عدد مرات الاستخدام المتاحه*</label>
                                <input type="number" min="1" value="{{$promo_code->count_of_uses}}" name="count_of_uses" required class="form-control" id="count_of_uses">
                            </div>
                            <div class="form-group">
                                <label>تاريخ انتهاء الصلاحية</label>
                                <div class="input-group">
                                    <input type="text" name="end_date" class="form-control" placeholder="mm/dd/yyyy" id="datepicker">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="ti-calendar"></i></span>
                                    </div>
                                </div><!-- input-group -->
                            </div>
                            <div class="form-group text-right mb-0">
                                <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
                                    تعديل
                                </button>
                            </div>
                        </form>
                    </div>
                </div><!-- end col -->
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- Validation js (Parsleyjs) -->
    <script src="{{asset('assets/libs/parsleyjs/parsley.min.js')}}"></script>
    <!-- validation init -->
    <script src="{{asset('assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

@endsection
