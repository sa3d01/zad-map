<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'لوحة التحكم') &mdash; {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ config('app.logo') }}">
    <!--Morris Chart-->
    <link rel="stylesheet" href="{{asset('assets/libs/morris-js/morris.css')}}" />
    <!-- App css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    @yield('styles')
</head>
<body>
    <div id="wrapper">
        @include('Dashboard.layouts.partials.header')
        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu">
            <div class="slimscroll-menu">
                <!-- User box -->
                @include('Dashboard.layouts.partials.account')
                <!--- Sidemenu -->
                @include('Dashboard.layouts.partials.sidebar')
                <!-- End Sidebar -->
                <div class="clearfix"></div>
            </div>
            <!-- Sidebar -left -->
        </div>
        <!-- Left Sidebar End -->
        <div class="content-page">
            @yield('content')
            @include('Dashboard.layouts.footer')
        </div>
    </div>
    <script src="{{asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{asset('assets/libs/jquery-knob/jquery.knob.min.js')}}"></script>
    <script src="{{asset('assets/libs/morris-js/morris.min.js')}}"></script>
    <script src="{{asset('assets/libs/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/dashboard.init.js')}}"></script>
    <script src="{{asset('assets/js/app.min.js')}}"></script>
    @yield('script')
</body>
</html>
