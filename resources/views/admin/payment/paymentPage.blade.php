@extends('admin.layout.master')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Payment List</h1>
            <form action="{{ route('super#Payment', 'search') }}" method="get">

                <div class="input-group">
                    <input type="text" name="searchKey" value="" class=" form-control"
                        placeholder="Search Payment type...">
                    <button type="submit" class=" btn bg-dark text-white"> <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="">
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body shadow">
                            <form action="{{ route('payment#Create') }}" method="post" class="p-3 rounded">
                                @csrf

                                <input type="text" name="type" value="{{ old('type') }}"
                                    class=" form-control mb-3 @error('type') is-invalid @enderror"
                                    placeholder="Payment type">
                                @error('type')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror

                                <input type="text" name="accountName" value="{{ old('accountName') }}"
                                    class=" form-control mb-3 @error('accountName') is-invalid @enderror"
                                    placeholder="Account Name">
                                @error('accountName')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror

                                <input type="text" name="accountNumber" value="{{ old('accountNumber') }}"
                                    class=" form-control mb-3 @error('accountNumber') is-invalid @enderror"
                                    placeholder="Account Number">
                                @error('accountNumber')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                                <input type="submit" value="Create" class="btn btn-outline-primary mt-3">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col ">
                    <table class="table table-hover shadow-sm ">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>ID</th>
                                <th>Payment Type</th>
                                <th>Account Name</th>
                                <th>Account Number</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($payments as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->account_name }}</td>
                                    <td>{{ $item->account_number }}</td>
                                    <td>
                                        <a href="{{ route('payment#Edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="fa-solid fa-pen-to-square"></i> </a>
                                        <button onclick="deleteProcess({{ $item->id }})"
                                            class="btn btn-sm btn-outline-danger"> <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach



                        </tbody>
                    </table>

                    <span class=" d-flex justify-content-end">{{ $payments->links() }}</span>

                </div>
            </div>
        </div>

    </div>
@endsection

@section('js-script')
    <script>
        function deleteProcess($id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    });
                    location.href = '/paymentDelete/' + $id
                }
            });
        }
    </script>
@endsection
