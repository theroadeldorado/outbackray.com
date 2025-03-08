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
    'lg:hover:text-light-green',
    'lg:hover:text-light-orange',
    'lg:hover:text-light-navy',
    'lg:hover:text-light-purple',
    'lg:hover:rotate-[-3deg]',
    'lg:hover:rotate-[-2deg]',
    'lg:hover:rotate-[-1deg]',
    'lg:hover:rotate-[0deg]',
    'lg:hover:rotate-[1deg]',
    'lg:hover:rotate-[2deg]',
    'lg:hover:rotate-[3deg]',
    'rotate-[0deg]',
    'rotate-[1deg]',
    'rotate-[2deg]',
    'rotate-[3deg]',
    'rotate-[4deg]',
    'rotate-[5deg]',
    'rotate-[-1deg]',
    'rotate-[-2deg]',
    'rotate-[-3deg]',
    'rotate-[-4deg]',
    'rotate-[-5deg]',
    'hover:rotate-[0deg]',
    'hover:rotate-[1deg]',
    'hover:rotate-[2deg]',
    'hover:rotate-[3deg]',
    'hover:rotate-[4deg]',
    'hover:rotate-[5deg]',
    'hover:rotate-[-1deg]',
    'hover:rotate-[-2deg]',
    'hover:rotate-[-3deg]',
    'hover:rotate-[-4deg]',
    'hover:rotate-[-5deg]',
    'lg:hover:scale-110',
    'transition-all',
    'duration-300',
    'ease-bounce',
  ],
  theme: {
    screens: {
      sm: '576px',
      md: '768px',
      lg: '992px',
      xl: '1400px',
      xxl: '1800px',
      // Update /inc/responsive-images.php to match above
    },
    container: {
      gapX: {
        DEFAULT: '1.25rem',
        sm: '1.25rem',
        lg: '1.25rem',
      },
      center: true,
      padding: {
        DEFAULT: '1rem',
        sm: '1rem',
        lg: '3rem',
        xl: '5rem',
      },
    },
    fontFamily: {
      // body: ['Quicksand', 'sans-serif'],
      body: ['Hind Madurai', 'sans-serif'],
      heading: ['Lora', 'serif'],
    },
    fontSize: {
      // removes bases sizes
    },
    fontWeight: {
      light: 300,
      normal: 400,
      medium: 500,
      semibold: 600,
      bold: 700,
    },
    extend: {
      colors: {
        'dark-blue': '#031934',
        green: '#4CAF50',
        'light-green': '#A5D6A7',
        orange: '#FF9800',
        'light-orange': '#FFC107',
        tan: '#ADA289',
        'light-tan': '#ECE4DB',
        navy: '#2C3E50',
        'light-navy': '#697D91',
        purple: '#0e470d',
        'light-purple': '#489746',
      },
      spacing: {
        'gap-0': '0',
        'gap-xs': '1.25rem',
        'gap-sm': '2rem',
        'gap-md': '3rem',
        'gap-lg': '5rem',
        'gap-xl': '8rem',
      },
      transitionTimingFunction: {
        bounce: 'cubic-bezier(0.175, 0.885, 0.32, 1.275)',
        sine: 'cubic-bezier(.47,0,.74,.71)',
      },
    },
  },
  plugins: [
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
