<?php
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
  <?php if ($copy): ?>
    <div class="fire-container py-20 lg:py-28 base-lg">
      <div class="wizzy text-center col-[main] md:col-[col-2/col-11] lg:col-[col-3/col-10]">
        <?php echo $copy; ?>
      </div>
    </div>
  <?php endif; ?>
<?php $section->end(); ?>