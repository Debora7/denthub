import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
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
    build: {
        manifest: true,
        outDir: 'public/build/',
    },
    server: {
        host: 'localhost',
        port: 5173,
        cors: {
            origin: '*', // Allow all origins
        },
        hmr: {
            host: 'localhost',
            protocol: 'ws',
        },
    },
    base: 'https://dentportal.ro/', // Ensure this is the correct base URL
});
