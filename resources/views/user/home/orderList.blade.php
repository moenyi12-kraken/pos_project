@extends('user.layout.master')

@section('content')
    <div class="container " style="margin-top: 150px">
        <div class="row">
            <table class="table table-hover shadow-sm ">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Date</th>
                        <th>Order Code</th>
                        <th>Order Status</th>
                    </tr>
                </thead>
                @foreach ($orderLists as $item)
                    <tbody>

                        <tr>
                            <td>{{ $item->created_at->format('j-F-y') }}</td>
                            <td>{{ $item->order_code }}</td>
                            <td>
                                @if ($item->status == 0)
                                    <i class="fa-regular fa-clock me-3 text-warning"></i> Pending
                                @endif
                                @if ($item->status == 1)
                                    <i class="fa-regular fa-circle-check me-3 text-success"></i> Confirm
                                @endif
                                @if ($item->status == 2)
                                    <i class="fa-regular fa-circle-xmark me-3 text-danger"></i> Reject
                                @endif
                            </td>

                        </tr>

                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
@endsection
