<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Formulir Pendaftaran PPDB - SUNTRI</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#004532",
                        "primary-container": "#065f46",
                        "on-surface": "#0d1c2e",
                        "on-surface-variant": "#3f4944",
                        "outline-variant": "#bec9c2",
                        "surface-bright": "#f8f9ff",
                        "error": "#ba1a1a",
                    }
                }
            }
        };
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f6fb; }
        .emerald-gradient { background: linear-gradient(135deg, #065f46 0%, #004532 100%); }
    </style>
</head>
<body class="text-on-surface antialiased">
    
    <div class="min-h-screen pb-12">
        {{-- Header --}}
        <header class="emerald-gradient text-white py-12 px-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white to-transparent"></div>
            <div class="max-w-3xl mx-auto relative z-10 text-center space-y-3">
                <h1 class="text-3xl font-bold">Formulir Pendaftaran Santri Baru</h1>
                <p class="text-white/80">Pendaftaran {{ $batch->name }}</p>
                <div class="inline-flex items-center gap-2 bg-white/20 px-4 py-1.5 rounded-full text-sm backdrop-blur-md border border-white/20 mt-4">
                    <span class="material-symbols-outlined text-[16px]">info</span>
                    Isi data diri dengan lengkap dan benar
                </div>
            </div>
        </header>

        {{-- Form Container --}}
        <div class="max-w-3xl mx-auto px-4 -mt-8 relative z-20">
            
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-xl shadow-sm">
                    <div class="flex items-center gap-2 text-red-700 font-bold mb-2">
                        <span class="material-symbols-outlined text-[20px]">error</span>
                        Terjadi Kesalahan
                    </div>
                    <ul class="list-disc pl-5 text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('public.ppdb.store', $batch->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-xl shadow-primary/5 p-6 md:p-8 space-y-8 border border-outline-variant/20">
                @csrf
                
                {{-- Data Pribadi --}}
                <div class="space-y-5">
                    <h2 class="text-xl font-bold flex items-center gap-2 text-primary border-b border-outline-variant/30 pb-3">
                        <span class="material-symbols-outlined">person</span> Data Pribadi Calon Santri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-on-surface mb-1">Nama Lengkap Sesuai Akta <span class="text-error">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-1">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        
                        @if($batch->unit != 'TK')
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-1">NISN (Opsional)</label>
                            <input type="text" name="nisn" value="{{ old('nisn') }}" maxlength="20" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-1">Tempat Lahir <span class="text-error">*</span></label>
                            <input type="text" name="birth_place" value="{{ old('birth_place') }}" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-1">Tanggal Lahir <span class="text-error">*</span></label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-1">Jenis Kelamin <span class="text-error">*</span></label>
                            <select name="gender" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-1">
                                Asal Sekolah 
                                @if(Str::startsWith($batch->unit, 'MTRQ')) <span class="text-error">*</span> @else (Opsional) @endif
                            </label>
                            <input type="text" name="previous_school" value="{{ old('previous_school') }}" {{ Str::startsWith($batch->unit, 'MTRQ') ? 'required' : '' }} class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                </div>

                {{-- Data Orang Tua & Alamat --}}
                <div class="space-y-5">
                    <h2 class="text-xl font-bold flex items-center gap-2 text-primary border-b border-outline-variant/30 pb-3">
                        <span class="material-symbols-outlined">family_restroom</span> Data Orang Tua & Kontak
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-1">Nama Ayah <span class="text-error">*</span></label>
                            <input type="text" name="father_name" value="{{ old('father_name') }}" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-1">Nama Ibu <span class="text-error">*</span></label>
                            <input type="text" name="mother_name" value="{{ old('mother_name') }}" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-on-surface mb-1">Nomor WhatsApp (Aktif) <span class="text-error">*</span></label>
                            <input type="text" name="parent_phone" value="{{ old('parent_phone') }}" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Contoh: 08123456789">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-on-surface mb-1">Alamat Lengkap <span class="text-error">*</span></label>
                            <textarea name="address" rows="3" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Berkas Persyaratan --}}
                <div class="space-y-5">
                    <h2 class="text-xl font-bold flex items-center gap-2 text-primary border-b border-outline-variant/30 pb-3">
                        <span class="material-symbols-outlined">folder_open</span> Upload Berkas Persyaratan
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="p-4 rounded-xl border border-outline-variant/50 bg-surface-bright flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <p class="font-bold text-sm">Kartu Keluarga (KK)</p>
                                <p class="text-xs text-on-surface-variant">Format: JPG, PNG, PDF (Max 2MB)</p>
                            </div>
                            <input type="file" name="document_kk" accept=".jpg,.jpeg,.png,.pdf" class="text-sm">
                        </div>
                        
                        <div class="p-4 rounded-xl border border-outline-variant/50 bg-surface-bright flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <p class="font-bold text-sm">Akta Kelahiran</p>
                                <p class="text-xs text-on-surface-variant">Format: JPG, PNG, PDF (Max 2MB)</p>
                            </div>
                            <input type="file" name="document_akta" accept=".jpg,.jpeg,.png,.pdf" class="text-sm">
                        </div>
                        
                        <div class="p-4 rounded-xl border border-outline-variant/50 bg-surface-bright flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <p class="font-bold text-sm">Pas Foto Terbaru (Berwarna)</p>
                                <p class="text-xs text-on-surface-variant">Format: JPG, PNG (Max 2MB)</p>
                            </div>
                            <input type="file" name="document_foto" accept=".jpg,.jpeg,.png" class="text-sm">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-outline-variant/30 flex flex-col-reverse md:flex-row justify-between items-center gap-4">
                    <a href="{{ route('public.ppdb.index') }}" class="text-sm font-bold text-on-surface-variant hover:text-primary transition-colors">
                        Kembali
                    </a>
                    <button type="submit" class="w-full md:w-auto px-8 py-3 emerald-gradient text-white font-bold rounded-xl shadow-lg hover:shadow-primary/30 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <span>Kirim Pendaftaran</span>
                        <span class="material-symbols-outlined text-[20px]">send</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
