import { resolve } from 'path';
import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [tailwindcss()],
  root: './',
  build: {
    manifest: true,
    outDir: 'dist',
    rollupOptions: {
      input: {
        app: resolve(__dirname, 'assets/js/app.js'),
      },
    },
  },
  server: {
    origin: 'http://localhost:5173',
    strictPort: true,
    cors: true,
    hmr: {
      host: 'localhost',
    },
  },
});
