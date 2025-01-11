import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import focus from '@alpinejs/focus';
import 'lazysizes';
import 'lazysizes/plugins/aspectratio/ls.aspectratio.js';
import AnimalGallery from '@template/components/animal-listing/animal-gallery';
import Gallery from '@template/components/gallery/gallery';

// https://alpinejs.dev/globals/alpine-data#registering-from-a-bundle
document.addEventListener('alpine:init', () => {
  // stores
  // components
  Alpine.data('animalGallery', AnimalGallery);
  Alpine.data('gallery', Gallery);
});

document.addEventListener('DOMContentLoaded', () => {
  window.Alpine = Alpine;
  // plugins
  Alpine.plugin(persist);
  Alpine.plugin(focus);
  Alpine.start();
});
