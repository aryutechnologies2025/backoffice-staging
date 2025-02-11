<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\assitance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactEmail;
use App\Mail\ContactAdminEmail;
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


        try {
            // Send email to the client
            Mail::to($assitanceForm->email)->send(new ContactEmail([
            'name' => $assitanceForm->name,
            'email' => $assitanceForm->email,
            'phone' => $assitanceForm->phone,
            'comments' => $assitanceForm->comments,
            ]));
            Log::info('Email sent to client: ' . $assitanceForm->email);

            // Send email to admin
            Mail::to('barathkrishnamoorthy17@gmail.com')->send(new ContactAdminEmail([
            'name' => $assitanceForm->name,
            'email' => $assitanceForm->email,
            'phone' => $assitanceForm->phone,
            'comments' => $assitanceForm->comments,
            ]));
            Log::info('Email sent to admin: ' . $assitanceForm->email);
        } catch (\Exception $e) {
            // Log any email sending errors
            Log::error('Mail failed: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Assistance created successfully',
            'data' => $assitanceForm
        ], 201);

}
}





