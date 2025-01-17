<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeEnquiryDetail;

class HomeEnquiryController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Enquiry List';
        $enquiry_dts = HomeEnquiryDetail::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.home_enquiry.homeenquirylist', compact('title', 'enquiry_dts'));
    }
}
