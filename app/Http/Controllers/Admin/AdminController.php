<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment_history;
use App\Models\User;

class AdminController extends Controller
{
    public function home()
    {
        $money      = Payment_history::sum('total_amt');
        $orderReq   = Order::whereIn('status', [0, 1])->count('id');
        $orderPend  = Order::where('status', 0)->count('id');
        $userRegist = User::where('role', 'user')->count('id');

        return view('admin.home.adminDash', compact('money', 'orderReq', 'orderPend', 'userRegist'));
    }
}
