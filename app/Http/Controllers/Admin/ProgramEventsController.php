<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgramEventsController extends Controller
{
    public function list()
    {
        $title = 'Events List';
        return view('admin.programevents.programEventList', compact('title'));
    }

    public function add()
    {
        $title = 'Add Event';
        return view('admin.programevents.programEventAdd', compact('title'));
    }

    public function insert(Request $request)
    {
        dd($request->all());
    }
}
