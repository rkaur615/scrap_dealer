@extends('layouts.page')
@section('title', 'Page Title')
@section('content')

    <!-- main form -->


<view-requirement id="{{$requirementId}}"></view-requirement>


<script src="{{ asset('js/app.js') }}"></script>
@endsection
