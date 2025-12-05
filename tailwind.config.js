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
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            // Fonts
            fontFamily: {
                poppins: ['Poppins', ...defaultTheme.fontFamily.sans],
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },

            // Couleurs personnalisées
            colors: {
                'ed-primary': 'rgb(var(--ed-primary) / <alpha-value>)',
                'ed-secondary': 'rgb(var(--ed-secondary) / <alpha-value>)',
                'ed-yellow': 'rgb(var(--ed-yellow) / <alpha-value>)',
                'ed-yellow-dark': 'rgb(var(--ed-yellow-dark) / <alpha-value>)',
                'ed-gray': 'rgb(var(--ed-gray) / <alpha-value>)',
                'ed-gray2': 'rgb(var(--ed-gray2) / <alpha-value>)',
                'ed-teal': 'rgb(var(--ed-teal) / <alpha-value>)',
                'ed-teal-dark': 'rgb(var(--ed-teal-dark) / <alpha-value>)',
            },

            // Animations personnalisées
            animation: {
                'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                'fade-in-down': 'fadeInDown 0.8s ease-out forwards',
                'slide-in-left': 'slideInLeft 0.6s ease-out',
                'slide-in-right': 'slideInRight 0.6s ease-out',
                'scale-in': 'scaleIn 0.5s ease-out',
                'float': 'float 6s ease-in-out infinite',
                'glow': 'glow 2s ease-in-out infinite',
                'gradient': 'gradient 8s linear infinite',
                'bounce-slow': 'bounce 3s infinite',
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },

            // Keyframes
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-50px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(50px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.9)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                glow: {
                    '0%, 100%': { boxShadow: '0 0 20px rgba(27, 154, 125, 0.3)' },
                    '50%': { boxShadow: '0 0 30px rgba(27, 154, 125, 0.6)' },
                },
                gradient: {
                    '0%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                    '100%': { backgroundPosition: '0% 50%' },
                },
            },

            // Spacing personnalisé
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '100': '25rem',
                '112': '28rem',
                '128': '32rem',
            },

            // Border radius
            borderRadius: {
                '4xl': '2rem',
                '5xl': '2.5rem',
            },

            // Box shadows
            boxShadow: {
                'glow-sm': '0 0 10px rgba(27, 154, 125, 0.2)',
                'glow': '0 0 20px rgba(27, 154, 125, 0.3)',
                'glow-lg': '0 0 30px rgba(27, 154, 125, 0.4)',
                'glow-yellow': '0 0 20px rgba(248, 188, 36, 0.3)',
                'glow-yellow-lg': '0 0 30px rgba(248, 188, 36, 0.4)',
                'inner-glow': 'inset 0 0 20px rgba(27, 154, 125, 0.2)',
            },

            // Backdrop blur
            backdropBlur: {
                xs: '2px',
            },

            // Z-index
            zIndex: {
                '60': '60',
                '70': '70',
                '80': '80',
                '90': '90',
                '100': '100',
            },

            // Container
            container: {
                center: true,
                padding: {
                    DEFAULT: '1rem',
                    sm: '2rem',
                    lg: '4rem',
                    xl: '5rem',
                    '2xl': '6rem',
                },
            },

            // Max width
            maxWidth: {
                '8xl': '88rem',
                '9xl': '96rem',
            },

            // Typography
            fontSize: {
                'xxs': '0.625rem',
                '2xs': '0.6875rem',
            },

            // Line height
            lineHeight: {
                'extra-tight': '1.1',
                'extra-loose': '2.5',
            },

            // Letter spacing
            letterSpacing: {
                'extra-wide': '0.15em',
            },
        },
    },

    plugins: [
        forms,
        typography,
        // Plugin personnalisé pour les utilities
        function({ addUtilities }) {
            const newUtilities = {
                '.scrollbar-hide': {
                    '-ms-overflow-style': 'none',
                    'scrollbar-width': 'none',
                    '&::-webkit-scrollbar': {
                        display: 'none',
                    },
                },
                '.scrollbar-default': {
                    '-ms-overflow-style': 'auto',
                    'scrollbar-width': 'auto',
                    '&::-webkit-scrollbar': {
                        display: 'block',
                    },
                },
            };
            addUtilities(newUtilities);
        },
    ],

    // Safelisting pour les classes dynamiques
    safelist: [
        'animate-fade-in-up',
        'animation-delay-200',
        'animation-delay-400',
        'animation-delay-600',
    ],
};