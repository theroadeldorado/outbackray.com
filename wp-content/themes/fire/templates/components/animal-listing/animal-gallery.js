export default () => ({
  isOpen: false,
  currentSlide: 0,
  activeAnimal: null,
  totalSlides: null,

  openGallery(animalIndex) {
    this.isOpen = true;
    this.activeAnimal = animalIndex;
    this.currentSlide = 0;

    // Count total slides by selecting all image divs in the gallery
    this.totalSlides = document.querySelectorAll('[data-animal]').length;
  },

  closeGallery() {
    this.isOpen = false;
    this.activeAnimal = null;
    this.currentSlide = 0;
  },

  nextImage() {
    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
    this.updateActiveAnimal();
  },

  prevImage() {
    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
    this.updateActiveAnimal();
  },

  updateActiveAnimal() {
    // Find the animal index for the current slide
    const currentElement = document.querySelector(`[data-animal][data-slide="${this.currentSlide}"]`);
    if (currentElement) {
      this.activeAnimal = parseInt(currentElement.dataset.animal);
    }
  },
});
