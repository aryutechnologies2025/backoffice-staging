<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnquiryDetail;
use App\Models\EnquiryFollow_up;
use App\Models\FollowUp;
use App\Models\HomeEnquiryDetail;
use Illuminate\Support\Facades\Log;

class EnquiryController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Booking List';
        $enquiry_dts = EnquiryDetail::with('package')->orderBy('created_at', 'desc')->get();
       
        return view('admin.enquiry.enquirylist', compact('title', 'enquiry_dts'));
    }

    public function addFollowUp(Request $request, $enquiryId)
    {
        $validated = $request->validate([
            'follow_up_date' => 'required|date',
            
            'lead_source' => 'required|string',
            'lead_status' => 'required|string',
            'follow_up_notes' => 'required|string',
            'deal_value' => 'required|numeric',
            'assigned_to' => 'required|string',
            'next_follow_up_date' => 'required|date',
        ]);
    
        $validated['home_id'] = $enquiryId;
        FollowUp::create($validated);
    
        return redirect()->route('admin.home_enquiry_list')->with('success', 'Follow-up added successfully!');
    }
    
    public function viewFollowUps($enquiryId)
    {
        $enquiry = HomeEnquiryDetail::with('followUps')->findOrFail($enquiryId);
    
        return view('admin.enquiry.followups', compact('enquiry'));
    }
    
    public function showEnquiryForm($id)
    {
        $enquiry = HomeEnquiryDetail::findOrFail($id); // Retrieve the enquiry by ID

        return view('admin.enquiry.form', compact('enquiry')); // Pass the enquiry to the view
    }


   
    public function addEnquiryFollowUp(Request $request, $enquiryId)
    {
        $data = $request->all();
        $data['enquiry_id'] = $enquiryId;
        EnquiryFollow_up::create($data);
    
        return redirect()->route('admin.enquiry_list')->with('success', 'Follow-up added successfully!');
    }
    
    public function viewEnquiryFollowUps($enquiryId)
    {
        $enquiry = EnquiryDetail::with('enquiryFollowUps')->findOrFail($enquiryId);
    
        return view('admin.enquiry.enquiry_followup', compact('enquiry'));
    }
    
    public function showEnquiryFollowUpForm($id)
    {
        $enquiry = EnquiryDetail::findOrFail($id); // Retrieve the enquiry by ID

        return view('admin.enquiry.form', compact('enquiry')); // Pass the enquiry to the view
    }

    //add store function for enquirydetails
    public function insert(Request $request){
        $enquirydetails = new EnquiryDetail();
        $enquirydetails->fill($request->all());
        $enquirydetails->save();
        return redirect()->route('admin.enquiry_list')->with('success', 'Enquiry added successfully!');
    } 

    //add add_form function for enquirydetails
    public function add_form(){
        $title = 'Add Book Enquiry';
        return view('admin.enquiry.add_enquiry', compact('title'));
    }
       
}







// @extends('layouts.app')

// @section('content')
// <div class="container">
//     <h2 class="text-center">Customer Travel Details Form</h2>
//     <form id="form_valid" method="POST" action="{{ route('admin.enquiry.enquiryaddFollowUp', $enquiry->id) }}">
//     @csrf

//         <div class="row">
//             <!-- Customer Name -->
//             <div class="col-md-6">
//                 <label>Customer Name</label>
//                 <input type="text" name="customer_name" class="form-control" required>
//             </div>
//             <!-- Customer Location -->
//             <div class="col-md-6">
//                 <label>Customer Location</label>
//                 <input type="text" name="customer_location" class="form-control" required>
//             </div>
//         </div>

//         <div class="row mt-3">
//             <!-- Event Name -->
//             <div class="col-md-6">
//                 <label>Event Name</label>
//                 <input type="text" name="event_name" class="form-control" required>
//             </div>
//             <!-- No. of Persons -->
//             <div class="col-md-6">
//                 <label>No. of Persons</label>
//                 <input type="number" name="no_of_persons" class="form-control" required>
//             </div>
//         </div>

//         <div class="row mt-3">
//             <!-- Customer Transportation -->
//             <div class="col-md-6">
//                 <label>Customer native to Delhi transportation</label>
//                 <select name="transportation_mode" class="form-control">
//                     <option value="Flight">Flight</option>
//                     <option value="Train">Train</option>
//                 </select>
//             </div>
//             <div class="col-md-6">
//                 <label>Travel Date & Timing</label>
//                 <input type="datetime-local" name="travel_date_time" class="form-control">
//             </div>
//         </div>

//         <div class="row mt-3">
//             <!-- Booking Date -->
//             <div class="col-md-6">
//                 <label>Booking Date</label>
//                 <input type="date" name="booking_date" class="form-control" required>
//             </div>
//             <!-- Travel Starting and Ending Date -->
//             <div class="col-md-3">
//                 <label>Travel Starting Date</label>
//                 <input type="date" name="travel_start_date" class="form-control">
//             </div>
//             <div class="col-md-3">
//                 <label>Travel Ending Date</label>
//                 <input type="date" name="travel_end_date" class="form-control">
//             </div>
//         </div>

