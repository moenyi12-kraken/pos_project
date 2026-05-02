<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Payment_history;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    //Direct to Home Page
    public function home()
    {
        $categories = Category::select('id', 'name')->get();
        $products   = Product::select('products.id', 'products.name', 'products.price', 'products.image', 'products.description', 'products.category_id', 'products.stock', 'categories.name as category_name')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->when(request('categoryId'), function ($query) {
                $query->where('products.category_id', request('categoryId'));
            })
            ->when(request('searchKey'), function ($query) {
                $query->where('products.name', 'like', '%' . request('searchKey') . '%');
            })
            ->when(request('minPrice') && ! request('maxPrice'), function ($query) { //Minium
                $query->where('products.price', '>=', request('minPrice'));
            })
            ->when(! request('minPrice') && request('maxPrice'), function ($query) { //Maxium
                $query->where('products.price', '<=', request('maxPrice'));
            })
            ->when(request('minPrice') && request('maxPrice'), function ($query) { //Min ~ Max
                $query->whereBetween('products.price', [request('minPrice'), request('maxPrice')]);
            })
            ->when(request('sortingType'), function ($query) {
                $sorting = explode(',', request('sortingType'));
                $query->orderBy('products.' . $sorting[0], $sorting[1]);
            })
            ->get();

        return view('user.home.list', compact(['products', 'categories']));
    }

    //Direct to Product Detail Page
    public function productDetail($id)
    {
        $comments = Comment::select('comments.id', 'comments.message', 'comments.user_id', 'users.name', 'users.nickname', 'users.profile', 'comments.created_at')
            ->leftJoin('users', 'comments.user_id', 'users.id')
            ->where('comments.product_id', $id)
            ->orderBy('comments.created_at', 'desc')
            ->get();
        $product = Product::select('products.id', 'products.name', 'products.price', 'products.image', 'products.description', 'products.category_id', 'products.stock', 'categories.name as category_name')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->where('products.id', $id)
            ->first();
        $rating     = number_format(Rating::where('product_id', $id)->avg('count'));
        $userRating = Rating::where('product_id', $id)
            ->where('user_id', Auth::user()->id)
            ->value('count');

        return view('user.home.productDetail', compact('product', 'comments', 'rating', 'userRating'));
    }

    //For Comment
    public function comment(Request $request)
    {
        Comment::create([
            'product_id' => $request->productId,
            'user_id'    => Auth::user()->id,
            'message'    => $request->comment,
        ]);
        return back();
    }

    //Delete Comment
    public function delete($id)
    {
        Comment::where('id', $id)->delete();
        Alert::success('Success Title', 'Delete Successfully');
        return back();
    }

    //Edit User Profile
    public function edit()
    {
        return view('user.profile.profileEdit');
    }

    //Upate Profile Process
    public function update(Request $request)
    {
        $this->profileValidation($request);
        $data = $this->getData($request);
        if ($request->hasFile('image')) {
            if (Auth::user()->profile != null) {
                unlink(public_path('profile/' . Auth::user()->profile));
                $fileName = uniqid() . $request->file('image')->getClientOriginalName();
                $request->file('image')->move(public_path() . '/profile/', $fileName);
                $data['profile'] = $fileName;
            } else {
                $fileName = uniqid() . $request->file('image')->getClientOriginalName();
                $request->file('image')->move(public_path() . '/profile/', $fileName);
                $data['profile'] = $fileName;
            }
        }

        User::where('id', Auth::user()->id)->update($data);
        Alert::success('Success Title', 'Create Successfully');

        return back();
        // return to_route('user#EditProfile');
    }

    //Profile Validation
    public function profileValidation($request)
    {
        $request->validate([
            'image'   => 'file',
            'name'    => 'required|min:2|max:20|unique:users,name,' . Auth::user()->id,
            'email'   => 'required|unique:users,email,' . Auth::user()->id,
            'phone'   => 'max:13',
            'address' => 'max:50',
        ]);
    }

    //Get Data
    public function getData($request)
    {
        return [
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
        ];
    }

    //Change Password Page
    public function changePassword()
    {
        return view('user.profile.changePassword');
    }

    //Change Password Process
    public function changeProcess(Request $request)
    {
        $originalPassword = Auth::user()->password;
        if (Hash::check($request->oldPassword, $originalPassword)) {
            $this->checkValidation($request);
            Alert::success('Process Success', 'Your New Password has changed.');
            User::where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->newPassword),
            ]);
            return back();
        } else {
            Alert::error('Process Fail', 'Old Password do not match our records. Try Again...');
            return back();
        }

    }

    //Password Validation
    public function checkValidation($request)
    {
        $request->validate([
            'oldPassword'     => 'required',
            'newPassword'     => 'required|min:6|max:12',
            'confirmPassword' => 'required|same:newPassword',
        ]);
    }

    //Rating
    public function rating(Request $request)
    {
        Rating::updateOrCreate([
            'product_id' => $request->productId,
            'user_id'    => Auth::user()->id,
        ],
            [
                'product_id' => $request->productId,
                'user_id'    => Auth::user()->id,
                'count'      => $request->productRating,
            ]);
        Alert::success('Success Title', 'Create Successfully');
        return back();
    }

    //Contact
    public function contact()
    {
        return view('user.home.contact');
    }

    //Contact Message
    public function contactMessage(Request $request)
    {
        $this->contactValidation($request);
        Contact::create([
            'user_id' => Auth::user()->id,
            'title'   => $request->title,
            'message' => $request->message,
        ]);
        Alert::success('Success Title', 'Sent Successfully');
        return back();
    }

    //Contact Validation
    public function contactValidation($request)
    {
        $request->validate([
            'email'   => 'required',
            'title'   => 'required|max:250',
            'message' => 'required|max:250',
        ]);
    }

    //Cart Page
    public function cartPage()
    {
        $carts = Cart::select('products.image', 'products.name', 'products.price', 'carts.qty', 'carts.id', 'products.id as product_id')
            ->leftJoin('products', 'carts.product_id', 'products.id')
            ->where('carts.user_id', Auth::user()->id)
            ->orderBy('carts.created_at', 'desc')
            ->get();

        $total = 0;
        foreach ($carts as $item) {
            $total += $item->price * $item->qty;
        }

        return view('user.home.cart', compact('carts', 'total'));
    }

    //Add to Cart Process
    public function addToCart(Request $request)
    {
        Cart::create([
            'user_id'    => $request->userId,
            'product_id' => $request->productId,
            'qty'        => $request->count,
        ]);
        Alert::success('Success Title', 'Add to Cart Successfully');
        return back();
    }

    //Cart Delete With API
    public function cartDelete(Request $request)
    {
        $cartId = $request->cartId;

        Cart::where('id', $cartId)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'cart delete success',
        ], 200);
    }

    //Order List With API || Put in Session
    public function tempStorage(Request $request)
    {
        $orderTemp = [];
        foreach ($request->all() as $item) {
            array_push($orderTemp, [
                'user_id'    => $item['userId'],
                'product_id' => $item['productId'],
                'count'      => $item['count'],
                'status'     => $item['status'],
                'order_code' => $item['orderCode'],
                'finalTotal' => $item['finalTotal'],
            ]);

        }

        Session::put('tempCart', $orderTemp);
        logger(Session::get('tempCart'));
        return response()->json([
            'status'  => 'success',
            'message' => 'Temporary Storage Store Successfully',
        ]);

    }

    //Payment Page
    public function paymentPage()
    {
        $payments = Payment::select('id', 'account_name', 'account_number', 'type')
            ->orderBy('type')
            ->get();
        $tempCart = Session::get('tempCart');
        return view('user.home.paymentPage', compact('payments', 'tempCart'));
    }

    //Order
    public function order(Request $request)
    {
        $tempCart = Session::get('tempCart');

        $request->validate([
            'phone'        => 'required|min:6|max:12',
            'address'      => 'required|min:2|max:250',
            'paymentType'  => 'required',
            'payslipImage' => 'required|file',
        ]);

        $data = [
            'user_name'      => $request->name,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'payment_method' => $request->paymentType,
            'order_code'     => $request->orderCode,
            'total_amt'      => $request->totalAmount,
        ];

        if ($request->hasFile('payslipImage')) {
            $fileName = uniqid() . $request->file('payslipImage')->getClientOriginalName();
            $request->file('payslipImage')->move(public_path() . '/payslipImage/', $fileName);
            $data['payslip_image'] = $fileName;
        }

        Payment_history::create($data);
        foreach ($tempCart as $item) {
            Order::create([
                'user_id'    => $item['user_id'],
                'product_id' => $item['product_id'],
                'count'      => $item['count'],
                'status'     => $item['status'],
                'order_code' => $item['order_code'],
            ]);
            Cart::where('user_id', $item['user_id'])->where('product_id', $item['product_id'])->delete();
        }

        Alert::success('You Order Successfully', 'Thank You For Your Orders');
        return back();
    }

    //Order List Page
    public function orderList()
    {
        $orderLists = Order::select('order_code', 'status', 'created_at')
            ->where('user_id', Auth::user()->id)
            ->groupBy('order_code')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.home.orderList', compact('orderLists'));
    }

}
