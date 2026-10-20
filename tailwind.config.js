import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['"Space Grotesk"', 'Inter', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                magenta: {
                    50: '#fff0f7',
                    100: '#ffe0ef',
                    200: '#ffc2df',
                    300: '#fb93c4',
                    400: '#f55aa5',
                    500: '#eb268f',
                    600: '#d91d78',
                    700: '#b51761',
                    800: '#951750',
                    900: '#7b1744',
                    950: '#4b0825',
                },
                night: {
                    950: '#06050a',
                    900: '#0c0a12',
                    850: '#110e1a',
                    800: '#171321',
                },
            },

            borderRadius: {
                '4xl': '2rem',
            },

            boxShadow: {
                card: '0 1px 2px rgba(16,24,40,.03), 0 6px 16px -10px rgba(16,24,40,.10)',
                'card-lg': '0 2px 4px rgba(16,24,40,.04), 0 18px 32px -18px rgba(16,24,40,.18)',
                glow: '0 0 0 1px rgba(235,38,143,.10), 0 8px 22px -12px rgba(235,38,143,.22)',
                'glow-sm': '0 4px 12px -8px rgba(235,38,143,.30)',
            },

            keyframes: {
                'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'scale-in': {
                    '0%': { opacity: '0', transform: 'scale(.96)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                'spin-slow': {
                    '0%': { transform: 'rotate(0deg)' },
                    '100%': { transform: 'rotate(360deg)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
               'pulse-ring': {
    '0%': { boxShadow: '0 0 0 0 rgba(214,0,123,.45)' },
    '70%': { boxShadow: '0 0 0 10px rgba(214,0,123,0)' },
    '100%': { boxShadow: '0 0 0 0 rgba(214,0,123,0)' },
},
            },

            animation: {
                'fade-up': 'fade-up .35s ease-out both',
                'fade-in': 'fade-in .25s ease-out both',
                'scale-in': 'scale-in .2s ease-out both',
                'spin-slow': 'spin-slow 14s linear infinite',
                shimmer: 'shimmer 2.2s linear infinite',
                'pulse-ring': 'pulse-ring 2s cubic-bezier(.4,0,.6,1) infinite',
            },
        },
    },

    plugins: [forms],
};
