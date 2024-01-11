<div class="supplierList">
    <div class="row">
        {{-- @dd($suppliers) --}}
        @if (!$requirements || !$requirements->count())
            <div class="alert alert-success">
                No match found for this search. Please modify search criteria!
            </div>

        @else
            @foreach ($requirements as $requirement)
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body cardRequirement">
                                <div class="imgAreacard">
                                    {{-- @dd($requirement->retailer) --}}
                                    <img src="{{asset($requirement->retailer->pic())}}" class="img-fluid rounded-start" alt="">
                                    {{-- <img src="assets/images/dummyImg.png" class="img-fluid rounded-start" alt=""> --}}
                                </div>
                                <h5 class="card-title"><a href="{{route('user.requirement.view',[$requirement->id])}}">{{$requirement->title}}</a></h5>
                                <p class="card-text mb-3 categoryArea">
                                    @php
                                        $requirement->cats()->pluck('title')->each(function($cat){echo "<span class='badge bg-".['success','warning','danger','info', 'primary'][array_rand(['success','warning','danger','info', 'primary'])]."'>".$cat."</span> &nbsp;"; });
                                        // echo $op;
                                    @endphp

                                    {{-- <span class="badge bg-success">text</span> --}}
                                </p>
                                <div class="row mt-3">
                                    <div class="col-md-10">
                                        <p class="card-text"><small class="text-muted">{{$requirement?->retailer->addresses?->address}}</small></p>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <a href="{{route('chat.rooms',[$requirement?->retailer->id,$requirement->id])}}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"><i class="fa fa-comments-o" aria-hidden="true" style="font-size: 22px;"></i></a>
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
