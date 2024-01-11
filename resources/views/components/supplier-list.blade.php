<div class="supplierList">
    <div class="row">
        {{-- @dd($suppliers) --}}
        @if (!$suppliers || !$suppliers->count())
            <div class="alert alert-success">
                No match found for this search. Please modify search criteria!
            </div>

        @else
        @foreach ($suppliers as $supplier)
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body cardRequirement">
                        <div class="imgAreacard">
                            <img src="{{asset($supplier->pic())}}" class="img-fluid rounded-start" alt="">
                            </div>
                            <h5 class="card-title"><a href="{{route('user.profile.public',[$supplier->id])}}">{{$supplier->name}}</a></h5>
                            <p class="card-text mb-3 categoryArea">
                                @php
                                    $supplier->categories->pluck('category.title')->each(function($cat){echo "<span class='badge bg-".['success','warning','danger','info', 'primary'][array_rand(['success','warning','danger','info', 'primary'])]."'>".$cat."</span> &nbsp;"; });
                                    // echo $op;
                                @endphp

                                {{-- <span class="badge bg-success">text</span> --}}
                            </p>
                            <div class="row" style="font-size: 12px;">
                                <div class="col-md-6" style="padding-right: 0px;">
                                    Delivery Radius <strong>{{$supplier?->addresses?->radius}}</strong> KM
                                </div>
                                <div class="col-md-6" style="padding-right: 0px;">
                                    Distance Less Than <strong>{{ceil((round($supplier->distance($myAddress['latitude'],$myAddress['longitude'])->first()?->distance,2))/10)*10}}</strong> KM
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-8">
                                    {{-- @dump(request()->has('search')) --}}


                                    <p class="card-text" style="height: 56px;font-size: 16px;line-height: 18px;"><small class="text-muted">{{$supplier?->addresses?->address}}

                                        {{-- {{$supplier?->addresses?->latitude}} - {{$supplier?->addresses?->longitude}} --}}
                                    @if (request()->has('search'))
                                    @endif </small></p>
                                </div>
                                <div class="col-md-4 text-end">
                                    @if ($showInvite)
                                        {{-- {{ session()->get('send_invite')}} supplierRequirements--}}
                                        @if (!in_array(session()->get('send_invite'),$supplier->supplierRequirements->pluck('requirement_id')->toArray()))
                                            <a class="btn btn-sm btn-success" onClick="sendInviteRequest(this);" hrefs="{{route('user.invite.sendSelected',[$supplier->id])}}"data-bs-toggle="tooltip" data-bs-placement="top" title="Send Invite"><i class="fa fa-envelope"></i></a>&nbsp;
                                        @else
                                            <a class="btn btn-sm btn-success disabled" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="Invite Sent"><i class="fa fa-envelope" style="font-size: 22px;"></i></a>&nbsp;
                                        @endif

                                    @endif


                                    <a href="{{route('chat.rooms',[$supplier->id, 0])}}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"><i class="fa fa-comments-o" aria-hidden="true" style="font-size: 22px;"></i></a>
                                </div>
                            </div>


                    </div>

                </div>
            </div>
        @endforeach
        @endif
        <script>
        function sendInviteRequest(event){
            if(!event.getAttribute('disabled')){
                location.href = event.getAttribute('hrefs');
            }
            event.setAttribute('disabled', true);
        }
        </script>
    </div>
</div>
