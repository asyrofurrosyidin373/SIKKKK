{{-- resources/views/home/peta.blade.php --}}
@extends('layouts.app')

@section('title', 'Peta Persebaran Komoditas')

@section('content')
        <div class="container peta-page py-4">
            <div class="row flex-column-reverse flex-lg-row">
                <!-- MAP -->
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 text-success">Peta Persebaran Komoditas Kacang-Kacangan</h5>
                        </div>
                        <div class="card-body p-0">
                            <div id="map" class="map-container"></div>
                        </div>
                    </div>

                    <!-- Info Panel -->
                    <div class="card mt-3" id="infoPanel" style="display: none;">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 text-success">Detail Kecamatan</h6>
                        </div>
                        <div class="card-body" id="infoPanelBody">
                            <!-- Detail akan diisi via JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- FILTER SIDEBAR -->
                <div class="col-lg-3 mx-auto">
                    <div class="filter-card bg-white rounded p-4 shadow-sm" style="top: 76px;">
                        <h5 class="mb-3 text-success"><i class="fas fa-filter me-2"></i>Filter Wilayah</h5>

                        <form id="filterForm">
                            <!-- Provinsi -->
                            <div class="mb-3">
                                <select class="form-select select2 w-full" id="provinsiSelect" name="provinsi_id">
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                    @foreach ($provinsi as $prov)
                                        <option value="{{ $prov->id }}">{{ $prov->nama_provinsi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Kabupaten -->
                            <div class="mb-3">
                                <select class="form-select select2 w-full" id="kabupatenSelect" name="kabupaten_id"
                                    disabled>
                                    <option value="" disabled selected hidden>Pilih Kabupaten</option>
                                </select>
                            </div>

                            <!-- Kecamatan -->
                            <div class="mb-3">
                                <select class="form-select select2 w-full" id="kecamatanSelect" name="kecamatan_id"
                                    disabled>
                                    <option value="" disabled selected hidden>Pilih Kecamatan</option>
                                </select>
                            </div>


                            <div class="d-grid gap-2 mt-3">
                                <button type="button" class="btn btn-success" id="cariButton">
                                    <i class="fas fa-search me-2"></i>Cari
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="resetFilter">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
@endsection

@push('styles')
<style>
    /* Keep navbar and its dropdown above the map */
    .navbar.navbar-brmp { position: sticky; top: 0; z-index: 3000; }
    .navbar .dropdown-menu { z-index: 4000 !important; }

    /* Lower map stacking so it never overlaps the navbar */
    .map-container { position: relative; z-index: 1; }
    .leaflet-container, .leaflet-pane { z-index: 1 !important; }
    /* Leaflet controls can stay above tiles but below navbar */
    .leaflet-top, .leaflet-bottom { z-index: 1500 !important; }
</style>
@endpush

@push('scripts')
    @push('styles')
    <style>
        .peta-page .row { row-gap: 16px; }
        .peta-page .card { margin-bottom: 16px; }
        .map-container { height: 520px; }
    </style>
    @endpush

    <script>
        let map;
        let markers = [];

        // Initialize map
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Leaflet map
            map = L.map('map').setView([-2.5489, 118.0149], 5); // Indonesia center

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Â© OpenStreetMap contributors'
            }).addTo(map);

            // Load initial data
            loadMapData();

            // Event listeners
            $('#provinsiSelect').change(function(){
                loadKabupaten();
                loadMapData();
            });
            $('#kabupatenSelect').change(function(){
                loadKecamatan();
                loadMapData();
            });
            $('#kecamatanSelect').change(loadMapData);
            $('#cariButton').click(redirectToHasil);
            $('#applyFilter').click(loadMapData);
            $('#resetFilter').click(resetFilters);
        });

        function loadKabupaten() {
            const provinsiId = $('#provinsiSelect').val();
            const kabupatenSelect = $('#kabupatenSelect');

            kabupatenSelect.prop('disabled', true).html('<option value="">Loading...</option>');
            $('#kecamatanSelect').prop('disabled', true).html('<option value="">Pilih Kecamatan</option>');

            if (!provinsiId) {
                kabupatenSelect.html('<option value="">Pilih Kabupaten</option>');
                return;
            }

            $.get(`/kabupaten/${provinsiId}`)
                .done(function(data) {
                    console.log('Kabupaten data received:', data);
                    let options = '<option value="">Semua Kabupaten</option>';
                    
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(function(kabupaten) {
                            options += `<option value="${kabupaten.id}">${kabupaten.nama_kabupaten}</option>`;
                        });
                        kabupatenSelect.html(options).prop('disabled', false);
                        showNotification(`Ditemukan ${data.length} kabupaten`, 'success');
                    } else {
                        kabupatenSelect.html('<option value="">Tidak ada kabupaten</option>');
                        showNotification('Tidak ada data kabupaten ditemukan', 'warning');
                    }

                    $('#kecamatanSelect').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
                })
                .fail(function(xhr, status, error) {
                    console.error('Failed to load kabupaten:', error);
                    console.error('Response:', xhr.responseText);
                    kabupatenSelect.html('<option value="">Error loading data</option>');
                    showNotification('Gagal memuat data kabupaten: ' + error, 'error');
                });

        }

        function loadKecamatan() {
            const kabupatenId = $('#kabupatenSelect').val();
            const kecamatanSelect = $('#kecamatanSelect');

            kecamatanSelect.prop('disabled', true).html('<option value="">Loading...</option>');

            if (!kabupatenId) {
                kecamatanSelect.html('<option value="">Pilih Kecamatan</option>');
                return;
            }

            $.get(`/kecamatan/${kabupatenId}`)
                .done(function(data) {
                    console.log('Kecamatan data received:', data);
                    let options = '<option value="">Semua Kecamatan</option>';
                    
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(function(kecamatan) {
                            options += `<option value="${kecamatan.id}">${kecamatan.nama_kecamatan}</option>`;
                        });
                        $('#kecamatanSelect').html(options).prop('disabled', false);
                        showNotification(`Ditemukan ${data.length} kecamatan`, 'success');
                    } else {
                        $('#kecamatanSelect').html('<option value="">Tidak ada kecamatan</option>');
                        showNotification('Tidak ada data kecamatan ditemukan', 'warning');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('Failed to load kecamatan:', error);
                    console.error('Response:', xhr.responseText);
                    $('#kecamatanSelect').html('<option value="">Error loading data</option>');
                    showNotification('Gagal memuat data kecamatan: ' + error, 'error');
                });

        }

        function loadMapData() {
            // Clear existing markers
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            const formData = {
                provinsi: $('#provinsiSelect').val(),
                kabupaten: $('#kabupatenSelect').val(),
                kecamatan: $('#kecamatanSelect').val()
            };

            const hasAnyFilter = formData.provinsi || formData.kabupaten || formData.kecamatan;

            // If no filters are applied, keep base map without markers
            if (!hasAnyFilter) {
                // Reset map view to Indonesia center
                map.setView([-2.5489, 118.0149], 5);
                // Ensure any loading state is off
                showLoadingState(false);
                return;
            }

            // Show loading state only when fetching filtered data
            showLoadingState(true);

            console.log('Loading map data with filters:', formData);
            // Use simple endpoint temporarily to avoid complex query issues
            $.get('/peta/data-simple', formData)
                .done(function(response) {
                    if (response.success && response.data) {
                        const selectedKecamatanId = $('#kecamatanSelect').val();

                        if (selectedKecamatanId) {
                            // Only add marker for the selected kecamatan
                            const target = response.data.find(k => String(k.id) === String(selectedKecamatanId));
                            if (target && target.latitude && target.longitude) {
                                addMarkerToMap(target);
                                map.setView([parseFloat(target.latitude), parseFloat(target.longitude)], 12);
                            } else {
                                showNotification('Kecamatan tidak memiliki koordinat', 'warning');
                            }
                        } else {
                            // Show multiple markers if no specific kecamatan selected
                            const dataWithCoords = response.data.filter(item => item.latitude && item.longitude);
                            if (dataWithCoords.length > 0) {
                                dataWithCoords.slice(0, 50).forEach(item => addMarkerToMap(item)); // Limit to 50 markers
                                
                                // Fit map to show all markers
                                if (dataWithCoords.length === 1) {
                                    map.setView([parseFloat(dataWithCoords[0].latitude), parseFloat(dataWithCoords[0].longitude)], 10);
                                } else {
                                    const group = new L.featureGroup(markers);
                                    map.fitBounds(group.getBounds().pad(0.1));
                                }
                            } else {
                                // No data with coordinates: keep base map view
                                map.setView([-2.5489, 118.0149], 5);
                                showNotification('Tidak ada data dengan koordinat ditemukan', 'info');
                            }
                        }

                        // Update info panel with metadata
                        updateInfoPanel(response.metadata);
                    } else {
                        showNotification('Error: ' + (response.message || 'Gagal memuat data peta'), 'error');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('Map data loading failed:', error);
                    showNotification('Gagal memuat data peta. Silakan coba lagi.', 'error');
                })
                .always(function() {
                    showLoadingState(false);
                });
        }

        function addMarkerToMap(kecamatan) {
            const lat = parseFloat(kecamatan.latitude);
            const lng = parseFloat(kecamatan.longitude);

            // Use consistent green color for markers
            let markerColor = 'green';

            const marker = L.circleMarker([lat, lng], {
                radius: 8,
                fillColor: markerColor,
                color: '#000',
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map);

            // Create popup content
            let popupContent = `
        <div class="popup-content">
            <h6><strong>${kecamatan.nama_kecamatan}</strong></h6>
            <p class="mb-1"><small>${kecamatan.kabupaten_nama || kecamatan.kabupaten?.nama_kabupaten}, ${kecamatan.provinsi_nama || kecamatan.kabupaten?.provinsi?.nama_provinsi}</small></p>
            <hr class="my-2">
    `;

            // Display komoditas information using new structure
            if (kecamatan.jenis_komoditas) {
                const komoditasName = kecamatan.nama_komoditas || kecamatan.jenis_komoditas;
                const komoditasColor = getKomoditasColor(kecamatan.jenis_komoditas);
                popupContent += `<p class="mb-1"><i class="fas fa-seedling ${komoditasColor} me-2"></i><strong>${komoditasName}</strong></p>`;
                
                if (kecamatan.nama_varietas && kecamatan.nama_varietas !== 'Tidak ada varietas') {
                    popupContent += `<p class="mb-1 small text-muted"><i class="fas fa-leaf me-2"></i>Varietas: ${kecamatan.nama_varietas}</p>`;
                }
                
                if (kecamatan.total_produksi && kecamatan.total_produksi > 0) {
                    popupContent += `<p class="mb-1 small text-muted"><i class="fas fa-chart-bar me-2"></i>Produksi: ${kecamatan.total_produksi} ton</p>`;
                }
            } else {
                // Backward compatibility for old structure
                if (kecamatan.komoditas_kedelai) {
                    popupContent += `<p class="mb-1 text-success"><i class="fas fa-seedling text-success me-2"></i>Kedelai</p>`;
                }
                if (kecamatan.komoditas_kacang_tanah) {
                    popupContent += `<p class="mb-1 text-warning"><i class="fas fa-seedling text-warning me-2"></i>Kacang Tanah</p>`;
                }
                if (kecamatan.komoditas_kacang_hijau) {
                    popupContent += `<p class="mb-1 text-info"><i class="fas fa-seedling text-info me-2"></i>Kacang Hijau</p>`;
                }
            }

            popupContent += `
            <div class="mt-2">
                ${kecamatan.latitude && kecamatan.longitude ? 
                    `<button class="w-full btn btn-sm btn-outline-primary ms-1" onclick="openInGoogleMaps(${kecamatan.latitude}, ${kecamatan.longitude})">
                        <i class="fas fa-external-link-alt me-1"></i>Maps
                    </button>` : ''
                }
            </div>
        </div>
    `;

            marker.bindPopup(popupContent);
            markers.push(marker);
        }

        function getKomoditasColor(jenisKomoditas) {
            switch(jenisKomoditas) {
                case 'kedelai': return 'text-success';
                case 'kacang_tanah': return 'text-warning';
                case 'kacang_hijau': return 'text-info';
                default: return 'text-secondary';
            }
        }

        function showKecamatanDetail(kecamatanId) {
            $.get(`/api/regions/kecamatan/${kecamatanId}`)
                .done(function(data) {
                    const infoPanel = $('#infoPanel');
                    const infoPanelBody = $('#infoPanelBody');

                    let content = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Umum</h6>
                        <table class="table table-sm">
                            <tr><td>Kecamatan</td><td><strong>${data.nama_kecamatan}</strong></td></tr>
                            <tr><td>Kabupaten</td><td>${data.kabupaten?.nama_kabupaten || 'N/A'}</td></tr>
                            <tr><td>Provinsi</td><td>${data.kabupaten?.provinsi?.nama_provinsi || 'N/A'}</td></tr>
                            <tr><td>IP Lahan</td><td>${data.ip_lahan || 'N/A'}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Kondisi Tanah</h6>
                        <table class="table table-sm">
                            <tr><td>Kadar P</td><td>${data.kdr_p || 'N/A'}</td></tr>
                            <tr><td>Kadar K</td><td>${data.kdr_k || 'N/A'}</td></tr>
                            <tr><td>Kadar C</td><td>${data.kdr_c || 'N/A'}</td></tr>
                            <tr><td>KTK</td><td>${data.ktk || 'N/A'}</td></tr>
                        </table>
                    </div>
                </div>
            `;

                    // Add komoditas information with new structure
                    if (data.jenis_komoditas) {
                        const komoditasColor = getKomoditasColor(data.jenis_komoditas);
                        content += `
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6>Data Komoditas</h6>
                                <table class="table table-sm">
                                    <tr><td>Jenis</td><td><span class="badge bg-success">${data.nama_komoditas || data.jenis_komoditas}</span></td></tr>
                                    <tr><td>Varietas</td><td>${data.nama_varietas || 'N/A'}</td></tr>
                                    <tr><td>Provitas</td><td>${data.provitas || 'N/A'}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Data Produksi</h6>
                                <table class="table table-sm">
                                    <tr><td>Luas Tanam</td><td>${data.luas_tanam || 'N/A'} ha</td></tr>
                                    <tr><td>Produktivitas</td><td>${data.produktivitas || 'N/A'} ton/ha</td></tr>
                                    <tr><td>Total Produksi</td><td>${data.total_produksi || 'N/A'} ton</td></tr>
                                </table>
                            </div>
                        </div>
                        `;
                    }

                    if (data.bulan_hujan_nama && data.bulan_hujan_nama.length > 0) {
                        content += `
                    <div class="mt-3">
                        <h6>Bulan Hujan</h6>
                        <div class="d-flex flex-wrap gap-1">
                            ${data.bulan_hujan_nama.map(bulan => `<span class="badge bg-primary">${bulan}</span>`).join('')}
                        </div>
                    </div>
                `;
                    }

                    // Add rekomendasi waktu tanam if available
                    if (data.rekomendasi_waktu_tanam && Array.isArray(data.rekomendasi_waktu_tanam) && data.rekomendasi_waktu_tanam.length > 0) {
                        content += `
                        <div class="mt-3">
                            <h6>Rekomendasi Waktu Tanam</h6>
                            <div class="d-flex flex-wrap gap-1">
                                ${data.rekomendasi_waktu_tanam.map(bulan => `<span class="badge bg-success">${bulan}</span>`).join('')}
                            </div>
                        </div>
                        `;
                    }

                    infoPanelBody.html(content);
                    infoPanel.show();

                    // Scroll to info panel
                    $('html, body').animate({
                        scrollTop: infoPanel.offset().top - 100
                    }, 500);
                })
                .fail(function() {
                    showNotification('Error loading kecamatan detail', 'error');
                });
        }

        function updateInfoPanel(metadata) {
            if (metadata) {
                const infoHtml = `
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Total Kecamatan</small>
                            <div class="fw-bold">${metadata.total}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Dengan Koordinat</small>
                            <div class="fw-bold">${metadata.with_coordinates}</div>
                        </div>
                    </div>
                `;
                $('#infoPanelBody').html(infoHtml);
            }
        }

        function resetFilters() {
            $('#filterForm')[0].reset();
            $('#kabupatenSelect, #kecamatanSelect').prop('disabled', true);
            $('#kabupatenSelect').html('<option value="">Pilih Kabupaten</option>');
            $('#kecamatanSelect').html('<option value="">Pilih Kecamatan</option>');
            $('#infoPanel').hide();

            // Clear existing markers
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            // Reset map view
            map.setView([-2.5489, 118.0149], 5);
            loadMapData();
        }

        // Helper functions
        function showLoadingState(show) {
            if (show) {
                $('#applyFilter').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Loading...');
            } else {
                $('#applyFilter').prop('disabled', false).html('<i class="fas fa-search me-2"></i>Terapkan Filter');
            }
        }

        function showNotification(message, type = 'info') {
            const alertClass = type === 'error' ? 'alert-danger' : 
                             type === 'warning' ? 'alert-warning' : 
                             type === 'success' ? 'alert-success' : 'alert-info';
            
            const notification = $(`
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            
            $('body').append(notification);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                notification.alert('close');
            }, 5000);
        }

        function openInGoogleMaps(lat, lng) {
            const url = `https://www.google.com/maps?q=${lat},${lng}`;
            window.open(url, '_blank');
        }

        // Function untuk tombol Cari - redirect ke halaman hasil
        function redirectToHasil() {
            const provinsiId = $('#provinsiSelect').val();
            const kabupatenId = $('#kabupatenSelect').val();
            const kecamatanId = $('#kecamatanSelect').val();
            
            // Build URL dengan parameter
            let url = '/hasil';
            const params = new URLSearchParams();
            
            if (provinsiId) {
                params.append('provinsi', provinsiId);
            }
            if (kabupatenId) {
                params.append('kabupaten', kabupatenId);
            }
            if (kecamatanId) {
                params.append('kecamatan', kecamatanId);
            }
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            // Redirect ke halaman hasil
            window.location.href = url;
        }

    </script>
@endpush
