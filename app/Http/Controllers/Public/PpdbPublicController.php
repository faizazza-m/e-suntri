<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PpdbBatch;
use Illuminate\Http\Request;

class PpdbPublicController extends Controller
{
    public function index()
    {
        $activeBatches = PpdbBatch::where('status', true)->orderBy('created_at', 'desc')->get();
        return view('public.ppdb.index', compact('activeBatches'));
    }

    public function create($batch_id)
    {
        $batch = PpdbBatch::where('status', true)->findOrFail($batch_id);
        return view('public.ppdb.register', compact('batch'));
    }

    public function store(Request $request, $batch_id)
    {
        $batch = PpdbBatch::where('status', true)->findOrFail($batch_id);

        $request->validate([
            'nik' => 'nullable|string|max:16',
            'nisn' => 'nullable|string|max:20',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'previous_school' => ($batch->unit == 'MTRQ' ? 'required' : 'nullable') . '|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'address' => 'required|string',
            'document_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_akta' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $registration_number = 'PPDB-' . date('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(6));

        $student = \App\Models\PpdbStudent::create([
            'batch_id' => $batch->id,
            'registration_number' => $registration_number,
            'nik' => $request->nik,
            'nisn' => $request->nisn,
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'previous_school' => $request->previous_school,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'parent_phone' => $request->parent_phone,
            'address' => $request->address,
            'status' => 'pending',
        ]);

        $documents = [
            'Kartu Keluarga' => 'document_kk',
            'Akta Kelahiran' => 'document_akta',
            'Pas Foto' => 'document_foto',
        ];

        foreach ($documents as $type => $input_name) {
            if ($request->hasFile($input_name)) {
                $path = $request->file($input_name)->store('ppdb_documents', 'public');
                \App\Models\PpdbDocument::create([
                    'student_id' => $student->id,
                    'document_type' => $type,
                    'file_path' => $path,
                    'status' => 'pending',
                ]);
            }
        }

        return redirect()->route('public.ppdb.index')->with('success', 'Pendaftaran berhasil dikirim! Nomor Registrasi Anda: ' . $registration_number);
    }
}
