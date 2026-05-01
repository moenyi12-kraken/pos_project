@extends('user.layout.master')

@section('content')
    <div class="container " style="margin-top: 150px">
        <div class="row">
            <div class="card col-12 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <h5 class="mb-4">Payment methods</h5>


                            @foreach ($payments as $item)
                                <div class="">
                                    <b>{{ $item->type }}</b> ( Name : {{ $item->account_name }})
                                </div>

                                Account : {{ $item->account_number }}

                                <hr>
                            @endforeach

                        </div>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    Payment Info
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <form action="{{ route('user#order') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <input type="text" name="name" id="" readonly
                                                        value="{{ Auth::user()->name != null ? Auth::user()->name : Auth::user()->nickname }}"
                                                        class="form-control ">
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="phone" id=""
                                                        value="{{ old('phone') }}"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        placeholder="09xxxxxxxx">
                                                    @error('phone')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col mt-4">
                                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="" cols="30"
                                                    rows="8" placeholder="Address">{{ old('address') }}</textarea>
                                                @error('address')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <select name="paymentType" id=""
                                                        class=" form-select @error('paymentType') is-invalid @enderror">
                                                        <option value="">Choose Payment methods...</option>
                                                        @foreach ($payments as $item)
                                                            <option @if (old('paymentType') == $item->type) selected @endif
                                                                value="{{ $item->type }}">{{ $item->type }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('paymentType')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col">
                                                    <input type="file" name="payslipImage" id=""
                                                        class="form-control @error('payslipImage') is-invalid @enderror">
                                                    @error('payslipImage')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col">
                                                    <input type="hidden" name="orderCode"
                                                        value="{{ $tempCart[0]['order_code'] }}">
                                                    Order Code : <span
                                                        class="text-secondary fw-bold">{{ $tempCart[0]['order_code'] }}</span>
                                                </div>
                                                <div class="col">
                                                    <input type="hidden" name="totalAmount"
                                                        value="{{ $tempCart[0]['finalTotal'] }}">
                                                    Total amt : <span class=" fw-bold">{{ $tempCart[0]['finalTotal'] }}
                                                        mmk</span>
                                                </div>
                                            </div>

                                            <div class="row mt-4 mx-2">
                                                <button type="submit" class="btn btn-outline-success w-100"><i
                                                        class="fa-solid fa-cart-shopping me-3"></i> Order
                                                    Now...</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
