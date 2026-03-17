<?php
  $background_texture = get_sub_field('background_texture');
  $background_color = get_sub_field('background_colors');
  $heading = get_sub_field('heading');
  $subheading = get_sub_field('subheading');
  $posts_per_page = get_sub_field('posts_per_page') ?: 9;
  $text_color = get_text_color($background_color);
  $is_first_section = ($section->count === 1);

  $paged = get_query_var('paged') ? get_query_var('paged') : 1;

  $blog_query = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => $posts_per_page,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
  ));

  $section->add_classes([
    $background_color,
    $background_texture,
    $text_color,
    $is_first_section ? 'pt-32 lg:pt-44 pb-14 lg:pb-20' : 'py-14 lg:py-20',
    'relative',
  ]);
?>

<?php $section->start(); ?>
  <div class="fire-container relative z-[2]">
    <div class="text-center mb-14 lg:mb-20">
      <span class="category-pill mb-4 inline-block">Blog</span>
      <?php if ($heading) : ?>
        <h1 class="heading-2 font-bold"><?php echo esc_html($heading); ?></h1>
      <?php endif; ?>
      <?php if ($subheading) : ?>
        <p class="base-normal mt-4 opacity-80"><?php echo esc_html($subheading); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="bg-light-tan text-dark-blue py-14 lg:py-20">
  <div class="fire-container relative z-[1]">
    <div class="flex flex-col lg:flex-row gap-10 lg:gap-20">
      <div class="lg:w-3/4">
        <?php if ($blog_query->have_posts()) : ?>
          <div class="flex flex-col gap-6 lg:gap-10">
            <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
              <?php
                $blog_feed_card = true;
                include get_template_directory() . '/templates/partials/blog-card.php';
              ?>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>

          <?php if ($blog_query->max_num_pages > 1) : ?>
            <nav class="blog-pagination mt-10 lg:mt-20 flex justify-center items-center gap-2 flex-wrap" aria-label="Blog pagination">
              <?php
                echo paginate_links(array(
                  'total'     => $blog_query->max_num_pages,
                  'current'   => $paged,
                  'prev_text' => '&laquo;',
                  'next_text' => '&raquo;',
                  'type'      => 'list',
                ));
              ?>
            </nav>
          <?php endif; ?>
        <?php else : ?>
          <p class="base-normal text-center opacity-70">No posts found.</p>
        <?php endif; ?>
      </div>

      <div class="hidden lg:block lg:w-1/4">
        <?php
          $current_category_id = null;
          $text_color = 'text-dark-blue';
          include get_template_directory() . '/templates/partials/category-sidebar.php';
        ?>
      </div>
    </div>
  </div>
<?php $section->end(); ?>
