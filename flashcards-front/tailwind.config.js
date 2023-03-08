/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
    container: {
      center: true,
    },
    fontWeight: {
      light: 300,
      normal: 400,
      bold: 500,
    }
  },
  plugins: [],
}
