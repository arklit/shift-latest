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
                input: ['resources/css/app.scss', 'resources/js/app.js', 'resources/js/dashboard.js'],
                refresh: true,
            }),
        ],
    });
}
