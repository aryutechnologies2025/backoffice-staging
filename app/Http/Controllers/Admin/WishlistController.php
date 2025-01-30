<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program_wishlist;

class WishlistController extends Controller
{
    public function list(Request $request)
    {
        $title = 'WishList';
        // $wishlist_dts = Program_wishlist::with('user','program_dts')->orderBy('created_at', 'desc')->paginate(10);

        $wishlist_dts = Program_wishlist::with(['program_dts', 'user'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
//         echo"<pre>";
// print_r($wishlist_dts);die;
        return view('admin.wish_list.wishlist', compact('title', 'wishlist_dts'));
    }
}