<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InclusivePackages;
use App\Models\program_pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function index()
    {
        $pdfs = program_pdf::where('is_deleted', '0')->orderBy('created_at', 'desc')->get();
        $program_dts = InclusivePackages::where('status', "1")->where('is_deleted', "0")->pluck('title', 'title');

        return view('admin.program_pdf.index', compact('pdfs', 'program_dts'));
    }

    public function create()
    {
        return view('admin.program_pdf.create');
    }

    public function add_form()
    {
        $title = 'Add ClientReview';
        $program_dts = InclusivePackages::where('status', "1")->where('is_deleted', "0")->pluck('title', 'id');
        return view('admin.program_pdf.index', compact('title','program_dts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_name' => 'required',
            'program_pdf' => 'required',
        ]);

        $pdf = new program_pdf();
        $pdf->program_name = $request->program_name;
        $pdf->is_deleted = 0;

        if ($request->hasFile('program_pdf')) {
            $file = $request->file('program_pdf');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/program_pdfs/', $filename);
            $pdf->program_pdf = $filename;
        }

        $pdf->save();
        return redirect()->route('admin.program_pdf_list')->with('success', 'Pdf created successfully');
    }

    public function edit($id)
    {
        $pdf = program_pdf::find($id);
        return view('admin.program_pdf_list', compact('pdf'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'program_name' => 'required',
            'program_pdf' => 'required',
        ]);

        $pdf = program_pdf::find($id);
        $pdf->program_name = $request->program_name;

        if ($request->hasFile('program_pdf')) {
            $file = $request->file('program_pdf');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/program_pdfs/', $filename);
            $pdf->program_pdf = $filename;
        }

        $pdf->save();
        return redirect()->route('admin.program_pdf_list')->with('success', 'Pdf updated successfully');
    }

    public function destroy($id)
    {
        $pdf = program_pdf::find($id);
        $pdf->is_deleted = 1;
        $pdf->save();
        return redirect()->route('admin.program_pdf_list')->with('success', 'Pdf deleted successfully');
    }
}
