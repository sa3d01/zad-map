@extends('Dashboard.layouts.master')
@section('title', 'تعديل مدير')
@section('styles')
    <link href="{{asset('assets/libs/dropify/dist/css/dropify.min.css')}}" rel="stylesheet" type="text/css" />
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
                        <form method="POST" action="{{route('admin.admins.update',$user->id)}}" enctype="multipart/form-data" data-parsley-validate novalidate>
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="userName">الإسم*</label>
                                <input type="text" name="name" required
                                       value="{{$user->name}}" class="form-control" id="userName">
                            </div>
                            <div class="form-group">
                                <label for="emailAddress">البريد الإلكترونى*</label>
                                <input type="email" name="email" required pattern="/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/"
                                       value="{{$user->email}}" class="form-control" id="emailAddress">
                            </div>
                            <div class="form-group">
                                <label for="image">الصورة الشخصية</label>
                                <div class="card-box">
                                    <input name="image" id="input-file-now-custom-1 image" type="file" class="dropify" data-default-file="{{$user->image}}"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pass1">كلمة المرور*</label>
                                <input id="pass1" type="password" name="password" placeholder="Password" required
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="passWord2">تأكيد كلمة المرور *</label>
                                <input data-parsley-equalto="#pass1" type="password" required
                                       placeholder="Password" class="form-control" id="passWord2">
                            </div>
                            <div class="form-group">
                                <label for="permission"> الدور </label>
                                <div class="control-group">
                                    @foreach($roles as $value)
                                        <input type="radio" name="role" value="{{$value->id}}" @if(in_array($value->id, $userRole)) checked @endif>
                                            {{$value->name}}
                                        <br>
                                    @endforeach
                                </div>
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
    <script src="{{asset('assets/libs/dropify/dist/js/dropify.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            // Basic
            $('.dropify').dropify();
            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });
            // Used events
            var drEvent = $('#input-file-events').dropify();
            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });
            drEvent.on('dropify.afterClear', function(event, element) {
                alert('File deleted');
            });
            drEvent.on('dropify.errors', function(event, element) {
                console.log('Has Errors');
            });
            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
    </script>

    <!-- Validation js (Parsleyjs) -->
    <script src="{{asset('assets/libs/parsleyjs/parsley.min.js')}}"></script>
    <!-- validation init -->
    <script src="{{asset('assets/js/pages/form-validation.init.js')}}"></script>
@endsection
