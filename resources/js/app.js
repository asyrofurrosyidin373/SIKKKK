import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue'; // Impor App.vue sebagai root component
import AppLayout from './components/AppLayout.vue';
import HalamanUtama from './components/HalamanUtama.vue';
import HalamanVarietas from './components/HalamanVarietas.vue';
import DeteksiHama from './components/DeteksiHama.vue';
import HalamanPeta from './components/HalamanPeta.vue';
import VarietasDetail from './components/VarietasDetail.vue';

const routes = [
  {
    path: '/',
    component: AppLayout,
    children: [
      { path: '', component: HalamanUtama, name: 'home' },
      { path: 'map', component: HalamanPeta, name: 'map' },
      { path: 'varietas', component: HalamanVarietas, name: 'varietas' },
      { path: 'varietas/detail/:id', component: VarietasDetail, name: 'varietas.detail' },
      { path: 'deteksi', component: DeteksiHama, name: 'deteksi' },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

const app = createApp(App); // Gunakan App.vue sebagai root component
app.use(router);
app.mount('#app');