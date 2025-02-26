import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/bill.js',
                'resources/js/bills.js',
                'resources/js/category.js',
                'resources/js/customers.js',
                'resources/js/imports_view.js',
                'resources/js/products.js',
                'resources/js/show_bills.js',
                'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
            ],
            refresh: true,
        }),
    ],
});
