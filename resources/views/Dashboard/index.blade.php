@extends('Dashboard.layouts.master')
@section('title', 'الإحصائيات')
@section('content')
    <div class="content">
        <div class="container-fluid">
            @include('Dashboard.Partials.main-cards')
            @include('Dashboard.Partials.charts')
{{--            @include('Dashboard.Partials.new_providers')--}}
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


        new Morris.Line({
            element: 'morris-line-orders',
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.
            data: [
                { day: '1', delivered: 20, in_progress: 2 ,new: 25, cancelled: 12 },
                { day: '2', delivered: 15, in_progress: 7 ,new: 24, cancelled: 21 },
                { day: '3', delivered: 0, in_progress: 0 ,new: 0, cancelled: 1 },
                { day: '4', delivered: 23, in_progress: 21 ,new: 20, cancelled: 2 },
                { day: '5', delivered: 24, in_progress: 22 ,new: 21, cancelled: 12 },
                { day: '6', delivered: 2, in_progress: 21 ,new: 20, cancelled: 0 },
                { day: '7', delivered: 12, in_progress: 12 ,new: 2, cancelled: 0 },
            ],
            // The name of the data record attribute that contains x-values.
            xkey: 'day',
            parseTime: false,
            // A list of names of data record attributes that contain y-values.
            ykeys: ['delivered','in_progress','new','cancelled'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['delivered','in_progress','new','cancelled'],
            lineColors: ['#32a852','#214185','#e56119','#db110d']
        });
    </script>
@endsection
