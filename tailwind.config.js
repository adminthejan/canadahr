/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",],
    theme: {
      extend: {
        boxShadow: {
          'popup': '0 0 12px rgba(0, 0, 0, 0.5)', // Pop-up effect shadow
        },
      },
    },
    variants: {
      extend: {
        boxShadow: ['hover'], // Enable hover variant for custom shadows
      },
    },
  plugins: [],
}

