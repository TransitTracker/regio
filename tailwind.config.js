/** @type {import('tailwindcss').Config} */
const m3 = require("tailwind-m3-colors");
export default {
  content: [
      './resources/**/*.vue',
      './resources/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
    fontFamily: {
        heading: ["Figtree", "sans-serif"],
        sans: ["Inter", "sans-serif"],
    },
  plugins: [
      m3("#2374ab", "#009a8d", "", {
          stepTonesBy10: true,
          inverseSteps: false,
      }),
  ],
}

