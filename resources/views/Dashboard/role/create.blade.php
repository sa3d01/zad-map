@extends('Dashboard.layouts.master')
@section('title', 'إضافة دور')
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
                        <form method="POST" action="{{route('admin.roles.store')}}" enctype="multipart/form-data" data-parsley-validate novalidate>
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="name">الإسم*</label>
                                <input type="text" name="name" required class="form-control" id="name">
                            </div>
                            <div class="form-group">
                                <label for="permission"> الصلاحيات </label>
                                <div class="control-group">
                                    @foreach($permission as $value)
                                        <input type="checkbox" name="permission[]" value="{{$value->id}}">{{$value->slug}}<br>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group text-right mb-0">
                                <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
                                    تأكيد
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
@endsection
