<?php
  $background_texture = get_sub_field('background_texture');
  $background_color = get_sub_field('background_colors');
  $heading = get_sub_field('heading');
  $text_color = get_text_color($background_color);
  $blog_link = get_field('blog_page_link', 'site_settings');

  $latest_posts = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
  ));

  $section->add_classes([
    $background_color,
    $background_texture,
    $text_color,
    'py-14 lg:py-20',
  ]);
?>

<?php $section->start(); ?>
  <div class="fire-container relative z-[1]">
    <?php if ($heading) : ?>
      <h2 class="text-center heading-3 font-bold mb-10 lg:mb-20"><?php echo esc_html($heading); ?></h2>
    <?php endif; ?>

    <?php if ($latest_posts->have_posts()) : ?>
      <div class="flex flex-col items-center gap-6 lg:gap-10">
        <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
          <?php include get_template_directory() . '/templates/partials/blog-card.php'; ?>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php endif; ?>

    <?php if ($blog_link) : ?>
      <div class="text-center mt-10 lg:mt-20 mb-10">
        <a href="<?php echo esc_url($blog_link['url']); ?>" target="<?php echo esc_attr($blog_link['target']); ?>" class="button-outline"><?php echo esc_html($blog_link['title']); ?></a>
      </div>
    <?php endif; ?>
  </div>
<?php $section->end(); ?>
