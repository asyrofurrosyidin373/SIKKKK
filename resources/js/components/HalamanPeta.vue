<template>
  <div class="container">
    <h2>Peta Tematik</h2>
    <div class="row mb-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-search me-2"></i>Filter Pencarian</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="search">
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="form-label">Provinsi</label>
                  <select v-model="form.provinsi" class="form-select">
                    <option value="">Pilih Provinsi</option>
                    <option v-for="prov in provinsi" :key="prov.id" :value="prov.id">{{ prov.nama_provinsi }}</option>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Kabupaten</label>
                  <select v-model="form.kabupaten" class="form-select" :disabled="!form.provinsi">
                    <option value="">Pilih Kabupaten</option>
                    <option v-for="kab in kabupaten" :key="kab.id" :value="kab.id">{{ kab.nama_kabupaten }}</option>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Kecamatan</label>
                  <select v-model="form.kecamatan" class="form-select" :disabled="!form.kabupaten">
                    <option value="">Pilih Kecamatan</option>
                    <option v-for="kec in kecamatan" :key="kec.id" :value="kec.id">{{ kec.nama }}</option>
                  </select>
                </div>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-success">
                  <i class="fas fa-search me-2"></i>Cari
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-map me-2"></i>Peta Tematik</h5>
          </div>
          <div class="card-body">
            <div id="map" style="height: 500px;"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-4">
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
                <tbody>
                  <tr v-if="!results.length">
                    <td colspan="4" class="text-center">Pilih wilayah untuk melihat data</td>
                  </tr>
                  <tr v-for="item in results" :key="item.id">
                    <td>{{ item.nama }}</td>
                    <td>{{ item.komKedelai?.luas_tanam || '-' }}</td>
                    <td>{{ item.komKedelai?.produktivitas || '-' }}</td>
                    <td>{{ item.komKedelai?.total_produksi || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import L from 'leaflet';

export default {
  name: 'HalamanPeta',
  data() {
    return {
      form: { provinsi: '', kabupaten: '', kecamatan: '' },
      provinsi: [],
      kabupaten: [],
      kecamatan: [],
      results: [],
      map: null,
    };
  },
  async mounted() {
    // Initialize map
    this.map = L.map('map').setView([-7.5360639, 112.2384017], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
    }).addTo(this.map);

    // Fetch provinces
    try {
      const response = await fetch('/provinsi');
      this.provinsi = await response.json();
    } catch (error) {
      console.error('Error fetching provinsi:', error);
    }
  },
  watch: {
    'form.provinsi': async function (newProvinsi) {
      if (newProvinsi) {
        try {
          const response = await fetch(`/kabupaten/${newProvinsi}`);
          this.kabupaten = await response.json();
          this.form.kabupaten = '';
          this.kecamatan = [];
          this.form.kecamatan = '';
        } catch (error) {
          console.error('Error fetching kabupaten:', error);
        }
      }
    },
    'form.kabupaten': async function (newKabupaten) {
      if (newKabupaten) {
        try {
          const response = await fetch(`/kecamatan/${newKabupaten}`);
          this.kecamatan = await response.json();
          this.form.kecamatan = '';
        } catch (error) {
          console.error('Error fetching kecamatan:', error);
        }
      }
    },
  },
  methods: {
    async search() {
      let url = '/map/search';
      if (this.form.kecamatan) {
        url = `/map/kecamatan/${this.form.kecamatan}`;
      } else if (this.form.kabupaten) {
        url = `/map/search?kabupaten=${this.form.kabupaten}`;
      } else if (this.form.provinsi) {
        url = `/map/search?provinsi=${this.form.provinsi}`;
      }
      try {
        const response = await fetch(url);
        this.results = await response.json();
        // Update map if needed
      } catch (error) {
        console.error('Error searching:', error);
      }
    },
  },
};
</script>

<style scoped>
/* Add custom styles if needed */
</style>