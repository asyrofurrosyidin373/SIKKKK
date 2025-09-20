@extends('layouts.app')

@section('styles')
    <style>
        .symptoms-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .results-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-height: 500px;
        }

        .plant-part {
            margin-bottom: 25px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .plant-part h5 {
            color: #198754;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #d4edda;
        }

        .symptom-item {
            padding: 12px;
            margin: 8px 0;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .symptom-item:hover {
            border-color: #198754;
            background: #d4edda;
            transform: translateX(5px);
        }

        .symptom-item.selected {
            border-color: #198754;
            background: #d4edda;
        }

        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        .result-item {
            border: 2px solid #dee2e6;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .result-item.high-confidence {
            border-color: #198754;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        }

        .result-item.medium-confidence {
            border-color: #ffc107;
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        }

        .result-item.low-confidence {
            border-color: #6c757d;
            background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%);
        }

        .confidence-badge {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .loading-spinner {
            display: none;
        }

        .loading .loading-spinner {
            display: inline-block;
        }

        .plant-icon {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }

        .detection-header {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="detection-header">
                    <div class="row align-items-center">
                        <div class="col-md-6 mt-4 mb-4">
                            <h2 class="mb-2 text-success">Deteksi Hama dan Penyakit Kedelai</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Symptoms Selection -->
            <div class="col-md-6">
                <div class="card symptoms-section p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Pilih Gejala yang Diamati</h4>
                    </div>

                    <form id="detectionForm">
                        @csrf
                        @php
                            $groupedGejala = $gejala->groupBy('daerah');
                        @endphp

                        @forelse($groupedGejala as $bagian => $gejalaList)
                            <div class="plant-part">
                                <h5 class="mt-3">
                                    @if ($bagian == 'Akar')
                                        <i class="fas fa-seedling plant-icon"></i>
                                    @elseif($bagian == 'Batang')
                                        <i class="fas fa-seedling plant-icon"></i>
                                    @elseif($bagian == 'Daun')
                                        <i class="fas fa-leaf plant-icon"></i>
                                    @else
                                        <i class="fas fa-question-circle plant-icon"></i>
                                    @endif
                                    {{ ucfirst($bagian ?: 'Umum') }}
                                </h5>

                                @foreach ($gejalaList as $g)
                                    <div class="symptom-item" data-bagian="{{ $bagian }}">
                                        <div class="form-check">
                                            <input class="form-check-input symptom-checkbox" type="checkbox" name="gejala[]"
                                                value="{{ $g->id_gejala }}" id="gejala{{ $g->id_gejala }}"
                                                data-bagian="{{ $bagian }}">
                                            <label class="form-check-label" for="gejala{{ $g->id_gejala }}">
                                                <strong>{{ $g->gejala }}</strong>
                                                @if ($g->id_gejala)
                                                    <small class="text-muted d-block">ID: {{ $g->id_gejala }}</small>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Belum ada data gejala yang tersedia.
                            </div>
                        @endforelse

                        <div class="d-flex justify-content-end align-items-center mt-4">
                            <button type="submit" class="btn btn-success" id="diagnoseBtn">Diagnosa Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Detection Results -->
            <div class="col-md-6">
                <div class="card results-section p-3">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Hasil Diagnosa</h4>
                        <div id="resultsStats" class="text-muted small" style="display: none;">
                            <i class="fas fa-clock me-1"></i>
                            <span id="detectionTime"></span>
                        </div>
                    </div>

                    <div id="detectionResults" class="mt-3">
                        <div class="text-center py-5">
                            <h5 class="text-muted">Pilih Gejala untuk Memulai</h5>
                            <p class="text-muted mb-0">
                                Pilih gejala yang terlihat pada tanaman kedelai Anda untuk mendapatkan diagnosis
                            </p>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="card-body">
                        <h6 class="card-title text-success">
                            <i class="fas fa-lightbulb me-2"></i>Tips Deteksi
                        </h6>
                        <ul class="small mb-0">
                            <li>Pilih minimal 2-3 gejala untuk hasil yang akurat</li>
                            <li>Perhatikan bagian tanaman yang terserang</li>
                            <li>Gejala pada beberapa bagian dapat meningkatkan akurasi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Cards -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-bug fa-3x text-success mb-3"></i>
                        <h5>Hama Kedelai</h5>
                        <p class="text-muted">Deteksi berbagai jenis hama yang menyerang tanaman kedelai</p>
                        <a href="{{ route('hama-penyakit.index') }}" class="btn btn-outline-success btn-sm">
                            Lihat Daftar Hama
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-virus fa-3x text-success mb-3"></i>
                        <h5>Penyakit Kedelai</h5>
                        <p class="text-muted">Identifikasi penyakit yang dapat merusak tanaman kedelai</p>
                        <a href="{{ route('penyakit.index') }}" class="btn btn-outline-success btn-sm">
                            Lihat Daftar Penyakit
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                        <h5>Pengendalian</h5>
                        <p class="text-muted">Metode pengendalian terintegrasi untuk hama dan penyakit</p>
                        <a href="{{ route('pengendalian.index') }}" class="btn btn-outline-success btn-sm">
                            Panduan Pengendalian
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detectionForm = document.getElementById('detectionForm');
            const detectionResults = document.getElementById('detectionResults');
            const diagnoseBtn = document.getElementById('diagnoseBtn');
            const selectedCountSpan = document.getElementById('selectedCount');
            const symptomCheckboxes = document.querySelectorAll('.symptom-checkbox');
            const resultsStats = document.getElementById('resultsStats');
            const detectionTimeSpan = document.getElementById('detectionTime');

            let detectionStartTime;

            // Update selected count and button state
            function updateSelectionState() {
                const selectedCheckboxes = document.querySelectorAll('.symptom-checkbox:checked');
                const count = selectedCheckboxes.length;

                selectedCountSpan.textContent = count;
                diagnoseBtn.disabled = count === 0;

                // Update visual state of symptom items
                document.querySelectorAll('.symptom-item').forEach(item => {
                    const checkbox = item.querySelector('.symptom-checkbox');
                    if (checkbox.checked) {
                        item.classList.add('selected');
                    } else {
                        item.classList.remove('selected');
                    }
                });
            }

            // Add event listeners to checkboxes
            symptomCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectionState);
            });

            // Clear selection function
            window.clearSelection = function() {
                symptomCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectionState();
                detectionResults.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-hand-pointer fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Pilih Gejala untuk Memulai</h5>
                        <p class="text-muted mb-0">
                            Pilih gejala yang terlihat pada tanaman kedelai Anda untuk mendapatkan diagnosis
                        </p>
                    </div>
                `;
                resultsStats.style.display = 'none';
            };

            // Handle form submission
            detectionForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                detectionStartTime = new Date();
                diagnoseBtn.classList.add('loading');
                diagnoseBtn.disabled = true;

                const formData = new FormData(detectionForm);
                const gejala = formData.getAll('gejala[]');

                // Show loading state
                detectionResults.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="text-primary">Menganalisis Gejala...</h5>
                        <p class="text-muted">Mencari kecocokan dengan database hama dan penyakit</p>
                    </div>
                `;

                try {
                    const response = await fetch('{{ route('deteksi.diagnose') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            gejala
                        }),
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const results = await response.json();
                    const detectionTime = ((new Date() - detectionStartTime) / 1000).toFixed(1);

                    displayResults(results, detectionTime);

                } catch (error) {
                    console.error('Error diagnosing:', error);
                    detectionResults.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5 class="text-warning">Terjadi Kesalahan</h5>
                            <p class="text-muted mb-3">Tidak dapat melakukan diagnosis. Silakan coba lagi.</p>
                            <button class="btn btn-primary" onclick="document.getElementById('detectionForm').dispatchEvent(new Event('submit'))">
                                <i class="fas fa-redo me-2"></i>Coba Lagi
                            </button>
                        </div>
                    `;
                } finally {
                    diagnoseBtn.classList.remove('loading');
                    updateSelectionState();
                }
            });

            function displayResults(results, detectionTime) {
                detectionTimeSpan.textContent = $ {
                    detectionTime
                }
                s;
                resultsStats.style.display = 'block';

                if (results.length === 0) {
                    detectionResults.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak Ada Hasil yang Cocok</h5>
                            <p class="text-muted mb-3">Gejala yang dipilih tidak cocok dengan database kami</p>
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-primary btn-sm" onclick="clearSelection()">
                                    <i class="fas fa-redo me-1"></i>Coba Lagi
                                </button>
                                <a href="{{ route('hama.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-list me-1"></i>Lihat Semua
                                </a>
                            </div>
                        </div>
                    `;
                    return;
                }

                let resultsHtml = `
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                Ditemukan ${results.length} kemungkinan hasil
                            </h6>
                            <a href="{{ route('deteksi.hasil') }}?gejala=${encodeURIComponent(JSON.stringify(results.map(r => r.id)))}" class="btn btn-success btn-sm">
                                <i class="fas fa-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                `;

                results.forEach((result, index) => {
                    const confidence = Math.round(result.confidence_score || 0);
                    let confidenceClass, confidenceIcon, confidenceBadgeClass;

                    if (confidence >= 80) {
                        confidenceClass = 'high-confidence';
                        confidenceIcon = 'fas fa-check-circle text-success';
                        confidenceBadgeClass = 'bg-success';
                    } else if (confidence >= 60) {
                        confidenceClass = 'medium-confidence';
                        confidenceIcon = 'fas fa-exclamation-circle text-warning';
                        confidenceBadgeClass = 'bg-warning';
                    } else {
                        confidenceClass = 'low-confidence';
                        confidenceIcon = 'fas fa-question-circle text-secondary';
                        confidenceBadgeClass = 'bg-secondary';
                    }

                    const jenisIcon = result.terjangkit === 'Hama' ? 'fas fa-bug text-danger' :
                        'fas fa-virus text-warning';
                    const jenisBadge = result.terjangkit === 'Hama' ? 'bg-danger' : 'bg-warning';

                    resultsHtml += `
                        <div class="result-item ${confidenceClass}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <i class="${jenisIcon} me-2"></i>
                                        ${result.nama_penyakit}
                                    </h6>
                                    <div class="d-flex gap-2 mb-2">
                                        <span class="badge ${jenisBadge}">${result.terjangkit}</span>
                                        <span class="badge ${confidenceBadgeClass} confidence-badge">${confidence}% cocok</span>
                                        <span class="badge bg-light text-dark">${result.jenis_tanaman}</span>
                                    </div>
                                </div>
                                <i class="${confidenceIcon} fa-lg"></i>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <small class="text-muted d-block mb-1">Gejala yang cocok:</small>
                                    <div class="d-flex flex-wrap gap-1">
                                        ${result.matched_symptoms ? result.matched_symptoms.slice(0, 3).map(symptom => 
                                            <span class="badge bg-success small">${symptom.daerah}: ${symptom.gejala}</span>
                                        ).join('') : ''}
                                        ${result.matched_symptoms && result.matched_symptoms.length > 3 ? 
                                            <span class="badge bg-secondary small">+${result.matched_symptoms.length - 3} lainnya</span> : ''
                                        }
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <small class="text-muted d-block">ID: ${result.id_penyakit}</small>
                                    <div class="mt-2">
                                        <button class="btn btn-outline-primary btn-sm" onclick="viewDetail('${result.id_penyakit}')">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                detectionResults.innerHTML = resultsHtml;
            }

            // View detail function
            window.viewDetail = function(idPenyakit) {
                // Redirect to detail page or open modal
                window.open({{ url('/hama') }} / $ {
                    idPenyakit
                }, '_blank');
            };

            // Initialize
            updateSelectionState();
        });
    </script>
@endsection
