/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: 'media',  // Esto habilita la detección automática del modo oscuro
  theme: {
    extend: {},
  },
  plugins: [],
};
