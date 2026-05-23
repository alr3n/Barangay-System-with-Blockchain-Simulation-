/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#1E3A5F',
                    50:  '#EEF2F8',
                    100: '#D4DEF0',
                    200: '#A9BCE1',
                    300: '#7E9BD2',
                    400: '#5379C3',
                    500: '#1E3A5F',
                    600: '#1A3356',
                    700: '#162C4C',
                    800: '#122543',
                    900: '#0E1E3A',
                },
                secondary: '#64748B',
                accent: '#3B82F6',
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                mono: ['JetBrains Mono', 'ui-monospace', 'SFMono-Regular', 'monospace'],
            },
            boxShadow: {
                card: '0 1px 3px 0 rgb(0 0 0 / 0.05), 0 1px 2px -1px rgb(0 0 0 / 0.05)',
            },
            borderRadius: {
                xl: '0.75rem',
                '2xl': '1rem',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
