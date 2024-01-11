@extends('layouts.page')
@section('title', 'Page Title')
@section('content')

    <!-- main form -->

    @if (isset($catalogId))
        <add-catalog cid="{{$catalogId}}"></add-catalog>
    @else

        <add-catalog cid="0"></add-catalog>
        {{-- <manage-product></manage-product> --}}
    @endif



    <script src="{{ asset('js/app.js') }}"></script>
@endsection
