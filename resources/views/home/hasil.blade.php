{{-- resources/views/home/hasil.blade.php --}}
@extends('layouts.app')

@section('title', 'Hasil Pencarian Kecamatan')

@section('content')
<div class="container hasil-page py-4">
    <!-- HEADER & STATS -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="text-success mb-1">Hasil Pencarian</h4>
                    <p class="text-muted mb-0">
                        @if(isset($filters['provinsi']) || isset($filters['kabupaten']) || isset($filters['kecamatan']))
                            Filter: 
                            @if(isset($filters['provinsi']) && $filters['provinsi'])
                                {{ $provinsi->where('id', $filters['provinsi'])->first()->nama_provinsi ?? 'Provinsi' }}
                            @endif
                            @if(isset($filters['kabupaten']) && $filters['kabupaten'])
                                → {{ $kabupaten->where('id', $filters['kabupaten'])->first()->nama_kabupaten ?? 'Kabupaten' }}
                            @endif
                            @if(isset($filters['kecamatan']) && $filters['kecamatan'])
                                → {{ $kecamatan->where('id', $filters['kecamatan'])->first()->nama_kecamatan ?? 'Kecamatan' }}
                            @endif
                        @else
                            Menampilkan semua data kecamatan
                        @endif
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fw-bold text-success" id="totalCount">0</div>
                            <small class="text-muted">Kecamatan</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-success" id="coordCount">0</div>
                            <small class="text-muted">Koordinat</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-success" id="prodCount">0</div>
                            <small class="text-muted">Produksi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTROLS -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div id="dataSourceInfo" class="text-muted">
                        <span id="dataSource" class="visually-hidden"></span>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success" onclick="exportData()">
                            <i class="fas fa-download me-1"></i>Export CSV
                        </button>
                        <a href="/peta" class="btn btn-outline-success">
                            <i class="fas fa-map me-1"></i>Kembali ke Peta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RESULTS CONTAINER -->
    <div id="resultsContainer">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-success">
                            <tr>
                                <th>Kecamatan</th>
                                <th>Kabupaten</th>
                                <th>Provinsi</th>
                                <th>Data Tanah</th>
                                <th>Komoditas</th>
                                <th>OPT</th>
                                <th>Produksi</th>
                                <th>Waktu Tanam</th>
                                <th>Iklim</th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody">
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Memuat data...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadData();
});

function loadData() {
    var urlParams = new URLSearchParams(window.location.search);
    var apiUrl = '/api/kecamatan-data';
    if (urlParams.toString()) {
        apiUrl += '?' + urlParams.toString();
    }
    
    console.log('Loading data from:', apiUrl);
    
    fetch(apiUrl)
        .then(function(response) {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        })
        .then(function(data) {
            console.log('API Response:', data);
            console.log('Validating response:', {
                hasData: !!data,
                success: data ? data.success : 'undefined',
                successType: data ? typeof data.success : 'undefined',
                hasDataArray: data && data.data ? 'yes' : 'no',
                isArray: data && data.data ? Array.isArray(data.data) : 'no data',
                dataLength: data && data.data ? data.data.length : 'no data'
            });
            
            if (data && data.data && Array.isArray(data.data) && data.data.length > 0) {
                console.log('✅ API data found - Records:', data.data.length, '(success flag =', data.success, ')');
                try {
                    displayData(data.data);
                } catch (e) {
                    console.error('Render error in displayData:', e);
                    tryFallback();
                    return;
                }
                var sourceInfo = data.source || 'Database MySQL';
                var totalInDb = data.total_in_db || data.data.length;
                updateDataSource('<span class="text-success">' + sourceInfo + '</span> (' + data.data.length + '/' + totalInDb + ' records)');
                updateStats(data.data);
            } else {
                console.log('❌ Primary API validation failed, trying fallback...');
                console.log('Response details:', data);
                tryFallback();
            }
        })
        .catch(function(error) {
            console.error('❌ Primary API error:', error);
            tryFallback();
        });
}

function tryFallback() {
    console.log('🔄 Trying fallback endpoint...');
    fetch('/api/kecamatan-simple')
        .then(function(response) {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        })
        .then(function(data) {
            console.log('Fallback Response:', data);
            if (data && data.success === true && data.data && Array.isArray(data.data)) {
                console.log('✅ Fallback success - Data received:', data.data.length, 'records');
                displayData(data.data);
                updateDataSource('<span class="text-warning">Database Sampel</span> (' + data.data.length + ' records)');
                updateStats(data.data);
            } else {
                console.log('❌ Fallback also failed, showing sample data');
                showSampleData();
            }
        })
        .catch(function(error) {
            console.error('❌ Fallback error:', error);
            showSampleData();
        });
}

