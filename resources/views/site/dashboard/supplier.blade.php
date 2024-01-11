@extends('layouts.page')

@section('title', 'Page Title')

@section('content')
    <!-- main Wrapper -->
	<section class="wrapperMain">
		<div class="container">
			<x-requirement-list></x-requirement-list>

		</div>
	</section>
	<!-- /main Wrapper -->
@stop
