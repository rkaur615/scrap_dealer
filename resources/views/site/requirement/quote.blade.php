@extends('layouts.page')
@section('title', 'Page Title')
@section('content')

    <!-- main form -->
<!-- <h3>Invoice</h3> -->

<view-requirement id="{{$rid}}" qid="{{$qid}}"></view-requirement>


<script src="{{ asset('js/app.js') }}"></script>
@endsection
