<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'لوحة التحكم') &mdash; {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="sa3d01" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{asset('media/images/logo.jpeg') }}">
    <link rel="stylesheet" href="{{asset('assets/libs/morris-js/morris.css')}}" />
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;900&display=swap" rel="stylesheet">
    <style>
        body,h4{
            font-family: 'Cairo', serif;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div id="wrapper">
        @include('Dashboard.layouts.partials.header')
        <div class="left-side-menu">
            <div class="slimscroll-menu">
                @include('Dashboard.layouts.partials.account')
                @include('Dashboard.layouts.partials.sidebar')
                <div class="clearfix"></div>
            </div>
        </div>
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
    <script>
        $('.carousel').carousel()
    </script>
    @yield('script')
</body>
</html>
