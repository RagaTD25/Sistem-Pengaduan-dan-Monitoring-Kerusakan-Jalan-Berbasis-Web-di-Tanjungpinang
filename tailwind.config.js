/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'brand-dark': '#021024',
        'brand-primary': '#052659',
        'brand-medium': '#5483B3',
        'brand-light': '#7DA0CA',
        'brand-bg': '#C1E8FF',
      }
    },
  },
  plugins: [],
}