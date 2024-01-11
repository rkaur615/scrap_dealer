@extends('layouts.page')
@section('content')
<section class="wrapperMain">
    @if($user->is_profile_completed)
        <view-public-profile cid="18" id='{{$uid}}'></view-public-profile>
    @else
        <div class="alert alert-danger">The Supplier has not updated his profile!</div>
    @endif

</section>
<script src="{{asset('js/app.js')}}"></script>
@endsection
