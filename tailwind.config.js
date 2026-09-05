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
            colors: {
                ink: '#0F0F0F',
                paper: '#FDFBF7',
                newsprint: '#F2F0EB',
                'city-accent': 'var(--color-city-accent, #B83B22)',
                'directory-blue': '#1F3A4B',
                success: '#2D5F3A',
                // New premium colors
                'gold': '#C5A059',
                'burgundy': '#6C2127',
                'sage': '#8BA888',
                'midnight': '#111827',
                'sand': '#E8E2D2'
            },
            fontFamily: {
                sans: ['"Inter"', ...defaultTheme.fontFamily.sans],
                serif: ['"Roboto"', ...defaultTheme.fontFamily.serif],
                display: ['"Roboto"', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'fade-in': 'fadeIn 0.5s ease-out forwards',
                'marquee': 'marquee 50s linear infinite',
                'blob': 'blob 10s infinite',
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                marquee: {
                    '0%': { transform: 'translateX(100%)' },
                    '100%': { transform: 'translateX(-100%)' },
                },
                blob: {
                    '0%': { transform: 'translate(0px, 0px) scale(1)' },
                    '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                    '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                    '100%': { transform: 'translate(0px, 0px) scale(1)' },
                }
            }
        },
    },

    plugins: [forms],
};
