<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\assitance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssitanceController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'comments' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $assitance =$request->all();
        $assitanceForm = assitance::create($assitance);
        return response()->json([
            'success' => true,
            'message' => 'Assistance created successfully',
            'data' => $assitanceForm
        ], 201);

}
}