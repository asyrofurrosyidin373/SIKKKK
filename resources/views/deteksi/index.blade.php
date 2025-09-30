@extends('layouts.app')

@section('styles')
    <style>
        :root {
            --primary-green: #2d5016;
            --secondary-green: #4a7c59;
            --light-green: #6b8e23;
            --accent-green: #8fbc8f;
            --dark-green: #1a3d0a;
            --text-dark: #2c3e50;
            --text-light: #ffffff;
            --bg-light: #f8f9fa;
        }

        .symptoms-section {
            background: linear-gradient(135deg, var(--bg-light) 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }

        .results-section {
            background: var(--text-light);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-height: 500px;
            border: 1px solid #e9ecef;
        }

        .plant-part {
            margin-bottom: 25px;
            padding: 15px;
            background: var(--text-light);
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
        }

        .plant-part h5 {
            color: var(--primary-green);
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-green);
        }

        .symptom-item {
            padding: 12px;
            margin: 8px 0;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            background: var(--bg-light);
            transition: all 0.3s ease;
        }

        .symptom-item:hover {
            border-color: var(--primary-green);
            background: rgba(45, 80, 22, 0.05);
            transform: translateX(5px);
        }

        .symptom-item.selected {
            border-color: var(--primary-green);
            background: rgba(45, 80, 22, 0.1);
        }

        .form-check-input:checked {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .result-item {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            background: var(--bg-light);
            transition: all 0.3s ease;
        }

        .result-item.high-confidence {
            border-color: var(--primary-green);
            background: linear-gradient(135deg, rgba(45, 80, 22, 0.1) 0%, rgba(74, 124, 89, 0.1) 100%);
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
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            color: var(--text-light);
            padding: 2rem 0;
            margin: -1.5rem -15px 2rem -15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .detection-header .row {
            padding: 0 15px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(45, 80, 22, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(45, 80, 22, 0.3);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            border-color: #6c757d;
            transform: translateY(-2px);
        }

        .btn-light {
            background: var(--text-light);
            border: 2px solid #e9ecef;
            color: var(--text-dark);
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-light:hover {
            background: var(--bg-light);
            border-color: var(--primary-green);
            color: var(--primary-green);
            transform: translateY(-2px);
        }

        .stats-card {
            background: var(--text-light);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }

        .filter-panel {
            background: var(--text-light);
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }

        .filter-panel .card-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            border-radius: 15px 15px 0 0 !important;
            border: none;
        }

        .form-label {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-select,
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: border-color 0.3s ease;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(45, 80, 22, 0.25);
        }

        .text-success {
            color: var(--primary-green) !important;
        }

        .border-bottom {
            border-bottom: 2px solid var(--accent-green) !important;
        }

        /* Spacing normalization for deteksi page */
        .deteksi-page .row {
            row-gap: 16px;
        }

        .deteksi-page .card {
            margin-bottom: 16px;
        }

        .deteksi-page .card-body>* {
            margin-bottom: 16px;
        }

        .deteksi-page .card-body>*:last-child {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container deteksi-page">


        <div class="row">
            <!-- Symptoms Selection -->
            <div class="col-md-6">
                <div class="card symptoms-section p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Pilih Gejala yang Diamati</h4>
                    </div>

                    <form id="detectionForm" method="GET" action="{{ route('deteksi.hasil') }}">
                        @csrf
                        <input type="hidden" name="gejala" id="gejalaInput" value="[]">
                        @php
                            $groupedGejala = $gejala->groupBy('daerah');
                        @endphp

                        @forelse($groupedGejala as $bagian => $gejalaList)
                            <div class="plant-part">
                                <h5 class="mt-3">{{ ucfirst($bagian ?: 'Umum') }}</h5>

                                @foreach ($gejalaList as $g)
                                    <div class="symptom-item" data-bagian="{{ $bagian }}">
                                        <div class="form-check">
                                            <input class="form-check-input symptom-checkbox" type="checkbox" name="gejala[]"
                                                value="{{ $g->id_gejala }}" id="gejala{{ $g->id_gejala }}"
                                                data-bagian="{{ $bagian }}">
                                            <label class="form-check-label" for="gejala{{ $g->id_gejala }}">
                                                <span class="fw-semibold">{{ $g->gejala }}</span>
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

                        <div class="d-flex justify-content-end align-items-center mt-4 gap-2">
                            <button type="button" class="btn btn-outline-success" id="resetBtn">Reset Pilihan</button>
                            <button type="submit" class="btn btn-success" id="diagnoseBtn">Diagnosa Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Detection Results -->
            <div class="col-md-6">
                <div class="card results-section p-3">

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
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detectionForm = document.getElementById('detectionForm');
            const detectionResults = document.getElementById('detectionResults');
            const diagnoseBtn = document.getElementById('diagnoseBtn');
            const resetBtn = document.getElementById('resetBtn');
            const symptomCheckboxes = document.querySelectorAll('.symptom-checkbox');

            function setInitialState() {
                detectionResults.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-hand-pointer fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Pilih Gejala untuk Memulai</h5>
                        <p class="text-muted mb-0">Pilih gejala yang terlihat untuk mendapatkan diagnosis</p>
                    </div>
                `;
                diagnoseBtn.disabled = true;
            }

            function getSelectedGejala() {
                return Array.from(document.querySelectorAll('.symptom-checkbox:checked')).map(cb => cb.value);
            }

            function updateButtonState() {
                diagnoseBtn.disabled = getSelectedGejala().length === 0;
            }

            function clearSelection() {
                symptomCheckboxes.forEach(cb => {
                    cb.checked = false;
                });
                const hiddenInput = document.getElementById('gejalaInput');
                if (hiddenInput) hiddenInput.value = '[]';
                updateButtonState();
                setInitialState();
            }

            symptomCheckboxes.forEach(cb => cb.addEventListener('change', updateButtonState));
            resetBtn.addEventListener('click', clearSelection);

            detectionForm.addEventListener('submit', function(e) {
                const gejala = getSelectedGejala();
                if (gejala.length === 0) {
                    e.preventDefault();
                    return;
                }
                // Inject selected gejala as JSON into hidden input, then allow normal GET submit
                document.getElementById('gejalaInput').value = JSON.stringify(gejala);
            });

            setInitialState();
        });
    </script>
@endsection
