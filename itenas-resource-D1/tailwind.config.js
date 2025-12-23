import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // === TAMBAHAN PENTING (WAJIB ADA) ===
    darkMode: 'class', 
    // ====================================

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    50: '#f0f4f8', 100: '#d9e2ec', 200: '#bcccdc', 300: '#9fb3c8',
                    400: '#829ab1', 500: '#486581', 600: '#334e68', 700: '#243b53',
                    800: '#102a43', 900: '#0a1929',
                },
                teal: {
                    50: '#effcf6', 100: '#c6f7e2', 200: '#8eedc7', 300: '#5bd3a9',
                    400: '#2eb88a', 500: '#199473', 600: '#0d7c61', 700: '#03644c',
                    800: '#014d3a', 900: '#003629',
                }
            },
            boxShadow: {
                'glass': '0 4px 30px rgba(0, 0, 0, 0.1)',
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            }
        },
    },

    plugins: [forms],
};