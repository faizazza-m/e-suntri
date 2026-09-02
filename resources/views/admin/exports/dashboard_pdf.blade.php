<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan - {{ $bulan }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #004532; margin-bottom: 20px; padding-bottom: 10px; }
        .header h1 { font-size: 20px; margin: 0; color: #004532; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 14px; }
        .section { margin-bottom: 25px; }
        .section h2 { font-size: 14px; background-color: #004532; color: #fff; padding: 5px 10px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background-color: #f4f4f4; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .summary-box { display: inline-block; width: 48%; margin-bottom: 10px; vertical-align: top; }
        .summary-box p { margin: 3px 0; }
        .summary-title { font-weight: bold; text-transform: capitalize; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Evaluasi Bulanan Santri</h1>
        <p>Periode: <strong>{{ $bulan }}</strong></p>
    </div>

    <!-- Kehadiran -->
    <div class="section">
        <h2>1. Rekapitulasi Kehadiran</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center">Status Kehadiran</th>
                    <th class="text-center">Total (Hari/Kali)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Hadir</td>
                    <td class="text-center">{{ $kehadiran['hadir'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Sakit</td>
                    <td class="text-center">{{ $kehadiran['sakit'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td class="text-center">{{ $kehadiran['izin'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Alpha</td>
                    <td class="text-center">{{ $kehadiran['alpha'] ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
        <p><em>*Data ditarik dari seluruh absensi santri aktif selama periode bulan berjalan.</em></p>
    </div>

    <!-- Setoran Hafalan -->
    <div class="section">
        <h2>2. Rekapitulasi Setoran Hafalan & Muraja'ah</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Jenis Setoran</th>
                    <th class="text-center">Total Frekuensi Setoran</th>
                    <th class="text-center">Estimasi Halaman (Total)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Hafalan Baru (Ziyadah)</strong></td>
                    <td class="text-center">{{ $totalSetoranBaru }} kali</td>
                    <td class="text-center">{{ $halamanHafalanBaru }} Halaman</td>
                </tr>
                <tr>
                    <td><strong>Muraja'ah</strong></td>
                    <td class="text-center">{{ $totalSetoranMurojaah }} kali</td>
                    <td class="text-center">{{ $halamanMurojaah }} Halaman</td>
                </tr>
            </tbody>
        </table>
        <p style="font-size: 10px; color: #555;">
            *Catatan: Estimasi halaman dihitung dari akumulasi jumlah ayat yang disetorkan (asumsi 1 Halaman &asymp; 15 Ayat). 
            Setoran tanpa rincian ayat dihitung sebagai 1 halaman secara bawaan.
        </p>
    </div>

    <div style="margin-top: 50px; text-align: right; font-size: 11px;">
        <p>Dicetak pada: {{ now()->locale('id')->isoFormat('D MMMM YYYY HH:mm') }}</p>
        <p>Sistem Informasi Pesantren e-Suntri</p>
    </div>

</body>
</html>
