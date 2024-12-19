<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Blogs List';
        $blog_dts = Blog::where('is_deleted', '0')->paginate(10);
        return view('admin.blogs.blogslist', compact('title', 'blog_dts'));
    }

    public function add_form()
    {
        $title = 'Blog Add';
        return view('admin.blogs.blogsadd', compact('title'));
    }

    public function insert(Request $request)
    {

        $request->validate([
            'blog_title' => 'required',
            'description' => 'required', // Validate description
            'img_one' => 'nullable|image|mimes:jpeg,png|max:2048',
            'img_two' => 'nullable|image|mimes:jpeg,png|max:2048',
            'img_three' => 'nullable|image|mimes:jpeg,png|max:2048',
            'img_four' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);

        $destinationPath = public_path('/uploads/blog_pic');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $images = [];
        foreach (['img_one', 'img_two', 'img_three', 'img_four'] as $img) {
            if ($request->hasFile($img)) {
                $file = $request->file($img);
                $filename = time() . '-' . $file->getClientOriginalName(); // Generate a unique file name
                $file->move($destinationPath, $filename); // Move the file to the destination directory
                $images[$img] = $filename; // Store the filename (or path) for further use
            }
        }


        $blog = new Blog;
        $blog->blog_title = $request->input('blog_title');
        $blog->description = $request->input('description');
        $blog->blog_pic_1 = $images['img_one'] ?? null;
        $blog->blog_pic_2 = $images['img_two'] ?? null;
        $blog->blog_pic_3 = $images['img_three'] ?? null;
        $blog->blog_pic_4 = $images['img_four'] ?? null;
        $blog->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $blog->created_date = date('Y-m-d H:i:s');
        $blog->created_by = 'admin';
        $blog->is_deleted = '0';
        $blog->save();

        return redirect()->route('admin.blog_list')
            ->with('success', 'Blog created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $blog_details = Blog::find($id);
        $title = 'Blog Edit';
        return view('admin.blogs.blogsedit', compact('blog_details', 'title'));
    }

    public function update(Request $request, $id)
    {

        $blog = Blog::find($id);
        $request->validate([
            'blog_title' => 'required',
            'description' => 'required',
            'img_one' => 'nullable|image|mimes:jpeg,png|max:2048',
            'img_two' => 'nullable|image|mimes:jpeg,png|max:2048',
            'img_three' => 'nullable|image|mimes:jpeg,png|max:2048',
            'img_four' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);

        foreach (['img_one', 'img_two', 'img_three', 'img_four'] as $img) {
            // echo $img;echo'-';
            if ($request->hasFile($img)) {
                $file = $request->file($img);
                $filename = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('/uploads/blog_pic'), $filename);
                $images[$img] = $filename;
                
            }
        }

        $blog->blog_title = $request->input('blog_title');
        $blog->description = $request->input('description');
        $blog->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $blog->updated_date = date('Y-m-d H:i:s');

        $blog->updated_by = 'admin';

        $blog->blog_pic_1 = $images['img_one'] ?? $blog->blog_pic_1;
    $blog->blog_pic_2 = $images['img_two'] ?? $blog->blog_pic_2;
    $blog->blog_pic_3 = $images['img_three'] ?? $blog->blog_pic_3;
    $blog->blog_pic_4 = $images['img_four'] ?? $blog->blog_pic_4;
        $blog->save();

        return redirect()->route('admin.blog_list')
            ->with('success', 'Blog updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $blog = Blog::find($record_id);

        if ($blog) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $blog->status = "0";
            } else {
                $blog->status = "1";
            }
            $role = session('admin_role');
            // Update the updated_date field
            $blog->updated_date = date('Y-m-d H:i:s');
            $blog->status_changed_by = 'admin';
            $blog->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Blog status changed successfully.'
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
        $Blog = Blog::find($record_id);
        if ($Blog) {
            // Update the is_deleted field to 1
            $Blog->is_deleted = "1";

            // Set the updated_date field
            $Blog->updated_date = date('Y-m-d H:i:s');
            $Blog->deleted_by = 'admin';
            // Save the changes
            $Blog->save();

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
