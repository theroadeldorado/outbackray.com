// Default Config: https://github.com/tailwindcss/tailwindcss/blob/master/stubs/defaultConfig.stub.js
module.exports = {
  important: false,
  future: {
    hoverOnlyWhenSupported: true,
  },
  content: ['./templates/**/*.php', './templates/**/*.js', './theme/assets/**/*.js', './theme/main.js', './*.php', './inc/**/*.php', './acf-json/**/*.json'],
  safelist: [
    {
      pattern: /(mt|mb)-gap-(0|xs|sm|md|lg|xl)/,
      variants: ['lg', 'md'],
    },
  ],
  theme: {
    screens: {
      sm: '576px',
      md: '768px',
      lg: '992px',
      xl: '1200px',
      // Update /inc/responsive-images.php to match above
    },
    container: {
      gridCols: 12,
      gapX: {
        DEFAULT: '0.75rem',
        sm: '0.75rem',
        lg: '1.5rem',
      },
      center: true,
      padding: {
        DEFAULT: '1rem',
        sm: '1rem',
        lg: '2rem',
      },
    },
    fontFamily: {
      body: ['Open Sans', 'Arial', 'sans-serif'],
    },
    fontSize: {
      // removes bases sizes
    },
    fontWeight: {
      thin: 200,
      light: 300,
      normal: 400,
      book: 450,
      medium: 500,
      bold: 700,
    },
    extend: {
      colors: {
        primary: {
          100: '#EBF8F2',
          200: '#CDEDDE',
          300: '#AFE1CA',
          400: '#73CBA3',
          500: '#37B57B',
          600: '#32A36F',
          700: '#216D4A',
          800: '#195137',
          900: '#113625',
        },
        secondary: {
          100: '#F0EFF4',
          200: '#DAD6E3',
          300: '#C3BDD2',
          400: '#968CB0',
          500: '#695B8E',
          600: '#5F5280',
          700: '#3F3755',
          800: '#2F2940',
          900: '#201B2B',
        },
      },
      spacing: {
        'gap-0': '0',
        'gap-xs': '1.25rem',
        'gap-sm': '2rem',
        'gap-md': '3rem',
        'gap-lg': '5rem',
        'gap-xl': '8rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    function ({ addBase, theme }) {
      const screens = theme('screens');
      let lastPadding = theme('container.padding.DEFAULT') || '0';
      let lastGap = theme('container.gapX.DEFAULT') || '0';
      const rootVars = {
        ':root': {
          '--fire-content-max-width': screens.sm,
          '--fire-content-padding': lastPadding,
          '--fire-content-gap-x': lastGap,
        },
      };
      Object.keys(screens).forEach((key) => {
        const padding = theme(`container.padding.${key}`);
        const gap = theme(`container.gapX.${key}`);
        if (padding) {
          lastPadding = padding;
        }
        if (gap) {
          lastGap = gap;
        }
        rootVars[`@screen ${key}`] = {
          ':root': {
            '--fire-content-max-width': `calc(${screens[key]} - ${lastGap} * 11)`,
            '--fire-content-padding': lastPadding,
            '--fire-content-gap-x': lastGap,
          },
        };
      });
      addBase(rootVars);
    },
  ],
};
