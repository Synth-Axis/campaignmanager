// tailwind.config.js
module.exports = {
  content: [
    "./*.php",
    "./views/**/*.php",
    "./app/**/*.php",
    "./assets/js/**/*.js",
  ],
  darkMode: "class",
  safelist: ["bg-highlight", "hover:bg-highlight", "hover:bg-highlight/10"],
  theme: {
    extend: {
      colors: {
        primary: "#00779E", // Azul da marca com contraste para texto branco
        "primary-hover": "#006586",
        brand: "#00AEEF",
        dark: "#002D3D", // Azul escuro
        light: "rgb(var(--color-canvas) / <alpha-value>)",
        surface: "rgb(var(--color-surface) / <alpha-value>)",
        field: "rgb(var(--color-field) / <alpha-value>)",
        ink: "rgb(var(--color-ink) / <alpha-value>)",
        neutral: "rgb(var(--color-muted) / <alpha-value>)",
        line: "rgb(var(--color-line) / <alpha-value>)",
        control: "rgb(var(--color-control) / <alpha-value>)",
        accent: "rgb(var(--color-accent) / <alpha-value>)",
        highlight: "rgb(var(--color-highlight) / <alpha-value>)",
        success: "#087D76",
        positive: "rgb(var(--color-positive) / <alpha-value>)",
        danger: "#CC216A",
        negative: "rgb(var(--color-negative) / <alpha-value>)",
      },
      backgroundImage: {
        "text-gradient": "linear-gradient(110deg, rgb(var(--color-ink)), rgb(var(--color-accent)))",
        "bg-gradient-vertical": "linear-gradient(135deg, #002D3D, #00779E)",
      },
      fontFamily: {
        sans: ["Sora", "sans-serif"],
      },
    },
  },
  plugins: [],
};
