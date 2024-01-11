@extends('layouts.page')
@section('title', 'Page Title')
@section('content')

    <!-- main form -->

    <section class="wrapperMain">
        <div class="container">
            <div class="headingSection">
                <h2>My Catalog</h2>
                {{-- @dd($products) --}}
                <div style="float: right;"><a href="{{route('user.catalog.add')}}" class="btn btn-primary">Add Product</a></div>
            </div>
            <div class="containerArea">
                <div class="table-responsive">
                    @if (!$products || ($products && !$products->count()))
                        <div class="alert alert-success">
                            You have not added any product in your catalog, click on add product button to add new Product!
                        </div>
                    @else


                    <table class="table table-striped">
                        <thead>
                            <tr>
                                {{-- <th>No.</th> --}}
                                {{-- <th>Photo</th> --}}
                                <th>Name</th>
                                <th>Category</th>
                                {{-- <th>Sub category</th> --}}
                                <th>Weight/Qty.</th>
                                <th>Unit (per item)</th>
                                <th>Price</th>
                                <th width="140">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    {{-- <td>1.</td> --}}
                                    {{-- <td><img src="assets/images/dummyImg.png" width="40"></td> --}}
                                    <td>{{$product->title->title}}</td>
                                    <td>{{$product->cat->title}}</td>
                                    {{-- <td>Sub category 1</td> --}}
                                    <td>{{$product->quantity}}</td>
                                    <td>{{$product->unit}}</td>
                                    <td>{{$product->price}}</td>
                                    <td>
                                        <a href="{{route('user.catalog.edit',[$product->id])}}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                        &nbsp;<a href="#" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
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

<script src="{{ asset('js/app.js') }}"></script>
@endsection
