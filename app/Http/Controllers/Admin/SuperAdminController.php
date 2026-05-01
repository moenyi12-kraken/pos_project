<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class SuperAdminController extends Controller
{
    //Indicate to payment Page
    public function payment($action = 'default')
    {
        $payments = Payment::select('id', 'account_name', 'account_number', 'type')
            ->when($action == 'search', function ($query) {
                $query->where('type', 'like', '%' . request('searchKey') . '%');
            })
            ->orderBy('type')
            ->paginate('3');
        return view('admin.payment.paymentPage', compact('payments'));
    }

    //Delete Payment Process
    public function delete($id)
    {
        Payment::where('id', $id)->delete();
        return back();
    }

    //Indicate to Edit Payment Page
    public function edit($id)
    {
        $payments = Payment::select('type', 'account_name', 'account_number', 'id')->where('id', $id)->first();
        return view('admin.payment.edit', compact('payments'));
    }

    //payment Create
    public function createPayment(Request $request)
    {
        $this->checkValidation($request);
        $data = $this->getData($request);

        Payment::create($data);

        Alert::success('Success Title', 'Create Successfully');
        return to_route('super#Payment');
    }

    //Update payment Process
    public function update(Request $request)
    {
        $this->checkValidation($request);
        $data = $this->getData($request);
        Payment::where('id', $request->paymentId)->update($data);

        Alert::success('Success Title', 'Update Successfully');
        return to_route('super#Payment');
    }

    //Validation
    public function checkValidation($request)
    {
        $request->validate([
            'type'          => 'required|min:2|max:10',
            'accountName'   => 'required|min:2|max:30',
            'accountNumber' => 'required|numeric',
        ]);
    }

    //Get Data
    public function getData($request)
    {
        return [
            'type'           => $request->type,
            'account_name'   => $request->accountName,
            'account_number' => $request->accountNumber,
        ];
    }

    //FOR ADD ADMIN and FOR ADMIN LIST and FOR USER LIST

    //Add Admin Page
    public function addAdmin()
    {
        return view('admin.list_adminUser.addAdmin');
    }

    //Create New Admin
    public function createAdmin(Request $request)
    {
        $this->checkAdminValidation($request);
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);
        Alert::success('Success Title', 'Create Successfully');
        return back();
    }

    //Check Admin Validation
    public function checkAdminValidation($request)
    {
        $request->validate([
            'name'            => 'required|min:2|max:20',
            'email'           => 'required|unique:users,email',
            'password'        => 'required|min:6|max:12',
            'confirmPassword' => 'required|min:6|max:12|same:password',
        ]);
    }

    //Admin List
    public function adminList()
    {
        $admins = User::select('id', 'name', 'email', 'address', 'phone', 'role', 'created_at', 'provider', 'profile')
            ->whereIn('role', ['admin', 'superadmin'])
            ->when(request('searchKey'), function ($query) {
                $query->whereAny(['name', 'email', 'address', 'phone', 'role', 'provider'], 'like', '%' . request('searchKey') . '%');
            })
            ->paginate(4);
        return view('admin.list_adminUser.adminList', compact('admins'));
    }

    //Delete Admin And User Also
    public function deleteAdmin($id)
    {
        User::where('id', $id)->delete();
        return back();
    }

    //User List
    public function userList()
    {
        $users = User::select('id', 'name', 'email', 'address', 'phone', 'role', 'created_at', 'provider', 'profile')
            ->where('role', 'user')
            ->when(request('searchKey'), function ($query) {
                $query->whereAny(['name', 'email', 'address', 'phone', 'role', 'provider'], 'like', '%' . request('searchKey') . '%');
            })
            ->paginate(4);
        return view('admin.list_adminUser.userList', compact('users'));
    }
}
