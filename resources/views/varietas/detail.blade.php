{{-- resources/views/varietas/detail.blade.php --}}
@extends('layouts.app')

@section('title', $varietas->nama_varietas)

@section('content')
    <div class="container py-4">

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <h1 class="h2 mb-0 me-3">{{ $varietas->nama_varietas }}</h1>
                    <span class="badge bg-success fs-6">{{ $varietas->tahun }}</span>
                </div>
                <p class="text-muted">{{ $varietas->galur ?? 'N/A' }} • {{ $varietas->asal ?? 'N/A' }}</p>
            </div>
            <div class="col-lg-4 text-end">
                <button class="btn btn-outline-success" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Cetak
                </button>
                <button class="btn btn-primary" onclick="shareVarietas()">
                    <i class="fas fa-share me-2"></i>Bagikan
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Image & Basic Info -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        @if ($varietas->gambar || $varietas->foto_utama)
                            <img src="{{ asset('storage/' . ($varietas->gambar ?? $varietas->foto_utama)) }}" 
                                 class="img-fluid w-100 rounded-top"
                                 alt="{{ $varietas->nama_varietas }}" 
                                 style="height: 300px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded-top"
                                style="height: 300px;">
                                <i class="fas fa-seedling fa-4x text-muted"></i>
                            </div>
                        @endif

                        <div class="p-3">
                            <h6>Informasi Dasar</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">SK Mentan</td>
                                    <td><strong>{{ $varietas->sk ?? 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Galur</td>
                                    <td><strong>{{ $varietas->galur ?? 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Asal</td>
                                    <td><strong>{{ $varietas->asal ?? 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pengenal</td>
                                    <td><strong>{{ $varietas->pengenal ?? 'N/A' }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="col-lg-8">
                <!-- Production & Quality -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Produktivitas & Kualitas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h3 class="text-success mb-1">{{ $varietas->potensi_hasil ?? 'N/A' }} t/ha</h3>
                                    <small class="text-muted">Potensi Hasil</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h3 class="text-success mb-1">{{ $varietas->rata_hasil ?? 'N/A' }} t/ha</h3>
                                    <small class="text-muted">Rata-rata Hasil</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="fw-bold text-success">{{ $varietas->kadar_protein ?? 'N/A' }}%</div>
                                    <small class="text-muted">Protein</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="fw-bold text-success">{{ $varietas->kadar_lemak ?? 'N/A' }}%</div>
                                    <small class="text-muted">Lemak</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="fw-bold text-success">{{ $varietas->bobot ?? $varietas->bobot_biji ?? 'N/A' }} g</div>
                                    <small class="text-muted">Bobot 100 Biji</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="fw-bold text-success">{{ $varietas->warna_biji ?? 'N/A' }}</div>
                                    <small class="text-muted">Warna Biji</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Growth Characteristics -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Karakteristik Pertumbuhan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success rounded p-2 me-3">
                                        <i class="fas fa-flower text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $varietas->umur_berbunga ?? 'N/A' }} hari</div>
                                        <small class="text-muted">Umur Berbunga</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success rounded p-2 me-3">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $varietas->umur_masak ?? 'N/A' }} hari</div>
                                        <small class="text-muted">Umur Masak</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success p-2 me-3">
                                        <i class="fas fa-arrows-alt-v text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $varietas->tinggi_tanaman ?? 'N/A' }} cm</div>
                                        <small class="text-muted">Tinggi Tanaman</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disease Resistance - Fixed to handle missing relationships -->
                @if (
                    (method_exists($varietas, 'organisme') && $varietas->organisme) || 
                    (method_exists($varietas, 'resistensiOpt') && $varietas->resistensiOpt && $varietas->resistensiOpt->count() > 0) ||
                    ($varietas->ketahanan_hama ?? false) ||
                    ($varietas->ketahanan_penyakit ?? false)
                )
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Ketahanan Terhadap OPT</h5>
                        </div>
                        <div class="card-body">
                            {{-- Check if organisme relationship exists and has data --}}
                            @if (method_exists($varietas, 'organisme') && $varietas->organisme)
                                <div class="mb-3">
                                    <h6 class="text-success">Organisme Utama</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success me-2">{{ $varietas->organisme->nama_opt ?? 'N/A' }}</span>
                                        <small class="text-muted">{{ $varietas->organisme->jenis ?? '' }}</small>
                                    </div>
                                </div>
                            @endif

                            {{-- Check if resistensiOpt relationship exists and has data --}}
                            @if (method_exists($varietas, 'resistensiOpt') && $varietas->resistensiOpt && $varietas->resistensiOpt->count() > 0)
                                <div class="mb-3">
                                    <h6 class="text-primary">Resistensi Tambahan</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($varietas->resistensiOpt as $opt)
                                            <span class="badge bg-primary">
                                                {{ $opt->nama_opt ?? 'N/A' }}
                                                @if ($opt->pivot->tingkat_resistensi ?? false)
                                                    ({{ $opt->pivot->tingkat_resistensi }})
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Fallback for simple text fields --}}
                            @if ($varietas->ketahanan_hama ?? false)
                                <div class="mb-3">
                                    <h6 class="text-danger">Ketahanan Hama</h6>
                                    <p class="mb-0">{{ $varietas->ketahanan_hama }}</p>
                                </div>
                            @endif

                            @if ($varietas->ketahanan_penyakit ?? false)
                                <div>
                                    <h6 class="text-danger">Ketahanan Penyakit</h6>
                                    <p class="mb-0">{{ $varietas->ketahanan_penyakit }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Distribution Map - Fixed to handle missing relationships -->
                @if (method_exists($varietas, 'komoditas') && $varietas->komoditas && $varietas->komoditas->count() > 0)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Persebaran Wilayah</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">Varietas ini ditanam di {{ $varietas->komoditas->count() }} lokasi:
                            </p>
                            <div class="row g-2">
                                @foreach ($varietas->komoditas->take(6) as $komoditas)
                                    @if ($komoditas->kecamatan)
                                        @foreach ($komoditas->kecamatan->take(2) as $kecamatan)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                                    <i class="fas fa-map-pin text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-bold small">{{ $kecamatan->nama_kecamatan ?? 'N/A' }}</div>
                                                        <small class="text-muted">
                                                            {{ $kecamatan->kabupaten->nama_kabupaten ?? 'N/A' }},
                                                            {{ $kecamatan->kabupaten->provinsi->nama_provinsi ?? 'N/A' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>

                            @if (
                                $varietas->komoditas->sum(function ($k) {
                                    return $k->kecamatan ? $k->kecamatan->count() : 0;
                                }) > 6)
                                <div class="text-center mt-3">
                                    <a href="{{ route('peta') }}?varietas={{ $varietas->id }}"
                                        class="btn btn-outline-primary">
                                        <i class="fas fa-map me-2"></i>Lihat Semua di Peta
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Additional Information -->
                @if ($varietas->adaptasi_lingkungan ?? false || $varietas->rekomendasi_tanam ?? false)
                    <div class="card mt-4 border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-globe me-2"></i>Informasi Penanaman</h5>
                        </div>
                        <div class="card-body">
                            @if ($varietas->adaptasi_lingkungan ?? false)
                                <div class="mb-3">
                                    <h6 class="text-info">Adaptasi Lingkungan</h6>
                                    <p class="mb-0">{{ $varietas->adaptasi_lingkungan }}</p>
                                </div>
                            @endif
                            @if ($varietas->rekomendasi_tanam ?? false)
                                <div>
                                    <h6 class="text-info">Rekomendasi Penanaman</h6>
                                    <p class="mb-0">{{ $varietas->rekomendasi_tanam }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- General Information -->
                @if ($varietas->keterangan_umum ?? false)
                    <div class="card mt-4 border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Keterangan Umum</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $varietas->keterangan_umum }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Additional Info -->
        @if ($varietas->inventor ?? false)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Informasi Peneliti</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><strong>Inventor:</strong> {{ $varietas->inventor }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function shareVarietas() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $varietas->nama_varietas }}',
                        text: 'Varietas {{ $type }} dengan potensi hasil {{ $varietas->potensi_hasil ?? 'N/A' }} t/ha',
                        url: window.location.href
                    });
                } else {
                    // Fallback - copy to clipboard
                    navigator.clipboard.writeText(window.location.href).then(function() {
                        alert('Link berhasil disalin ke clipboard!');
                    });
                }
            }

            function showAllLocations() {
                // Show modal with all komoditas locations
                alert('Fitur ini akan menampilkan semua lokasi penanaman varietas ini.');
            }
        </script>
    @endpush
@endsection