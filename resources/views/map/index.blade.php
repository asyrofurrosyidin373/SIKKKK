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

    .brmp-header {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: var(--text-light);
        padding: 2rem 0;
        margin: -1.5rem -15px 2rem -15px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .brmp-header .row {
        padding: 0 15px;
    }

    .brmp-header h2 {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .brmp-header p {
        opacity: 0.9;
        margin-bottom: 0;
    }

    .filter-card {
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-radius: 15px;
    }

    .filter-card .card-header {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        border-radius: 15px 15px 0 0 !important;
        border: none;
    }

    .filter-card .card-body {
        padding: 1.5rem;
    }

    .form-label {
        color: var(--text-dark);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-select, .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.2rem rgba(45, 80, 22, 0.25);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
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

    .map-card {
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }

    .map-card .card-header {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        border: none;
    }

    .result-card {
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-radius: 15px;
    }

    .result-card .card-header {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        border-radius: 15px 15px 0 0 !important;
        border: none;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: var(--bg-light);
        border-bottom: 2px solid var(--primary-green);
        color: var(--text-dark);
        font-weight: 600;
    }

    .table tbody tr:hover {
        background-color: rgba(45, 80, 22, 0.05);
    }

    .custom-marker {
        border: 2px solid var(--text-light);
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .popup-content {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .popup-content h6 {
        color: var(--primary-green);
        font-weight: 600;
    }

    .text-success {
        color: var(--primary-green) !important;
    }

    .border-bottom {
        border-bottom: 2px solid var(--accent-green) !important;
    }

    .form-check-input:checked {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }

    .form-check-label {
        color: var(--text-dark);
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="brmp-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2">
                    <i class="fas fa-map-marked-alt me-3"></i>
                    SIG BRMP Aneka Kacang - Peta Tematik
                </h2>
                <p class="mb-0">Sistem Informasi Geografis untuk Analisis Produksi Aneka Kacang</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex justify-content-end gap-2">
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        Interactive Map
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Row utama -->
    <div class="row mb-4 flex-column-reverse flex-lg-row">
        <!-- PETA -->
        <div class="col-lg-8 col-md-12 mb-3 mb-lg-0 order-lg-1 order-2">
            <div class="card map-card">
                <div class="card-header text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-map me-2"></i>Peta Interaktif</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-light" id="resetMap">
                            <i class="fas fa-undo me-1"></i>Reset
                        </button>
                        <button type="button" class="btn btn-light" id="fullscreenMap">
                            <i class="fas fa-expand me-1"></i>Fullscreen
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="map" class="map-container" style="height: 600px;"></div>
                </div>
            </div>
        </div>

        <!-- FILTER ENHANCED -->
        <div class="col-lg-4 col-md-12 order-lg-2 order-1">
            <div class="sticky-top" style="top: 76px;">
                <div class="card h-100 filter-card">
                    <div class="card-header text-white">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Canggih</h5>
                    </div>
                    <div class="card-body">
                        <form id="searchForm">
                            <!-- Filter Wilayah -->
                            <div class="mb-4">
                                <h6 class="text-success border-bottom pb-2 mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Filter Wilayah
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label">Provinsi</label>
                                    <select class="form-select" id="provinsi" name="provinsi">
                                        <option value="">Semua Provinsi</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kabupaten</label>
                                    <select class="form-select" id="kabupaten" name="kabupaten" disabled>
                                        <option value="">Semua Kabupaten</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kecamatan</label>
                                    <select class="form-select" id="kecamatan" name="kecamatan" disabled>
                                        <option value="">Semua Kecamatan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Komoditas -->
                            <div class="mb-4">
                                <h6 class="text-success border-bottom pb-2 mb-3">
                                    <i class="fas fa-seedling me-2"></i>Filter Komoditas
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kacang</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="kedelai" name="komoditas[]" value="kedelai" checked>
                                        <label class="form-check-label" for="kedelai">
                                            <i class="fas fa-seedling text-success me-1"></i>Kedelai
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="kacang_tanah" name="komoditas[]" value="kacang_tanah" checked>
                                        <label class="form-check-label" for="kacang_tanah">
                                            <i class="fas fa-seedling text-warning me-1"></i>Kacang Tanah
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="kacang_hijau" name="komoditas[]" value="kacang_hijau" checked>
                                        <label class="form-check-label" for="kacang_hijau">
                                            <i class="fas fa-seedling text-info me-1"></i>Kacang Hijau
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Produksi -->
                            <div class="mb-4">
                                <h6 class="text-success border-bottom pb-2 mb-3">
                                    <i class="fas fa-chart-bar me-2"></i>Filter Produksi
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label">Luas Tanam (Ha)</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm" id="luasMin" name="luas_min" placeholder="Min">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm" id="luasMax" name="luas_max" placeholder="Max">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Produktivitas (Ton/Ha)</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm" id="produktivitasMin" name="produktivitas_min" placeholder="Min" step="0.1">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm" id="produktivitasMax" name="produktivitas_max" placeholder="Max" step="0.1">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Status -->
                            <div class="mb-4">
                                <h6 class="text-success border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Filter Status
                                </h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hasCoordinates" name="has_coordinates" checked>
                                    <label class="form-check-label" for="hasCoordinates">
                                        <i class="fas fa-map-pin text-primary me-1"></i>Hanya yang memiliki koordinat
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hasProduction" name="has_production" checked>
                                    <label class="form-check-label" for="hasProduction">
                                        <i class="fas fa-chart-line text-success me-1"></i>Hanya yang memiliki data produksi
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-search me-2"></i>Terapkan Filter
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                                    <i class="fas fa-times me-2"></i>Hapus Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Wilayah -->
    <div class="row">
        <div class="col-12">
            <div class="card result-card">
                <div class="card-header text-white">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>Data Wilayah</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Wilayah</th>
                                    <th>Luas Tanam (Ha)</th>
                                    <th>Produktivitas (Ton/Ha)</th>
                                    <th>Total Produksi (Ton)</th>
                                </tr>
                            </thead>
                            <tbody id="resultTable">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Pilih wilayah untuk melihat data
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi peta
    const map = L.map('map').setView([-7.5360639, 112.2384017], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Layer groups untuk berbagai komoditas
    const kedelaiLayer = L.layerGroup().addTo(map);
    const kacangTanahLayer = L.layerGroup().addTo(map);
    const kacangHijauLayer = L.layerGroup().addTo(map);
    
    // Layer control
    const layerControl = L.control.layers(null, {
        "Kedelai": kedelaiLayer,
        "Kacang Tanah": kacangTanahLayer,
        "Kacang Hijau": kacangHijauLayer
    }).addTo(map);

    const searchForm = document.getElementById('searchForm');
    const provinsiSelect = document.getElementById('provinsi');
    const kabupatenSelect = document.getElementById('kabupaten');
    const kecamatanSelect = document.getElementById('kecamatan');
    const resultTable = document.getElementById('resultTable');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const resetMapBtn = document.getElementById('resetMap');
    const fullscreenMapBtn = document.getElementById('fullscreenMap');

    // Load provinsi data
    loadProvinsi();

    // Load kabupaten
    provinsiSelect.addEventListener('change', async function() {
        kabupatenSelect.disabled = true;
        kecamatanSelect.disabled = true;
        kabupatenSelect.innerHTML = '<option value="">Semua Kabupaten</option>';
        kecamatanSelect.innerHTML = '<option value="">Semua Kecamatan</option>';

        if (this.value) {
            const response = await fetch(`/kabupaten/${this.value}`);
            const kabupaten = await response.json();
            kabupaten.forEach(kab => {
                const option = document.createElement('option');
                option.value = kab.id;
                option.textContent = kab.nama_kabupaten;
                kabupatenSelect.appendChild(option);
            });
            kabupatenSelect.disabled = false;
        }
    });

    // Load kecamatan
    kabupatenSelect.addEventListener('change', async function() {
        kecamatanSelect.disabled = true;
        kecamatanSelect.innerHTML = '<option value="">Semua Kecamatan</option>';

        if (this.value) {
            const response = await fetch(`/kecamatan/${this.value}`);
            const kecamatan = await response.json();
            kecamatan.forEach(kec => {
                const option = document.createElement('option');
                option.value = kec.id;
                option.textContent = kec.nama_kecamatan;
                kecamatanSelect.appendChild(option);
            });
            kecamatanSelect.disabled = false;
        }
    });

    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        searchForm.reset();
        provinsiSelect.innerHTML = '<option value="">Semua Provinsi</option>';
        kabupatenSelect.innerHTML = '<option value="">Semua Kabupaten</option>';
        kabupatenSelect.disabled = true;
        kecamatanSelect.innerHTML = '<option value="">Semua Kecamatan</option>';
        kecamatanSelect.disabled = true;
        
        // Reset checkboxes
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.checked = true;
        });
        
        loadProvinsi();
        loadMapData();
    });

    // Reset map
    resetMapBtn.addEventListener('click', function() {
        map.setView([-7.5360639, 112.2384017], 8);
        clearAllLayers();
    });

    // Fullscreen map
    fullscreenMapBtn.addEventListener('click', function() {
        const mapContainer = document.getElementById('map');
        if (mapContainer.requestFullscreen) {
            mapContainer.requestFullscreen();
        }
    });

    // Submit form dengan filter canggih
    searchForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        await loadMapData();
    });

    // Load provinsi data
    async function loadProvinsi() {
        try {
            const response = await fetch('/peta/data');
            const data = await response.json();
            
            if (data.success) {
                const provinsiData = [...new Set(data.data.map(item => ({
                    id: item.kabupaten?.provinsi?.id,
                    nama: item.kabupaten?.provinsi?.nama_provinsi
                })))].filter(p => p.id && p.nama);
                
                provinsiData.forEach(prov => {
                    const option = document.createElement('option');
                    option.value = prov.id;
                    option.textContent = prov.nama;
                    provinsiSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading provinsi:', error);
        }
    }

    // Load map data dengan filter
    async function loadMapData() {
        try {
            const formData = new FormData(searchForm);
            const params = new URLSearchParams();
            
            // Add all form data to params
            for (let [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
            
            const response = await fetch(`/peta/data?${params.toString()}`);
            const data = await response.json();
            
            if (data.success) {
                clearAllLayers();
                displayMapData(data.data);
                updateResultTable(data.data);
            }
        } catch (error) {
            console.error('Error loading map data:', error);
        }
    }

    // Clear all layers
    function clearAllLayers() {
        kedelaiLayer.clearLayers();
        kacangTanahLayer.clearLayers();
        kacangHijauLayer.clearLayers();
    }

    // Display map data
    function displayMapData(data) {
        data.forEach(item => {
            if (!item.latitude || !item.longitude) return;
            
            const komoditas = getSelectedKomoditas();
            const icon = getMarkerIcon(item, komoditas);
            
            const marker = L.marker([item.latitude, item.longitude], { icon })
                .bindPopup(createPopupContent(item));
            
            // Add to appropriate layer based on komoditas
            if (komoditas.includes('kedelai') && item.komKedelai) {
                kedelaiLayer.addLayer(marker);
            }
            if (komoditas.includes('kacang_tanah') && item.komKacangTanah) {
                kacangTanahLayer.addLayer(marker);
            }
            if (komoditas.includes('kacang_hijau') && item.komKacangHijau) {
                kacangHijauLayer.addLayer(marker);
            }
        });
    }

    // Get selected komoditas
    function getSelectedKomoditas() {
        const checkboxes = document.querySelectorAll('input[name="komoditas[]"]:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }

    // Get marker icon based on data
    function getMarkerIcon(item, komoditas) {
        let color = '#28a745'; // default green
        
        if (komoditas.includes('kedelai') && item.komKedelai) {
            color = '#28a745'; // green for kedelai
        } else if (komoditas.includes('kacang_tanah') && item.komKacangTanah) {
            color = '#ffc107'; // yellow for kacang tanah
        } else if (komoditas.includes('kacang_hijau') && item.komKacangHijau) {
            color = '#17a2b8'; // blue for kacang hijau
        }
        
        return L.divIcon({
            className: 'custom-marker',
            html: `<div style="background-color: ${color}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
    }

    // Create popup content
    function createPopupContent(item) {
        let content = `
            <div class="popup-content">
                <h6 class="mb-2">${item.nama_kecamatan}</h6>
                <p class="mb-1"><strong>Kabupaten:</strong> ${item.kabupaten_nama}</p>
                <p class="mb-1"><strong>Provinsi:</strong> ${item.provinsi_nama}</p>
        `;
        
        if (item.komKedelai) {
            content += `
                <hr>
                <h6 class="text-success">Kedelai</h6>
                <p class="mb-1">Luas: ${item.komKedelai.luas_tanam || '-'} Ha</p>
                <p class="mb-1">Produktivitas: ${item.komKedelai.produktivitas || '-'} Ton/Ha</p>
            `;
        }
        
        if (item.komKacangTanah) {
            content += `
                <hr>
                <h6 class="text-warning">Kacang Tanah</h6>
                <p class="mb-1">Luas: ${item.komKacangTanah.luas_tanam || '-'} Ha</p>
                <p class="mb-1">Produktivitas: ${item.komKacangTanah.produktivitas || '-'} Ton/Ha</p>
            `;
        }
        
        if (item.komKacangHijau) {
            content += `
                <hr>
                <h6 class="text-info">Kacang Hijau</h6>
                <p class="mb-1">Luas: ${item.komKacangHijau.luas_tanam || '-'} Ha</p>
                <p class="mb-1">Produktivitas: ${item.komKacangHijau.produktivitas || '-'} Ton/Ha</p>
            `;
        }
        
        content += '</div>';
        return content;
    }

    // Update result table
    function updateResultTable(data) {
        resultTable.innerHTML = '';
        
        if (data.length === 0) {
            resultTable.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        <i class="fas fa-search me-2"></i>Tidak ada data yang sesuai dengan filter
                    </td>
                </tr>
            `;
            return;
        }
        
        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <strong>${item.nama_kecamatan}</strong><br>
                    <small class="text-muted">${item.kabupaten_nama}, ${item.provinsi_nama}</small>
                </td>
                <td>
                    ${item.komKedelai?.luas_tanam || '-'} Ha<br>
                    <small class="text-muted">Kedelai</small>
                </td>
                <td>
                    ${item.komKedelai?.produktivitas || '-'} Ton/Ha<br>
                    <small class="text-muted">Kedelai</small>
                </td>
                <td>
                    ${item.komKedelai?.total_produksi || '-'} Ton<br>
                    <small class="text-muted">Kedelai</small>
                </td>
            `;
            resultTable.appendChild(row);
        });
    }

    // Load initial data
    loadMapData();
});
</script>
@endsection
