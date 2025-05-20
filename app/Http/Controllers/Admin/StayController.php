<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StayController extends Controller
{
    public function list(){
        return view('admin.stays.stays');
    }
}
