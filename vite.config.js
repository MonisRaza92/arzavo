import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/builder.js', 'resources/js/theme.js'],
            refresh: true,
        }),
        tailwindcss({
            // ✅ Yahan specific paths define karo
            content: [
                './resources/views/**/*.blade.php',
                './resources/js/**/*.js',
                './app/View/Components/**/*.php',
            ]
        }),
    ],
});
