<?php
  $background_texture = get_sub_field('background_texture');
  $background_color = get_sub_field('background_colors');
  $heading = get_sub_field('heading');
  $link_to_all_animals = get_sub_field('link_to_all_animals');
  $featured_animals = get_sub_field('featured_animals');
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
  <div class="fire-container relative z-[1]" x-data="animalGallery">
    <?php if ($heading) : ?>
      <h2 class="text-center heading-3 font-bold mb-10 lg:mb-20"><?php echo $heading; ?></h2>
    <?php endif; ?>

    <?php if ($featured_animals) : ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 lg:gap-20">
        <?php foreach ($featured_animals as $animal) :
          $animal_id = $animal;
          $animal_name = get_the_title($animal_id);
          $birth_date = get_field('birth_date', $animal_id);
          $age = $birth_date ? date_diff(date_create($birth_date), date_create('now'))->y : '';
          $species = get_field('species', $animal_id);
          $gallery = get_field('gallery', $animal_id);
          $image = $gallery[0];
          $rotate_image = rand(0, 1) ? rand(-4, -2) : rand(2, 4);
          ?>

          <div
            class="animal-card w-full bg-texture-croc overflow-hidden flex flex-col rounded-xl lg:rounded-[30px] <?php echo $card_colors[$color_index % count($card_colors)]; ?>">
            <div class="aspect-[3/2] w-full overflow-hidden rounded-b-xl shrink-0 lg:rounded-b-[30px]">
              <?php echo ResponsivePics::get_picture($image, 'sm:600 600|f', 'lazyload-effect full-image', true, true); ?>
            </div>
            <div class="p-4 grow w-full text-center">
              <h3 class="text-[1.25rem]md:text-[1.5rem] font-bold"><?php echo $animal_name; ?></h3>
              <p class="text-[0.875rem] md:text-[1rem] font-medium"><?php echo $species; ?></p>
            </div>
          </div>
        <?php $index++; $color_index++; endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($link_to_all_animals) : ?>
      <div class="text-center mt-10 lg:mt-20 mb-10">
        <a href="<?php echo $link_to_all_animals['url']; ?>" target="<?php echo $link_to_all_animals['target']; ?>" class="button"><?php echo $link_to_all_animals['title']; ?></a>
      </div>
    <?php endif; ?>
  </div>
<?php $section->end(); ?>
