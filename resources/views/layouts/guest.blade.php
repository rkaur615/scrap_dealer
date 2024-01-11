<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="Author" content="">
	<meta name="Keywords" content="">
	<meta name="Description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="icon" type="image/png" href="{{asset('/assets/images/favicon.png')}}">
	<title>PickNDeal</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="{{asset('assets/dist/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/responsive.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/font-awesome.min.css')}}" rel="stylesheet">
</head>
<body>
    {{-- @dd(config('app.asset_url')) --}}
	<!-- sign up Page -->
	<div class="skewed-bg bgGreySignUp">
		<div class="container">
			<div class="form-wrapper">
				<div class="row justify-content-center">
					<div class="col-md-8" id="app">

                        @yield('content')
						<!-- /main form -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /sign up page -->

    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyArx9JBkqyCl5DoVTCTAH6bQGfA2lOzR0g"></script>
	<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
	<script src="{{asset('assets/js/popper.min.js')}}"></script>
	<script src="{{asset('assets/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script>
        window.addEventListener('load',()=>{
            console.log('loaded');
        });
    </script>
 </body>
</html>
