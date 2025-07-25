import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // <- importante
        //host: '192.168.1.72', // direccion en otro lado
        port: 5173,      // Cambia el puerto si es necesario
        hmr: {
            //host: '192.168.1.72', // direccion en casa
            host: '127.0.0.1', // direccion en otro lado
        },
        /* headers: {
            'Access-Control-Allow-Origin': '*',
        }, */
    },
});
