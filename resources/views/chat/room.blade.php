@extends('layouts.page')
@section('title', 'Page Title')
@section('content')

    <!-- main form -->

    <section class="wrapperMain" style="padding-bottom: 0px;padding-top: 30px;">
        <div class="container">
            <div class="headingSection">
                <h2>Chat</h2>
                {{-- @dd($products) --}}
                <div style="float: right;">
                    {{-- <a href="{{route('user.catalog.add')}}" class="btn btn-primary">Add Product</a> --}}
                </div>
            </div>
            <div class="containerArea">
                <div class="container chatPageMain">
                    <div class="row no-gutters">
                      <div class="col-md-4 border-right">
                        <div class="settings-tray">
                          <img class="profile-image" src="{{asset(auth()->user()->pic())}}" alt="Profile img">

                        </div>
                        <div class="listingChatUSer">
                            {{-- <div class="search-box">
                              <div class="input-wrapper">
                                <i class="fa fa-search"></i>
                                <input placeholder="Search here" type="text">
                              </div>
                            </div> --}}
                            @foreach ($orooms as $oroom)
                                <a href="{{route('chat.slug',[$oroom->slug])}}">
                                    <div class="friend-drawer friend-drawer--onhover">
                                        <img class="profile-image" src="{{$oroom->other()?->pic()}}" alt="">
                                        <div class="text">
                                        {{-- @dd($room->other()) --}}
                                        <h6>{{$oroom->other()?->name}}</h6>
                                        <p class="text-muted">{{$oroom->getLastMessage->first()?->body}}</p>
                                        </div>
                                        {{-- <span class="time text-muted small">13:21</span> --}}
                                    </div>
                                </a>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="settings-tray">
                            <div class="friend-drawer no-gutters friend-drawer--grey" style="border: 0px solid;">

                            <img class="profile-image" src="{{$room->other()?->pic()}}" alt="">
                            <div class="text">
                              <h6>{{$room->other()?->name}}</h6>
                              <p class="text-muted">...</p>
                            </div>
                          </div>
                        </div>

                        <div class="chat-panel">
                          <div class="chatAreaPanel">
                            <livewire:chat.messages :key="'msg-'+$room->id" :room="$room" :messages="$room->messages"></livewire:chat.messages>
                          </div>

                          <div class="chatBoxInput">
                              <livewire:chat.new-message :room="$room" :key="$room->id"></livewire:chat.new-message>
                          </div>

                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script src="{{ asset('js/app.js') }}"></script>
@endsection
