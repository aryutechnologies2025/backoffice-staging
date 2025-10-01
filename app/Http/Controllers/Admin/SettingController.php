<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Geo_feature;
use App\Models\Settings;

class SettingController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Setting';
        $settings = Settings::where('id', '1')->first();

        return view('admin.general-settings.setting', compact('title', 'settings'));
    }

    public function insert(Request $request)
    {

        $validated = $request->validate([
            'meta_title' => 'required|string',
            'meta_keywords' => 'required|string',
            'meta_desc' => 'required|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,svg',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,svg',
            'official_logo' => 'nullable|image|mimes:jpeg,png,svg',
            'fav_icon' => 'nullable|image|mimes:jpeg,png,svg',
            'app_name' => 'required|string',
            'contact_email' => 'required|string',
            'contact_number' => 'required|string',
            'contact_address' => 'required|string',
            // Other validations as needed
        ]);

        // Retrieve the existing settings or create a new instance
        $settings = Settings::firstOrNew(['id' => 1]); // Assuming a single settings record

        // Update the settings attributes
        $settings->meta_title = $request->meta_title;
        $settings->meta_keywords = $request->meta_keywords;
        $settings->meta_desc = $request->meta_desc;
        $settings->app_name = $request->app_name;
        $settings->contact_email = $request->contact_email;
        $settings->contact_number = $request->contact_number;
        $settings->contact_address = $request->contact_address;

        $settings->facebook = $request->facebook;
        $settings->instagram = $request->instagram;
        $settings->twitter_x = $request->twitter_x;
        $settings->pinterest = $request->pinterest;
        $settings->linkedin = $request->linkedin;
        $settings->youtube_url = $request->youtube_url;
        $settings->android_link = $request->android_link;
        $settings->ios_link = $request->ios_link;
        $settings->copyright = $request->copyright;

        $settings->leader_name = $request->leader;
        $settings->leader_contact = $request->leader_contact;
        $settings->gpay_number = $request->gpay_number;
        $settings->ac_name = $request->ac_name;
        $settings->ac_number = $request->ac_number;
        $settings->ifsc_code = $request->ifsc_code;
        $settings->bank_name = $request->bank_name;
        $settings->branch_name = $request->branch_name;

        // Handle file uploads
        if ($request->hasFile('site_logo')) {
            // $settings->site_logo = $request->file('site_logo')->store('public/settings');
            $site_logoPath = public_path('/uploads/settings/site_logo');
            if (!file_exists($site_logoPath)) {
                mkdir($site_logoPath, 0755, true);
            }
            if ($request->hasFile('site_logo')) {
                $file1 = $request->file('site_logo');
                $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
                $file1->move($site_logoPath, $filename1);
                $filePath1 = 'uploads/settings/site_logo/' . $filename1;
                $settings->site_logo = $filePath1;
            }
        }

        if ($request->hasFile('official_logo')) {
            $official_logoPath = public_path('/uploads/settings/official_logo');
            if (!file_exists($official_logoPath)) {
                mkdir($official_logoPath, 0755, true);
            }
            if ($request->hasFile('official_logo')) {
                $file2 = $request->file('official_logo');
                $filename2 = time() . '_1.' . $file2->getClientOriginalExtension();
                $file2->move($official_logoPath, $filename2);
                $filePath4 = 'uploads/settings/official_logo/' . $filename2;
                $settings->official_logo = $filePath4;
            }
        }

        if ($request->hasFile('footer_logo')) {
            $footer_logoPath = public_path('/uploads/settings/footer_logo');
            if (!file_exists($footer_logoPath)) {
                mkdir($footer_logoPath, 0755, true);
            }
            if ($request->hasFile('footer_logo')) {
                $file2 = $request->file('footer_logo');
                $filename2 = time() . '_1.' . $file2->getClientOriginalExtension();
                $file2->move($footer_logoPath, $filename2);
                $filePath2 = 'uploads/settings/footer_logo/' . $filename2;
                $settings->footer_logo = $filePath2;
            }
        }

        if ($request->hasFile('fav_icon')) {
            $fav_iconPath = public_path('/uploads/settings/fav_icon');
            if (!file_exists($fav_iconPath)) {
                mkdir($fav_iconPath, 0755, true);
            }
            if ($request->hasFile('fav_icon')) {
                $file3 = $request->file('fav_icon');
                $filename3 = time() . '_1.' . $file3->getClientOriginalExtension();
                $file3->move($fav_iconPath, $filename3);
                $filePath3 = 'uploads/settings/fav_icon/' . $filename3;
                $settings->fav_icon = $filePath3;
            }
        }

        // Save the settings
        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
