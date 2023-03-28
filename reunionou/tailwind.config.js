/** @type {import('tailwindcss').Config} */

const colors = require('tailwindcss/colors')

module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx,vue}",
  ],
  theme: {
    extend: {
      colors: {
        transparent: 'transparent',
        current: 'currentColor',
        purple: '#905ff4',
        white: '#ffffff',
        black: '#2b2b2b',
        white2: '#f6f6f6',
        gray: '#adadad',
      }
    },
  },
  plugins: [],
}
