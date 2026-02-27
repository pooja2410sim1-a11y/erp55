import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

// Ensure APP_URL is always available in local/dev environments.
// This avoids "APP_URL: undefined" in Vite startup output when .env is missing.
process.env.APP_URL ||= 'http://localhost'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
})
