<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;


class ProgramController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Program List';
        $program_dts = Program::where('is_deleted', '0')->paginate(10);
        return view('admin.program.programlist', compact('title', 'program_dts'));
    }

    public function add_form()
    {
        $title = 'Program Add';

        return view('admin.program.programadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'program_title' => 'required',
            'image_1' => 'required',
            'list_order' => 'required',
        ]);

        $programPath = public_path('/uploads/program_img');
        if (!file_exists($programPath)) {
            mkdir($programPath, 0755, true);
        }

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($programPath, $filename1);
            $filePath1 = 'uploads/program_img/' . $filename1;
        }

        $program = new Program;
        $program->program_title = $request->input('program_title');
        $program->program_img = $filePath1;
        $program->list_order = $request->input('list_order');
        $program->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $program->created_date = date('Y-m-d H:i:s');
        $program->created_by = 'admin';
        $program->is_deleted = '0';
        $program->updated_at = null;
        $program->save();

        return redirect()->route('admin.program_list')
            ->with('success', 'Program created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $program_details = Program::find($id);
        $title = 'Program Edit';
        return view('admin.program.programedit', compact('program_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'program_title' => 'required',
            'list_order' => 'required',
        ]);

        $programPath = public_path('/uploads/program_img');
        if (!file_exists($programPath)) {
            mkdir($programPath, 0755, true);
        }

        $program = Program::find($id);
        if (!$program) {
            return redirect()->route('admin.program_list')
                ->with('error', 'Program not found.');
        }

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($programPath, $filename1);
            $filePath1 = 'uploads/program_img/' . $filename1;
            $program->program_img = $filePath1;
        }


        $program->program_title = $request->input('program_title');
        $program->list_order = $request->input('list_order');
        $program->updated_date = date('Y-m-d H:i:s');
        $program->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $program->updated_by = 'admin';
        $program->save();

        return redirect()->route('admin.program_list')
            ->with('success', 'program updated successfully');
    }


    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $program = Program::find($record_id);

        if ($program) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $program->status = "0";
            } else {
                $program->status = "1";
            }
            // Update the updated_date field
            $program->updated_date = date('Y-m-d H:i:s');
            $program->status_changed_by = 'admin';
            $program->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Program status changed successfully.'
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
        $program = Program::find($record_id);
        if ($program) {
            // Update the is_deleted field to 1
            $program->is_deleted = "1";

            // Set the updated_date field
            $program->updated_date = date('Y-m-d H:i:s');
            $program->deleted_by = 'admin';
            // Save the changes
            $program->save();

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
