/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './pages/**/*.{html,js}',
    "./src/**/*.{vue,js,ts,jsx,tsx}",
    './src/views/**/*.{vue,js,ts}',
    './src/components/**/*.{vue,js,ts}',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
}
