export default () => ({
  isOpen: false,
  currentSlide: 0,
  activeAnimal: null,
  animalSlides: {},
  animalMedia: {},

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
