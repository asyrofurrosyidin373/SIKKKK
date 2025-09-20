<template>
  <div class="container">
    <h2>Pencarian Varietas</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="filter-card">
          <form @submit.prevent="searchVarietas">
            <div class="mb-3">
              <label class="form-label">Pilih Komoditas</label>
              <select v-model="form.type" class="form-select" required>
                <option value="">Pilih Komoditas</option>
                <option value="kedelai">Kedelai</option>
                <option value="kacang_tanah">Kacang Tanah</option>
                <option value="kacang_hijau">Kacang Hijau</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Tahun</label>
              <input v-model="form.tahun" type="number" class="form-control" placeholder="Masukkan tahun">
            </div>
            <button type="submit" class="btn btn-primary w-100">Cari Varietas</button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="results-card">
          <h4>Hasil Pencarian</h4>
          <div v-if="results.length === 0">Pilih komoditas untuk mencari varietas</div>
          <div v-else>
            <div v-for="item in results" :key="item.id" class="varietas-item">
              {{ item.nama_varietas }}
              <button @click="viewDetail(item.id, form.type)" class="btn btn-sm btn-info ms-2">Detail</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HalamanVarietas',
  data() {
    return {
      form: { type: '', tahun: '' },
      results: [],
    };
  },
  methods: {
    async searchVarietas() {
      try {
        const params = new URLSearchParams();
        if (this.form.type) params.append('type', this.form.type);
        if (this.form.tahun) params.append('tahun', this.form.tahun);
        const response = await fetch(`/varietas/search?${params.toString()}`);
        this.results = await response.json();
      } catch (error) {
        console.error('Error fetching varietas:', error);
      }
    },
    viewDetail(id, type) {
      this.$router.push({ path: `/varietas/detail/${id}`, query: { type } });
    },
  },
};
</script>

<style scoped>
.filter-card {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
}
.results-card {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.varietas-item {
  border: 1px solid #dee2e6;
  border-radius: 5px;
  padding: 15px;
  margin-bottom: 15px;
}
</style>