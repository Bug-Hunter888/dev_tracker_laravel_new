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

    // Dynamic class names built via PHP arrays/match — must be safelisted
    safelist: [
        // Column header borders + text (board columns)
        'border-white', 'border-yellow-500', 'border-neon-green',
        'text-white',   'text-yellow-500',   'text-neon-green',
        // Priority badge borders + text
        'border-red-400',  'text-red-400',
        'border-gray-600', 'text-gray-600',
        'border-neon-green','text-neon-green',
        // Status badge (task detail)
        'border-gray-400', 'text-gray-400',
        // Overdue task card border
        'border-red-500',
        // Status text colours on board stats bar
        'text-yellow-500', 'text-neon-green',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                mono: ['"Fira Code"', '"Courier New"', 'monospace'],
            },
            colors: {
                'neon-green': '#39FF14',
                'pitch-black': '#000000',
                'dark-gray': '#121212',
            },
            boxShadow: {
                'hard':       '4px 4px 0px 0px #FFFFFF',
                'hard-green': '4px 4px 0px 0px #39FF14',
                'hard-red':   '4px 4px 0px 0px #ef4444',
            },
        },
    },

    plugins: [forms, typography],
};
