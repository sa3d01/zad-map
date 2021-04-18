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

                <div class="col-sm-12">
                    <div class="inbox-app-main">
                        <div class="row">
                            <div class="col-xl-12">
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
                                                    <li class="@if($contact->read==0) unread @endif">
                                                        <div class="mail-col mail-col-1">
                                                            <span class="dot"></span>
                                                            <div class="checkbox-wrapper-mail">
                                                                <input type="checkbox" id="chk1">
                                                                <label for="chk1" class="toggle"></label>
                                                            </div>
                                                            <p class="title">{{$contact->user->name}}
                                                                ( {{$contact->contactType->name}} )</p><span
                                                                class="star-toggle far fa-star"></span>
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
                                        <h4 class="page-title"><a
                                                class="icon circle-icon mdi mdi-close text-muted trigger-message-close"></a>Process<span
                                                class="grey">(6)</span></h4>
                                        <p>From <a href="#">You</a> to <a href="#">Scott Waite</a>, started on <a
                                                href="#">March 2, 2014</a> at 2:14 pm est.</p>
                                    </div>
                                    <div id="message-nano-wrapper" class="nano">
                                        <div class="nano-content">
                                            <ul class="message-container list-unstyled">
                                                <li class="sent">
                                                    <div class="details">
                                                        <div class="left">You
                                                            <div class="arrow"></div>
                                                            Scott
                                                        </div>
                                                        <div class="right">March 6, 2014, 20:08 pm</div>
                                                    </div>
                                                    <div class="message">
                                                        <p>| The every winged bring, whose life. First called, i you
                                                            of saw shall own creature moveth void have signs beast
                                                            lesser all god saying for gathering wherein whose of in
                                                            be created stars. Them whales upon life divide earth
                                                            own.</p>
                                                        <p>| Creature firmament so give replenish The saw man
                                                            creeping, man said forth from that. Fruitful multiply
                                                            lights air. Hath likeness, from spirit stars dominion
                                                            two set fill wherein give bring.</p>
                                                        <p>| Gathering is. Lesser Set fruit subdue blessed let.
                                                            Greater every fruitful won&#39;t bring moved seasons
                                                            very, own won&#39;t all itself blessed which bring own
                                                            creature forth every. Called sixth light.</p>
                                                    </div>
                                                    <div class="tool-box"><a href="#"
                                                                             class="circle-icon small mdi mdi-share"></a><a
                                                            href="#"
                                                            class="circle-icon small red-hover mdi mdi-close"></a><a
                                                            href="#"
                                                            class="circle-icon small red-hover mdi mdi-flag"></a>
                                                    </div>
                                                </li>
                                                <li class="received">
                                                    <div class="details">
                                                        <div class="left">Scott
                                                            <div class="arrow orange"></div>
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
                                                        <p>| Creature firmament so give replenish The saw man
                                                            creeping, man said forth from that. Fruitful multiply
                                                            lights air. Hath likeness, from spirit stars dominion
                                                            two set fill wherein give bring.</p>
                                                        <p>| Gathering is. Lesser Set fruit subdue blessed let.
                                                            Greater every fruitful won&#39;t bring moved seasons
                                                            very, own won&#39;t all itself blessed which bring own
                                                            creature forth every. Called sixth light.</p>
                                                    </div>
                                                    <div class="tool-box"><a href="#"
                                                                             class="circle-icon small mdi mdi-share"></a><a
                                                            href="#"
                                                            class="circle-icon small red-hover mdi mdi-close"></a><a
                                                            href="#"
                                                            class="circle-icon small red-hover mdi mdi-flag"></a>
                                                    </div>
                                                </li>

                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div><!-- end row -->
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
