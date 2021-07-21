const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  purge: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Nunito', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        primary: {
          lighter: '#e9eaf4',
          light: '#c7c9e5',
          DEFAULT: '#8083c1',
          dark: '#48459e',
          darker: '#2b1f72',
        },
        secondary: {
          lighter: '#fff8e0',
          light: '#fee17c',
          DEFAULT: '#fecb00',
          dark: '#ffa000',
          darker: '#ff6c00',
        }
      },
    },
  },

  variants: {
    extend: {
      opacity: ['disabled'],
    },
  },

  plugins: [require('@tailwindcss/forms')],
};
