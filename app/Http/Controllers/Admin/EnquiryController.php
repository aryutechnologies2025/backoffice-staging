<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnquiryDetail;

class EnquiryController extends Controller
{
    public function list(Request $request)
{
    $title = 'Booking List';

    // Base query to get all enquiries
    $query = EnquiryDetail::with('package');

    // Check if `package_id` is provided in the request for filtering
    if ($request->has('package_id')) {
        $query->where('package_id', $request->input('package_id'));
    }

    // Fetch enquiries with pagination
    $enquiry_dts = $query->orderBy('created_at', 'desc')->paginate(10);

    // Return the same view with filtered or unfiltered data
    return view('admin.enquiry.enquirylist', compact('title', 'enquiry_dts'));
}

}
