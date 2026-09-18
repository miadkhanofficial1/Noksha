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
                noksha: {
                    primary: '#4F46E5',
                    secondary: '#7C3AED',
                    accent: '#06B6D4',
                    dark: '#0F172A',
                    surface: '#1E293B',
                    light: '#F8FAFC',
                }
            }
        },
    },
    plugins: [],
};
