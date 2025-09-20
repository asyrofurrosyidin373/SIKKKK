// Map initialization
let map = null;
let currentLayer = null;
let markers = [];

function initMap() {
    map = L.map('map').setView([-7.5360639, 112.2384017], 8);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
}

function loadProvinsiData() {
    fetch('/api/provinsi')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('provinsi');
            select.innerHTML = '<option value="">Pilih Provinsi</option>';
            data.forEach(prov => {
                const option = document.createElement('option');
                option.value = prov.id;
                option.textContent = prov.nama_provinsi;
                select.appendChild(option);
            });
        });
}

function loadKabupatenData(provinsiId) {
    fetch(`/api/kabupaten/${provinsiId}`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('kabupaten');
            select.innerHTML = '<option value="">Pilih Kabupaten</option>';
            data.forEach(kab => {
                const option = document.createElement('option');
                option.value = kab.id;
                option.textContent = kab.nama_kabupaten;
                select.appendChild(option);
            });
        });
}

function loadKomoditasData(type, kecamatanId) {
    fetch(`/api/komoditas/${type}/${kecamatanId}`)
        .then(response => response.json())
        .then(data => {
            updateMap(data);
            updateTable(data);
        });
}

function updateMap(data) {
    // Clear existing markers
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    if (currentLayer) {
        map.removeLayer(currentLayer);
    }

    if (data && data.latitude && data.longitude) {
        const marker = L.marker([data.latitude, data.longitude])
            .bindPopup(`
                <strong>${data.nama_kecamatan}</strong><br>
                Luas Tanam: ${data.luas_tanam} Ha<br>
                Produktivitas: ${data.produktivitas} Ton/Ha<br>
                Total Produksi: ${data.jumlah_produksi} Ton
            `)
            .addTo(map);
        
        markers.push(marker);
        map.setView([data.latitude, data.longitude], 12);
    }
}

function updateTable(data) {
    const tbody = document.getElementById('resultTable');
    if (!data) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada data</td></tr>';
        return;
    }

    tbody.innerHTML = `
        <tr>
            <td>${data.nama_kecamatan || '-'}</td>
            <td>${data.luas_tanam || '0'}</td>
            <td>${data.produktivitas || '0'}</td>
            <td>${data.jumlah_produksi || '0'}</td>
        </tr>
    `;
}

document.addEventListener('DOMContentLoaded', function() {
    initMap();
    loadProvinsiData();

    // Event Listeners
    document.getElementById('provinsi').addEventListener('change', function(e) {
        if (e.target.value) {
            loadKabupatenData(e.target.value);
        }
    });

    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const komoditas = document.getElementById('komoditas').value;
        const kabupatenId = document.getElementById('kabupaten').value;
        
        if (kabupatenId) {
            loadKomoditasData(komoditas, kabupatenId);
        }
    });
});
