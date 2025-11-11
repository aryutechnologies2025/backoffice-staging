<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MailTemplate;

class MailTemplateController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Mail Template List';
        $mail_details = MailTemplate::where('is_deleted', '0')->orderBy('id', 'desc')->get();
        return view('admin.mailtemplates.mailtemplatelist', compact('title', 'mail_details'));
    }

    public function add()
    {
        $title = 'Add Mail Template';
        return view('admin.mailtemplates.mailtemplateadd', compact('title'));
    }

    public function insert(Request $request)
    {
        // dd($request->all());
        $originalTitle = $request->input('title');
        $convertedTitle = strtolower(str_replace(' ', '_', $originalTitle));

        $pricing = new MailTemplate();
        $pricing->title = $request->input('title');
        $pricing->sub_title = $convertedTitle;
        $pricing->mail_template = $request->input('email_template');
        $pricing->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $pricing->is_deleted = '0';
        $pricing->save();

        return redirect()->route('admin.mailtemplatelist')
            ->with('success', 'Mail template created successfully.');
    }


    public function edit(Request $request, $id)
    {
        $template = MailTemplate::find($id);
        $title = 'Edit Mail Template';
        return view('admin.mailtemplates.mailtemplatedit', compact('template', 'title'));
    }


    public function update(Request $request, $id)
    {
        $originalTitle = $request->input('title');
        $convertedTitle = strtolower(str_replace(' ', '_', $originalTitle));

        $pricing = MailTemplate::findOrFail($id);
        $pricing->title = $request->input('title');
        $pricing->sub_title = $convertedTitle;
        $pricing->mail_template = $request->input('email_template');
        $pricing->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $pricing->is_deleted = '0';
        $pricing->save();

        return redirect()->route('admin.mailtemplatelist')
            ->with('success', 'Mail template updated successfully.');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $City = MailTemplate::find($record_id);

        if ($City) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $City->status = "0";
            } else {
                $City->status = "1";
            }

            $City->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Mail status changed successfully.'
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
        $City = MailTemplate::find($record_id);
        if ($City) {
            // Update the is_deleted field to 1
            $City->is_deleted = "1";


            $City->save();

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
