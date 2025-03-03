<?php
  $background_texture = get_sub_field('background_texture');
  $background_color = get_sub_field('background_colors');
  $text_color = get_text_color($background_color);
  $card_colors = ['bg-green', 'bg-orange', 'bg-tan', 'bg-purple', 'bg-navy'];

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
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 lg:gap-20">
        <?php while ($animals_query->have_posts()) : $animals_query->the_post();
          $animal_name = get_the_title();
          $birth_date = get_field('birth_date');
          $birth_date_formatted = DateTime::createFromFormat('d/m/Y', $birth_date);
          $age = $birth_date ? ($birth_date_formatted ? date_diff($birth_date_formatted, date_create('now'))->y : null) : null;
          $species = get_field('species');
          $bio = get_field('bio');
          $gallery = get_field('gallery');
          $image = $gallery[0];
          $videos = get_field('videos');
          $rotate_image = rand(0, 1) ? rand(-5, -2) : rand(2, 5);
          $animal_id = get_the_ID();
          $animal_slug = get_post_field('post_name', $animal_id);
          ?>

          <button
            @click="openGallery(<?php echo $animal_id; ?>)"
            id="<?php echo $animal_slug; ?>"
            class="animal-card w-full bg-texture-croc overflow-hidden flex flex-col rounded-xl lg:rounded-[30px] hover:scale-105 transition-all hover:rotate-[<?php echo $rotate_image; ?>deg] duration-300 ease-bounce <?php echo $card_colors[$color_index % count($card_colors)]; ?>">
            <div class="aspect-[3/2] w-full overflow-hidden rounded-b-xl shrink-0 lg:rounded-b-[30px]">
              <?php echo ResponsivePics::get_picture($image, 'sm:600 600|f', 'lazyload-effect full-image', true, true); ?>
            </div>
            <div class="p-3 md:p-4 grow w-full">
              <h3 class="text-[1.5rem] font-bold"><?php echo $animal_name; ?></h3>
              <p class="font-medium"><?php echo $species; ?></p>
            </div>
          </button>
        <?php $index++; $color_index++; endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>

      <div
           x-show="isOpen"
           x-trap.noscroll.noautofocus="isOpen"
           x-transition
            x-cloak
           class="fixed w-screen h-screen inset-0 z-[1001] flex items-center justify-center bg-black/80">
        <?php while ($animals_query->have_posts()) : $animals_query->the_post();
          $animal_name = get_the_title();
          $birth_date = get_field('birth_date');
          $birth_date_formatted = DateTime::createFromFormat('d/m/Y', $birth_date);
          $age = $birth_date ? ($birth_date_formatted ? date_diff($birth_date_formatted, date_create('now'))->y : null) : null;
          $species = get_field('species');
          $gallery = get_field('gallery');
          $videos = get_field('videos');
          $animal_id = get_the_ID();

          // Combine images and videos into a single media array
          $media_index = 0;
          ?>

          <?php if($gallery): ?>
            <?php foreach($gallery as $image): ?>
              <div
                class="flex items-center justify-center w-[90vw] h-[90vh]"
                data-animal="<?php echo $animal_id; ?>"
                data-media-type="image"
                data-url="<?php echo wp_get_attachment_image_url($image, 'full'); ?>"
                data-slide="<?php echo $media_index; ?>"
                x-show="isOpen && activeAnimal === <?php echo $animal_id; ?> && currentSlide === <?php echo $media_index; ?>"
              >
                <div class="relative w-full h-full flex items-center justify-center [&>img]:w-auto [&>img]:h-auto [&>img]:max-w-full [&>img]:max-h-full [&>img]:object-contain">
                  <?php echo ResponsivePics::get_image($image, 'sm-12, md-12, lg-12'); ?>
                </div>
              </div>
              <?php $media_index++; ?>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if($videos && is_array($videos)): ?>
            <?php foreach($videos as $video):
              if (!empty($video['video'])) {
                // Extract the iframe and modify dimensions
                $iframe = $video['video'];
                $iframe = preg_replace('/width="(\d+)"/', 'width="1280"', $iframe);
                $iframe = preg_replace('/height="(\d+)"/', 'height="720"', $iframe);
                ?>
                <div class="flex items-center justify-center"
                     data-animal="<?php echo $animal_id; ?>"
                     data-media-type="video"
                     data-slide="<?php echo $media_index; ?>"
                     x-show="isOpen && activeAnimal === <?php echo $animal_id; ?> && currentSlide === <?php echo $media_index; ?>">
                  <div class="aspect-video w-full max-w-[90vw]">
                    <?php echo $iframe; ?>
                  </div>
                </div>
                <?php
                $media_index++;
              }
            endforeach; ?>
          <?php endif; ?>

          <div x-show="activeAnimal === <?php echo $animal_id; ?>"
               class="fixed bottom-4 left-4 rounded-lg bg-green bg-texture-leaves max-w-[500px] pl-8 pr-4 pt-4 pb-4">
            <h2 class="text-white heading-6 font-bold mb-3 pr-4"><?php echo $animal_name; ?></h2>
            <div class="flex justify-between gap-4">
              <div>
                <?php if($species): ?>
                  <p class="text-white font-bold"><?php echo $species; ?></p>
                <?php endif; ?>
                <?php if($age): ?>
                  <p class="text-white font-bold mb-3"><?php echo $age; ?> years old</p>
                <?php endif; ?>
              </div>
              <div class="flex justify-end self-end gap-3 shrink-0">
                <button @click="closeGallery()" class="text-white shrink-0 font-bold flex items-center justify-center size-8 p-2 bg-emerald-900 hover:bg-emerald-800 ease-in-out duration-300 transition-all hover:scale-105 rounded-full rotate-90">
                  <?php new Fire_SVG('icon--close'); ?>
                </button>
                <?php if(($gallery && count($gallery) > 1) || ($videos && count($videos) > 1) || (($gallery ? count($gallery) : 0) + ($videos ? count($videos) : 0)) > 1): ?>
                  <button @click="prevImage(activeAnimal)" class="text-white font-bold flex items-center justify-center size-8 p-2 bg-emerald-900 hover:bg-emerald-800 ease-in-out duration-300 transition-all hover:scale-105 rounded-full rotate-90">
                    <?php new Fire_SVG('icon--chevron-down'); ?>
                  </button>
                  <button @click="nextImage(activeAnimal)" class="text-white font-bold flex items-center justify-center size-8 p-2 bg-emerald-900 hover:bg-emerald-800 ease-in-out duration-300 transition-all hover:scale-105 rounded-full -rotate-90">
                    <?php new Fire_SVG('icon--chevron-down'); ?>
                  </button>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php $animal_index++; endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
<?php $section->end(); ?>
