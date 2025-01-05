<?php
  $image = get_sub_field('image');
  $full_height_image = get_sub_field('full_height_image');
  $image_left = get_sub_field('image_position');
  $copy = get_sub_field('copy');

  $background_texture = get_sub_field('background_texture');
  $background_color = get_sub_field('background_colors');
  $text_color = get_text_color($background_color);
  $secondary_color = get_secondary_color($background_color);
  $shadow_color = get_shadow_color($background_color);
  $rotate_image = rand(0, 1) ? rand(-5, -2) : rand(2, 5);

  $points = [];

  if ($image_left) {
    // Right side image
    $points[] = '0% 0%'; // Top left
    $points[] = 'calc(100% - (var(--fire-content-max-width) / 12 + var(--fire-content-gap-x)/2)) 0%'; // Top right

    // Random number of points between 5-7
    $num_points = rand(5, 7);
    $prev_y = 0;

    for ($i = 0; $i < $num_points; $i++) {
      $y_pos = rand(($prev_y + 5) * 10, min(95, $prev_y + 25) * 10) / 10;
      $x_pos = rand(88, 96);
      $prev_y = $y_pos;
      $points[] = "{$x_pos}% {$y_pos}%";
    }

    $points[] = 'calc(100% - (var(--fire-content-max-width) / 12 + var(--fire-content-gap-x)/2)) 100%'; // Bottom right
    $points[] = '0% 100%'; // Bottom left

  } else {
    $points[] = 'calc(var(--fire-content-max-width) / 12 + var(--fire-content-gap-x)/2) 0%'; // Top left
    // Random number of points between 5-7
    $num_points = rand(5, 7);
    $prev_y = 0;

    for ($i = 0; $i < $num_points; $i++) {
      $y_pos = rand(($prev_y + 5) * 10, min(95, $prev_y + 25) * 10) / 10;
      $x_pos = rand(4, 12);
      $prev_y = $y_pos;
      $points[] = "{$x_pos}% {$y_pos}%";
    }
    $points[] = 'calc(var(--fire-content-max-width) / 12 + var(--fire-content-gap-x)/2) 100%'; // Bottom left
    $points[] = '100% 100%'; // Bottom right
    $points[] = '100% 0%'; // Top right
  }

  $clip_paths = [
    'polygon(0 55%, 8% 21%, 3% 4%, 31% 0, 48% 5%, 80% 2%, 100% 8%, 100% 44%, 95% 66%, 100% 93%, 48% 99%, 4% 94%)',
    'polygon(6% 56%, 0 21%, 8% 0, 31% 5%, 57% 0, 76% 11%, 99% 1%, 96% 43%, 100% 66%, 89% 99%, 48% 99%, 8% 87%)',
    'polygon(1% 74%, 0 38%, 4% 8%, 31% 1%, 55% 8%, 75% 4%, 99% 10%, 100% 39%, 100% 94%, 59% 96%, 38% 83%, 5% 98%)',
    'polygon(10% 69%, 1% 37%, 18% 21%, 6% 0, 54% 4%, 67% 15%, 95% 1%, 100% 81%, 88% 100%, 55% 88%, 20% 97%, 0 90%)'
  ];
  $random_rotate = rand(-7, 7);
  $random_top = rand(-10, 85);
  $random_width = 'calc(var(--fire-content-max-width) / 12 * ' . number_format(rand(100, 250) / 100, 2) . ')';
  $random_height = rand(30, 80);
  $translate_x = $image_left ? '-50%' : '50%';
  $shape_style = 'clip-path: ' . $clip_paths[array_rand($clip_paths)] . '; transform: rotate(' . $random_rotate . 'deg) translate(' . $translate_x . ', -50%); width: ' . $random_width . '; top: ' . $random_top . '%; height: ' . $random_height . '%; background-color: ' . $secondary_color . ';';

  $section->add_classes([
    $background_color,
    $background_texture,
    $text_color,
  ]);
?>

<?php $section->start(); ?>
<style type="text/css">
  @media (min-width: 768px) {
    .clip-path-<?php echo $section->count; ?> {
      clip-path: polygon(<?php echo implode(', ', $points); ?>);
    }
  }
</style>

  <div class="fire-container relative gap-y-10 overflow-hidden md:py-0 <?php echo $full_height_image ? 'pb-20 ':'py-20 ';?>">
    <?php if ($copy) : ?>
      <div class="md:row-start-1 relative z-[2] content-center wizzy py-0 md:py-20 lg:py-40 font-medium
        <?php echo $image_left ? 'col-[main] md:col-[col-8/col-12]':'col-[main] md:col-[col-1/col-5]';?>
      ">
        <?php echo $copy; ?>
      </div>
    <?php endif; ?>


    <?php if ($image) : ?>
      <div class="row-start-1 relative
        <?php echo $full_height_image && $image_left ? 'col-[full-width] md:col-[full-width/col-7]':'';?>
        <?php echo $full_height_image && !$image_left ? 'col-[full-width] md:col-[col-6/full-width]':'';?>
        <?php echo !$full_height_image && $image_left ? 'col-[main] md:col-[col-2/col-6]':'';?>
        <?php echo !$full_height_image && !$image_left ? 'col-[main] md:col-[col-7/col-11]':'';?>
        <?php echo $full_height_image ? 'clip-path-'.$section->count:'';?>
      ">
        <?php if ($full_height_image) : ?>
          <div class="absolute top-0 w-full bottom-0 <?php echo $image_left ? 'left-0':'right-0';?>">
            <div aria-hidden class="absolute hidden md:block -translate-y-1/2 <?php echo $image_left ? 'left-0':'right-0';?>" style="<?php echo $shape_style; ?>"></div>

            <?php echo ResponsivePics::get_picture($image, 'sm:600 400|f, md:500 500|f, lg:600 600|f, xl:950 950|f, xxl:1200 1200|f', 'lazyload-effect full-image scale-[1.01]', false, false); ?>
          </div>
        <?php else : ?>
          <div class="relative my-0 md:my-20 lg:my-40 overflow-clip rounded-[30px] drop-shadow-[-12px_-11px_0px_var(--shadow-color)] lg:drop-shadow-[-24px_-23px_0px_var(--shadow-color)]" style="--shadow-color: <?php echo $shadow_color; ?>; transform: rotate(<?php echo $rotate_image; ?>deg);">
            <?php echo ResponsivePics::get_picture($image, 'sm:400 400|f, lg:500 500|f, xl:600 600|f', 'lazyload-effect full-image', false, false); ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
<?php $section->end(); ?>
