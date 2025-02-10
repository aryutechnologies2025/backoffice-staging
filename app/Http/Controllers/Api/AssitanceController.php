<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\assitance;
use Illuminate\Http\Request;

class AssitanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'comments' => 'required',
        ]);
        $assitance = assitance::create($request->all());
        return response()->json($assitance, 201);

}
}