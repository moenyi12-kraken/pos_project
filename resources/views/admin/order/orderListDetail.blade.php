@extends('admin.layout.master')

@section('content')
    <div class="container-fluid">
        <a href="{{ route('admin#OrderBoard') }}" class=" text-black m-3"> <i class="fa-solid fa-arrow-left-long"></i> Back</a>

        <!-- DataTales Example -->


        <div class="row">
            <div class="card col-5 shadow-sm m-4 col">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-5">Name :</div>
                        <div class="col-7">{{ $info[0]->name != 0 ? $info[0]->name : $info[0]->nickname }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5">Phone :</div>
                        <div class="col-7">
                            {{ $info[0]->phone }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5">Addr :</div>
                        <div class="col-7">
                            {{ $info[0]->address != null ? $info[0]->address : '...' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5">Order Code :</div>
                        <div class="col-7" id="orderCode">{{ $info[0]->order_code }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5">Order Date :</div>
                        <div class="col-7">{{ $info[0]->order_date }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5">Total Price :</div>
                        <div class="col-7">
                            {{ $info[0]->total_amt }} mmk<br>
                            <small class=" text-danger ms-1">( Contain Delivery Charges )</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card col-5 shadow-sm m-4 col">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-5">Contact Phone :</div>
                        <div class="col-7">{{ $info[0]->pay_phone }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5">Address :</div>
                        <div class="col-7">{{ $info[0]->pay_address }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5">Payment Method :</div>
                        <div class="col-7">{{ $info[0]->payment_method }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5">Purchase Date :</div>
                        <div class="col-7">{{ $info[0]->purcase_date }}</div>
                    </div>
                    <div class="row mb-3">
                        <img style="width: 150px" src="{{ asset('payslipImage/' . $info[0]->payslip_image) }}"
                            class=" img-thumbnail">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-flex justify-content-between">
                    <div class="">
                        <h6 class="m-0 font-weight-bold text-primary">Order Board</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover shadow-sm " id="productTable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="col-2">Image</th>
                                <th>Name</th>
                                <th>Order Count</th>
                                <th>Available Stock</th>
                                <th>Product Price (each)</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($orderProduct as $item)
                                <tr>
                                    <input type="hidden" class="productId" value="{{ $item->product_id }}">
                                    <input type="hidden" class="productOrderCount" value="{{ $item->count }}">

                                    <td>
                                        <img src="{{ asset('productImage/' . $item->image) }}" class=" w-50 img-thumbnail">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->count }} @if ($item->count > $item->stock)
                                            <small class="text-danger">(out of stock)</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->stock }}</td>
                                    <td>{{ $item->price }}mmk</td>
                                    <td>{{ $item->price * $item->count }} mmk</td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <div class="">
                    @if ($status == true)
                        <input type="button" id="btn-order-confirm" class="btn btn-success rounded shadow-sm"
                            value="Confirm">
                    @endif
                    <input type="button" id="btn-order-reject" class="btn btn-danger rounded shadow-sm" value="Reject">
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js-script')
    <script>
        $(document).ready(function() {

            $('#btn-order-confirm').click(function() {
                orderCode = $('#orderCode').text();
                orderList = [];
                $('#productTable tbody tr').each(function(index, row) {
                    orderCount = $(row).find('.productOrderCount').val();
                    productId = $(row).find('.productId').val();

                    orderList.push({
                        orderCount: orderCount,
                        productId: productId,
                        orderCode: orderCode
                    });
                })

                $.ajax({
                    type: 'get',
                    url: '/admin/order/access',
                    data: Object.assign({}, orderList),
                    dataType: 'json',
                    success: function(res) {
                        res.status == 'success' ? location.href = '/admin/order/orderBoard' : ''
                    }
                })

            })

            $('#btn-order-reject').click(function() {
                orderCode = $('#orderCode').text();

                $.ajax({
                    type: 'get',
                    url: '/admin/order/reject',
                    data: {
                        'orderCode': orderCode
                    },
                    dataType: 'json',
                    success: function(res) {
                        res.status == 'success' ? location.href = '/admin/order/orderBoard' :
                            '';
                    }
                })
            })
        })
    </script>
@endsection
