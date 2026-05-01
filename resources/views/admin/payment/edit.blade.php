@extends('admin.layout.master')

@section('content')
    <div class="d-flex justify-content-start col-4 offset-4">
        <a href="{{ route('super#Payment') }}" class=" btn btn-sm btn-dark rounded">Back</a>
    </div>
    <div class="row">
        <div class="col-4 offset-4">
            <div class="card">
                <div class="card-body shadow">
                    <form action="{{ route('payment#Update') }}" method="post" class="p-3 rounded">
                        @csrf
                        <input type="hidden" name='paymentId' value="{{ $payments->id }}">
                        <input type="text" name="type" value="{{ old('type', $payments->type) }}"
                            class="mb-3 form-control @error('type') is-invalid @enderror ">
                        @error('type')
                            <small class="invalid-feedback">{{ $message }}</small>
                        @enderror

                        <input type="text" name="accountName" value="{{ old('accountName', $payments->account_name) }}"
                            class="mb-3 form-control @error('accountName') is-invalid @enderror "
                            placeholder="Account Name">
                        @error('accountName')
                            <small class="invalid-feedback">{{ $message }}</small>
                        @enderror

                        <input type="text" name="accountNumber"
                            value="{{ old('accountNumber', $payments->account_number) }}"
                            class="mb-3 form-control @error('accountNumber') is-invalid @enderror "
                            placeholder="Account Number">
                        @error('accountNumber')
                            <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                        <input type="submit" value="Update" class="btn btn-outline-primary mt-3">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
