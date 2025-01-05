export default () => ({
  isOpen: false,
  currentSlide: 0,
  totalSlides: 0,

  openGallery(index) {
    this.isOpen = true;
    this.currentSlide = 0;
    this.stopAllVideos();
  },

  closeGallery() {
    this.isOpen = false;
    this.stopAllVideos();
  },

  nextSlide() {
    const slides = document.querySelectorAll('[x-show^="currentSlide ==="]');
    this.stopAllVideos();
    this.currentSlide = (this.currentSlide + 1) % slides.length;
    this.playVideoIfCurrent();
  },

  previousSlide() {
    const slides = document.querySelectorAll('[x-show^="currentSlide ==="]');
    this.stopAllVideos();
    this.currentSlide = (this.currentSlide - 1 + slides.length) % slides.length;
    this.playVideoIfCurrent();
  },

  goToSlide(index) {
    this.stopAllVideos();
    this.currentSlide = index;
    this.playVideoIfCurrent();
  },

  stopAllVideos() {
    document.querySelectorAll('video').forEach((video) => {
      video.pause();
      video.currentTime = 0;
    });
  },

  playVideoIfCurrent() {
    this.$nextTick(() => {
      const currentVideo = document.querySelector(`[x-show="currentSlide === ${this.currentSlide}"] video`);
      if (currentVideo) {
        currentVideo.play();
      }
    });
  },
});
