<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\assitance;
use Illuminate\Http\Request;

class AssitanceFormController extends Controller
{
    public function list()
    {
        $title = 'Assitance Form List';
        $assitance = assitance::where('is_delected', '0')->orderBy('created_at', 'desc')->get();
        return view('admin.contact_us.assitance' , compact ('title', 'assitance'));
    }
}
