{{-- resources/views/home/peta.blade.php --}}
@extends('layouts.app')

@section('title', 'Peta Persebaran Komoditas')

@section('content')
        <div class="container-fluid py-4 px-5">
            <div class="row flex-column-reverse flex-lg-row">
                <!-- MAP -->
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                Peta Persebaran Komoditas Kacang-Kacangan
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div id="map" class="map-container"></div>
                        </div>
                    </div>

                    <!-- Info Panel -->
                    <div class="card mt-4" id="infoPanel" style="display: none;">
                        <div class="card-header">
                            <h6 class="mb-0">Detail Kecamatan</h6>
                        </div>
                        <div class="card-body" id="infoPanelBody">
                            <!-- Detail akan diisi via JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- FILTER SIDEBAR -->
                <div class="col-lg-3 mx-auto">
                    <div class="filter-card shadow-sm" style="top: 76px;">
                        <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filter Wilayah</h5>

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


                            <div class="d-grid gap-2 mt-5">
                                <button type="button" class="btn btn-primary" id="applyFilter">
                                    <i class="fas fa-search me-2"></i>Terapkan Filter
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="resetFilter">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                            </div>
                        </form>

                        <!-- Legend -->
                        <div class="mt-4">
                            <h6>Legenda</h6>
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-success rounded me-2" style="width: 20px; height: 20px;"></div>
                                <small>Kedelai</small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-warning rounded me-2" style="width: 20px; height: 20px;"></div>
                                <small>Kacang Tanah</small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-info rounded me-2" style="width: 20px; height: 20px;"></div>
                                <small>Kacang Hijau</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
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
            $('#provinsiSelect').change(loadKabupaten);
            $('#kabupatenSelect').change(loadKecamatan);
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

            $.get(`/api/regions/provinsi/${provinsiId}/kabupaten`)
                .done(function(data) {
                    let options = '<option value="">Semua Kabupaten</option>';
                    data.forEach(function(kabupaten) {
                        options += `<option value="${kabupaten.id}">${kabupaten.nama_kabupaten}</option>`;
                    });
                    kabupatenSelect.html(options).prop('disabled', false).trigger('change');

                    $('#kecamatanSelect').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true)
                        .trigger('change');
                        
                })
                .fail(function() {
                    kabupatenSelect.html('<option value="">Error loading data</option>');
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

            $.get(`/api/regions/kabupaten/${kabupatenId}/kecamatan`)
                .done(function(data) {
                    let options = '<option value="">Semua Kecamatan</option>';
                    data.forEach(function(kecamatan) {
                        options += `<option value="${kecamatan.id}">${kecamatan.nama_kecamatan}</option>`;
                    });
                    $('#kecamatanSelect').html(options).prop('disabled', false).trigger('change');

                })
                .fail(function() {
                    $('#kecamatanSelect').html('<option value="">Error loading data</option>');
                });

        }

        function loadMapData() {
            // Clear existing markers
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            const formData = {
                provinsi_id: $('#provinsiSelect').val(),
                kabupaten_id: $('#kabupatenSelect').val(),
                kecamatan_id: $('#kecamatanSelect').val()
            };

            $.get('/peta/data', formData)
                .done(function(data) {
                    data.forEach(function(kecamatan) {
                        if (kecamatan.latitude && kecamatan.longitude) {
                            addMarkerToMap(kecamatan);
                        }
                    });

                    if (data.length > 0) {
                        // Fit map to markers
                        const group = new L.featureGroup(markers);
                        map.fitBounds(group.getBounds().pad(0.1));
                    }
                })
                .fail(function() {
                    alert('Error loading map data');
                });
        }

        function addMarkerToMap(kecamatan) {
            const lat = parseFloat(kecamatan.latitude);
            const lng = parseFloat(kecamatan.longitude);

            // Determine marker color based on primary commodity
            let markerColor = 'blue';
            if (kecamatan.komoditas_kedelai) markerColor = 'green';
            else if (kecamatan.komoditas_kacang_tanah) markerColor = 'orange';
            else if (kecamatan.komoditas_kacang_hijau) markerColor = 'lightblue';

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
            <p class="mb-1"><small>${kecamatan.kabupaten.nama_kabupaten}, ${kecamatan.kabupaten.provinsi.nama_provinsi}</small></p>
            <hr class="my-2">
    `;

            if (kecamatan.komoditas_kedelai) {
                popupContent += `<p class="mb-1"><i class="fas fa-seedling text-success me-2"></i>Kedelai</p>`;
            }
            if (kecamatan.komoditas_kacang_tanah) {
                popupContent += `<p class="mb-1"><i class="fas fa-seedling text-warning me-2"></i>Kacang Tanah</p>`;
            }
            if (kecamatan.komoditas_kacang_hijau) {
                popupContent += `<p class="mb-1"><i class="fas fa-seedling text-info me-2"></i>Kacang Hijau</p>`;
            }

            popupContent += `
            <button class="btn btn-sm btn-primary mt-2" onclick="showKecamatanDetail('${kecamatan.id}')">
                <i class="fas fa-info-circle me-1"></i>Detail
            </button>
        </div>
    `;

            marker.bindPopup(popupContent);
            markers.push(marker);
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
                            <tr><td>Kabupaten</td><td>${data.kabupaten.nama_kabupaten}</td></tr>
                            <tr><td>Provinsi</td><td>${data.kabupaten.provinsi.nama_provinsi}</td></tr>
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

                    infoPanelBody.html(content);
                    infoPanel.show();

                    // Scroll to info panel
                    $('html, body').animate({
                        scrollTop: infoPanel.offset().top - 100
                    }, 500);
                })
                .fail(function() {
                    alert('Error loading kecamatan detail');
                });
        }

        function resetFilters() {
            $('#filterForm')[0].reset();
            $('#kabupatenSelect, #kecamatanSelect').prop('disabled', true);
            $('#kabupatenSelect').html('<option value="">Pilih Kabupaten</option>');
            $('#kecamatanSelect').html('<option value="">Pilih Kecamatan</option>');
            $('#infoPanel').hide();

            // Reset map view
            map.setView([-2.5489, 118.0149], 5);
            loadMapData();
        }

    </script>
@endpush
