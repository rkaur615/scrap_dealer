@extends('layouts.guest')
@section('content')

    <!-- main form -->


<Availability :to-be-shown=true :userid="{{auth()->user()->id}}" :role="'RETAILER'"></Availability>


<script src="{{ asset('js/app.js') }}"></script>
@endsection
