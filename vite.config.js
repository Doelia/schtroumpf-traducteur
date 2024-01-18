import {defineConfig} from "vite";
import { resolve } from 'path';

// Thanks to https://grafikart.fr/tutoriels/vitejs-symfony-1895

const twigRefreshPlugin = {
    name: 'twig-refresh',
    configureServer ({ watcher, ws }) {
        watcher.add(resolve('templates/**/*.twig'));
        watcher.on('change', function (path) {
            if (path.endsWith('.twig')) {
                ws.send({
                    type: 'full-reload'
                });
            }
        });
    }
}
export default defineConfig({
    plugins: [twigRefreshPlugin],
    root: './',
    base: '/build',
    publicDir: false,
    server: {
        origin: 'http://localhost:5173',
    },
    build: {
        assetsDir: './',
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            input: 'assets_vite/app.js',
        },
    },
})
