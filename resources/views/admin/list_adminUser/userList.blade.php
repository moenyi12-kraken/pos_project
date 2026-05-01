@extends('admin.layout.master')

@section('content')

    <div class="container">
        <div class=" d-flex justify-content-between my-2">
            <a href="{{ route('admin#List') }}"> <button class=" btn btn-sm btn-secondary  "> Admin List</button> </a>
            <div class="">
                <form action="{{ route('user#List') }}" method="get">

                    <div class="input-group">
                        <input type="text" name="searchKey" value="" class=" form-control"
                            placeholder="Enter Search Key...">
                        <button type="submit" class=" btn bg-dark text-white"> <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-hover shadow-sm text-center">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Created Date</th>
                            <th> Platform</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($users) != 0)
                            @foreach ($users as $item)
                                <tr>
                                    <td><img class="w-25 rounded"
                                            src="{{ asset($item->profile == null ? 'default/no_photo_image.jpg' : 'profile/' . $item->profile) }}">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{!! $item->address == null ? "<span style='color:red;opacity:0.6'>...</span>" : $item->address !!}
                                    </td>
                                    <td>{!! $item->phone == null ? "<span style='color:red;opacity:0.6'>...</span>" : $item->phone !!}</td>
                                    <td><span
                                            class="btn btn-sm bg-danger text-white rounded shadow-sm">{{ $item->role }}</span>
                                    </td>

                                    <td>{{ $item->created_at->format('j-F-y') }}</td>
                                    <td>
                                        @if ($item->provider == 'google')
                                            <i class="fa-brands fa-google"></i>
                                        @endif
                                        @if ($item->provider == 'github')
                                            <i class="fa-brands fa-github"></i>
                                        @endif
                                        @if ($item->provider == 'simple')
                                            <i class="fa-solid fa-user"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger"
                                            onclick="deleteProcess({{ $item->id }})"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                @if (count($users) == 0)
                    <div class="text-muted text-center fs-3 border-bottom rounded pt-3 pb-3">
                        <h5>There is no data...</h5>
                    </div>
                @endif

                <span class=" d-flex justify-content-end">{{ $users->links() }}</span>

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
                    location.href = '/admin/profile/deleteAdmin/' + $id
                }
            });
        }
    </script>
@endsection
