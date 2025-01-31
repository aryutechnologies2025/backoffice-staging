<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnquiryDetail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EnquiryController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Booking List';
        $enquiry_dts = EnquiryDetail::with('package')->orderBy('created_at', 'desc')->get();

        return view('admin.enquiry.enquirylist', compact('title', 'enquiry_dts'));
    }

   
}
