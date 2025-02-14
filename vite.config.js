import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { dirname } from "path";
import { fileURLToPath } from "url";

export default defineConfig({
    root: dirname(fileURLToPath(import.meta.url)),
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            buildDirectory: "../../core/Modules/Pos/build",
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            detectTls: "safecart.test",
        }),
    ],
    server: {
        host: "safecart.test"
    }
});
