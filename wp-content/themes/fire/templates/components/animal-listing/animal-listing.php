<?php
  $background_texture = get_sub_field('background_texture');
  $background_color = get_sub_field('background_colors');
  $text_color = get_text_color($background_color);
  $card_colors = ['bg-green', 'bg-orange', 'bg-tan', 'bg-navy', 'bg-purple'];

  $index = 0;
  $color_index = 0;
  $gallery_index = 0;
  $animal_index = 0;

  if (in_array($background_color, $card_colors)) {
    $card_colors = array_diff($card_colors, [$background_color]);
    $card_colors = array_values($card_colors);
  }

  $section->add_classes([
    $background_color,
    $background_texture,
    $text_color,
    'py-14 lg:py-20',
  ]);
?>

<?php $section->start(); ?>
  <div class="fire-container" x-data="animalGallery">
    <?php
    $args = array(
      'post_type' => 'animal',
      'posts_per_page' => -1,
      'orderby' => 'title',
      'order' => 'ASC'
    );

    $animals_query = new WP_Query($args);

    if ($animals_query->have_posts()) :  ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <?php while ($animals_query->have_posts()) : $animals_query->the_post();
          $animal_name = get_the_title();
          $birth_date = get_field('birth_date');
          $age = $birth_date ? date_diff(date_create($birth_date), date_create('now'))->y : '';
          $species = get_field('species');
          $bio = get_field('bio');
          $gallery = get_field('gallery');
          $image = $gallery[0];
          $videos = get_field('videos');?>
          <button @click="openGallery(<?php echo $index; ?>)"
            class="animal-card w-full bg-texture-croc overflow-hidden rounded-xl hover:scale-105 transition-all duration-300 ease-bounce <?php echo $card_colors[$color_index % count($card_colors)]; ?>">
            <div class="aspect-[3/2] overflow-hidden rounded-b-xl">
              <?php echo ResponsivePics::get_picture($image, 'sm:600 600|f', 'lazyload-effect full-image', true, true); ?>
            </div>
            <div class="p-3">
              <h3 class="text-[1.125rem] font-bold"><?php echo $animal_name; ?></h3>
            </div>
          </button>
        <?php $index++; $color_index++; endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>

      <div class="fixed inset-0 z-[1001] grid-stack" x-show="isOpen">
        <?php while ($animals_query->have_posts()) : $animals_query->the_post();
          $animal_name = get_the_title();
          $birth_date = get_field('birth_date');
          $age = $birth_date ? date_diff(date_create($birth_date), date_create('now'))->y : '';
          $species = get_field('species');
          $gallery = get_field('gallery');
          $videos = get_field('videos');?>

          <?php foreach($gallery as $slide_index => $image): ?>
            <div data-animal="<?php echo $animal_index; ?>" data-slide="<?php echo $slide_index; ?>"
                x-show="isOpen && activeAnimal === <?php echo $animal_index; ?> && currentSlide === <?php echo $slide_index; ?>">
              <?php echo ResponsivePics::get_picture($image, 'sm:400 800|f, md:1920 1080|f', 'lazyload-effect w-auto full-image', false, false); ?>
            </div>
          <?php endforeach; ?>

          <div x-show="activeAnimal === <?php echo $animal_index; ?>" class="fixed bottom-4 left-4 rounded-lg bg-green bg-texture-leaves max-w-[500px] pl-8 pr-4 pt-4 pb-4">
            <h2 class="text-white heading-6 font-bold mb-3 pr-4"><?php echo $animal_name; ?></h2>
            <div class="flex justify-between gap-4">
              <div>
                <?php if($age): ?>
                  <p class="text-white font-bold mb-3"><?php echo $age; ?> years old</p>
                <?php endif; ?>
                <?php if($species): ?>
                  <p class="text-white font-bold"><?php echo $species; ?></p>
                <?php endif; ?>
              </div>
              <div class="flex justify-end self-end gap-3 shrink-0">
                <button @click="closeGallery()" class="text-white shrink-0 font-bold flex items-center justify-center size-8 p-2 bg-emerald-900 hover:bg-emerald-800 ease-in-out duration-300 transition-all hover:scale-105 rounded-full rotate-90">
                <?php new Fire_SVG('icon--close'); ?>
              </button>
                <button @click="prevImage(<?php echo $animal_index; ?>, <?php echo $slide_index; ?>)" class="text-white font-bold flex items-center justify-center size-8 p-2 bg-emerald-900 hover:bg-emerald-800 ease-in-out duration-300 transition-all hover:scale-105 rounded-full rotate-90">
                  <?php new Fire_SVG('icon--chevron-down'); ?>
                </button>
                <button @click="nextImage(<?php echo $animal_index; ?>, <?php echo $slide_index; ?>)" class="text-white font-bold flex items-center justify-center size-8 p-2 bg-emerald-900 hover:bg-emerald-800 ease-in-out duration-300 transition-all hover:scale-105 rounded-full -rotate-90">
                  <?php new Fire_SVG('icon--chevron-down'); ?>
                </button>
              </div>
            </div>
          </div>
        <?php $animal_index++; endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
<?php $section->end(); ?>
