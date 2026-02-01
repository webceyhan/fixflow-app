import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import laravelWatcher from './vite-plugins/laravel-watcher';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        laravelWatcher({
            watchers: [
                {
                    pattern: 'app/Enums/**.php',
                    command: 'php artisan extract:enums --ts & npm run format',
                },
                {
                    pattern: 'app/Models/**.php',
                    command: 'php artisan extract:models --ts & npm run format',
                },
            ],
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
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
});
