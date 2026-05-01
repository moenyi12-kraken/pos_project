@extends('admin.layout.master')

@section('content')
    <div class="container">
        <a class="btn btn-sm rounded btn-secondary col-1 offset-2" href="{{ route('product#List') }}">Back</a>
        <div class="row">
            <div class="col-8 offset-2 card p-3 shadow-sm rounded">
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <img class="img-profile mb-1 w-25 img-thumbnail" id="output"
                            src="{{ asset('productImage/' . $products->image) }}">
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">Name:</label>
                                <div class="col-9 border">
                                    {{ $products->name }}
                                </div>

                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">Category Name:</label>
                                <div class="col-9 border">
                                    {{ $products->category_name }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">Price:</label>
                                <div class="col-9 border">
                                    {{ $products->price }}
                                </div>

                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">Stock:</label>
                                <div class="col-9 border">
                                    {{ $products->stock }}
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="form-label">Description:</div>
                        <div class="border">
                            <div class="d-flex justify-content-start ml-3">
                                <div class="ms-5">{{ $products->description }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
