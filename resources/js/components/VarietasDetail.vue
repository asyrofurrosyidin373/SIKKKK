<template>
  <div class="container">
    <h2>Detail Varietas</h2>
    <div v-if="varietas" class="card">
      <div class="card-body">
        <h5 class="card-title">{{ varietas.nama_varietas }}</h5>
        <p><strong>Tahun:</strong> {{ varietas.tahun }}</p>
        <p><strong>Potensi Hasil:</strong> {{ varietas.potensi_hasil }} Ton/Ha</p>
        <!-- Add other fields as needed -->
        <router-link to="/varietas" class="btn btn-primary">Kembali</router-link>
      </div>
    </div>
    <div v-else>Loading...</div>
  </div>
</template>

<script>
export default {
  name: 'VarietasDetail',
  data() {
    return {
      varietas: null,
    };
  },
  async mounted() {
    const id = this.$route.params.id;
    const type = this.$route.query.type;
    try {
      const response = await fetch(`/varietas/detail/${id}?type=${type}`);
      this.varietas = await response.json();
    } catch (error) {
      console.error('Error fetching varietas detail:', error);
    }
  },
};
</script>

<style scoped>
/* Add custom styles if needed */
</style>