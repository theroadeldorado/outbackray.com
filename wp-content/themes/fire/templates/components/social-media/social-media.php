<?php
  $background_texture = get_sub_field('background_texture');
  $background_color = get_sub_field('background_colors');
  $text_color = get_text_color($background_color);
  $social_headline = get_field('social_headline', 'site_settings');
  $social_links = get_field('social_links', 'site_settings');

  $section->add_classes([
    $background_color,
    $background_texture,
    $text_color,
  ]);
?>

<?php $section->start(); ?>
  <?php if($social_links):?>
    <div class="fire-container relative gap-y-10 overflow-hidden py-14 lg:py-20">
      <?php if ($social_headline) : ?>
        <div class="col-[main] lg:col-[col-2/col-6] content-center">
          <h2 class="text-center lg:text-left heading-3 text-balance font-bold"><?php echo $social_headline; ?></h2>
        </div>
      <?php endif; ?>

      <?php if (!empty($social_links)) : ?>
        <div class="col-[main] lg:col-[col-7/col-11] content-center flex flex-wrap items-center justify-center gap-x-10 gap-y-6">
          <?php foreach ($social_links as $platform => $link) :
            if($link):?>
                <a class="flex items-center justify-center size-12 lg:size-20 text-white no-underline [&>svg]:block" target="_blank" href="<?php echo $link;?>">
                  <?php new Fire_SVG('icon--social-' . $platform); ?>
                  <span class="sr-only"><?php echo $platform; ?></span>
                </a>
              <?php endif;
            endforeach;?>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
<?php $section->end(); ?>
