export default () => ({
  isOpen: false,
  currentSlide: 0,
  activeAnimal: null,
  animalSlides: {},
  animalMedia: {},
  touchStartX: 0,
  touchEndX: 0,
  minSwipeDistance: 20, // Minimum pixels to trigger swipe

  init() {
    // Build a map of media (images + videos) for each animal
    const animals = document.querySelectorAll('[data-animal]');
    animals.forEach((el) => {
      const animalIndex = parseInt(el.dataset.animal);
      const mediaType = el.dataset.mediaType;

      if (!this.animalMedia[animalIndex]) {
        this.animalMedia[animalIndex] = [];
      }

      this.animalMedia[animalIndex].push({
        type: mediaType,
        url: el.dataset.url,
        youtubeId: el.dataset.youtubeId,
      });

      this.animalSlides[animalIndex] = this.animalMedia[animalIndex].length;
    });

    // Add touch event listeners
    this.$watch('isOpen', (value) => {
      if (value) {
        document.addEventListener('touchstart', this.handleTouchStart.bind(this), { passive: true });
        document.addEventListener('touchend', this.handleTouchEnd.bind(this), { passive: true });
      } else {
        document.removeEventListener('touchstart', this.handleTouchStart.bind(this));
        document.removeEventListener('touchend', this.handleTouchEnd.bind(this));
      }
    });
  },

  handleTouchStart(e) {
    this.touchStartX = e.touches[0].clientX;
  },

  handleTouchEnd(e) {
    this.touchEndX = e.changedTouches[0].clientX;
    const swipeDistance = this.touchEndX - this.touchStartX;

    // Only process swipe if we have an active animal and the swipe is long enough
    if (this.activeAnimal && Math.abs(swipeDistance) > this.minSwipeDistance) {
      const totalSlides = this.animalSlides[this.activeAnimal];

      if (swipeDistance > 0 && this.currentSlide > 0) {
        // Swiped right - show previous
        this.prevImage(this.activeAnimal);
      } else if (swipeDistance < 0 && this.currentSlide < totalSlides - 1) {
        // Swiped left - show next
        this.nextImage(this.activeAnimal);
      }
    }
  },

  openGallery(animalIndex) {
    this.isOpen = true;
    this.activeAnimal = animalIndex;
    this.currentSlide = 0;
  },

  closeGallery() {
    this.isOpen = false;
    this.activeAnimal = null;
    this.currentSlide = 0;
  },

  nextImage(animalIndex) {
    const totalSlides = this.animalSlides[animalIndex];
    this.currentSlide = (this.currentSlide + 1) % totalSlides;
  },

  prevImage(animalIndex) {
    const totalSlides = this.animalSlides[animalIndex];
    this.currentSlide = (this.currentSlide - 1 + totalSlides) % totalSlides;
  },
});
