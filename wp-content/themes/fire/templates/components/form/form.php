<?php
  $form = get_sub_field('form');
  $copy = get_sub_field('copy');
  $image = get_sub_field('image');

  $background_texture = get_sub_field('background_texture');
  $background_color = get_sub_field('background_colors');
  $text_color = get_text_color($background_color);

  $section->add_classes([
    $background_color,
    $background_texture,
    $text_color,
    'py-20'
  ]);
?>

<?php $section->start(); ?>
  <div class="fire-container relative z-[1]">
    <?php if ($copy) : ?>
      <div class="wizzy col-[main] md:col-[col-2/col-5] mb-10">
        <?php echo $copy; ?>
        <?php if ($image) : ?>
          <div class="mt-4 lg:block hidden overflow-hidden rounded-xl lg:rounded-[30px]">
            <?php echo ResponsivePics::get_picture($image, 'sm:600, md:500, lg:800', 'lazyload-effect full-image', false, false); ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <?php if ($form) : ?>
      <div class="col-[main] md:col-[col-7/col-11]">
        <?php echo do_shortcode('[gravityform id="'.$form.'" title="false" description="false" ajax="true"]'); ?>
      </div>
    <?php endif; ?>
  </div>
<?php $section->end(); ?>
