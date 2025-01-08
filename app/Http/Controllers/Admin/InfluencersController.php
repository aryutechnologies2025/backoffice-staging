<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Influencers;

class InfluencersController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Influencers List';
        $influencers = Influencers::where('is_deleted', '0')->paginate(10);
        return view('admin.influencers.influencer_list', compact('title', 'influencers'));
    }

    public function add_form()
    {
        $title = 'Add Influencer';
        return view('admin.influencers.influencer_add', compact('title'));
    }

    public function insert(Request $request)
{
    $influencer = new Influencers;

    // Generate the next reference_id
    $lastInfluencer = Influencers::where('reference_id', 'LIKE', 'Inf-%')->orderBy('id', 'desc')->first();
    if ($lastInfluencer) {
        $lastReferenceId = intval(substr($lastInfluencer->reference_id, 4)); // Extract numeric part
        $newReferenceId = 'Inf-' . str_pad($lastReferenceId + 1, 3, '0', STR_PAD_LEFT); // Increment and pad with leading zeros
    } else {
        $newReferenceId = 'Inf-001'; // Start from Inf-001 if no records exist
    }

    $influencer->fill($request->all());
    $influencer->reference_id = $newReferenceId;
    $influencer->created_by = 'admin';
    $influencer->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
    $influencer->is_deleted = '0';
    $influencer->save();

    return redirect()->route('admin.influencer_list')
        ->with('success', 'Influencer added successfully.');
}


    public function edit_form(Request $request, $id)
    {
        $influencer = Influencers::find($id);
        if (!$influencer) {
            return redirect()->route('admin.influencers.list')
                ->with('error', 'Influencer not found.');
        }

        $title = 'Edit Influencer';
        return view('admin.influencer.influenceredit', compact('influencer', 'title'));
    }

    public function update(Request $request, $id)
    {
        $influencer = Influencers::find($id);
        if (!$influencer) {
            return redirect()->route('admin.influencers.list')
                ->with('error', 'Influencer not found.');
        }

        $influencer->fill($request->all());
        $influencer->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $influencer->save();

        return redirect()->route('admin.influencers_list')
            ->with('success', 'Influencer updated successfully.');
    }

    public function change_status(Request $request)
    {
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        $influencer = Influencers::find($record_id);

        if ($influencer) {
            $influencer->status = $mode == 0 ? '0' : '1';
            $influencer->status_changed_by = 'admin';
            $influencer->updated_date = now();
            $influencer->save();

            return response()->json([
                'status' => '1',
                'response' => 'Influencer status changed successfully.',
            ]);
        }

        return response()->json([
            'status' => '0',
            'response' => 'Record not found.',
        ]);
    }

    public function delete(Request $request)
    {
        $record_id = $request->input('record_id');

        $influencer = Influencers::find($record_id);
        if ($influencer) {
            $influencer->is_deleted = "1";
            $influencer->deleted_by = 'admin';
            $influencer->updated_date = now();
            $influencer->save();

            return response()->json([
                'status' => '1',
                'response' => 'Influencer marked as deleted successfully.',
            ]);
        }

        return response()->json([
            'status' => '0',
            'response' => 'Record not found.',
        ]);
    }
}
