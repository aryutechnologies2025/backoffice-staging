<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Hash;

class AdminUserController extends Controller
{

    public function list(Request $request)
    {
        $title = 'User List';

        $admins = Admin::where('is_deleted','0')
                        ->orderBy('created_at','desc')
                        ->get();

        return view('admin.adminuser.userlist', compact('title','admins'));
    }


    public function add_form()
    {
        $title = 'Add User';
        return view('admin.adminuser.useradd', compact('title'));
    }


    public function insert(Request $request)
    {

        $validator = \Validator::make($request->all(), [

            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email|unique:admin,email',
            'phone'      => 'required|unique:admin,phone',
            'profile_pic'=> 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'   => 'required|min:6',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $uploadPath = public_path('uploads/user_profile');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath,0755,true);
        }

        $filePath = "uploads/user_profile/default.png";

        if ($request->hasFile('profile_pic')) {

            $file = $request->file('profile_pic');

            $filename = time().'_'.
            preg_replace('/[^A-Za-z0-9_\-]/','_',
            pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME));

            $filename .= '.' . $file->getClientOriginalExtension();

            $file->move($uploadPath,$filename);

            $filePath = "uploads/user_profile/".$filename;

        }

        $user = new Admin;

        $user->first_name   = $request->first_name;
        $user->last_name    = $request->last_name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->password     = Hash::make($request->password);
        $user->profile_pic  = $filePath;
        $user->status       = $request->status ? '1' : '0';

        // 🔥 Save logged-in admin email
        $user->created_by   = Auth::user()->email;

        $user->is_deleted   = '0';

        $user->created_at   = now();
        $user->updated_at   = now();

        $user->save();

        return redirect()->route('admin.admin_user_list')
                ->with('success','User created successfully.');
    }


    public function edit_form(Request $request,$id)
    {
        $user_details = Admin::findOrFail($id);
        $title = 'Edit Admin';

        return view('admin.adminuser.useredit',compact('user_details','title'));
    }


    public function update(Request $request,$id)
    {

        $user = Admin::findOrFail($id);

        $request->validate([

            'first_name'  => 'required',
            'last_name'   => 'required',
            'email'       => 'required|email|unique:admin,email,'.$id,
            'phone'       => 'required|unique:admin,phone,'.$id,
            'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'    => 'nullable|min:6',

        ]);

        $uploadPath = public_path('uploads/user_profile');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath,0755,true);
        }

        if ($request->hasFile('profile_pic')) {

            if ($user->profile_pic && file_exists(public_path($user->profile_pic))) {
                unlink(public_path($user->profile_pic));
            }

            $file = $request->file('profile_pic');

            $filename = time().'_'.
            preg_replace('/[^A-Za-z0-9_\-]/','_',
            pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME));

            $filename .= '.' . $file->getClientOriginalExtension();

            $file->move($uploadPath,$filename);

            $user->profile_pic = "uploads/user_profile/".$filename;
        }

        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->status     = $request->status ? '1' : '0';

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->updated_at = now();

        $user->save();

        return redirect()->route('admin.admin_user_list')
                ->with('success','User updated successfully.');
    }


    public function change_status(Request $request)
    {

        $record_id = $request->record_id;
        $mode = $request->mode;

        $user = Admin::find($record_id);

        if ($user) {

            $user->status = $mode == 0 ? "0" : "1";
            $user->updated_at = now();
            $user->save();

            return response()->json([
                'status'=>'1',
                'response'=>'User status changed successfully.'
            ]);
        }

        return response()->json([
            'status'=>'0',
            'response'=>'Record not found.'
        ]);
    }


    public function delete(Request $request)
    {

        $record_id = $request->record_id;

        $user = Admin::find($record_id);

        if ($user) {

            $user->is_deleted = "1";
            $user->updated_at = now();
            $user->save();

            return response()->json([
                'status'=>'1',
                'response'=>'Record marked as deleted successfully.'
            ]);
        }

        return response()->json([
            'status'=>'0',
            'response'=>'Record not found.'
        ]);
    }

}