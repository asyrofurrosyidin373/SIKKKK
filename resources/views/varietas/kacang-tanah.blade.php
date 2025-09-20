{{-- resources/views/varietas/kacang-tanah.blade.php --}}
@extends('layouts.app')

@section('title', 'Varietas Kacang Tanah')

@section('content')
    <div class="container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <h1 class="h2 text-success"><i class="fas fa-seedling me-2"></i>Varietas Kacang Tanah</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Filter Pencarian</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="filterForm">
                            <!-- Filter Form -->
                            <div class="container-fluid px-0">
                                <div class="row g-4">
                                    <!-- Kolom Kiri -->
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column gap-3">
                                            <!-- Tahun -->
                                            <div class="d-flex align-items-center">
                                                <label class="form-label mb-0 me-3"
                                                    style="min-width: 130px; font-weight: 500;">Tahun</label>
                                                <div class="flex-fill position-relative">
                                                    <input type="range" name="tahun" class="form-range" min="2000"
                                                        max="2024" value="{{ request('tahun', 2000) }}" id="rangeThun"
                                                        style="height: 10px;">
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">2000</small>
                                                        <small class="text-muted">2025</small>
                                                    </div>
                                                    <div class="mt-1">
                                                        <small class="text-success fw-bold">Nilai: <span
                                                                id="tahunValue">{{ request('tahun', 2000) }}</span></small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Potensi Hasil -->
                                            <div class="d-flex align-items-center">
                                                <label class="form-label mb-0 me-3"
                                                    style="min-width: 130px; font-weight: 500;">Potensi Hasil</label>
                                                <div class="flex-fill position-relative">
                                                    <input type="range" name="potensi_hasil" class="form-range"
                                                        min="0" max="10" step="0.1"
                                                        value="{{ request('potensi_hasil', 0) }}" id="rangePotensi"
                                                        style="height: 8px;">
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">0</small>
                                                        <small class="text-muted">10 ton/ha</small>
                                                    </div>
                                                    <div class="mt-1">
                                                        <small class="text-success fw-bold">Nilai: <span
                                                                id="potensiValue">{{ request('potensi_hasil', 0) }}</span>
                                                            ton/ha</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Rata-rata Hasil -->
                                            <div class="d-flex align-items-center">
                                                <label class="form-label mb-0 me-3"
                                                    style="min-width: 130px; font-weight: 500;">Rata-rata Hasil</label>
                                                <div class="flex-fill position-relative">
                                                    <input type="range" name="rata_hasil" class="form-range"
                                                        min="0" max="8" step="0.1"
                                                        value="{{ request('rata_hasil', 0) }}" id="rangeRata"
                                                        style="height: 8px;">
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">0</small>
                                                        <small class="text-muted">8 ton/ha</small>
                                                    </div>
                                                    <div class="mt-1">
                                                        <small class="text-success fw-bold">Nilai: <span
                                                                id="rataValue">{{ request('rata_hasil', 0) }}</span>
                                                            ton/ha</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Umur Berbunga -->
                                            <div class="d-flex align-items-center">
                                                <label class="form-label mb-0 me-3"
                                                    style="min-width: 130px; font-weight: 500;">Umur Berbunga</label>
                                                <div class="flex-fill position-relative">
                                                    <input type="range" name="umur_berbunga" class="form-range"
                                                        min="0" max="60"
                                                        value="{{ request('umur_berbunga', 0) }}" id="rangeBerbunga"
                                                        style="height: 8px;">
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">0</small>
                                                        <small class="text-muted">60 hari</small>
                                                    </div>
                                                    <div class="mt-1">
                                                        <small class="text-success fw-bold">Nilai: <span
                                                                id="bungaValue">{{ request('umur_berbunga', 0) }}</span>
                                                            hari</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Umur Masak -->
                                            <div class="d-flex align-items-center">
                                                <label class="form-label mb-0 me-3"
                                                    style="min-width: 130px; font-weight: 500;">Umur Masak</label>
                                                <div class="flex-fill position-relative">
                                                    <input type="range" name="umur_masak_range" class="form-range"
                                                        min="60" max="120"
                                                        value="{{ request('umur_masak_range', 60) }}" id="rangeMasak"
                                                        style="height: 8px;">
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">60</small>
                                                        <small class="text-muted">120 hari</small>
                                                    </div>
                                                    <div class="mt-1">
                                                        <small class="text-success fw-bold">Nilai: <span
                                                                id="masakValue">{{ request('umur_masak_range', 60) }}</span>
                                                            hari</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kolom Kanan -->
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column gap-3">
                                            <!-- Tinggi Tanaman -->
                                            <div class="d-flex align-items-center">
                                                <label class="form-label mb-0 me-3"
                                                    style="min-width: 130px; font-weight: 500;">Tinggi Tanaman</label>
                                                <div class="flex-fill position-relative">
                                                    <input type="range" name="tinggi_tanaman" class="form-range"
                                                        min="30" max="100"
                                                        value="{{ request('tinggi_tanaman', 30) }}" id="rangeTinggi"
                                                        style="height: 8px;">
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">30</small>
                                                        <small class="text-muted">100 cm</small>
                                                    </div>
                                                    <div class="mt-1">
                                                        <small class="text-success fw-bold">Nilai: <span
                                                                id="tinggiValue">{{ request('tinggi_tanaman', 30) }}</span>
                                                            cm</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bobot -->
                                            <div class="d-flex align-items-center">
                                                <label class="form-label mb-0 me-3"
                                                    style="min-width: 130px; font-weight: 500;">Bobot</label>
                                                <div class="flex-fill position-relative">
                                                    <input type="range" name="bobot" class="form-range"
                                                        min="0" max="100"
                                                        value="{{ request('bobot', 0) }}" id="rangeBobot"
                                                        style="height: 8px;">
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">0</small>
                                                        <small class="text-muted">100 g/100biji</small>
                                                    </div>
                                                    <div class="mt-1">
                                                        <small class="text-success fw-bold">Nilai: <span
                                                                id="bobotValue">{{ request('bobot', 0) }}</span>
                                                            g/100biji</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kadar Protein -->
                                            <div class="d-flex align-items-center">
                                                <label class="form-label mb-0 me-3"
                                                    style="min-width: 130px; font-weight: 500;">Kadar Protein</label>
                                                <div class="flex-fill position-relative">
                                                    <input type="range" name="kadar_protein" class="form-range"
                                                        min="0" max="50" step="0.1"
                                                        value="{{ request('kadar_protein', 0) }}" id="rangeProtein"
                                                        style="height: 8px;">
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">0</small>
                                                        <small class="text-muted">50%</small>
                                                    </div>
                                                    <div class="mt-1">
                                                        <small class="text-success fw-bold">Nilai: <span
                                                                id="proteinValue">{{ request('kadar_protein', 0) }}</span>%</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kadar Lemak -->
                                            <div class="d-flex align-items-center">
                                                <label class="form-label mb-0 me-3"
                                                    style="min-width: 130px; font-weight: 500;">Kadar Lemak</label>
                                                <div class="flex-fill position-relative">
                                                    <input type="range" name="kadar_lemak" class="form-range"
                                                        min="0" max="50" step="0.1"
                                                        value="{{ request('kadar_lemak', 0) }}" id="rangeLemak"
                                                        style="height: 8px;">
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">0</small>
                                                        <small class="text-muted">50%</small>
                                                    </div>
                                                    <div class="mt-1">
                                                        <small class="text-success fw-bold">Nilai: <span
                                                                id="lemakValue">{{ request('kadar_lemak', 0) }}</span>%</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Cari -->
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <a href="{{ route('varietas.kacang-tanah') }}"
                                            class="btn btn-outline-secondary px-5 py-2 w-10">
                                            <i class="fas fa-undo me-2"></i>Reset
                                        </a>
                                        <button type="submit" class="btn btn-success px-5 py-2 me-2 w-10">
                                            <i class="fas fa-search me-2"></i>CARI
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        @if (request()->hasAny([
                'tahun',
                'potensi_hasil',
                'rata_hasil',
                'umur_berbunga',
                'umur_masak_range',
                'tinggi_tanaman',
                'bobot',
                'kadar_protein',
                'kadar_lemak',
            ]))
            <div class="row mb-4" id="resultsSection">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>Hasil Pencarian
                                @if (isset($varietas) && $varietas->count() > 0)
                                    <span class="badge bg-success ms-2">{{ $varietas->total() }} varietas ditemukan</span>
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @if (isset($varietas) && $varietas->count() > 0)
                                <!-- Results Grid -->
                                <div class="row g-4">
                                    @foreach ($varietas as $item)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card varietas-card h-100 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <h6 class="card-title text-success fw-bold mb-0">
                                                            {{ $item->nama_varietas }}</h6>
                                                        <span class="badge badge-custom">{{ $item->tahun }}</span>
                                                    </div>

                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Potensi Hasil</small>
                                                            <strong class="text-success">{{ $item->potensi_hasil }}
                                                                ton/ha</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Rata-rata Hasil</small>
                                                            <strong class="text-success">{{ $item->rata_hasil }}
                                                                ton/ha</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Umur Berbunga</small>
                                                            <strong>{{ $item->umur_berbunga }} hari</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Umur Masak</small>
                                                            <strong>{{ $item->umur_masak }} hari</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Tinggi</small>
                                                            <strong>{{ $item->tinggi_tanaman }} cm</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Bobot</small>
                                                            <strong>{{ $item->bobot }} g/100biji</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Protein</small>
                                                            <strong>{{ $item->kadar_protein }}%</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Lemak</small>
                                                            <strong>{{ $item->kadar_lemak }}%</strong>
                                                        </div>
                                                    </div>

                                                    @if ($item->warna_biji)
                                                        <div class="mt-3">
                                                            <small class="text-muted">Warna Biji:</small>
                                                            <span class="badge bg-secondary">{{ $item->warna_biji }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="card-footer bg-light">
                                                    <button class="btn btn-sm btn-outline-success w-100"
                                                        onclick="viewDetail('{{ $item->id }}')">
                                                        <i class="fas fa-eye me-1"></i>Lihat Detail
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- No Results -->
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Tidak ada varietas yang ditemukan</h5>
                                        <p class="text-muted">Coba ubah kriteria pencarian Anda</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if (isset($varietas) && $varietas->hasPages())
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $varietas->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-filter fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Gunakan filter di atas untuk mencari varietas kacang tanah</h5>
                    <p class="text-muted">Atur kriteria pencarian sesuai kebutuhan Anda</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Popup -->
    <div class="modal fade" id="noResultsModal" tabindex="-1" aria-labelledby="noResultsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="noResultsModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Pencarian Tidak Ditemukan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-search-minus fa-4x text-muted mb-3"></i>
                    <h6 class="mb-3">Tidak ada varietas yang sesuai dengan kriteria Anda</h6>
                    <p class="text-muted mb-0">
                        Coba ubah atau kurangi kriteria filter untuk mendapatkan hasil yang lebih luas
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .varietas-card:hover {
                transform: translateY(-5px);
                transition: transform 0.3s ease;
            }

            .badge {
                font-size: 0.8em;
            }

            .badge-custom {
                background-color: #198754;
                color: white;
            }

            .form-range::-webkit-slider-thumb {
                background: #198754 !important;
                border: 2px solid #fff !important;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15) !important;
                width: 15px !important;
                height: 15px !important;
                border-radius: 50% !important;
            }

            .form-range::-webkit-slider-track {
                background: #e9ecef !important;
                height: 8px !important;
                border-radius: 4px !important;
            }

            .form-range::-moz-range-thumb {
                background: #198754 !important;
                border: 2px solid #fff !important;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15) !important;
                width: 20px !important;
                height: 20px !important;
                border-radius: 50% !important;
            }

            .form-range::-moz-range-track {
                background: #e9ecef !important;
                height: 8px !important;
                border-radius: 4px !important;
            }

            .form-range:focus::-webkit-slider-thumb {
                box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.25) !important;
            }

            .form-range:focus::-moz-range-thumb {
                box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.25) !important;
            }

            .modal-content {
                border-radius: 15px;
                border: none;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }

            #resultsSection {
                animation: fadeInUp 0.5s ease-in-out;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function updateRangeValues() {
                    // Tahun
                    const tahunSlider = document.getElementById('rangeThun');
                    const tahunValue = document.getElementById('tahunValue');
                    if (tahunSlider && tahunValue) {
                        tahunSlider.addEventListener('input', function() {
                            tahunValue.textContent = this.value;
                        });
                    }

                    // Potensi Hasil
                    const potensiSlider = document.getElementById('rangePotensi');
                    const potensiValue = document.getElementById('potensiValue');
                    if (potensiSlider && potensiValue) {
                        potensiSlider.addEventListener('input', function() {
                            potensiValue.textContent = this.value;
                        });
                    }

                    // Rata-rata Hasil
                    const rataSlider = document.getElementById('rangeRata');
                    const rataValue = document.getElementById('rataValue');
                    if (rataSlider && rataValue) {
                        rataSlider.addEventListener('input', function() {
                            rataValue.textContent = this.value;
                        });
                    }

                    // Umur Berbunga
                    const bungaSlider = document.getElementById('rangeBerbunga');
                    const bungaValue = document.getElementById('bungaValue');
                    if (bungaSlider && bungaValue) {
                        bungaSlider.addEventListener('input', function() {
                            bungaValue.textContent = this.value;
                        });
                    }

                    // Umur Masak
                    const masakSlider = document.getElementById('rangeMasak');
                    const masakValue = document.getElementById('masakValue');
                    if (masakSlider && masakValue) {
                        masakSlider.addEventListener('input', function() {
                            masakValue.textContent = this.value;
                        });
                    }

                    // Tinggi Tanaman
                    const tinggiSlider = document.getElementById('rangeTinggi');
                    const tinggiValue = document.getElementById('tinggiValue');
                    if (tinggiSlider && tinggiValue) {
                        tinggiSlider.addEventListener('input', function() {
                            tinggiValue.textContent = this.value;
                        });
                    }

                    // Bobot
                    const bobotSlider = document.getElementById('rangeBobot');
                    const bobotValue = document.getElementById('bobotValue');
                    if (bobotSlider && bobotValue) {
                        bobotSlider.addEventListener('input', function() {
                            bobotValue.textContent = this.value;
                        });
                    }

                    // Protein
                    const proteinSlider = document.getElementById('rangeProtein');
                    const proteinValue = document.getElementById('proteinValue');
                    if (proteinSlider && proteinValue) {
                        proteinSlider.addEventListener('input', function() {
                            proteinValue.textContent = this.value;
                        });
                    }

                    // Lemak
                    const lemakSlider = document.getElementById('rangeLemak');
                    const lemakValue = document.getElementById('lemakValue');
                    if (lemakSlider && lemakValue) {
                        lemakSlider.addEventListener('input', function() {
                            lemakValue.textContent = this.value;
                        });
                    }
                }

                updateRangeValues();
                @if (request()->hasAny([
                        'tahun',
                        'potensi_hasil',
                        'rata_hasil',
                        'umur_berbunga',
                        'umur_masak_range',
                        'tinggi_tanaman',
                        'bobot',
                        'kadar_protein',
                        'kadar_lemak',
                    ]) &&
                        (!isset($varietas) || $varietas->count() === 0))
                    var noResultsModal = new bootstrap.Modal(document.getElementById('noResultsModal'));
                    noResultsModal.show();
                @endif
            });

            function viewDetail(id) {
                window.location.href = `/varietas/kacang-tanah/${id}`;
            }
        </script>
    @endpush
@endsection