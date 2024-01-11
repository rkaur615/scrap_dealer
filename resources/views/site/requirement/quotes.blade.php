@extends('layouts.page')
@section('title', 'Page Title')
@section('content')

    <!-- main form -->

    <section class="wrapperMain">
        <div class="container">
            <div class="headingSection">
                <h2>{{$requirement->title}}</h2>
                {{-- @dd($products) --}}
                {{-- <div style="float: right;"><a href="{{route('user.catalog.add')}}" class="btn btn-primary">Add </a></div> --}}
            </div>
            <div class="containerArea">
                <div class="table-responsive">
                    @if (!$requirement->quotes || ($requirement->quotes && !$requirement->quotes->count()))
                        <div class="alert alert-success">
                            No Quotes Received on this Requirement yet!
                        </div>
                    @else
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                {{-- <th>Photo</th> --}}
                                <th>Total Amount</th>
                                <th>status</th>
                                {{-- <th>Expected Date</th> --}}
                                {{-- <th>Sub category</th> --}}
                                {{-- <th>Weight/Qty.</th>
                                <th>Unit (per item)</th>
                                <th>Price</th> --}}
                                <th width="240">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- @dd($requirement->quotes) --}}
                            @foreach ($requirement->quotes as $quote)
                                <tr>
                                    <td>{{$quote->supplier->name}}</td>

                                    {{-- <td><img src="assets/images/dummyImg.png" width="40"></td> --}}
                                    <td>

                                        {{
                                            $settings->filter(function($item){return $item->title=='currency_symbol'; })->first()->value}}
                                            {{
                                            collect($quote->quote)->reduce(function($carry, $item){

                                                if(isset($item->quote_amount)){ return $carry + $item->quote_amount; } else{ return $carry ;}
                                            }, 0)}}
                                     {{-- {{['value']}} --}}
                                     {{-- {{$settings->filter(function($item){return $item->title=='currency_symbol'; })->first()->value}}{{collect($quote->quote)->reduce(function($carry, $item){
                                        return isset($item->quote_amount)?$carry + $item->quote_amount:0;
                                    }, 0)}} --}}
                                    </td>
                                    <td>
                                        @switch($quote->qstatus())
                                            @case('quoteSent')
                                                Quote Received
                                                @break

                                            @case('approved')
                                                Quote Approved
                                                @break

                                            @case('pending')
                                                Pending
                                                @break

                                            @case('completed')
                                                Completed
                                                @break

                                            @case('rejected')
                                                Rejected
                                                @break

                                            @default

                                        @endswitch
                                        </td>
                                    {{-- <td>{{$requirement->expected_date}}</td> --}}
                                    {{-- <td>Sub category 1</td> --}}
                                    {{-- <td>{{$product->quantity}}</td>
                                    <td>{{$product->unit}}</td>
                                    <td>{{$product->price}}</td> --}}
                                    <td>
                                        {{-- <a href="{{route('user.requirement.edit',[$requirement->id])}}" class="btn btn-primary btn-sm">Edit</a>&nbsp;
                                        <a href="{{route('user.invite.send',[$requirement->id])}}" class="btn btn-warning btn-sm">Invite</a>&nbsp; --}}
                                        <a href="{{route('user.requirement.viewQuote',[$quote->id])}}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View"><i class="fa fa-eye"></i></a>&nbsp;
                                        {{-- @if ($quote->status==4)
                                        <a href="{{route('user.requirement.completeQuote',[$quote->id])}}" class="btn btn-sm btn-pick" data-bs-toggle="tooltip" data-bs-placement="top" title="Complete"><i class="fa fa-check"></i></a>&nbsp;
                                        @endif --}}

                                        @if ($quote->requirement->status==0)
                                        <a id="rating_btn" onclick="updateRequirementId({{$requirement->id}},{{$quote->user_id}})" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Feedback"><i class="fa fa-commenting-o"></i></a>&nbsp;
                                        @endif
                                        <a href="{{route('chat.rooms',[$quote->user_id,$requirement->id])}}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"><i class="fa fa-comments-o"></i></a>&nbsp;

                                    </td>
                                </tr>
                            @endforeach






                        </tbody>
                    </table>
                    @endif
                </div>



            </div>
        </div>
    </section>
