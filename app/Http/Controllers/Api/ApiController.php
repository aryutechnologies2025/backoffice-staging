<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City; // Replace with your model

class ApiController extends Controller
{
    //
    public function getData()
    {
            
        // Fetch data from the database
        $data = City::all(); 

        // Return data as JSON
        return response()->json($data);
    }
}