//         <div class="row mt-3">
//             <!-- Return Delhi to Native -->
//             <div class="col-md-6">
//                 <label>Return Delhi to Native</label>
//                 <select name="return_mode" class="form-control">
//                     <option value="Flight">Flight</option>
//                     <option value="Train">Train</option>
//                 </select>
//             </div>
//             <div class="col-md-6">
//                 <label>Return Travel Date & Timing</label>
//                 <input type="datetime-local" name="return_travel_date_time" class="form-control">
//             </div>
//         </div>

//         <div class="row mt-3">
//             <!-- Delhi to Manali Bus Service -->
//             <div class="col-md-6">
//                 <label>Delhi to Manali Bus Service</label>
//                 <select name="bus_service" class="form-control" id="bus_service" onchange="toggleBusDetails()">
//                     <option value="Yes">Yes</option>
//                     <option value="No">No</option>
//                 </select>
//             </div>
//         </div>

//         <!-- Bus Service Details (Conditional Fields) -->
//         <div id="bus_details" class="mt-3">
//             <div class="row">
//                 <div class="col-md-6">
//                     <label>Booked/Non-Booked</label>
//                     <select name="bus_status" class="form-control">
//                         <option value="Booked">Booked</option>
//                         <option value="Non-Booked">Non-Booked</option>
//                     </select>
//                 </div>
//                 <div class="col-md-6">
//                     <label>Travel Date & Timing</label>
//                     <input type="datetime-local" name="bus_travel_date_time" class="form-control">
//                 </div>
//             </div>
//         </div>

//         <div id="cab_details" class="mt-3" style="display: none;">
//             <div class="row">
//                 <div class="col-md-6">
//                     <label>Cab Pickup Point & Name</label>
//                     <input type="text" name="cab_pickup" class="form-control">
//                 </div>
//                 <div class="col-md-6">
//                     <label>Travel Date & Timing</label>
//                     <input type="datetime-local" name="cab_travel_date_time" class="form-control">
//                 </div>
//             </div>
//         </div>

        // <div class="row mt-3">
        //     <!-- Additional Program Details -->
        //     <div class="col-md-6">
        //         <label>Additional Program Details</label>
        //         <input type="text" name="program_details" class="form-control">
        //     </div>
        //     <div class="col-md-6">
        //         <label>Anniversary/Birthday</label>
        //         <input type="text" name="special_occasion" class="form-control">
        //     </div>
        // </div>

        // <div class="row mt-3">
        //     <!-- Customer Stay List -->
        //     <div class="col-md-6">
        //         <label>Customer Total Stay List</label>
        //         <textarea name="stay_list" class="form-control" rows="3"></textarea>
        //     </div>
        //     <div class="col-md-6">
        //         <label>Property Name</label>
        //         <input type="text" name="property_name" class="form-control">
        //     </div>
        // </div>

        // <div class="row mt-3">
        //     <!-- Cab Service Details -->
        //     <div class="col-md-6">
        //         <label>Cab Service Details</label>
        //         <input type="text" name="cab_service" class="form-control">
        //     </div>
        // </div>

        // <div class="row mt-3">
        //     <!-- Trip Current Status -->
        //     <div class="col-md-6">
        //         <label>Trip Current Status</label>
        //         <select name="trip_status" class="form-control">
        //             <option value="Completed">Completed</option>
        //             <option value="Non-Completed">Non-Completed</option>
        //             <option value="Cancelled">Cancelled</option>
        //         </select>
        //     </div>
        // </div>

//         <div class="text-center mt-4">
//             <button type="submit" class="btn btn-primary">Submit</button>
//         </div>
//     </form>
// </div>

// <script>
//     function toggleBusDetails() {
//         let busService = document.getElementById("bus_service").value;
//         document.getElementById("bus_details").style.display = (busService === "Yes") ? "block" : "none";
//         document.getElementById("cab_details").style.display = (busService === "No") ? "block" : "none";
//     }
// </script>


// <h4 class="mt-5">Follow-up History</h4>
// <table class="table mb-5" id="followUpTable">
//     <thead>
//         <tr>
//             <th>Customer Name</th>
//             <th>Customer Location</th>
//             <th>Event Name</th>
//             <th>No Of Persons</th>
//             <th>Trip Status</th>
            
//         </tr>
//     </thead>
//     <tbody>
//         @foreach($enquiry->enquiryFollowUps as $followUp)
//         <tr>
//             <td>{{ $followUp->customer_name }}</td>
//             <td>{{ $followUp->customer_location }}</td>
//             <td>{{ $followUp->event_name }}</td>
//             <td>{{ $followUp->no_of_persons }}</td>
//             <td>{{ $followUp->trip_status }}</td>
           
//         </tr>
//         @endforeach
//     </tbody>
// </table>
// </div>

// <script>
//     $(document).ready(function() {
//         $('#followUpTable').DataTable();
//     });
// </script>

// @endsection
