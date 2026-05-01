@extends('admin.layout.master')

@section('content')
    <div class="d-flex justify-content-start col-4 offset-4">
        <a href="{{ route('admin#Category') }}" class=" btn btn-sm btn-dark rounded">Back</a>
    </div>
    <div class="row">
        <div class="col-4 offset-4">
            <div class="card">
                <div class="card-body shadow">
                    <form action="{{ route('category#Update', $categories->id) }}" method="post" class="p-3 rounded">
                        @csrf
                        <input type="text" name="categoryName" value="{{ old('categoryName', $categories->name) }}"
                            class=" form-control @error('categoryName') is-invalid @enderror "
                            placeholder="Category Name...">
                        @error('categoryName')
                            <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                        <input type="submit" value="Update" class="btn btn-outline-primary mt-3">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
