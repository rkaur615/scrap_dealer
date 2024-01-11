<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="Author" content="">
	<meta name="Keywords" content="">
	<meta name="Description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="icon" type="image/png" href="{{asset('assets/images/favicon.png')}}">
	<title>PickNDeal</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="{{asset('assets/dist/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/responsive.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/font-awesome.min.css')}}" rel="stylesheet">
</head>
<body>

	<!-- sign up Page -->
	<div class="skewed-bg bgGreySignUp">
		<div class="container">
            <br><br>
			<div class="form-wrapper loginPage">
                <form method="POST" action="{{ route('user.password.forgot') }}">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="signUpmainForm h-100">
                                <div class="headerForm">
                                    <img src="{{asset('assets/images/logo.png')}}"> <br><br>
                                    <h1 class="text-start">Forgot Password</h1>
                                    {{-- <p class="text-start">To access marketplace of suppliers and retailers!</p> --}}
                                </div>
                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{session('error')}}
                                    </div>
                                @endif
                                <!-- main form -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="formGroup">
                                            <label for="userName" class="form-label">Username</label>
                                            @csrf
                                            <input type="text" class="form-control" id="userName" name="email" required>
                                        </div>
                                    </div>

                                </div>
                            <div class="mb-3 mt-5 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                            <div class="d-flex justify-content-center links">
                                Don't have an account?&nbsp;&nbsp;<a href="{{route('user.register')}}">Sign Up</a>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="{{route('user.password.forgot')}}">Forgot your password?</a>
                            </div>

                            <!-- /main form -->
                            </div>


                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>
	<!-- /sign up page -->


	<script src="/assets/js/jquery-3.6.0.min.js"></script>
	<script src="/assets/js/popper.min.js"></script>
	<script src="/assets/dist/js/bootstrap.bundle.min.js"></script>

 </body>
</html>
