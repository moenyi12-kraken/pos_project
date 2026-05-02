@extends('admin.layout.master')

@section('content')
    <div class="container">
        <div class=" d-flex justify-content-between my-2">
            <div class=""></div>
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

        <div class="alert alert-warning alert-dismissible fade show w-50" role="alert">
            <strong><i class="fa-solid fa-triangle-exclamation"></i></strong> You can click Order Code to check item.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="row">
            <div class="col">
                <table classorder="table table-hover shadow-sm ">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="col-3 ">Date</th>
                            <th class="col-3 ">Order Code</th>
                            <th class="col-3 ">User Name</th>
                            <th class="col-3 ">Action</th>
                            <th class="col-3 "></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($orderLists as $item)
                            <tr>

                                <td class="ps-2">{{ $item->created_at->format('j-F-y') }}</td>
                                <td class="ps-2"><a class="orderCode"
                                        href="{{ route('admin#OrderListDetail', $item->order_code) }}">{{ $item->order_code }}</a>
                                    @if ($item->count > $item->stock && $item->status != 1)
                                        <small class="text-danger">(Out of Stock)</small>
                                    @endif
                                </td>
                                <td class="ps-2">{{ $item->name != null ? $item->name : $item->nickname }}</td>
                                <td class="ps-2">
                                    <select name="status" class="status">
                                        <option value="0" @if ($item->status == 0) selected @endif>Pending
                                        </option>
                                        @if ($item->count <= $item->stock)
                                            <option value="1" @if ($item->status == 1) selected @endif>Confirm
                                            </option>
                                        @endif
                                        @if ($item->status == 1)
                                            <option value="1" @if ($item->status == 1) selected @endif>Confirm
                                            </option>
                                        @endif
                                        <option value="2" @if ($item->status == 2) selected @endif>Reject
                                        </option>
                                    </select>
                                </td>
                                <td class="ps-2">
                                    @if ($item->status == 0)
                                        <i class="fa-regular fa-clock me-3 text-warning"></i>
                                    @endif
                                    @if ($item->status == 1)
                                        <i class="fa-regular fa-circle-check me-3 text-success"></i>
                                    @endif
                                    @if ($item->status == 2)
                                        <i class="fa-regular fa-circle-xmark me-3 text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>


            </div>
        </div>
    </div>
@endsection

@section('js-script')
    <script>
        $(document).ready(function() {
            $('.status').change(function() {
                status = $(this).val();
                parentNode = $(this).parents('tr');
                orderCode = parentNode.find('.orderCode').text();

                $.ajax({
                    type: 'get',
                    url: '/order/statusChange',
                    data: {
                        'status': status,
                        'orderCode': orderCode
                    },
                    dataType: 'json',
                    success: function(res) {
                        res.status == 'success' ? location.reload() : ''
                    }
                })
            })
        })
    </script>
@endsection