<!-- Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Your Feedback</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<div class="row">
				<div class="col-3">
					<img src="assets/images/user2.jpg" id="upic" style="max-width: 75px;">
				</div>
				<div class="col-9">
					<h3><strong id="uname"></strong></h3>
				</div>
			</div>

			<div class="mb-3 mt-3">
                <input type="hidden" name="rating_id" id="rating_id" value="0"/>
                <input type="hidden" name="supplier_id" id="supplier_id"/>
                <input type="hidden" name="requirement_id" id="requirement_id"/>
                <input type="hidden" name="rating" id="rating"/>
				<label for="" class="form-label" style="margin-bottom: 0px;"><strong>Please Rate</strong></label>
				<!-- <jsuites-rating value="0" tooltip="Ugly, Bad, Average, Good, Outstanding"></jsuites-rating>
  -->
                <div id='ratingS'></div>
                <div id='console'></div>
			</div>
			<div class="mb-3 mt-3">
				<label for="" class="form-label" style="margin-bottom: 0px;"><strong>Your Feedback</strong></label>
				<div>
					<textarea id="feedback" name="feedback" class="form-control" rows="4" style="border: 1px solid #ccc;"></textarea>
				</div>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<a class="btn btn-primary" id="submitRating">Submit</a>
		  </div>
		</div>
	  </div>
	</div>
    <script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
    <script src="https://jsuites.net/v4/jsuites.js"></script>

<script src="https://jsuites.net/v4/jsuites.webcomponents.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />



<script>
// document.querySelector('jsuites-rating').addEventListener('onchange', function(e) {
//     document.getElementById('console').innerHTML = 'New value: ' + this.value;
//     $("#rating").val(this.value);
// });

</script>

<script>
    var myRating = jSuites.rating(document.getElementById('ratingS'), {
    value: 0,
    tooltip: [ 'Very bad', 'Bad', 'Average', 'Good', 'Very good' ],
});
async function updateRequirementId(id, sid){
    console.log("req.", id)
    $("#requirement_id").val(id);
    $("#supplier_id").val(sid);
    let response = await fetch(baseURL+"user/details/"+sid+"/"+id)
    let res = {};
    try {
        res = await response.json();
  } catch(e) {
    console.log('error:', e.message);
  }
    console.log("here is PIC URL", `${baseURL}${res.data.pic}`);

    $("#upic").attr("src",`${baseURL}${res.data.pic}`)
    $("#uname").html(res.data.name)
    $("#upic").attr('src',res.data.pic)
    if(res.data.feedback.length){
        $("#requirement_id").val(res.data.feedback[0].requirement_id);
        $("#rating_id").val(res.data.feedback[0].id);
        myRating.setValue(res.data.feedback[0].rating);
        $("#supplier_id").val(res.data.feedback[0].supplier_id);
        $("#rating").val(res.data.feedback[0].rating);
        $("#feedback").val(res.data.feedback[0].feedback);
    }

    $("#feedbackModal").modal("show")
}
$("#submitRating").on('click', function(){
    console.log('baseURL',baseURL);
    let dataToBePushed = {};

    dataToBePushed.rating_id =  $("#rating_id").val();
    dataToBePushed.requirement_id =  $("#requirement_id").val();
    dataToBePushed.supplier_id =  $("#supplier_id").val();
    dataToBePushed.rating =  myRating.getValue();
    dataToBePushed.feedback =  $("#feedback").val();
    dataToBePushed.__token =  $("#feedback").val();

    (async () => {
        const rawResponse = await fetch(baseURL+'user/requirement/saveRating', {
            method: 'POST',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
            },
            body: JSON.stringify(dataToBePushed)
        });
        const content = await rawResponse.json();
        location.reload();
        console.log(content);
        })();
});
</script>
@endsection
