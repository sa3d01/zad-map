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
