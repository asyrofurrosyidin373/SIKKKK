import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/app.js'], // Pastikan path ini benar
      refresh: true, // Aktifkan Hot Module Replacement (HMR)
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  server: {
    host: 'localhost', // Pastikan host ini sesuai
    hmr: {
      host: 'localhost',
      port: 5173, // Port default Vite
    },
  },
});