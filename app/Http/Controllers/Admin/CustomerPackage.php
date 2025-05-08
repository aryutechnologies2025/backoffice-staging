<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\customer_package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerPackageNotification;
use Illuminate\Support\Facades\DB;

class CustomerPackage extends Controller
{
    public function list(Request $request)
    {
        $title = 'Customer Package List';
        $customer_package_list = customer_package::where('is_deleted', '0')->latest()->paginate(10);
        return view('admin.customer_package.customerpackagelist', compact('title', 'customer_package_list'));
    }

    public function add_form()
    {
        $title = 'Add Customer Package';
        $titles = DB::table('inclusive_package_details')->where('is_deleted','0')->pluck('title', 'id'); 
        return view('admin.customer_package.customerpackageadd', compact('title','titles'));
    }


    public function insert(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string',
            // 'phone_number' => 'string|max:10',
            // 'email' => 'email',
            'package_type' => 'required|string',
        ]);
       
        $customer_package = new customer_package();
        $customer_package->name = ucfirst($request->name);
        $customer_package->phone_number = $request->phone_number;
        $customer_package->email = $request->email;
        $packageData = json_decode($request->package_type, true);
        $customer_package->package_id = $packageData['id'];
        $customer_package->package_type = $packageData['name'];

        
        $customer_package->save();

        // Mail::to($customer_package->email) // or use a different recipient
        // ->send(new CustomerPackageNotification([
        //     'name'=> $customer_package->name,
        //     'phone_number'=> $customer_package->phone_number,
        //     'email'=> $customer_package->email,
        //     'package_type'=> $customer_package->package_type,
        //     'package_id'=> $customer_package->package_id
        // ]));

        return redirect()->route('admin.CustomerPackage_list')
        ->with('success', 'customer inserted successfully');

    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $customer_package = customer_package::find($record_id);

        if ($customer_package) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $customer_package->status = "0";
            } else {
                $customer_package->status = "1";
            }

            $customer_package->save();

            $response = [
                'status' => '1',
                'response' => 'Customer status changed successfully.'
            ];
        } else {
            // Record not found
            $response = [
                'status' => '0',
                'response' => 'Record not found.'
            ];
        }

        // Return the response as JSON
        return response()->json($response);
    }


    public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $program = customer_package::find($record_id);
        if ($program) {
            // Update the is_deleted field to 1
            $program->is_deleted = "1";
            $program->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Record marked as deleted successfully.'
            ];
        } else {
            // Record not found
            $response = [
                'status' => '0',
                'response' => 'Record not found.'
            ];
        }

        // Return the response as JSON
        return response()->json($response);
    }

    public function getNameById($id)
    {
        // Validate ID is numeric
        if (!is_numeric($id)) {
            return response()->json([
                'error' => 'Invalid ID format'
            ], 400);
        }

        $customer = customer_package::find($id);

        if (!$customer) {
            return response()->json([
                'error' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'id' => $customer->id,
            'name' => $customer->name
        ]);
    }


}