import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';
import http from 'http';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
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
        tailwindcss(),
        {
            name: 'laravel-dev-proxy',
            configureServer(server) {
                server.middlewares.use((req, res, next) => {
                    const url = req.url || '/';
                    const isViteAsset = url.startsWith('/@') || 
                                       url.startsWith('/resources/') || 
                                       url.startsWith('/node_modules/') ||
                                       url.includes('?import') ||
                                       url.includes('?vue') ||
                                       /\.(js|ts|vue|css|json|png|jpg|jpeg|gif|svg|ico|webp|woff|woff2|ttf|eot)$/i.test(url.split('?')[0]);

                    if (isViteAsset && !url.startsWith('/api/')) {
                        return next();
                    }

                    // Proxy to Laravel backend (127.0.0.1:8000)
                    const proxyReq = http.request({
                        hostname: '127.0.0.1',
                        port: 8000,
                        path: url,
                        method: req.method,
                        headers: {
                            ...req.headers,
                            host: '127.0.0.1:8000',
                            'x-forwarded-host': req.headers.host || '127.0.0.1:5173',
                            'x-forwarded-proto': 'http',
                        }
                    }, (proxyRes) => {
                        res.writeHead(proxyRes.statusCode || 200, proxyRes.headers);
                        proxyRes.pipe(res);
                    });

                    proxyReq.on('error', () => {
                        // Fallback HTML when Laravel server is initializing
                        res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
                        res.end(`<!DOCTYPE html>
<html lang="bn" class="light" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emisha Academy - প্রফেশনাল স্কিল ডেভেলপমেন্ট প্ল্যাটফর্ম</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script type="module" src="/@vite/client"></script>
    <script type="module" src="/resources/js/app.ts"></script>
</head>
<body class="bg-[#0b1120] text-slate-100 font-bangla antialiased">
    <div id="app"></div>
</body>
</html>`);
                    });

                    if (req.readable) {
                        req.pipe(proxyReq);
                    } else {
                        proxyReq.end();
                    }
                });
            },
        },
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            'vue': 'vue/dist/vue.esm-bundler.js',
        },
    },
    server: {
        host: '127.0.0.1',
        port: 5173,
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
