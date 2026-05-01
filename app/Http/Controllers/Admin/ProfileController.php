<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    //indicate Change Password Page
    public function changePasswordPage()
    {
        return view('admin.profile.changePassword');
    }

    //Change Password Process
    public function changePassword(Request $request)
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

    //Profile Edit Page
    public function editProfilePage()
    {
        return view('admin.profile.editProfile');
    }

    //Edit Profile Process
    public function editProfile(Request $request)
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
}
