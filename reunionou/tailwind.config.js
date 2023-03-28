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
        cpurple: '#905ff4',
        cwhite: '#ffffff',
        cblack: '#2b2b2b',
        cwhite2: '#f6f6f6',
        cgray: '#adadad',
        cred: '#ed2456',
      }
    },
  },
  plugins: [],
}
