// Default Config: https://github.com/tailwindcss/tailwindcss/blob/master/stubs/defaultConfig.stub.js
module.exports = {
  important: false,
  corePlugins: {
    preflight: false,
  },
  content: ['./acf-json/**/*.json'],
  safelist: [],
  theme: {
    extend: {
      colors: {
        green: '#4CAF50',
        'light-green': '#A5D6A7',
        orange: '#FF9800',
        'light-orange': '#FFC107',
        tan: '#ADA289',
        'light-tan': '#ECE4DB',
        navy: '#2C3E50',
        'light-navy': '#697D91',
        purple: '#8E44AD',
        'light-purple': '#D1C4E9',
      },
    },
  },
};
