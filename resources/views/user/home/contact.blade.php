@extends('user.layout.master')
@section('content')
    <div class="container mt-5 pt-5">
        <div class="row mt-5">
            <div class="col-6 offset-3 card shadow">
                <div class="card-body p-4">
                    <h1 class=" display-6 mb-3">Contact Us</h1>
                    <form action="{{ route('user#ContactMessage') }}" method="POST">
                        @csrf
                        <div class=" d-flex">
                            <span class="pt-2 me-2">Name:</span>
                            <input type="text" name="name" id="" class="form-control w-50" disabled
                                placeholder="{{ Auth::user()->name != null ? Auth::user()->name : Auth::user()->nickname }}">
                        </div>
                        <div class="mt-2 d-flex">
                            <span class="pt-2 pe-1 me-2">Email:</span>
                            <input type="email" name="email" id=""
                                class="form-control w-50 @error('email') is-invalid  @enderror"
                                placeholder="example123@gmail.com" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <div class="text-danger ms-5 ps-2">{{ $message }}</div>
                        @enderror
                        <div class="mt-2 d-flex">
                            <span class="pt-2 me-2">Title:</span>
                            <input type="text" name="title" id=""
                                class="form-control @error('title') is-invalid  @enderror" placeholder="Title"
                                value="{{ old('title') }}">
                        </div>
                        @error('title')
                            <span class="text-danger ms-5">{{ $message }}</span>
                        @enderror
                        <div class="mt-2">
                            <textarea name="message" id="" cols="30" rows="10"
                                class="form-control @error('message') is-invalid  @enderror" placeholder="Write message">{{ old('message') }}</textarea>
                        </div>
                        @error('message')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="row mt-3">
                            <input type="submit" value="Sent"
                                class="btn btn-sm rounded bg-dark text-white col-4 offset-4">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
