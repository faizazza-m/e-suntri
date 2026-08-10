@extends('layouts.mobile')

@section('title', 'Perizinan Santri')
@section('greeting_name', 'Bapak/Ibu ' . explode(' ', auth()->user()->name)[0])

@section('content')

@if(session('success'))
<div class="bg-success/10 border border-success/20 text-success px-4 py-3 rounded-xl mb-4 relative" role="alert">
    <span class="block sm:inline text-sm font-bold">{{ session('success') }}</span>
</div>
@endif

@if($errors->any())
<div class="bg-error/10 border border-error/20 text-error px-4 py-3 rounded-xl mb-4 relative" role="alert">
    <ul class="list-disc pl-5 text-[13px] font-medium">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Form Pengajuan Izin --}}
<section class="clean-card bg-white p-5 border-none mb-6 shadow-sm">
    <h3 class="text-[16px] font-bold text-on-surface mb-5 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-[22px]" style="font-variation-settings: 'FILL' 1;">add_circle</span>
        Ajukan Izin Baru
    </h3>
    
    <form action="{{ route('wali.izin.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-[11px] font-bold text-gray-500 mb-1.5 uppercase tracking-widest">Santri</label>
            <select name="santri_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[14px] font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                @foreach($santris as $s)
                    <option value="{{ $s->id }}">{{ $s->nama }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-[11px] font-bold text-gray-500 mb-1.5 uppercase tracking-widest">Jenis Izin</label>
            <select name="jenis" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[14px] font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                <option value="sakit">Sakit</option>
                <option value="pulang">Pulang</option>
                <option value="kegiatan_luar">Kegiatan Luar</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
        
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-gray-500 mb-1.5 uppercase tracking-widest">Mulai</label>
                <input type="date" name="tanggal_mulai" required min="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-3 text-[13px] font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-[11px] font-bold text-gray-500 mb-1.5 uppercase tracking-widest">Selesai</label>
                <input type="date" name="tanggal_selesai" required min="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-3 text-[13px] font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>
        
        <div>
            <label class="block text-[11px] font-bold text-gray-500 mb-1.5 uppercase tracking-widest">Alasan</label>
            <textarea name="alasan" required rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[14px] font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Tuliskan alasan perizinan secara detail..."></textarea>
        </div>
        
        <button type="submit" class="w-full bg-primary hover:bg-primary-container hover:text-primary text-white font-bold py-3.5 rounded-[14px] shadow-sm transition-colors mt-2">
            Kirim Pengajuan
        </button>
    </form>
</section>



@endsection
