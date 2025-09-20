<template>
  <div class="app-layout">
    <header>
      <h1>Aplikasi Laravel + Vue</h1>
    </header>
    <main>
      <router-view />
    </main>
    <footer>
      <p>&copy; 2025 Aplikasi Saya</p>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'AppLayout',
};
</script>

<style scoped>
.app-layout {
  display: flex;
  flex-direction: column;
  height: 100vh;
}
header {
  background-color: #f8f9fa;
  padding: 1rem;
  text-align: center;
}
main {
  flex: 1;
  padding: 2rem;
}
footer {
  background-color: #f8f9fa;
  padding: 1rem;
  text-align: center;
}
</style>