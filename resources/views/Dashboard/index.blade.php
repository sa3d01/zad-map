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


        let orders=[];
        let seven_orders=$('#seven-orders');
        seven_orders.find('div').each(function(){
            let obj = JSON.parse($(this).attr('data-order'));
            orders.push(obj);
        });
        const groups = orders.reduce((dates, order) => {
            const date =new Date(order.created_at).getDate();
            if (!dates[date]) {
                dates[date] = [];
            }
            dates[date].push(order);
            return dates;
        }, {});
        const groupArrays = Object.keys(groups).map((date) => {
            return {
                date,
                orders: groups[date]
            };
        });
        let orders_data=[];
        let new_orders=0;
        let pre_paid_orders=0;
        let in_progress_orders=0;
        let completed_orders=0;
        let rejected_orders=0;
        let i=0;
        let o=0;
        for (i ; i<groupArrays.length ; i++){
            for (o ; o<groupArrays[i]['orders'].length ; o++){
                if (groupArrays[i]['orders'][o].status==='new'){
                    new_orders++;
                }else if(groupArrays[i]['orders'][o].status==='rejected'){
                    rejected_orders++;
                }else if(groupArrays[i]['orders'][o].status==='pre_paid'){
                    pre_paid_orders++;
                }else if(groupArrays[i]['orders'][o].status==='in_progress'){
                    in_progress_orders++;
                }else{
                    completed_orders++;
                }
            }
            orders_data.push({ day: groupArrays[i]['date'], completed: completed_orders, in_progress: in_progress_orders , pre_paid: pre_paid_orders ,new: new_orders, rejected: rejected_orders});
            o=0;
            completed_orders=0;
            in_progress_orders=0;
            pre_paid_orders=0;
            new_orders=0;
            rejected_orders=0;
        }
        // console.log(orders_data)
        new Morris.Line({
            element: 'morris-line-orders',
            data: orders_data,
            xkey: 'day',
            parseTime: false,
            ykeys: ['new','pre_paid','in_progress','rejected','completed'],
            labels: ['new','pre_paid','in_progress','rejected','completed'],
            lineColors: ['#214185','#e56119','#db110d','#0b0b0b','#32a852']
        });
    </script>
@endsection
