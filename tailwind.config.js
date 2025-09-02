// tailwind.config.js
module.exports = {
  content: [
    "./**/*.php",
    "./**/*.html",
    "./**/*.js",
    "./**/*.jsx",
    "./**/*.tsx",
  ],
  safelist: ["bg-highlight", "hover:bg-highlight", "hover:bg-highlight/10"],
  theme: {
    extend: {
      colors: {
        primary: "#00AEEF", // Azul principal
        dark: "#002D3D", // Azul escuro
        light: "#F5F7FA", // Cinza muito claro
        neutral: "#7A7A7A", // Cinza médio
        highlight: "#B3E9F5", // Azul claro
        success: "#30C0B7",
        danger: "#EE227D",
      },
      backgroundImage: {
        "text-gradient": "linear-gradient(to right, #304C55, #10C4FF)",
        "bg-gradient-vertical": "linear-gradient(to bottom, #304C55, #10C4FF)",
      },
      fontFamily: {
        sans: ["Sora", "sans-serif"],
      },
    },
  },
  plugins: [],
};
