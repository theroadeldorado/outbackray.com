export default () => ({
  currentSlide: 0,
  interval: null,

  init() {
    this.startSlideshow();
  },

  startSlideshow() {
    this.interval = setInterval(() => {
      this.nextSlide();
    }, 3000);
  },

  nextSlide() {
    const slides = this.$el.querySelectorAll('[data-slide]');
    this.currentSlide = (this.currentSlide + 1) % slides.length;
  },

  destroy() {
    if (this.interval) {
      clearInterval(this.interval);
    }
  },
});
