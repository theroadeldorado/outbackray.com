<?php
  $gallery = get_sub_field('gallery');
  $image_count = $gallery ? count($gallery) : 0;
  $copy = get_sub_field('copy');

  $background_texture = get_sub_field('background_texture');
  $background_color = get_sub_field('background_colors');
  $text_color = get_text_color($background_color);



  $section->add_classes([
    $background_color,
    $background_texture,
    $text_color,
  ]);
?>

<?php $section->start(); ?>
  <div class="fire-container relative gap-y-10 overflow-clip md:py-0" x-data="gallery" @scroll.window="handleScroll">
    <?php if ($copy) : ?>
      <div class="md:row-start-1 relative z-[2] col-[main] md:col-[col-1/col-4]">
        <div class=" wizzy pt-20 md:py-20 sticky top-20 lg:mb-20 font-medium">
          <?php echo $copy; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($gallery) : ?>
      <div class="md:row-start-1 relative col-[full-width] md:col-[col-5/full-width]">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-x-4 md:gap-x-8 py-20 px-8">
          <?php
          // Split gallery into columns
          $num_columns = wp_is_mobile() ? 2 : 3;
          $columns = array_chunk($gallery, ceil(count($gallery) / $num_columns));

          foreach ($columns as $column_index => $column_images) :
            $translateY = match($column_index) {
              1 => 'translate-y-[var(--scroll-down)]',
              2 => 'translate-y-[var(--scroll-up-slow)]',
              default => 'translate-y-[var(--scroll-up)] mt-20'
            };
          ?>
            <div
              class="grid gap-y-4 md:gap-y-10 lg:gap-y-20 transition-transform duration-100"
              :style="{
                '--scroll-up': scrollUp,
                '--scroll-down': scrollDown,
                '--scroll-up-slow': scrollUpSlow,
              }"
              x-bind:class="'<?php echo $translateY; ?>'"
            >
              <?php foreach ($column_images as $image) :
                $rotate_image = rand(0, 1) ? rand(-5, -2) : rand(2, 5);
              ?>
                <div
                  class="rounded-lg md:rounded-xl lg:rounded-[30px] overflow-hidden cursor-pointer"
                  style="transform: rotate(<?php echo $rotate_image; ?>deg)"
                  @click="showImage('<?php echo wp_get_attachment_image_url($image['id'], 'full'); ?>')"
                >
                  <?php echo ResponsivePics::get_picture($image['id'], 'sm:600|f, md:500|f, lg:600|f, xl:700|f, xxl:1000|f', 'lazyload-effect full-image', true, false); ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Popover -->
    <div
      x-show="isPopoverOpen"
      x-trap.noscroll.noautofocus="isPopoverOpen"
      x-transition
      class="fixed w-screen h-screen inset-0 z-[1001] flex items-center justify-center bg-black/80"
      @keydown.escape.window="closePopover()"
    >
      <button
        class="absolute top-4 right-4 text-white p-2 hover:scale-110 transition-transform duration-300 ease-bounce z-[1]"
        @click="closePopover()"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      <img
        :src="activeImage"
        class="max-h-[90vh] max-w-[90vw] object-contain"
      >
    </div>
  </div>
<?php $section->end(); ?>
