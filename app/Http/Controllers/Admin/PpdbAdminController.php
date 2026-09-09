<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbBatch;
use App\Models\PpdbStudent;
use Illuminate\Http\Request;

class PpdbAdminController extends Controller
{
    public function index()
    {
        $batches = PpdbBatch::orderBy('created_at', 'desc')->get();
        return view('admin.ppdb.index', compact('batches'));
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'quota' => 'required|integer|min:1',
            'registration_link' => 'nullable|url|max:255',
            'status' => 'boolean',
        ]);

        PpdbBatch::create($request->all());
        return redirect()->route('admin.ppdb.index')->with('success', 'Gelombang pendaftaran berhasil ditambahkan.');
    }

    public function students(Request $request)
    {
        $query = PpdbStudent::with('batch')->orderBy('created_at', 'desc');

        if ($request->has('batch_id') && $request->batch_id != '') {
            $query->where('batch_id', $request->batch_id);
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $students = $query->paginate(20);
        $batches = PpdbBatch::all();

        return view('admin.ppdb.students', compact('students', 'batches'));
    }

    public function showStudent($id)
    {
        $student = PpdbStudent::with(['batch', 'documents', 'score'])->findOrFail($id);
        return view('admin.ppdb.student_detail', compact('student'));
    }

    public function updateStudentStatus(Request $request, $id)
    {
        $student = PpdbStudent::findOrFail($id);
        $student->update(['status' => $request->status]);
        return back()->with('success', 'Status pendaftar berhasil diperbarui.');
    }
}
