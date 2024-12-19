<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    public function list(Request $request)
    {
        return view('admin.packages.packagelist');
    }

    public function add_form()
    {
        $title = 'Package Add';
        return view('admin.packages.packageadd', compact('title'));
    }
}
