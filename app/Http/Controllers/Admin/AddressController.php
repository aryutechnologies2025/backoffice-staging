<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;

class AddressController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Address List';
        $user_dts = Address::where('is_deleted', '0')->orderBy('created_at', 'desc')->get();
        return view('admin.address.addresslist', compact('title', 'user_dts'));
    }

    public function add_form()
    {
        $title = 'Add Address';

        return view('admin.address.addressadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required|integer',
            'country' => 'required'

        ]);

        $user = new Address;
        $user->title = $request->input('title');
        $user->address = $request->input('address');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->pincode = $request->input('pincode');
        $user->country = $request->input('country');
        $user->created_date = date('Y-m-d H:i:s');
        $user->created_by = 'admin';
        $user->is_deleted = '0';
        $user->updated_at = null;

        $user->save();

        return redirect()->route('admin.address_list')
            ->with('success', 'Address created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $user_details = Address::find($id);
        $title = 'Edit Address';
        return view('admin.address.addressedit', compact('user_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        // Find the user by ID
        $user = Address::findOrFail($id);

        // Validate input
        $validated = $request->validate([
            'title' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'country' => 'required'
        ]);


        // Update user details
        $user->title = $request->input('title');
        $user->address = $request->input('address');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->pincode = $request->input('pincode');
        $user->country = $request->input('country');
        $user->updated_by = 'Admin';

        $user->updated_at = now(); // Update timestamp
        $user->save();

        return redirect()->route('admin.address_list')
            ->with('success', 'Address updated successfully.');
    }


    public function delete(Request $request)
    {
        $record_id = $request->input('record_id');

        $user = Address::find($record_id);
        if ($user) {
            $user->is_deleted = "1";

            // $user->updated_date = date('Y-m-d H:i:s');
            // $user->deleted_by = 'admin';
            $user->save();
            $response = [
             
                'response' => 'Record marked as deleted successfully.'
            ];
        } else {
            $response = [
               
                'response' => 'Record not found.'
            ];
        }

        // Return the response as JSON
        return response()->json($response);
    }
}
