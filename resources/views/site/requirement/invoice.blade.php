@extends('layouts.page')
@section('title', 'Page Title')
@section('content')


<section class="wrapperMain">
    <div class="container">
        <div class="headingSection">
            <h2>Invoice - {{$requirement->title}}</h2>
            {{-- @dd($products) --}}
            {{-- <div style="float: right;"><a href="{{route('user.catalog.add')}}" class="btn btn-primary">Add </a></div> --}}
        </div>
        <div class="containerArea">
            <div class="col-xs-12">

                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <address>

                            <strong>{{auth()->user()->addresses->name}}</strong><br>
                            {{auth()->user()->addresses->address}}<br>
                            {{auth()->user()->phone_number}}<br>
                                <!-- Apt. 4B<br>
                                Springfield, ST 54321 -->
                            </address>

                    </div>
                    <div class="col-md-6 text-end">
                        {{-- <address>
                            <strong>{{$requirement->retailer->addresses->name}}:</strong><br>
                            {{$requirement->retailer->addresses->address}}<br>
                            {{$requirement->retailer->phone_number}}<br>
                                <strong>Expected Delivery Date: </strong>{{$requirement->expected_date}}<br>

                            </address> --}}
                    </div>
                </div>
                <div class="table-responsive">


                    @foreach ($requirement->qitems as $qitem)
                    {{-- @dd($qitem->quotes) --}}
                    <table class="table">
                        <tr class="bg-primary"> <th colspan="4" class="text-white"> <strong> {{$qitem->title}} ({{$qitem->quantity}} {{$qitem->unit}})</strong></th> </tr>
                        <tr>

                            <th>
                                <span >Supplier </span>
                            </th>
                            <th>
                                <span >Quote Amount </span>
                            </th>
                            <th >Status</th>

                        </tr>




                            @foreach ($qitem->quotes->filter(function($itm){ return $itm->status==1;}) as $quote)


                                        <tr>

                                            <td>
                                                <strong> {{$quote->supplier_requirement->supplier->name}}</strong>
                                            </td>

                                            <td>
                                                <strong> {{
                                                    $settings->filter(function($item){return $item->title=='currency_symbol'; })->first()->value}} {{$quote->quote_amount}}</strong>
                                            </td>
                                            <td>
                                                @isset($quote->status)
                                                <strong id="status_{{$quote->id}}"> {{ match($quote->status){-1=>'Invalid', 0=>'Pending', 1=>'Approved', 2=>'Rejected', 3=>'Revised'} }}</strong>
                                                @endisset

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
                    {{-- @dd($requirement->qitems) --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