function displayData(data) {
    var tbody = document.getElementById('dataTableBody');
    var html = '';
    
    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        
        var coords = 'Tidak ada';
        if (item.latitude && item.longitude && item.latitude != 0 && item.longitude != 0) {
            coords = parseFloat(item.latitude).toFixed(4) + ', ' + parseFloat(item.longitude).toFixed(4);
        }
        
        var soilData = [];
        if (item.ip_lahan) soilData.push('IP: ' + item.ip_lahan);
        if (item.kdr_p) soilData.push('P: ' + item.kdr_p);
        if (item.kdr_c) soilData.push('C: ' + item.kdr_c);
        if (item.kdr_k) soilData.push('K: ' + item.kdr_k);
        if (item.ktk) soilData.push('KTK: ' + item.ktk);
        var soilDataStr = soilData.length > 0 ? soilData.join(', ') : 'Tidak ada';
        
        // Komoditas dengan struktur baru
        var komoditasStr = '<span class="text-muted">Tidak ada</span>';
        if (item.jenis_komoditas) {
            var komoditasClass = 'bg-secondary';
            var komoditasName = item.nama_komoditas || item.jenis_komoditas;
            
            switch(item.jenis_komoditas) {
                case 'kedelai':
                    komoditasClass = 'bg-success';
                    komoditasName = 'Kedelai';
                    break;
                case 'kacang_tanah':
                    komoditasClass = 'bg-warning';
                    komoditasName = 'Kacang Tanah';
                    break;
                case 'kacang_hijau':
                    komoditasClass = 'bg-info';
                    komoditasName = 'Kacang Hijau';
                    break;
            }
            
            komoditasStr = '<span class="badge ' + komoditasClass + '">' + komoditasName + '</span>';
            
            // Tambahkan varietas jika ada
            if (item.nama_varietas && item.nama_varietas !== 'Tidak ada varietas') {
                komoditasStr += '<br><small class="text-muted">Varietas: ' + item.nama_varietas + '</small>';
            }
        }
        
        // OPT (Hama/Penyakit) - normalize berbagai kemungkinan struktur data API
        var optStr = '<span class="text-muted">Tidak ada</span>';
        function normalizeOpt(val) {
            if (!val) return null;
            if (typeof val === 'string') return val;
            if (Array.isArray(val)) {
                // array berisi string/objek
                return val.map(function(x){
                    if (!x) return null;
                    if (typeof x === 'string') return x;
                    if (typeof x === 'object') return x.nama || x.nama_penyakit || x.title || null;
                    return null;
                }).filter(Boolean);
            }
            if (typeof val === 'object') {
                return val.nama || val.nama_penyakit || val.title || null;
            }
            return null;
        }
        var optCandidates = [item.opt, item.hama_penyakit, item.top_opt];
        for (var oc = 0; oc < optCandidates.length; oc++) {
            var normalized = normalizeOpt(optCandidates[oc]);
            if (Array.isArray(normalized) && normalized.length) {
                optStr = '<small>' + normalized.slice(0,3).join(', ') + (normalized.length > 3 ? '…' : '') + '</small>';
                break;
            } else if (typeof normalized === 'string' && normalized) {
                optStr = '<small>' + normalized + '</small>';
                break;
            }
        }

        // Data produksi dengan struktur baru
        var produksiData = [];
        if (item.luas_tanam && item.luas_tanam > 0) {
            produksiData.push('<small class="text-success">Luas: ' + parseFloat(item.luas_tanam).toFixed(2) + ' ha</small>');
        }
        if (item.produktivitas && item.produktivitas > 0) {
            produksiData.push('<small class="text-info">Produktivitas: ' + parseFloat(item.produktivitas).toFixed(2) + ' ton/ha</small>');
        }
        if (item.total_produksi && item.total_produksi > 0) {
            produksiData.push('<small class="text-warning">Total: ' + parseFloat(item.total_produksi).toFixed(2) + ' ton</small>');
        }
        var produksiStr = produksiData.length > 0 ? produksiData.join('<br>') : '<span class="text-muted">Tidak ada</span>';
        
        // Waktu tanam berdasarkan jenis komoditas
        var plantingTimesStr = '<span class="text-muted">Tidak ada</span>';
        if (item.jenis_komoditas) {
            var waktuTanam = [];
            switch(item.jenis_komoditas) {
                case 'kedelai':
                    waktuTanam = parseJsonArray(item.rekomendasi_waktu_tanam_kedelai);
                    break;
                case 'kacang_tanah':
                    waktuTanam = parseJsonArray(item.rekomendasi_waktu_tanam_kacang_tanah);
                    break;
                case 'kacang_hijau':
                    waktuTanam = parseJsonArray(item.rekomendasi_waktu_tanam_kacang_hijau);
                    break;
            }
            
            if (waktuTanam.length > 0) {
                plantingTimesStr = '<small class="text-primary">' + waktuTanam.join(', ') + '</small>';
            }
        }
        
        var climateData = [];
        
        // Parse JSON strings to arrays
        var bulanHujan = parseJsonArray(item.bulan_hujan);
        var bulanKering = parseJsonArray(item.bulan_kering);
        
        if (bulanHujan.length > 0) {
            climateData.push('<small class="text-primary">Hujan: ' + bulanHujan.join(', ') + '</small>');
        }
        if (bulanKering.length > 0) {
            climateData.push('<small class="text-danger">Kering: ' + bulanKering.join(', ') + '</small>');
        }
        var climateDataStr = climateData.length > 0 ? climateData.join('<br>') : '<span class="text-muted">Tidak ada</span>';
        
        html += '<tr>' +
            '<td><strong>' + item.nama_kecamatan + '</strong></td>' +
            '<td>' + (item.kabupaten_nama || 'N/A') + '</td>' +
            '<td>' + (item.provinsi_nama || 'N/A') + '</td>' +
            '<td><small>' + soilDataStr + '</small></td>' +
            '<td>' + komoditasStr + '</td>' +
            '<td>' + optStr + '</td>' +
            '<td>' + produksiStr + '</td>' +
            '<td>' + plantingTimesStr + '</td>' +
            '<td>' + climateDataStr + '</td>' +
            '</tr>';
    }
    
    tbody.innerHTML = html;
}

