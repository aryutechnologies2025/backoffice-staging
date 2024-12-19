<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FAQ;

class FAQController extends Controller
{
    public function list(Request $request)
    {
        $title = 'FAQ List';
        $faq_dts = FAQ::where('is_deleted', '0')->paginate(10);
        return view('admin.faq.faqlist', compact('title', 'faq_dts'));
    }

    public function add_form()
    {
        $title = 'FAQ Add';
        return view('admin.faq.faqadd', compact('title'));
    }


    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);
        $FAQ = new FAQ;
        $FAQ->question = $request->input('question');
        $FAQ->answer = $request->input('answer');
        $FAQ->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $FAQ->created_date = date('Y-m-d H:i:s');
        $FAQ->created_by = 'admin';
        $FAQ->is_deleted = '0';
        $FAQ->save();

        return redirect()->route('admin.faqlist')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $faq_details = FAQ::find($id);
        $title = 'FAQ Edit';
        return view('admin.faq.faqedit', compact('faq_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $FAQ = FAQ::find($id);
        if (!$FAQ) {
            return redirect()->route('admin.faqlist')
                ->with('error', 'FAQ not found.');
        }

        $FAQ->question = $request->input('question');
        $FAQ->answer = $request->input('answer');
        $FAQ->updated_date = date('Y-m-d H:i:s');
        $FAQ->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $FAQ->updated_by = 'admin';
        $FAQ->save();

        return redirect()->route('admin.faqlist')
            ->with('success', 'FAQ updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $FAQ = FAQ::find($record_id);

        if ($FAQ) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $FAQ->status = "0";
            } else {
                $FAQ->status = "1";
            }
            $role = session('admin_role');
            // Update the updated_date field
            $FAQ->updated_date = date('Y-m-d H:i:s');
            $FAQ->status_changed_by = 'admin';
            $FAQ->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Faq status changed successfully.'
            ];
        } else {
            // Record not found
            $response = [
                'status' => '0',
                'response' => 'Record not found.'
            ];
        }

        // Return the response as JSON
        return response()->json($response);
    }

    public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $FAQ = FAQ::find($record_id);
        if ($FAQ) {
            // Update the is_deleted field to 1
            $FAQ->is_deleted = "1";

            // Set the updated_date field
            $FAQ->updated_date = date('Y-m-d H:i:s');
            $FAQ->deleted_by = 'admin';
            // Save the changes
            $FAQ->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Record marked as deleted successfully.'
            ];
        } else {
            // Record not found
            $response = [
                'status' => '0',
                'response' => 'Record not found.'
            ];
        }

        // Return the response as JSON
        return response()->json($response);
    }
}
