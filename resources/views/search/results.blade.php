@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2">Hasil Pencarian</h1>
            <p class="text-muted">Menampilkan hasil untuk: <strong>"{{ $query }}"</strong></p>
        </div>
    </div>
    
    <!-- Search Results -->
    <div id="searchResults">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Mencari...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const query = @json($query);
    
    if (query) {
        searchAll(query);
    }
});

function searchAll(query) {
    fetch(`/api/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displayResults(data);
        })
        .catch(error => {
            document.getElementById('searchResults').innerHTML = `
                <div class="alert alert-danger">
                    Terjadi kesalahan saat mencari. Silakan coba lagi.
                </div>
            `;
        });
}

function displayResults(data) {
    let html = '';
    
    // Varietas Results
    const allVarietas = [
        ...data.varietas_kedelai.map(v => ({...v, type: 'kedelai', type_name: 'Kedelai'})),
        ...data.varietas_kacang_tanah.map(v => ({...v, type: 'kacang-tanah', type_name: 'Kacang Tanah'})),
        ...data.varietas_kacang_hijau.map(v => ({...v, type: 'kacang-hijau', type_name: 'Kacang Hijau'}))
    ];
    
    if (allVarietas.length > 0) {
        html += `
            <div class="mb-5">
                <h3 class="h4 text-primary mb-3"><i class="fas fa-seedling me-2"></i>Varietas</h3>
                <div class="row">
        `;
        
        allVarietas.forEach(varietas => {
            html += `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">${varietas.nama_varietas}</h6>
                            <p class="text-muted small">${varietas.type_name} • ${varietas.tahun || 'N/A'}</p>
                            <p class="text-success mb-2">Potensi: ${varietas.potensi_hasil || 'N/A'} t/ha</p>
                            <a href="/varietas/${varietas.type}/${varietas.id}" class="btn btn-sm btn-primary">Detail</a>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div></div>';
    }
    
    // OPT Results
    if (data.opt.length > 0) {
        html += `
            <div class="mb-5">
                <h3 class="h4 text-danger mb-3"><i class="fas fa-bug me-2"></i>Hama & Penyakit</h3>
                <div class="row">
        `;
        
        data.opt.forEach(opt => {
            const badgeClass = opt.jenis === 'hama' ? 'bg-danger' : 'bg-warning';
            html += `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">${opt.nama_opt}</h6>
                            <span class="badge ${badgeClass}">${opt.jenis}</span>
                            <div class="mt-2">
                                <a href="/opt/${opt.id}" class="btn btn-sm btn-danger">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div></div>';
    }
    
    // Kecamatan Results
    if (data.kecamatan.length > 0) {
        html += `
            <div class="mb-5">
                <h3 class="h4 text-info mb-3"><i class="fas fa-map-marker-alt me-2"></i>Wilayah</h3>
                <div class="row">
        `;
        
        data.kecamatan.forEach(kec => {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">${kec.nama_kecamatan}</h6>
                            <p class="text-muted small">
                                ${kec.kabupaten.nama_kabupaten}, ${kec.kabupaten.provinsi.nama_provinsi}
                            </p>
                            <a href="/peta?kecamatan_id=${kec.id}" class="btn btn-sm btn-info">Lihat di Peta</a>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div></div>';
    }
    
    // No Results
    if (html === '') {
        html = `
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada hasil yang ditemukan</h5>
                <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
            </div>
        `;
    }
    
    document.getElementById('searchResults').innerHTML = html;
}
</script>
@endpush
@endsection
        