<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="Author" content="">
	<meta name="Keywords" content="">
	<meta name="Description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
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
			<div class="form-wrapper">
                    @if (!$errors->isEmpty())
                        <div class="alert alert-danger">
                        @foreach ($errors->all() as $err)
                            {{$err}}
                        @endforeach
                        </div>
                    @endif
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <form method="POST" action="{{route('user.post.register')}}">
                                <div class="signUpmainForm h-100">
                                    <div class="headerForm">
                                        <h1 style="font-weight: bold;font-size: 40px;font-family: inherit;font-style: normal;color: #000;">Welcome to <img src="{{asset('/assets/images/logo.png')}}"></h1>
                                        <p>Enter your details below </p>
                                    </div>
                                    <!-- main form -->
                                    <div class="selectProfile">
                                        <div class="userSelect selected" data-role="retailer">
                                            <img src="{{asset('/assets/images/userImg.png')}}">
                                            Retailer
                                        </div>
                                        <div class="userSelect"  data-role="supplier">
                                            <img src="{{asset('/assets/images/userImg.png')}}">
                                            Supplier
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            <div class="formGroup">
                                                <label for="firstName" class="form-label">First Name</label>
                                                {{ csrf_field() }}
                                                <input  type="hidden" name="role" id="role" value="retailer">
                                                <input required type="text" pattern="[a-zA-Z0-9\s]+" class="form-control" value="{{old('first_name')}}" name="first_name" id="firstName" >
                                            </div>
                                        </div>
                                        <div class="col-md-6  col-6">
                                            <div class="formGroup">
                                                <label for="lastName" class="form-label">Last Name</label>
                                                <input required type="text" pattern="[a-zA-Z0-9\s]+" class="form-control" value="{{old('last_name')}}" name="last_name" id="lastName" >
                                            </div>
                                        </div>
                                        <div class="col-md-6  col-6">
                                            <div class="formGroup">
                                                <label for="emailaddress" class="form-label">Email Address</label>
                                                <input required type="email" class="form-control" name="email" value="{{old('email')}}" id="emailaddress" >
                                            </div>
                                        </div>
                                        <div class="col-md-6  col-6">
                                            <div class="formGroup">
                                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                                <input required type="tel" pattern="[0-9]{10}" placeholder="12345678910" class="form-control" id="phoneNumber" value="{{old('phone_number')}}" name="phone_number">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="formGroup">
                                                <label for="password" class="form-label">Password</label>
                                                <div class="input-group mb-3">
                                                    <input required type="password" class="form-control" id="password" name="password">
                                                    <!-- <span>
                                                        <i class="fa fa-eye"  style="cursor: pointer;"></i>
                                                    </span> -->
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="formGroup">
                                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                                <div class="input-group mb-3">
                                                <input required type="password" class="form-control" id="password_confirmation" name="password_confirmation" >
                                                <!-- <span>
                                                    <i class="fa fa-eye" style="cursor: pointer;"></i>
                                                </span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="form-check">
                                    <input required class="form-check-input" type="checkbox" value="" id="checkTemPolicy">
                                    <label class="form-check-label" for="checkTemPolicy">
                                        I Agree to <strong>Terms of Services</strong> and <strong>Privacy policy</strong>.
                                    </label>
                                </div>
                                 --}}
                                <div class="mb-3 mt-5 text-center">
                                    <button type="submit"  class="btn btn-primary">Sign Up</button>
                                </div>
                                <div class="mb-3 mt-5 text-center">
                                    <div class="login-link">Have an account? <a href="{{route('user.login')}}" class="is-link has-text-brand"><strong>Sign In</strong></a></div>
                                </div>
                                <!-- /main form -->
                            </form>
                            </div>
                        </div>
                    </div>

			</div>
		</div>
	</div>
	<!-- /sign up page -->


	<script src="/assets/js/jquery-3.6.0.min.js"></script>
	<script src="/assets/js/popper.min.js"></script>
	<script src="/assets/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selector = document.querySelectorAll('.userSelect');

        for (let i = 0; i < selector.length; i++) {
            selector[i].addEventListener('click', ()=>{
                let role = selector[i].getAttribute('data-role');
                console.log('am role', role);
                let roleSelector = document.querySelector('#role');
                roleSelector.value = role;
                if(role=='supplier'){
                    selector[0].classList.remove('selected');
                    selector[1].classList.add('selected');
                }
                if(role=='retailer'){
                    selector[0].classList.add('selected');
                    selector[1].classList.remove('selected');
                }
            })
        }

    </script>
 </body>
</html>
