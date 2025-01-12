export default () => ({
  scroll: 0,
  scrollUp: '0px',
  scrollDown: '0px',
  scrollUpSlow: '0px',
  isPopoverOpen: false,
  activeImage: null,

  init() {
    this.$watch('scroll', (value) => {
      const isMobile = window.innerWidth < 768;
      if (isMobile) return;
      const multiplier = 0.1;
      const slowMultiplier = 0.025;

      this.scrollUp = `${value * -multiplier}px`;
      this.scrollDown = `${value * multiplier}px`;
      this.scrollUpSlow = `${value * -slowMultiplier}px`;
    });
  },

  handleScroll() {
    this.scroll = window.pageYOffset;
  },

  showImage(imageUrl) {
    this.activeImage = imageUrl;
    this.isPopoverOpen = true;
  },

  closePopover() {
    this.isPopoverOpen = false;
    this.activeImage = null;
  },
});
