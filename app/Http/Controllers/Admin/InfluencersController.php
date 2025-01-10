<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Influencers;
use App\Models\InclusivePackageDetail;

class InfluencersController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Influencers List';
        $influencers = Influencers::where('is_deleted', '0')->where('status', "1")->paginate(10);
    
        // Fetch affiliate links for each influencer
        foreach ($influencers as $influencer) {
            // This assumes you have an affiliate_links relation or method
            $influencer->affiliate_links = $influencer->getAffiliateLinks();
        }
    
        return view('admin.influencers.influencer_list', compact('title', 'influencers'));
    }
    
    public function getAffiliateLinks($id)
    {
        $influencer = Influencers::find($id);
        if (!$influencer) {
            return response()->json(['status' => '0', 'response' => 'Influencer not found.'], 404);
        }
    
        // Eager load the packages and generate affiliate links
        $affiliateLinks = $influencer->getAffiliateLinks();
    
        return response()->json(['status' => '1', 'data' => $affiliateLinks]);
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
        
        // Generate referral code
        $newReferralCode = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8)); // Create a unique referral code
        
        // Fill influencer data
        $influencer->fill($request->all());
        $influencer->reference_id = $newReferenceId;
        $influencer->referral_code = $newReferralCode; // Set the referral code
        $influencer->created_by = 'admin';
        $influencer->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $influencer->is_deleted = '0';
        
        $influencer->save();
        
        // Generate signup URL
        $signupUrl = url('/signup/' . $newReferenceId);
      
        return redirect()->route('admin.influencer_list')
            ->with('success', 'Influencer added successfully. Reference ID: ' . $newReferenceId . ', Referral Code: ' . $newReferralCode . ', Signup URL: ' . $signupUrl);
    }
    

    // public function getAffiliateLinks($id)
    // {
        
    //     // Fetch the influencer
    //     $influencer = Influencers::find($id);
    //     if (!$influencer) {
    //         return response()->json(['status' => '0', 'response' => 'Influencer not found.'], 404);
    //     }
    
    //     // Fetch package titles
    //     $titles = InclusivePackages::where('is_deleted', '0')->get(['id', 'title']);
        
    //     // Generate affiliate links
    //     $affiliateLinks = $titles->map(function ($title) use ($influencer) {
    //         // Check if referral_code is present
    //         if (empty($influencer->referral_code)) {
    //             return null;  // Or handle it with a default code
    //         }
    
    //         // Generate the base URL and append the referral code
    //         $baseUrl = url('/' . $title->id . '/' . str_replace(' ', '-', strtolower($title->title)));
    //         $url = $baseUrl . '?ref=' . $influencer->referral_code;
        
    //         // Log the generated URL for debugging
    //         \Log::info('Generated URL: ' . $url);
    
    //         return [
    //             'title' => $title->title,
    //             'url' => $url,
    //         ];
    //     });
    
    //     // Filter out any null values (if referral_code was missing)
    //     $affiliateLinks = $affiliateLinks->filter()->values();
    
    //     return response()->json(['status' => '1', 'data' => $affiliateLinks]);
    // }
    

    public function edit_form(Request $request, $id)
    {
        $influencer = Influencers::find($id);
        if (!$influencer) {
            return redirect()->route('admin.influencers.list')
                ->with('error', 'Influencer not found.');
        }

        $title = 'Edit Influencer';
        return view('admin.influencers.influencer_edit', compact('influencer', 'title'));
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

        return redirect()->route('admin.influencer_list')
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


    // A helper method to generate affiliate links for an influencer
private function getAffiliateLinksForInfluencer($influencer)
{
    $links = [];
    $packages = InclusivePackages::all(); // Assuming you are fetching all packages or filtering as necessary

    foreach ($packages as $package) {
        $links[] = [
            'title' => $package->title,
            'url' => url('/' . $package->id . '/' . \Str::slug($package->title) . '?ref=' . $influencer->reference_id)
        ];
    }

    return $links;
}
}
