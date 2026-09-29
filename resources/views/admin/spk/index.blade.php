@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">SPK Santri Teladan (Metode SAW)</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Pengaturan Bobot -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan Bobot Kriteria</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('spk.kriteria.update') }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Kriteria</th>
                                        <th>Bobot (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kriterias as $k)
                                    <tr>
                                        <td>{{ $k->kode }}<br><small class="text-muted">{{ ucfirst($k->sifat) }}</small></td>
                                        <td>{{ $k->nama }}</td>
                                        <td>
                                            <input type="number" name="bobot[{{ $k->id }}]" class="form-control form-control-sm" value="{{ $k->bobot }}" min="1" max="100" required>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Simpan Perubahan Bobot</button>
                    </form>
                    <div class="mt-3 small text-muted">
                        * C1 (Tahfizh) diambil dari jumlah baris setoran.<br>
                        * C2 (Kedisiplinan) diambil dari persentase hadir.<br>
                        * C3 (Akademik) diambil dari rata-rata nilai akhir.<br>
                        * C4 (Pelanggaran) diisi manual di tabel kanan.
                    </div>
                </div>
            </div>
        </div>

        <!-- Input C4 & Hasil Ranking -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Hasil Perankingan SAW</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('spk.penilaian.save') }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Nama Santri</th>
                                        <th>C1<br><small>Tahfizh</small></th>
                                        <th>C2<br><small>Disiplin(%)</small></th>
                                        <th>C3<br><small>Akademik</small></th>
                                        <th style="width: 120px;">C4<br><small>Pelanggaran</small></th>
                                        <th>Total Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rankings as $index => $r)
                                    <tr class="{{ $index == 0 ? 'table-warning font-weight-bold' : '' }}">
                                        <td>
                                            @if($index == 0) 🥇 
                                            @elseif($index == 1) 🥈 
                                            @elseif($index == 2) 🥉 
                                            @else {{ $index + 1 }} 
                                            @endif
                                        </td>
                                        <td>{{ $r['nama'] }}</td>
                                        <td>{{ $r['c1'] }}</td>
                                        <td>{{ $r['c2'] }}%</td>
                                        <td>{{ $r['c3'] }}</td>
                                        <td>
                                            <input type="number" name="c4[{{ $r['id'] }}]" class="form-control form-control-sm" value="{{ $r['c4'] }}" min="0">
                                        </td>
                                        <td>{{ number_format($r['total'], 4) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success mt-3 float-end">Simpan Nilai Pelanggaran (C4) & Hitung Ulang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
