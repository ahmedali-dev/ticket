import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                display: ['Sora', 'ui-sans-serif', 'sans-serif'],
                sans: ['Inter', 'ui-sans-serif', 'sans-serif','Figtree', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                soft: '0 6px 16px -4px rgba(17,24,39,0.10), 0 2px 4px rgba(17,24,39,0.05)',
                lift: '0 20px 40px -12px rgba(11,43,38,0.18)',
            },
            borderRadius: {
                xl2: '16px',
            },
        },
    },

    plugins: [forms],
};
