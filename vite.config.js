import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
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
            laravel({
                input: [
                    'resources/scss/app.scss',
                    'resources/scss/admin/admin.scss',
                    'resources/js/admin/dashboard.js',
                    'resources/js/app.js'
                ],
                refresh: true,
            }),
        ],
    });
}
