@extends('admin.layout.master')

@section('content')
    <div class="container">
        <a class="btn btn-sm rounded btn-secondary col-1 offset-2" href="{{ route('product#List') }}">Back</a>
        <div class="row">
            <div class="col-8 offset-2 card p-3 shadow-sm rounded">

                <form action="{{ route('product#Update') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="oldPhoto" value="{{ $products->image }}">
                    <input type="hidden" name="productId" value="{{ $products->id }}">

                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-center"><img class="img-profile mb-1 w-25" id="output"
                                    src="{{ asset('productImage/' . $products->image) }}"></div>
                            <input type="file" name="image" id="" class="form-control mt-1 "
                                onchange="loadFile(event)">

                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" placeholder="Enter Name..."
                                        value="{{ old('name', $products->name) }}">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Category Name</label>
                                    <select name="categoryId" id=""
                                        class="form-control @error('categoryId') is-invalid @enderror">
                                        @foreach ($category as $item)
                                            <option value="{{ $item->id }}"
                                                @if (old('categoryId', $products->category_id) == $item->id) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="text" name="price" value="{{ old('price', $products->price) }}"
                                        class="form-control @error('price') is-invalid @enderror"
                                        placeholder="Enter Email...">
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Stock</label>
                                    <input type="text" name="stock" value="{{ old('stock', $products->stock) }}"
                                        class="form-control @error('stock') is-invalid @enderror"
                                        placeholder="Enter Email...">
                                    @error('stock')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="" cols="30" rows="10"
                                class="form-control @error('description') is-invalid @enderror" placeholder="Enter Password...">{{ old('description', $products->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <input type="submit" value="Update Product" class=" btn btn-primary w-100 rounded shadow-sm">
                        </div>
                    </div>
                </form>


            </div>

        </div>
    </div>
@endsection
