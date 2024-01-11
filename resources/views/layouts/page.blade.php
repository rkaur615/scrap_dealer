<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="Author" content="">
	<meta name="Keywords" content="">
	<meta name="Description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/png" href="{{asset('/assets/images/favicon.png')}}">
	<title>PickNDeal</title>

	<link href="{{asset('assets/dist/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/responsive.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/font-awesome.min.css')}}" rel="stylesheet">
    <script>
        const baseURL = 'https://pickndeal.oidea.online/laravel_app/public/';

    </script>
    @livewireStyles
    @livewireScripts
</head>
<body   class="dx-viewport">

    <div  id="app">
	<span class="screen-darken"></span>
    <notifications></notifications>
	<!-- Header -->
	<header class="navbar-light bg-light">
		<div class="d-md-none mobileTopHead">
			<div class="container">

				<button data-trigger="navbar_main" class="d-md-none btn" type="button">
					<i class="fa fa-bars" aria-hidden="true"></i>
				</button>
				<a class="navbar-brand" href="#"><img src="{{asset('/assets/images/logo.png')}}"></a>
                <div class="d-md-none" style="float: right;margin-top: -30px;">
                    <div class="nav-item" style="position: relative;">
						<a onclick="fetch(baseURL+'user/markedAllRead')" class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> <i class="fa fa-bell" style="display: inline-block;"></i> <span class="badge bg-danger">{{count(auth()->user()->unreadNotifications)}}</span></a>
						<ul class="dropdown-menu dropdown-menu-end">
                            @foreach (auth()->user()->notifications as $notification)
                                @switch($notification->data['type'])
                                    @case("InviteReceived")
                                        @if (\App\Models\Requirement::where('id',$notification->data['requirementId'])->count())
                                        <li><a class="dropdown-item" href="{{route('user.requirement.view',[$notification->data['requirementId']])}}">You have been invited to
                                            {{\App\Models\Requirement::where('id',$notification->data['requirementId'])->first()->title}}
                                        </a></li>
                                        @endif

                                        @break
                                    @case("QuoteReceived")
                                        @if (\App\Models\Requirement::where('id',$notification->data['requirementId'])->count())
                                        <li><a  class="dropdown-item" href="{{route('user.requirement.viewQuotes',[$notification->data['requirementId']])}}">{{\App\Models\Requirement::where('id',$notification->data['requirementId'])->first()->title}} has received a new Quote.
                                        </a></li>
                                        @endif

                                        @break
                                    @case("QuoteApproved")
                                        @if (\App\Models\Requirement::where('id',$notification->data['requirementId'])->count())
                                        <li><a href="{{route('user.requirement.view',[$notification->data['requirementId']])}}" class="dropdown-item">You Quote has been approved -
                                            {{\App\Models\Requirement::where('id',$notification->data['requirementId'])->first()->title}}
                                        </a></li>
                                        @endif

                                        @break
                                    @case("QuoteRejected")
                                        @if (\App\Models\Requirement::where('id',$notification->data['requirementId'])->count())
                                        <li><a class="dropdown-item" href="{{route('user.requirement.view',[$notification->data['requirementId']])}}">Your quote has been rejected -
                                            {{\App\Models\Requirement::where('id',$notification->data['requirementId'])->first()->title}}
                                        </a></li>
                                        @endif

                                        @break
                                        @case("QuoteUpdated")
                                            @if (\App\Models\Requirement::where('id',$notification->data['requirementId'])->count())
                                            <li><a class="dropdown-item" href="{{route('user.requirement.view',[$notification->data['requirementId']])}}">Your quote has been updated -
                                                {{\App\Models\Requirement::where('id',$notification->data['requirementId'])->first()->title}}
                                            </a></li>
                                            @endif

                                            @break

                                        @case("")
                                        <li><a class="dropdown-item" href="#"> {{$notification}}</a></li>
                                        @break
                                    @default
                                @endswitch
                            @endforeach

						</ul>
                    </div>
                </div>

			</div>
		</div>
		<nav id="navbar_main" class="mobile-offcanvas navbar navbar-expand-md navbar-light bg-light">
			<div class="container">
				<div class="offcanvas-header">
					<button class="btn-close float-end"></button>
				</div>
				<a class="navbar-brand" href="{{route('user.dashboard')}}"><img src="{{asset('/assets/images/logo.png')}}"></a>
				<div class="d-md-none">
					<div class="mobileUserInfo">
						<div class="userImg">
							<img src="{{asset('/assets/images/userImg.png')}}">
						</div>
						<div class="userName">
                            <a href="{{route('user.profile')}}">{{auth()->user()->name}}</a>
						</div>
					</div>
				</div>
				<form class="form-inline my-2 my-md-0" action="{{route('search')}}" style="margin-right: 15px;">
					<input class="form-control" type="text" name="search" value="{{app('request')->input('search') }}" placeholder="Search" aria-label="q" style="padding: 5px 10px;border-bottom: 1px solid #ccc;">
				 </form>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item"><a @if(request()->routeIs('user.dashboard')) class="nav-link active" @else class="nav-link" @endif  href="{{route('user.dashboard')}}">Home</a></li>
                    @if(Helper::isSupplier(auth()->user()->id))
                        <li class="nav-item"><a @if(request()->routeIs('user.catalog.list')) class="nav-link active" @else class="nav-link" @endif href="{{route('user.catalog.list')}}">Catalog</a></li>

                        <li class="nav-item"><a @if(request()->routeIs('user.supplier.quotes')) class="nav-link active" @else class="nav-link" @endif  href="{{route('user.supplier.quotes')}}">My Quotes</a></li>

                    @endif
                    @if(Helper::isRetailer(auth()->user()->id))
                        <li class="nav-item"><a @if(request()->routeIs('user.requirement.add')) class="nav-link active" @else class="nav-link" @endif href="{{route('user.requirement.add')}}">Add Requirement</a></li>
                        <li class="nav-item"><a @if(request()->routeIs('user.requirement.list')) class="nav-link active" @else class="nav-link" @endif  href="{{route('user.requirement.list')}}">My Requirements</a></li>
                    @endif






					<li class="nav-item notificationHeader" style="position: relative;">
						<a onclick="fetch(baseURL+'user/markedAllRead')" class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> <i class="fa fa-bell" style="display: inline-block;"></i> <span class="badge bg-danger">{{count(auth()->user()->unreadNotifications)}}</span></a>
						<ul class="dropdown-menu dropdown-menu-end">
                            @foreach (auth()->user()->notifications as $notification)
                                @switch($notification->data['type'])
                                    @case("InviteReceived")
                                        @if (\App\Models\Requirement::where('id',$notification->data['requirementId'])->count())
                                        <li><a class="dropdown-item" href="{{route('user.requirement.view',[$notification->data['requirementId']])}}">You have been invited to
                                            {{\App\Models\Requirement::where('id',$notification->data['requirementId'])->first()->title}}
                                        </a></li>
                                        @endif

                                        @break
                                    @case("QuoteReceived")
                                        @if (\App\Models\Requirement::where('id',$notification->data['requirementId'])->count())
                                        <li><a  class="dropdown-item" href="{{route('user.requirement.viewQuotes',[$notification->data['requirementId']])}}">{{\App\Models\Requirement::where('id',$notification->data['requirementId'])->first()->title}} has received a new Quote.
                                        </a></li>
                                        @endif

                                        @break
                                    @case("QuoteApproved")
                                        @if (\App\Models\Requirement::where('id',$notification->data['requirementId'])->count())
                                        <li><a href="{{route('user.requirement.view',[$notification->data['requirementId']])}}" class="dropdown-item">You Quote has been approved -
                                            {{\App\Models\Requirement::where('id',$notification->data['requirementId'])->first()->title}}
                                        </a></li>
                                        @endif

                                        @break
                                    @case("QuoteRejected")
                                        @if (\App\Models\Requirement::where('id',$notification->data['requirementId'])->count())
                                        <li><a class="dropdown-item" href="{{route('user.requirement.view',[$notification->data['requirementId']])}}">Your quote has been rejected -
                                            {{\App\Models\Requirement::where('id',$notification->data['requirementId'])->first()->title}}
                                        </a></li>
                                        @endif

                                        @break
                                        @case("QuoteUpdated")
                                            @php
                                                $quote = \App\Models\SupplierRequirement::where(['requirement_id'=>$notification->data['requirementId'],'user_id'=>$notification->data['supplierId']])
                                            @endphp
                                            @if ($quote->count())
                                            <li><a class="dropdown-item" href="{{route('user.requirement.viewQuote',[$quote->first()->id])}}">Your quote has been updated -
                                                {{\App\Models\Requirement::where('id',$notification->data['requirementId'])->first()->title}}
                                            </a></li>
                                            @endif

                                            @break

                                        @case("")
                                        <li><a class="dropdown-item" href="#"> {{$notification}}</a></li>
                                        @break
                                    @default
                                @endswitch
                            @endforeach

						</ul>
					</li>
					<li class="nav-item userNameHeader"><a @if(request()->routeIs('user.profile')) class="nav-link active" @else class="nav-link" @endif  href="{{route('user.profile')}}"><img src="{{asset('assets/images/userImg.png')}}" width="20">&nbsp;{{auth()->user()->name}}</a></li>
                    <li class="nav-item userNameHeader logoutBtn"><a class="nav-link" href="{{route('user.logout')}}">Logout</a></li>
				</ul>
			</div>
		</nav>

	</header>
    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif
	<!-- /Header -->

	@yield('content')


		{{-- <script src="assets/js/jquery-3.6.0.min.js"></script>
		<script src="assets/js/popper.min.js"></script> --}}
        {{-- <script src="{{asset('js/app.js')}}"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="{{asset('assets/dist/js/bootstrap.bundle.min.js')}}"></script>
        <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyArx9JBkqyCl5DoVTCTAH6bQGfA2lOzR0g"></script>
        <script>
            window.addEventListener('load',()=>{
                console.log('loaded');
                // Echo.channel('chat').listen('ChatEvent', (e)=>{
                //     console.log("New Message", e);
                // })
                // Echo.private('App.Model.User.{{auth()->user()->id}}').listen('ChatEvent', (e)=>{
                //     console.log("New Message", e);
                // })
            });
        </script>
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
	</script>
<div id="speed"></div>
<script>
  function showSpeed() {
    // var startTime = performance.timing.requestStart;
    // var endTime = performance.timing.responseEnd;
    // var duration = endTime - startTime;
    // var speedMbps = (8 / (duration / 1000)).toFixed(2);
    // document.getElementById("speed").innerHTML = "Your internet speed: " + speedMbps + " Mbps";
    var xhr = new XMLHttpRequest();
var startTime;
    xhr.onreadystatechange = function() {
    if (xhr.readyState === 1) {
        // request started
        startTime = performance.now();
    } else if (xhr.readyState === 4) {
        // request finished and response is ready
        var endTime = performance.now();
        var duration = endTime - startTime;
        var speedMbps = (8 / (duration / 1000)).toFixed(2);
        document.getElementById("speed").innerHTML = "Your internet speed: " + speedMbps + " Mbps";
    }
}

xhr.open("GET", "https://pickndeal.oidea.online/laravel_app/public/user/chat/f3786b3b-3e2e-43c3-b78d-e955ffe332be", true);
xhr.send();
setTimeout(function(){
    var endTime = performance.now();
    var duration = endTime - startTime;
    var speedMbps = (8 / (duration / 1000)).toFixed(2);
    document.getElementById("speed").innerHTML = "Your internet speed: " + speedMbps + " Mbps";
}, 10000);

  }
  //setInterval(showSpeed, 5000);

  




</script>


    </div>
 </body>
</html>
