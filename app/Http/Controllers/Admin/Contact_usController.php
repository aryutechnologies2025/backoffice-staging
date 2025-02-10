<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;

class Contact_usController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Contact-Us List';
        $contact_dts = ContactUs::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.contact_us.contactlist', compact('title', 'contact_dts'));
    }
}
