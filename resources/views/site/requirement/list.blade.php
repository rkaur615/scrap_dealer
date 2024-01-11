@extends('layouts.page')
@section('title', 'Page Title')
@section('content')

    <!-- main form -->

    <section class="wrapperMain">
        <div class="container">
            <div class="headingSection">
                <h2>My Requirements</h2>
                {{-- @dd($products) --}}
                {{-- <div style="float: right;"><a href="{{route('user.catalog.add')}}" class="btn btn-primary">Add </a></div> --}}
            </div>
            <div class="containerArea requirementsList">
                <div class="table-responsive">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                {{-- <th>No.</th> --}}
                                {{-- <th>Photo</th> --}}
                                <th>Name</th>
                                <th>Expected Date</th>
                                <th>Status</th>
                                {{-- <th>Sub category</th> --}}
                                {{-- <th>Weight/Qty.</th>
                                <th>Unit (per item)</th>
                                <th>Price</th> --}}
                                <th width="240"><div style="width: 300px;">Action</div></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($requirements as $requirement)
                                <tr>
                                    {{-- <td>{{$requirement->id}}.</td> --}}
                                    {{-- <td><img src="assets/images/dummyImg.png" width="40"></td> --}}
                                    <td>{{$requirement->title}}</td>
                                    <td>{{$requirement->expected_date}}</td>
                                    <td>{{$requirement->status?'Open':'Closed'}}</td>
                                    {{-- <td>Sub category 1</td> --}}
                                    {{-- <td>{{$product->quantity}}</td>
                                    <td>{{$product->unit}}</td>
                                    <td>{{$product->price}}</td> --}}
                                    <td>
                                        <a href="{{route('user.requirement.edit',[$requirement->id])}}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;
                                        <a href="{{route('user.invite.send',[$requirement->id])}}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Invite"><i class="fa fa-envelope"></i></a>&nbsp;
                                        <a href="{{route('user.requirement.viewQuotes',[$requirement->id])}}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Quotes"><i class="fa fa-file-text-o" aria-hidden="true"></i> View Quotes By Suppliers</a>&nbsp;
                                        <a class="btn btn-warning btn-sm showItems" onClick="tableToggle('table_{{$requirement->id}}')" >View Quotes By Items</a>&nbsp;
                                        @if($requirement->status)<a href="{{route('user.requirement.close',[$requirement->id])}}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Close Requirement"><i class="fa fa-solid fa-power-off"></i></a> @else <a class="btn btn-warning btn-sm" href="{{route('requirement.show.invoice',[$requirement->id])}}" >Invoice</a> @endif
                                        &nbsp;<a href="{{route('user.requirement.delete',[$requirement->id])}}" onclick="return confirm('Are you sure, you want to delete this requirement?');" class="btn btn-danger btn-sm"  data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <tr >
                                    <td colspan="4">
                                        <div  id="table_{{$requirement->id}}" class="d-none">
                                            @foreach ($requirement->qitems as $qitem)
                                                {{-- @dd($qitem->quotes) --}}
                                                <table class="table">
                                                    <tr class="bg-primary"> <th colspan="4" class="text-white"> {{$qitem->title}} ({{$qitem->quantity}} {{$qitem->unit}})</th> </tr>
                                                <tr>

                                                    <th>
                                                        <span><storng>Supplier</storng></span>
                                                    </th>
                                                    <th>
                                                        <span ><storng>Quote Amount</storng> </span>
                                                    </th>
                                                    <th ><storng>Status</storng></th>
                                                    <th ><storng>Actions</storng></th>

                                                </tr>




                                                    @foreach ($qitem->quotes as $quote)


                                                                <tr>

                                                                    <td>
                                                                         {{$quote->supplier_requirement->supplier->name}}
                                                                    </td>

                                                                    <td>
                                                                          {{
                                                                            $settings->filter(function($item){return $item->title=='currency_symbol'; })->first()->value}} {{$quote->quote_amount}}
                                                                    </td>
                                                                    <td>
                                                                        @isset($quote->status)
                                                                        <span id="status_{{$quote->id}}"> {{ match($quote->status){-1=>'Invalid', 0=>'Pending', 1=>'Approved', 2=>'Rejected', 3=>'Revised'} }}</span>
                                                                        @endisset

                                                                   </td>
                                                                   <td>
                                                                    <div id="btns_{{$quote->id}}" @if(in_array($quote->status ,[1,2] )) class="d-none" @endif>
                                                                        <a class="btn btn-sm btn-success" onClick="acceptQuote({{$quote->id}})">Approve</a>&nbsp;
                                                                        <a class="btn btn-sm btn-danger" onClick="rejectQuote({{$quote->id}})">Reject</a>
                                                                        &nbsp;

                                                                    </div>
                                                                    <a href="{{route('chat.rooms',[$quote->user_id,$requirement->id])}}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"><i class="fa fa-comments-o"></i></a>
                                                                    </td>
                                                                </tr>

                                                        {{-- @foreach($quote->quote as $quotedItem)

                                                            {{json_encode($quotedItem)}}
                                                            @if(isset($quotedItem->quote_amount) && $quotedItem->quote_amount > 0 )

                                                                @dump($quote->supplier->name)
                                                                @dump($quotedItem)
                                                            @endif
                                                        @endforeach --}}
                                                    @endforeach
                                                    </table>
                                                    <hr />

                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach






                        </tbody>
                    </table>
                </div>



            </div>
        </div>
    </section>

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
<script>
// const baseURL = 'https://pickndeal.oidea.online/laravel_app/public/';
function tableToggle(tid){
    if($("#"+tid).hasClass('d-none')){
        $("#"+tid).removeClass('d-none')
    }
    else{
        $("#"+tid).addClass('d-none')
    }
}

async function  acceptQuote(sqid){

    let response = await fetch(baseURL+"user/acceptItemQuote/"+sqid)
    let resData = await response.json();
    console.log(resData)
    if(resData.type=="success"){
            //UPDATE STATUS AND ACTION BUTTONS
            $("#status_"+sqid).text('Approved');
            $("#btns_"+sqid).addClass('d-none');
        }
}

async function  rejectQuote(sqid){
    if(confirm("Are you sure?")){
        let response = await fetch(baseURL+"user/rejectItemQuote/"+sqid)
        let resData = await response.json();
        console.log(resData)
        if(resData.type=="success"){
            //UPDATE STATUS AND ACTION BUTTONS
            $("#status_"+sqid).text('Rejected');
            $("#btns_"+sqid).addClass('d-none');
        }
    }
}
</script>
@endsection
