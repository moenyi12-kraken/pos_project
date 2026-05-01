<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //Order Board Page indicate
    public function orderBoard()
    {
        $orderLists = Order::select('orders.created_at', 'orders.order_code', 'orders.status', 'orders.count', 'users.nickname', 'users.name', 'orders.id', 'products.stock')
            ->leftJoin('users', 'users.id', 'orders.user_id')
            ->leftJoin('products', 'orders.product_id', 'products.id')
            ->when(request('searchKey'), function ($query) {
                $query->whereAny(['orders.created_at', 'orders.order_code', 'users.nickname', 'users.name'], 'like', '%' . request('searchKey') . '%');
            })
            ->groupBy('orders.order_code')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.order.orderBoard', compact('orderLists'));
    }

    //Order List Detail page indicate
    public function orderListDetail($orderCode)
    {
        $orderProduct = Order::select('products.image', 'products.name', 'products.stock', 'products.price', 'orders.count', 'products.id as product_id')
            ->leftJoin('products', 'orders.product_id', 'products.id')
            ->where('orders.order_code', $orderCode)
            ->get();
        $info = Order::select('users.name', 'users.nickname', 'users.phone', 'users.address', 'orders.order_code', 'orders.created_at as order_date', 'payment_histories.total_amt', 'payment_histories.phone as pay_phone', 'payment_histories.payment_method', 'payment_histories.created_at as purcase_date', 'payment_histories.payslip_image', 'payment_histories.address as pay_address')
            ->leftJoin('users', 'orders.user_id', 'users.id')
            ->leftJoin('payment_histories', 'orders.order_code', 'payment_histories.order_code')
            ->where('orders.order_code', $orderCode)
            ->get();

        $status = true;
        foreach ($orderProduct as $item) {
            if ($item->count <= $item->stock) {
                $status = true;
            } else {
                $status = false;
                break;
            }
        }

        return view('admin.order.orderListDetail', compact('orderProduct', 'info', 'status'));
    }

    //Order Reject Process With API
    public function rejectProcess(Request $request)
    {
        Order::where('order_code', $request->orderCode)->update([
            'status' => '2',
        ]);
        return response()->json([
            'status'  => 'success',
            'message' => 'Reject Order Successfully',
        ], 200);
    }

    //Order Status Change Process With API
    public function statusChange(Request $request)
    {
        $status    = $request->status;
        $orderCode = $request->orderCode;

        Order::where('order_code', $orderCode)->update([
            'status' => $status,
        ]);

        if ($status == 1) {
            $data = Order::select('count', 'product_id')->where('order_code', $orderCode)->get();

            foreach ($data as $item) {
                Product::where('id', $item['product_id'])->decrement('stock', $item['count']);
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Status Change Successfully',
        ]);
    }

    //Order Access Process With API
    public function accessProcess(Request $request)
    {
        Order::where('order_code', $request[0]['orderCode'])->update([
            'status' => '1',
        ]);

        foreach ($request->all() as $item) {
            Product::where('id', $item['productId'])->decrement('stock', $item['orderCount']);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Order Access Successfully',
        ], 200);
    }

    //Sale Information Page Indicate
    public function saleInformation()
    {
        $saleInfoLists = Order::select('orders.created_at', 'orders.order_code', 'orders.status', 'users.nickname', 'users.name')
            ->leftJoin('users', 'users.id', 'orders.user_id')
            ->where('orders.status', 1)
            ->when(request('searchKey'), function ($query) {
                $query->whereAny(['orders.created_at', 'orders.order_code', 'users.nickname', 'users.name'], 'like', '%' . request('searchKey') . '%');
            })
            ->groupBy('orders.order_code')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.order.saleInfo', compact('saleInfoLists'));
    }
}
