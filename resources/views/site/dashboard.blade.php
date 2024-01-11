@extends('layouts.page')

@section('title', 'Page Title')

@section('content')
    <!-- main Wrapper -->
	<section class="wrapperMain">
		<div class="container">
			<x-supplier-list></x-supplier-list>

		</div>
	</section>
	<!-- /main Wrapper -->
@stop
