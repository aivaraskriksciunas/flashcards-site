/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ['./**/*.html'],
    theme: {
      extend: {},
      container: {
        center: true,
        padding: {
          DEFAULT: '1rem',
          // sm: '2rem',
          // lg: '4rem',
          // xl: '5rem',
          // '2xl': '6rem',
        },
      },
      screens: {
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1280px',
        '2xl': '1536px',
      },
      colors: {
        'white': 'rgb( var(--color-white) / <alpha-value> )',
        'gray-background': 'rgb( var(--color-bg) / <alpha-value> )',
        'primary': 'rgb( var(--color-primary) / <alpha-value> )',
        'secondary': 'rgb( var(--color-secondary) / <alpha-value> )',
      }
    },
    variants: {
      extend: {},
    },
    plugins: [],
  }