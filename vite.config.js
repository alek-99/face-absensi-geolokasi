import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// Jika menggunakan React/Vue, import plugin-nya di sini (misal: import react from '@vitejs/plugin-react')

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        // react(), // aktifkan jika Anda menggunakan React
    ],
    build: {
        // 1. Tambahkan konfigurasi Rollup untuk memecah node_modules
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        // Memisahkan library besar menjadi chunk-nya masing-masing
                        return id.toString().split('node_modules/')[1].split('/')[0].toString();
                    }
                },
            },
        },
        // 2. Naikkan sedikit batas toleransi chunk jika Anda menggunakan library visualisasi data/dashboard besar
        chunkSizeWarningLimit: 1000, // Menaikkan batas peringatan ke 1000 kB (1 MB)
    },
});