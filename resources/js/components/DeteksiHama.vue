<template>
  <div class="container">
    <h2>Deteksi Hama dan Penyakit</h2>
    <div class="row">
      <div class="col-md-5">
        <div class="symptoms-section">
          <h4>Pilih Gejala yang Diamati</h4>
          <form @submit.prevent="diagnose">
            <div v-for="group in groupedGejala" :key="group.bagian" class="plant-part">
              <h5>{{ group.bagian || 'Umum' }}</h5>
              <div v-for="gejala in group.gejala" :key="gejala.id" class="symptom-item">
                <div class="form-check">
                  <input type="checkbox" v-model="selectedGejala" :value="gejala.id" :id="'gejala' + gejala.id" class="form-check-input">
                  <label :for="'gejala' + gejala.id" class="form-check-label">{{ gejala.deskripsi }}</label>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Diagnosa</button>
          </form>
        </div>
      </div>
      <div class="col-md-7">
        <div class="results-section">
          <h4>Hasil Diagnosa</h4>
          <div v-if="results.length === 0">Pilih gejala untuk memulai diagnosa</div>
          <div v-else>
            <div v-for="result in results" :key="result.id" class="result-item">
              {{ result.nama_penyakit }} - Kemiripan: {{ result.similarity }}%
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DeteksiHama',
  data() {
    return {
      groupedGejala: [],
      selectedGejala: [],
      results: [],
    };
  },
  async mounted() {
    try {
      const response = await fetch('/deteksi/gejala');
      const gejala = await response.json();
      this.groupedGejala = this.groupBy(gejala, 'bagian_tanaman');
    } catch (error) {
      console.error('Error fetching gejala:', error);
    }
  },
  methods: {
    groupBy(array, key) {
      return Object.values(
        array.reduce((acc, item) => {
          const groupKey = item[key] || 'Umum';
          if (!acc[groupKey]) acc[groupKey] = { bagian: groupKey, gejala: [] };
          acc[groupKey].gejala.push(item);
          return acc;
        }, {})
      );
    },
    async diagnose() {
      try {
        const response = await fetch('/deteksi/diagnose', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({ gejala: this.selectedGejala }),
        });
        this.results = await response.json();
      } catch (error) {
        console.error('Error diagnosing:', error);
      }
    },
  },
};
</script>

<style scoped>
.symptoms-section {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
}
.results-section {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.plant-part {
  margin-bottom: 20px;
}
.symptom-item {
  padding: 10px;
  margin: 5px 0;
  border: 1px solid #dee2e6;
  border-radius: 5px;
  background: white;
}
.result-item {
  border: 1px solid #dee2e6;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 15px;
}
</style>