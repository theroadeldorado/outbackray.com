<?php
  $background_texture = get_sub_field('background_texture');
  $background_color = get_sub_field('background_colors');
  $text_color = get_text_color($background_color);
  $tag = get_sub_field('tag');
  $heading = get_sub_field('title');

  $section->add_classes([
    $background_color,
    $background_texture,
    $text_color,
    'py-14 lg:py-20',
  ]);
?>

<?php $section->start(); ?>

  <?php if(shortcode_exists('trustindex')): ?>
    <div class="fire-container relative z-[1]">
      <?php if($heading):?>
        <?php new Fire_Heading($tag, $heading, 'heading-3 font-bold text-center mb-5 lg:mb-10');?>
      <?php endif;?>
      <!-- trustindex no-registration=google -->
      <?php echo do_shortcode( '[trustindex data-widget-id="5038aa241d561223bb4600ba7db"]' ); ?>
    </div>
  <?php endif; ?>
<?php $section->end(); ?>
