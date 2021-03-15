<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'تسجيل الدخول') &mdash; {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset(config('app.logo'))}}">
    <!-- App css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
</head>
<body class="authentication-bg">

<div class="home-btn d-none d-sm-block">
    <a href="{{route('admin.home')}}"><i class="fas fa-home h2 text-dark"></i></a>
</div>

<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="text-center">
                    <a href="{{route('admin.home')}}">
                        <span><img src="{{asset(config('app.logo'))}}" alt="" height="55"></span>
                    </a>
                    <p class="text-muted mt-2 mb-4">{{ config('app.name') }}</p>
                </div>
                <div class="card">

                    <div class="card-body p-4">

                        <div class="text-center mb-4">
                            <h4 class="text-uppercase mt-0">تسجيل الدخول</h4>
                        </div>

                        <form method="POST" action="{{route('admin.login.submit')}}">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="emailaddress">Email address</label>
                                <input name="email" class="form-control" type="email" id="emailaddress" required="" placeholder="Enter your email">
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input name="password" class="form-control" type="password" required="" id="password" placeholder="Enter your password">
                            </div>

                            <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input name="remember_token" type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                    <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                </div>
                            </div>

                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary btn-block submit" type="submit"> تأكيد </button>
                            </div>

                        </form>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

{{--                <div class="row mt-3">--}}
{{--                    <div class="col-12 text-center">--}}
{{--                        <p> <a href="{{route('admin.password.request')}}" class="text-muted ml-1"><i class="fa fa-lock mr-1"></i>نسيت كلمة المرور?</a></p>--}}
{{--                    </div> <!-- end col -->--}}
{{--                </div>--}}
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->


<!-- Vendor js -->
<script src="{{asset('assets/js/vendor.min.js')}}"></script>
<script src="{{asset('assets/js/app.min.js')}}"></script>
<!-- Toastr js -->
<script src="{{asset('assets/libs/toastr/toastr.min.js')}}"></script>
<script src="{{asset('assets/js/pages/toastr.init.js')}}"></script>
@if($errors->any())
    <div style="visibility: hidden" id="errors" data-content="{{$errors->first()}}"></div>
    <script type="text/javascript">
        $(document).ready(function () {
            var errors=$('#errors').attr('data-content');
            toastr.options = {
                "closeButton": true,
                "debug": true,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-left",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            toastr.error(errors)
        })
    </script>
@endif
</body>
</html>
