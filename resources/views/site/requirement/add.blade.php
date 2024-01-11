@extends('layouts.page')
@section('title', 'Page Title')
@section('content')

    <!-- main form -->
@if (isset($rid))
<add-requirement rid="{{$rid}}"></add-requirement>
@else

    <add-requirement rid="0"></add-requirement>
    {{-- <manage-product></manage-product> --}}
@endif




<script src="{{ asset('js/app.js') }}"></script>
@endsection
