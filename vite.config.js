import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                compilerOptions: {
                  isCustomElement: (tag) => [
                      'md-elevated-card',
                      'md-list',
                      'md-list-item',
                      'md-icon',
                      'md-dialog',
                      'md-text-button',
                      'md-fab',
                  ].includes('tag')
                },
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
