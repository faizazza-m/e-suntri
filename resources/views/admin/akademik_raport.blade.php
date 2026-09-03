@extends('layouts.app')

@section('title', 'Cetak Raport — SUNTRI')

@push('styles')
<style>
    /* Styling for the screen preview to make it look like a paper */
    #printable-raport {
        background-color: white;
        color: black;
        font-family: 'Times New Roman', Times, serif; /* Classic formal font */
        padding: 40px;
        max-width: 800px;
        margin: 0 auto;
        position: relative;
    }
    
    /* Watermark for screen preview */
    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.1;
        width: 80%;
        z-index: 0;
        pointer-events: none;
    }

    .raport-content {
        position: relative;
        z-index: 10;
    }

    .raport-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .raport-table th, .raport-table td {
        border: 1px solid black;
        padding: 4px 8px;
        font-size: 12px;
    }
    .raport-table th {
        text-align: center;
    }

    .raport-header {
        border-bottom: 2px solid black;
        padding-bottom: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        position: relative;
    }
    .raport-header::after {
        content: "";
        position: absolute;
        bottom: -4px;
        left: 0;
        right: 0;
        border-bottom: 1px solid black;
    }
    .header-logo {
        width: 80px;
        height: auto;
        margin-right: 20px;
    }
    .header-text {
        text-align: center;
        flex-grow: 1;
    }
    .header-text h2 {
        margin: 0;
        font-size: 16px;
        font-weight: bold;
    }
    .header-text h1 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
    }
    .header-text p {
        margin: 5px 0 0;
        font-size: 11px;
        font-style: italic;
    }

    .raport-title {
        text-align: center;
        margin-bottom: 20px;
    }
    .raport-title h3 {
        margin: 0;
        font-size: 14px;
        font-weight: bold;
        text-decoration: underline;
    }
    .raport-title p {
        margin: 5px 0 0;
        font-size: 12px;
        font-weight: bold;
    }

    .biodata-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 12px;
    }
    .biodata-left, .biodata-right {
        width: 48%;
    }
    .biodata-row {
        display: flex;
        margin-bottom: 4px;
    }
    .biodata-label {
        width: 140px;
    }
    .biodata-separator {
        width: 10px;
    }
    .biodata-value {
        font-weight: bold;
    }

    /* Print specific styles */
    @media print {
        @page {
            size: A4 portrait;
            margin: 1cm;
        }
        body * {
            visibility: hidden;
        }
        #printable-raport, #printable-raport * {
            visibility: visible;
        }
        #printable-raport {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0;
            margin: 0;
            background-color: transparent !important;
            box-shadow: none;
            border: none;
        }
        .watermark {
            opacity: 0.1 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .print-hide {
            display: none !important;
        }
        .raport-table th {
            background-color: #f0f0f0 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endpush

@php
    function terbilang($x) {
        $angka = ["", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS"];
        if ($x < 12) return " " . $angka[(int)$x];
        elseif ($x < 20) return terbilang($x - 10) . " BELAS";
        elseif ($x < 100) return terbilang($x / 10) . " PULUH" . terbilang($x % 10);
        elseif ($x == 100) return "SERATUS";
        else return "LEBIH DARI SERATUS";
    }

    function predikatToKeterangan($predikat) {
        return match($predikat) {
            'A' => 'ISTIMEWA',
            'B' => 'SANGAT BAIK',
            'C' => 'BAIK',
            'D' => 'CUKUP',
            'E' => 'KURANG',
            default => '-'
        };
    }
@endphp

@section('content')
<div class="space-y-6">
    {{-- Page Header (Hidden in Print) --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up print-hide">
        <div>
            <div class="flex items-center gap-2 text-on-surface-variant mb-1">
                <a href="{{ route('akademik') }}" class="hover:text-primary transition-colors text-xs font-bold uppercase tracking-wider">Akademik</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-xs font-bold uppercase tracking-wider text-secondary">Raport</span>
            </div>
            <h1 class="text-2xl font-bold text-on-surface">Cetak Raport Santri</h1>
            <p class="text-sm text-on-surface-variant">Pilih santri untuk melihat dan mencetak laporan hasil belajar dengan format resmi.</p>
        </div>
        
        @if($selectedSantri && $nilais->count() > 0)
        <button onclick="window.print()" class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">print</span>
            Cetak Raport
        </button>
        @endif
    </div>

    {{-- Filter Form (Hidden in Print) --}}
    <div class="glassmorphism p-6 rounded-2xl border border-white/20 print-hide fade-in-up delay-1">
        <form method="GET" action="{{ route('akademik.raport') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-grow">
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Pilih Santri</label>
                <select name="santri_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                    <option value="">Cari Santri...</option>
                    @foreach($santris as $s)
                    <option value="{{ $s->id }}" {{ request('santri_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }} ({{ $s->nis }})</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-48">
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Semester</label>
                <select name="semester" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                    <option value="1" {{ request('semester') == 1 ? 'selected' : '' }}>1 (Ganjil)</option>
                    <option value="2" {{ request('semester') == 2 ? 'selected' : '' }}>2 (Genap)</option>
                </select>
            </div>
            <button type="submit" class="h-12 px-6 bg-secondary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Tampilkan</button>
        </form>
    </div>

    {{-- Printable Raport Area --}}
    @if($selectedSantri)
        @if($nilais->count() > 0)
        <div id="printable-raport" class="rounded-xl shadow-md fade-in-up delay-2">
            
            {{-- Watermark Logo --}}
            <img src="{{ asset('logo.jpg') }}" alt="Watermark" class="watermark">

            <div class="raport-content">
                {{-- Header Raport --}}
                <div class="raport-header">
                    <img src="{{ asset('logo.jpg') }}" alt="Logo" class="header-logo">
                    <div class="header-text">
                        <h2>YAYASAN RIJAALUL QUR'AN LIL HIFDZI WATTASMI</h2>
                        <h1>MA'HAD TAHFIDZ RIJAALUL QUR'AN KOTA BOGOR</h1>
                        <p>Kpp IPB, Baranang 3 Blok No.14, RT.05/RW.08, Tegallega, Kota Bogor, 16154</p>
                    </div>
                </div>

                {{-- Raport Title --}}
                <div class="raport-title">
                    <h3>LAPORAN HASIL BELAJAR SANTRI</h3>
                    <p>SEMESTER {{ request('semester', 1) == 1 ? 'GANJIL' : 'GENAP' }} TAHUN AJARAN 2025/2026</p>
                </div>

                {{-- Biodata --}}
                <div class="biodata-container">
                    <div class="biodata-left">
                        <div class="biodata-row">
                            <div class="biodata-label">Nama</div>
                            <div class="biodata-separator">:</div>
                            <div class="biodata-value uppercase">{{ $selectedSantri->nama }}</div>
                        </div>
                        <div class="biodata-row">
                            <div class="biodata-label">Nomor Induk Siswa</div>
                            <div class="biodata-separator">:</div>
                            <div class="biodata-value">{{ $selectedSantri->nis ?? '-' }}</div>
                        </div>
                        <div class="biodata-row">
                            <div class="biodata-label">Kelas</div>
                            <div class="biodata-separator">:</div>
                            <div class="biodata-value uppercase">{{ $selectedSantri->kelas->nama ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="biodata-right">
                        <div class="biodata-row">
                            <div class="biodata-label">Semester</div>
                            <div class="biodata-separator">:</div>
                            <div class="biodata-value">{{ request('semester', 1) == 1 ? '1 (Ganjil)' : '2 (Genap)' }}</div>
                        </div>
                        <div class="biodata-row">
                            <div class="biodata-label">Tahun ajaran</div>
                            <div class="biodata-separator">:</div>
                            <div class="biodata-value">2025/2026</div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Nilai Utama --}}
                <table class="raport-table">
                    <thead>
                        <tr style="background-color: #f0f0f0;">
                            <th rowspan="2" style="width: 5%;">NO</th>
                            <th rowspan="2" style="width: 30%;">MATA PELAJARAN</th>
                            <th rowspan="2" style="width: 8%;">KKM</th>
                            <th colspan="2">NILAI</th>
                            <th rowspan="2" style="width: 17%;">KETERANGAN</th>
                        </tr>
                        <tr style="background-color: #f0f0f0;">
                            <th style="width: 10%;">ANGKA</th>
                            <th style="width: 30%;">HURUF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $totalNilai = 0;
                            $count = 0;
                        @endphp
                        @foreach($nilais as $index => $n)
                        @php 
                            $nilaiBulat = round($n->nilai_akhir);
                            $totalNilai += $n->nilai_akhir;
                            $count++;
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ strtoupper($n->mapel->nama) }}</td>
                            <td style="text-align: center;">75</td>
                            <td style="text-align: center; font-weight: bold;">{{ $nilaiBulat }}</td>
                            <td style="font-style: italic; text-align: center;">{{ trim(terbilang($nilaiBulat)) }}</td>
                            <td style="text-align: center;">{{ predikatToKeterangan($n->predikat) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="3" style="text-align: center; font-style: italic; border-left: none;">
                                Nilai Rata-Rata : {{ $count > 0 ? round($totalNilai / $count, 1) : 0 }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

                {{-- Tabel Sekunder (Akhlak & Kehadiran) --}}
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    {{-- Akhlak --}}
                    <div style="flex: 1;">
                        <table class="raport-table" style="margin-bottom: 0;">
                            <thead>
                                <tr style="background-color: #f0f0f0;">
                                    <th colspan="2">AKHLAK DAN KEPRIBADIAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 70%;">Akhlak</td>
                                    <td style="text-align: center; width: 30%;">B</td>
                                </tr>
                                <tr>
                                    <td>Kebersihan</td>
                                    <td style="text-align: center;">A</td>
                                </tr>
                                <tr>
                                    <td>Kerapihan</td>
                                    <td style="text-align: center;">B</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Kehadiran --}}
                    <div style="flex: 1;">
                        <table class="raport-table" style="margin-bottom: 0;">
                            <thead>
                                <tr style="background-color: #f0f0f0;">
                                    <th colspan="2">KETIDAK HADIRAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 70%;">Izin</td>
                                    <td style="text-align: center; width: 30%;">{{ $kehadiran['izin'] > 0 ? $kehadiran['izin'] : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Sakit</td>
                                    <td style="text-align: center;">{{ $kehadiran['sakit'] > 0 ? $kehadiran['sakit'] : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Alpha</td>
                                    <td style="text-align: center;">{{ $kehadiran['alpha'] > 0 ? $kehadiran['alpha'] : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Catatan --}}
                <div style="margin-bottom: 30px;">
                    <div style="font-size: 12px; margin-bottom: 5px;">CATATAN:</div>
                    <div style="border: 1px solid black; padding: 10px; font-size: 12px; min-height: 60px;">
                        Alhamdulillah... Ananda dapat mengikuti pembelajaran dengan baik sehingga mendapatkan hasil yg memuaskan. Semoga Ananda lebih semangat lagi untuk mendapatkan hasil belajar yang lebih memuaskan. Baarakallahu fiikum
                    </div>
                </div>

                {{-- Signatures --}}
                <div style="display: flex; justify-content: space-between; text-align: center; font-size: 12px; margin-top: 40px; margin-bottom: 20px;">
                    <div style="width: 250px;">
                        <p style="margin-bottom: 80px;">Orang tua wali santri</p>
                        <p>(............................................)</p>
                    </div>
                    <div style="width: 250px;">
                        <p style="margin-bottom: 80px;">Kepala sekolah</p>
                        <p>DR. Ilham Waliyudin, M.A., M.Pd.</p>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="glassmorphism rounded-2xl border border-white/20 p-12 text-center fade-in-up delay-2">
            <span class="material-symbols-outlined text-5xl text-error mb-3">error</span>
            <h3 class="text-lg font-bold text-on-surface mb-1">Data Nilai Belum Tersedia</h3>
            <p class="text-sm text-on-surface-variant">Belum ada nilai yang diinputkan untuk santri ini pada semester tersebut.</p>
        </div>
        @endif
    @endif
</div>
@endsection
