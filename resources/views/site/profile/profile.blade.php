@extends('layouts.page')
@section('content')
<section class="wrapperMain">
    <div class="container profileMain">
        <div class="headingSection">
            <h2>Account Settings </h2>
            <div style="float: right;background: #ff6626;border-radius: 10px;padding: 3px 15px;color: #fff;">{{Helper::myRoleVerb(auth()->user()->id)}}</div>
        </div>
        <div class="bg-white rounded-lg d-block d-sm-flex">
            <div class="profile-tab-nav border-right">
                <div class="p-4">
                    <div class="img-circle text-center mb-3">

                        <img src="{{asset(auth()->user()->pic())}}" alt="Image" class="shadow">
                    </div>
                    <h4 class="text-center">{{auth()->user()->name}}</h4>
                </div>
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="account-tab" data-bs-toggle="pill" data-bs-target="#account" role="tab" aria-controls="account" aria-selected="true">
                        <i class="fa fa-home text-center mr-1"></i>
                        Account
                    </a>
                    <a class="nav-link" id="business-tab" data-bs-toggle="pill" data-bs-target="#business" role="tab" aria-controls="business" aria-selected="false">
                        <i class="fa fa-tv text-center mr-1"></i>
                        Business Details
                    </a>
                    @if (Helper::myRoleVerb(auth()->user()->id)=='Supplier')
                    <a class="nav-link" id="reviews-tab" data-bs-toggle="pill" data-bs-target="#reviews" role="tab" aria-controls="reviews" aria-selected="false">
                        <i class="fa fa-star text-center mr-1"></i>
                        Reviews
                    </a>
                    @endif

                    <a class="nav-link" id="password-tab" data-bs-toggle="pill" data-bs-target="#password" role="tab" aria-controls="password" aria-selected="false">
                        <i class="fa fa-key text-center mr-1"></i>
                        Change Password
                    </a>


                </div>
            </div>
            <div class="tab-content p-4 p-md-5" id="v-pills-tabContent" style="padding-right: 0px;">
                <change-pdetails></change-pdetails>
                <change-password></change-password>
                {{-- <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    <h3 class="mb-5" style="font-size: 30px;font-weight: bold;color: #000;">Password Settings</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                  <label>Old password</label>
                                  <input type="password" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                  <label>New password</label>
                                  <input type="password" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                  <label>Confirm new password</label>
                                  <input type="password" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-primary">Update</button>
                        <button class="btn btn-light">Cancel</button>
                    </div>
                </div> --}}
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <h3 class="mb-5" style="font-size: 30px;font-weight: bold;color: #000;">Reviews</h3>

                    <section id="testimonials">
                        <div class="testimonial-box-container">
                            @if (isset(auth()->user()->reviews) && auth()->user()->reviews)
                                @foreach (auth()->user()->reviews as $review)
                                <div class="testimonial-box">
                                    <div class="box-top">
                                        <div class="profile">
                                            <div class="profile-img">
                                                <img src="{{asset($review->retailer->pic())}}" />
                                            </div>
                                            <div class="name-user">
                                                <strong>{{$review->retailer->name}}</strong>
                                            </div>
                                        </div>
                                        <div class="reviews">
                                            @for ($i = 0; $i<$review->rating; $i++)
                                                <i class="fa fa-star"></i>
                                            @endfor
                                            @for ($j = 5-$review->rating; $j<=5-$review->rating; $j++)
                                                <i class="fa fa-star-o"></i>
                                            @endfor

                                        </div>
                                    </div>
                                    <div class="client-comment">
                                        <p>{{$review->feedback}}</p>
                                    </div>
                                </div>
                                @endforeach
                            @endif


                        </div>
                    </section>


                </div>
                <div class="tab-pane fade" id="business" role="tabpanel" aria-labelledby="business-tab">
                    <h3 class="mb-5" style="font-size: 30px;font-weight: bold;color: #000;">Business Details</h3>
                    {{-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="business_name" class="form-label">Business Name/ Retailer Name</label>
                                    <input type="text" class="form-control" id="business_name" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="services" class="form-label">Services</label>
                                    <select name="services" id="services" class="form-control">
                                        <option value="selectService">Select Service</option>
                                        <option value="Service1">Service 1</option>
                                        <option value="Service2">Service 2</option>
                                        <option value="Service2">Service 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="w-100 d-none d-md-block"></div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" id="address" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address1" class="form-label">Address 1</label>
                                    <input type="text" name="address1" class="form-control" id="address1" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" id="city" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" name="state" class="form-control" id="state" placeholder="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="zip" class="form-label">Zip</label>
                                    <input type="text" name="zip" class="form-control" id="zip" placeholder="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country" class="form-label">Country</label>
                                    <div class="selectBox">
                                        <select class="form-control" id="country">
                                            <option value="" selected>India</option>
                                            <option value="Afghanistan"> Afghanistan </option>
                                            <option value="Albania"> Albania </option>
                                            <option value="Algeria"> Algeria </option>
                                            <option value="American Samoa"> American Samoa </option>
                                            <option value="Andorra"> Andorra </option>
                                            <option value="Angola"> Angola </option>
                                            <option value="Anguilla"> Anguilla </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="businessHours" class="form-label">Business Hours</label>
                                    <div class="card border-0">
                                        <div class="row">
                                            <div class="col-sm-2"> <label class=" mt-4">Open Hours</label> </div>
                                            <div class="col-sm-10 list">
                                                <div class="mb-2 row justify-content-between px-3">
                                                    <select class="mb-2 mob" style="width: 130px;height: 30px;margin-top: 21px;">
                                                        <option value="opt1">Monday</option>
                                                        <option value="opt2">Tuesday</option>
                                                    </select>
                                                    <div class="mob" style="width: 160px;">
                                                        <label class=" mr-1">From</label> <input class="ml-1" type="time" name="from" style="width: 100%;">
                                                    </div>
                                                    <div class="mob mb-2" style="width: 160px;">
                                                        <label class=" mr-4">To</label> <input class="ml-1" type="time" name="to" style="width: 100%;">
                                                    </div>
                                                    <div class="mt-1 cancel text-danger" style="margin-top: 30px !important;color: red !important;width: 40px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-2"> </div>
                                            <div class="col-sm-10 list">
                                                <div class="mb-2 row justify-content-between px-3 timingSetArea">
                                                    <select class="mb-2 mob" style="width: 130px;height: 30px;">
                                                        <option value="opt1">Monday</option>
                                                        <option value="opt2">Tuesday</option>
                                                    </select>
                                                    <div class="mob" style="width: 160px;">
                                                        <input class="ml-1" type="time" name="from" style="width: 100%;">
                                                    </div>
                                                    <div class="mob mb-2" style="width: 160px;">
                                                        <input class="ml-1" type="time" name="to" style="width: 100%;">
                                                    </div>
                                                    <div class="mt-1 cancel fa fa-times text-danger" style="color: red !important;width: 40px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12" style="text-align: right;">
                                                <button class="btn btn-primary" style="padding: 3px 10px;"><i class="fa fa-plus-circle"></i>&nbsp;Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="aboutUs" class="form-label">About Us</label>
                                    <textarea class="form-control" rows="3" style="border: 1px solid #ccc;"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="w-100 d-none d-md-block"></div>
                                    <button class="btn btn-primary">Update</button>&nbsp;&nbsp;
                                    <button class="btn btn-light">Cancel</button>
                                </div>
                            </div>

                    </div> --}}
                    <Availability :toBeShown=false :userid="{{auth()->user()->id}}" :role="'{{Str::of(Helper::myRoleVerb(auth()->user()->id))->upper()}}'"></Availability>



                </div>

            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
   <!-- <script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/bootstrap.min.js')}}"></script>


    <script type="text/javascript">

		function darken_screen(yesno){
			if( yesno == true ){
				document.querySelector('.screen-darken').classList.add('active');
			}
			else if(yesno == false){
				document.querySelector('.screen-darken').classList.remove('active');
			}
		}

		function close_offcanvas(){
			darken_screen(false);
			document.querySelector('.mobile-offcanvas.show').classList.remove('show');
			document.body.classList.remove('offcanvas-active');
		}

		function show_offcanvas(offcanvas_id){
			darken_screen(true);
			document.getElementById(offcanvas_id).classList.add('show');
			document.body.classList.add('offcanvas-active');
		}

		document.addEventListener("DOMContentLoaded", function(){
			document.querySelectorAll('[data-trigger]').forEach(function(everyelement){

				let offcanvas_id = everyelement.getAttribute('data-trigger');

				everyelement.addEventListener('click', function (e) {
					e.preventDefault();
					show_offcanvas(offcanvas_id);

				});
			});

			document.querySelectorAll('.btn-close').forEach(function(everybutton){

				everybutton.addEventListener('click', function (e) {
					e.preventDefault();
					close_offcanvas();
				});
			});

			document.querySelector('.screen-darken').addEventListener('click', function(event){
				close_offcanvas();
			});

		});
		// DOMContentLoaded  end



			$('.friend-drawer--onhover').on('click', function () {

				$('.chat-bubble').hide('slow').show('slow');

			});



		</script> -->
</section>

@endsection
