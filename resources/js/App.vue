<template>
  <div id="app">
    <nav>
      <router-link to="/">Home</router-link> |
      <router-link to="/map">Map</router-link> |
      <router-link to="/varietas">Varietas</router-link> |
      <router-link to="/deteksi">Deteksi Hama</router-link>
    </nav>
    <router-view />
  </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'App',
});
</script>

<style>
#app {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  height: 100vh;
}
nav {
  background-color: #f8f9fa;
  padding: 1rem;
}
nav a {
  color: #2c3e50;
  margin: 0 0.5rem;
  text-decoration: none;
}
nav a.router-link-exact-active {
  color: #42b983;
}
</style>