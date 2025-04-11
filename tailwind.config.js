import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            backgroundImage: {
                'gradient-navbar': 'linear-gradient(0deg, rgba(0,117,111,1) 0%, rgba(0,182,173,1) 100%)',
              },
            colors: {
                c3turquoise: '#00B6AD',
                c3purple: '#6B3BB5',
                c3green: '#C6F3EA',
                c3darkturq: '#00756F',
              },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
