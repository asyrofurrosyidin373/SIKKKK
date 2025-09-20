@extends('layouts.app')

@section('content')
<div class="container-fluid ">
    <!-- Row utama -->
    <div class="row mb-4 flex-column-reverse flex-lg-row">
        <!-- PETA -->
        <div class="col-lg-8 col-md-12 mb-3 mb-lg-0 order-lg-1 order-2">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-map me-2"></i>Peta Tematik</h5>
                </div>
                <div class="card-body">
                    <div id="map" class="map-container"></div>
                </div>
            </div>
        </div>

        <!-- FILTER -->
        <div class="col-lg-4 col-md-12 order-lg-2 order-1">
            <div class="sticky-top" style="top: 76px;">
                <div class="card h-100 filter-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Filter Pencarian</h5>
                    </div>
                    <div class="card-body">
                        <form id="searchForm">
                            <div class="mb-3">
                                <label class="form-label">Provinsi</label>
                                <select class="form-select" id="provinsi" name="provinsi">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kabupaten</label>
                                <select class="form-select" id="kabupaten" name="kabupaten" disabled>
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kecamatan</label>
                                <select class="form-select" id="kecamatan" name="kecamatan" disabled>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-search me-2"></i>Cari
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
            <div class="card">
                <div class="card-header bg-success text-white">
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
                                    <td colspan="4" class="text-center">Pilih wilayah untuk melihat data</td>
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

    const searchForm = document.getElementById('searchForm');
    const provinsiSelect = document.getElementById('provinsi');
    const kabupatenSelect = document.getElementById('kabupaten');
    const kecamatanSelect = document.getElementById('kecamatan');
    const resultTable = document.getElementById('resultTable');

    // Load kabupaten
    provinsiSelect.addEventListener('change', async function() {
        kabupatenSelect.disabled = true;
        kecamatanSelect.disabled = true;
        kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten</option>';
        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

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
        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

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

    // Submit form
    searchForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const provinsi = provinsiSelect.value;
        const kabupaten = kabupatenSelect.value;
        const kecamatan = kecamatanSelect.value;

        let url = '/map/search';
        if (kecamatan) url = `/map/kecamatan/${kecamatan}`;
        else if (kabupaten) url = `/map/search?kabupaten=${kabupaten}`;
        else if (provinsi) url = `/map/search?provinsi=${provinsi}`;

        const response = await fetch(url);
        const data = await response.json();
        resultTable.innerHTML = '';

        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.nama}</td>
                <td>${item.komKedelai?.luas_tanam || '-'}</td>
                <td>${item.komKedelai?.produktivitas || '-'}</td>
                <td>${item.komKedelai?.total_produksi || '-'}</td>
            `;
            resultTable.appendChild(row);
        });
    });
});
</script>
@endsection
