/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    DEFAULT: '#20165c',
                    dark: '#181045',
                    light: '#2c1e7a',
                    purple: '#20165c',
                },
                accent: {
                    DEFAULT: '#f47402',
                    hover: '#d96602',
                    tangerine: '#f47402',
                    light: '#ff8a1e',
                },
                charcoal: {
                    DEFAULT: '#121826',
                    muted: '#1e293b',
                    soft: '#334155',
                },
                noksha: {
                    primary: '#20165c',
                    secondary: '#f47402',
                    accent: '#f47402',
                    dark: '#121826',
                    surface: '#ffffff',
                    light: '#f8fafc',
                }
            }
        },
    },
    plugins: [],
};
