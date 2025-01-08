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
    <div class="fire-container">
      <?php if($heading):?>
        <?php new Fire_Heading($tag, $heading, 'heading-3 font-bold text-center mb-5 lg:mb-10');?>
      <?php endif;?>
      <?php echo do_shortcode( '[trustindex no-registration=google]' ); ?>
    </div>
  <?php endif; ?>
<?php $section->end(); ?>
