/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: ['./index.html', './src/**/*.{ts,tsx}'],
  theme: {
    extend: {
      transitionProperty: {
        'theme': 'background-color, color, border-color'
      }
    }
  },
  plugins: [],
}

