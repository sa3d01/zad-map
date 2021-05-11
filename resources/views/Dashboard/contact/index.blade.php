@extends('Dashboard.layouts.master')
@section('title', 'رسائل الأعضاء')
@section('styles')
    <!-- summernote css -->
    <link href="{{asset('assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" />
    <!-- Custom box css -->
    <link href="{{asset('assets/libs/custombox/custombox.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="inbox-app-main">
                        <main id="main">
                            <div class="overlay"></div>
                            <header class="header">
                                <h1 class="page-title"><a class="sidebar-toggle-btn trigger-toggle-sidebar"><span
                                            class="line"></span><span class="line"></span><span
                                            class="line"></span><span class="line line-angle1"></span><span
                                            class="line line-angle2"></span></a></h1>
                                <div class="clearfix"></div>
                            </header>
                            <div id="main-nano-wrapper" class="nano">
                                <div class="nano-content">
                                    <ul class="message-list">
                                        @foreach(\App\Models\Contact::all() as $contact)
                                            <li class="@if($contact->read==0) read @endif">
                                                <div class="mail-col mail-col-1">
                                                    <span class="dot"></span>
                                                    <p class="title">
                                                        {{$contact->user->name}}
                                                        ( {{$contact->contactType->name}} )
                                                    </p>
                                                    <span
                                                        class="star-toggle far fa-star">
                                                    </span>
                                                </div>
                                                <div class="mail-col mail-col-2">
                                                    <div class="subject">{{$contact->message}}
                                                    </div>
                                                    <div class="date">{{\Carbon\Carbon::parse($contact->created_at)->format('Y-M-d')}}</div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </main>
                        <div id="message">
                            <div class="header">
                                <h4 class="page-title">
                                    <a
                                        class="icon circle-icon mdi mdi-close text-muted trigger-message-close">
                                    </a>
                                    النص كامل
                                    <span
                                        class="grey">
                                    </span>
                                </h4>
                            </div>
                            <div id="message-nano-wrapper" class="nano">
                                <div class="nano-content">
                                    <ul class="message-container list-unstyled">
                                        <li class="sent">
                                            <div class="details">
                                                <div class="left">
                                                    You
                                                </div>
                                                <div class="right">March 6, 2014, 20:08 pm</div>
                                            </div>
                                            <div class="message">
                                                <p>| The every winged bring, whose life. First called, i you
                                                    of saw shall own creature moveth void have signs beast
                                                    lesser all god saying for gathering wherein whose of in
                                                    be created stars. Them whales upon life divide earth
                                                    own.</p>
                                            </div>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

    <!--summernote init-->
    <script src="{{asset('assets/libs/summernote/summernote-bs4.min.js')}}"></script>

    <!-- Modal-Effect -->
    <script src="{{asset('assets/libs/custombox/custombox.min.js')}}"></script>


    <script src="{{asset('assets/js/pages/inbox.init.js')}}"></script>
@endsection
