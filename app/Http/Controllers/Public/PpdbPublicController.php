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
}
