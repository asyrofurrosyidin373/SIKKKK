{{-- resources/views/varietas/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Database Varietas')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="text-center">
                    <h1 class="h1 fw-semibold text-success">Database Varietas Aneka Kacang</h1>
                </div>
            </div>
        </div>

        <!-- Navigation Cards -->
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <img src="{{ asset('kackedelai.jpg') }}" alt="Gambar Kacang Kedelai"
                                class="img-fluid rounded mb-3" style="height: 200px; object-fit: cover; width: 100%;">
                        </div>
                        <h4 class="card-title text-success">Varietas Kacang Kedelai</h4>
                        <p class="card-text text-muted">Koleksi varietas kedelai unggulan dengan produktivitas tinggi dan
                            tahan hama.</p>
                        <a href="{{ route('varietas.kedelai') }}" class="mt-3 btn btn-success w-100">
                            Jelajahi Kacang Kedelai
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <img src="{{ asset('kactanah.jpg') }}" alt="Gambar Kacang Tanah"
                                class="img-fluid rounded mb-3" style="height: 200px; object-fit: cover; width: 100%;">
                        </div>
                        <h4 class="card-title text-success">Varietas Kacang Tanah</h4>
                        <p class="card-text text-muted">Varietas kacang tanah dengan kandungan minyak tinggi dan adaptasi
                            luas.</p>
                        <a href="{{ route('varietas.kacang-tanah') }}" class="mt-3 btn btn-success w-100">
                            Jelajahi Kacang Tanah
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <img src="{{ asset('kachijau.jpg') }}" alt="Gambar Kacang Hijau"
                                class="img-fluid rounded mb-3" style="height: 200px; object-fit: cover; width: 100%;">
                        </div>
                        <h4 class="card-title text-success">Varietas Kacang Hijau</h4>
                        <p class="card-text text-muted">Varietas kacang hijau dengan protein tinggi dan umur genjah.</p>
                        <a href="{{ route('varietas.kacang-hijau') }}" class="mt-3 btn btn-success w-100">
                            </i>Jelajahi Kacang Hijau
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Varietas by Rata-rata Penjualan/Tahun -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title text-success mb-0">Varietas Unggul (Berdasarkan Rata-rata Penjualan per Tahun)</h4>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px">No</th>
                                        <th>Varietas</th>
                                        <th>Jenis</th>
                                        <th>Rata2</th>
                                        <th style="width: 120px">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($top_sales ?? collect()) as $idx => $item)
                                        <tr>
                                            <td>{{ $idx + 1 }}</td>
                                            <td>{{ $item['nama_varietas'] ?? '-' }}</td>
                                            <td>
                                                @php
                                                    $labelMap = [
                                                        'kedelai' => 'Kedelai',
                                                        'kacang-tanah' => 'Kacang Tanah',
                                                        'kacang-hijau' => 'Kacang Hijau',
                                                    ];
                                                    $jenisLabel = $labelMap[$item['jenis']] ?? ucfirst(str_replace('-', ' ', $item['jenis'] ?? ''));
                                                @endphp
                                                <span class="badge bg-success-subtle text-success fw-semibold">
                                                    {{ $jenisLabel }}
                                                </span>
                                            </td>
                                            <td>{{ number_format((float)($item['rata2'] ?? 0), 2) }}</td>
                                            <td>
                                                <a href="{{ route('varietas.show', ['type' => $item['jenis'], 'id' => $item['id']]) }}" class="btn btn-sm btn-success">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada data varietas unggul untuk ditampilkan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