function showSampleData() {
    var sampleData = [{
        id: '350701',
        nama_kecamatan: 'Donomulyo',
        latitude: -8.2435,
        longitude: 112.4419,
        kabupaten_nama: 'Malang',
        provinsi_nama: 'Jawa Timur',
        ip_lahan: 3.2,
        kdr_p: 2.5,
        kdr_c: 2.1,
        kdr_k: 1.8,
        ktk: 2.9,
        jenis_komoditas: 'kedelai',
        nama_komoditas: 'Kedelai',
        nama_varietas: 'Grobogan',
        luas_tanam: 150.50,
        produktivitas: 2.5,
        total_produksi: 376.25,
        rekomendasi_waktu_tanam_kedelai: ['Maret', 'April'],
        rekomendasi_waktu_tanam_kacang_tanah: ['April', 'Mei'],
        rekomendasi_waktu_tanam_kacang_hijau: ['Mei', 'Juni'],
        bulan_hujan: ['Januari', 'Februari', 'Maret'],
        bulan_kering: ['Juli', 'Agustus', 'September']
    }];
    
    displayData(sampleData);
    updateDataSource('<span class="text-danger">Data Contoh</span> (1 record - Error database)');
    updateStats(sampleData);
    
    // Show error message only if really needed
    console.log('Showing sample data due to API failure');
    // Don't show error message automatically - let user see the data source indicator
}

function updateDataSource(text) {
    document.getElementById('dataSource').innerHTML = text;
}

function updateStats(data) {
    document.getElementById('totalCount').textContent = data.length;
    
    var withCoords = 0;
    var withProduction = 0;
    
    for (var i = 0; i < data.length; i++) {
        if (data[i].latitude && data[i].longitude && data[i].latitude != 0 && data[i].longitude != 0) {
            withCoords++;
        }
        if (data[i].jenis_komoditas && (data[i].luas_tanam > 0 || data[i].total_produksi > 0)) {
            withProduction++;
        }
    }
    
    document.getElementById('coordCount').textContent = withCoords;
    document.getElementById('prodCount').textContent = withProduction;
}

function parseJsonArray(jsonString) {
    if (!jsonString) return [];
    
    // If it's already an array, return it
    if (Array.isArray(jsonString)) {
        return jsonString;
    }
    
    // If it's a string, try to parse it as JSON
    if (typeof jsonString === 'string') {
        try {
            var parsed = JSON.parse(jsonString);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            console.warn('Failed to parse JSON array:', jsonString);
            return [];
        }
    }
    
    return [];
}

function exportData() {
    // Get current URL parameters for filtering
    var urlParams = new URLSearchParams(window.location.search);
    var exportUrl = '/hasil/export';
    if (urlParams.toString()) {
        exportUrl += '?' + urlParams.toString();
    }
    
    // Create temporary link and trigger download
    var link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'data_kecamatan_' + new Date().toISOString().slice(0, 10) + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Show notification
    showNotification('Export CSV dimulai...', 'info');
}

function showNotification(message, type = 'info') {
    const alertClass = type === 'error' ? 'alert-danger' : 
                     type === 'warning' ? 'alert-warning' : 
                     type === 'success' ? 'alert-success' : 'alert-info';
    
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>
@endpush
