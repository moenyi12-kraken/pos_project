@extends('admin.layout.master')

@section('content')
    <div class="container">
        <div class=" d-flex justify-content-between my-2">
            <h3 class="">Sale Information</h3>
            <div class="">
                <form action="" method="get">

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
                <table classorder="table table-hover shadow-sm ">
                    <thead class=" text-dark border">
                        <tr>
                            <th class="col-3 ">Date</th>
                            <th class="col-3 ">Order Code</th>
                            <th class="col-3 ">User Name</th>
                            <th class="col-3 ">Action</th>
                            <th class="col-3 "></th>
                        </tr>
                    </thead>
                    <tbody class='border-top-0 border-left-0 border-right-0 border'>

                        @foreach ($saleInfoLists as $item)
                            <tr>

                                <td class="ps-2">{{ $item->created_at->format('j-F-y') }}</td>
                                <td class="ps-2">{{ $item->order_code }}
                                </td>
                                <td class="ps-2">{{ $item->name != null ? $item->name : $item->nickname }}</td>
                                <td class="ps-2">
                                    Access
                                </td>
                                <td class="ps-2">
                                    <i class="fa-regular fa-circle-check me-3 text-success"></i>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>
                @if (count($saleInfoLists) == 0)
                    <h4 class="text-center mt-3 pb-3 border-top-0 border-left-0 border-right-0 border">There is no
                        information
                    </h4>
                @endif

            </div>
        </div>
    </div>
@endsection
