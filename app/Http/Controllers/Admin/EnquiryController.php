<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnquiryDetail;

class EnquiryController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Enquiry List';
        $enquiry_dts = EnquiryDetail::with('package')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.enquiry.enquirylist', compact('title', 'enquiry_dts'));
    }
}
