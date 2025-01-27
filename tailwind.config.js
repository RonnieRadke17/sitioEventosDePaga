/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js"
  ],
  darkMode: 'media',  // Esto habilita la detección automática del modo oscuro
  theme: {
    extend: {},
  },
  plugins: [
    require('flowbite/plugin')
  ],
};
