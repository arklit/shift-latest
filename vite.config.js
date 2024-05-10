import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
export default ({ mode }) => {
    process.env = {...process.env, ...loadEnv(mode, process.cwd())};

    const port = process.env.VITE_DOCKER_PORT;

    return defineConfig({
        server: {
            host: true,
            port,
            hmr: {
                host: process.env.VITE_HOST ?? 'localhost',
                port,
            },
        },
        plugins: [
            vue(),
            laravel({
                input: [
                    'resources/scss/client/app.scss',
                    'resources/scss/admin/app.scss',
                    'resources/js/client/app.js',
                    'resources/js/admin/app.js'
                ],
                refresh: true,
            }),
        ],
    });
}
