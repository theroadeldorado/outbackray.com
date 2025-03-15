export default () => ({
  isOpen: false,
  currentSlide: 0,
  activeAnimal: null,
  animalSlides: {},
  animalMedia: {},
  touchStartX: 0,
  touchEndX: 0,
  minSwipeDistance: 20, // Minimum pixels to trigger swipe
  activeIframes: {},

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

    // Watch for slide changes to handle videos
    this.$watch('currentSlide', (newSlide, oldSlide) => {
      if (this.activeAnimal) {
        this.stopVideos();
      }
    });
  },

  stopVideos() {
    // Find all video iframes in the gallery
    const videoIframes = document.querySelectorAll('[data-animal][data-media-type="video"] iframe');

    videoIframes.forEach((iframe) => {
      // Store the original src
      const originalSrc = iframe.src;

      // If we haven't stored this iframe yet, store its original source
      if (!this.activeIframes[originalSrc]) {
        this.activeIframes[originalSrc] = originalSrc;
      }

      // Remove src to stop video
      iframe.src = '';

      // Set timeout to restore the src if this is the active slide
      setTimeout(() => {
        const slideContainer = iframe.closest('[data-animal][data-media-type="video"]');
        if (slideContainer) {
          const slideIndex = parseInt(slideContainer.dataset.slide);
          const animalId = parseInt(slideContainer.dataset.animal);

          // Only restore src if this is currently the active slide
          if (this.isOpen && this.activeAnimal === animalId && this.currentSlide === slideIndex) {
            iframe.src = this.activeIframes[originalSrc];
          }
        }
      }, 50);
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
    this.stopVideos();
    this.isOpen = false;
    this.activeAnimal = null;
    this.currentSlide = 0;
    this.activeIframes = {};
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
