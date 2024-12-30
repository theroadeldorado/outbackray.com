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
        black: '#000000',
        white: '#ffffff',
      },
    },
  },
};