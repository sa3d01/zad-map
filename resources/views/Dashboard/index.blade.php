@extends('Dashboard.layouts.master')
@section('title', 'الإحصائيات')
@section('content')
    <div class="content">
        <div class="container-fluid">
            @include('Dashboard.Partials.main-cards')
            @include('Dashboard.Partials.charts')
            @include('Dashboard.Partials.new_providers')
            @include('Dashboard.Partials.new_orders')
        </div>
    </div>
@endsection
@section('script')
    <script>
        let users=document.getElementById('morris-donut-users').getAttribute('data-users');
        let providers=document.getElementById('morris-donut-users').getAttribute('data-providers');
        let deliveries=document.getElementById('morris-donut-users').getAttribute('data-deliveries');
        let families=document.getElementById('morris-donut-users').getAttribute('data-families');
        Morris.Donut({
            element: 'morris-donut-users',
            resize: true,
            colors: [
                '#f05050',
                '#648b55',
                '#ffbd4a',
                '#4080ff',
            ],
            data: [
                {label:"المستخدمين", value:users},
                {label:"مقدمى الخدمات", value:providers},
                {label:"الأسر المنتجة", value:families},
                {label:"مندوبى التوصيل", value:deliveries},
            ]
        });
        Morris.Bar({
            element: 'morris-bar-orders',
            data: [
                { y: '2006', a: 100},
                { y: '2007', a: 75},
                { y: '2008', a: 50}
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Series A']
        });
    </script>
@endsection
