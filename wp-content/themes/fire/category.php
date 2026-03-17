<?php
/**
 * The template for displaying category archives.
 *
 * @package Fire
 */

get_header();

$category = get_queried_object();
$card_colors = ['bg-green', 'bg-orange', 'bg-tan', 'bg-navy', 'bg-purple'];
$hero_color = $card_colors[$category->term_id % count($card_colors)];
$text_color = get_text_color($hero_color);


$paged = get_query_var('paged') ? get_query_var('paged') : 1;
?>

  <main id="primary" class="site-main">

    <section class="<?php echo $hero_color; ?> bg-texture-croc <?php echo $text_color; ?> pt-32 lg:pt-44 pb-14 lg:pb-20">
      <div class="fire-container relative z-[1]">
        <div class="text-center">
          <span class="category-pill mb-4 inline-block">Category</span>
          <h1 class="heading-2 font-bold"><?php echo esc_html($category->name); ?></h1>
          <?php if ($category->description) : ?>
            <p class="base-normal mt-4 opacity-80"><?php echo esc_html($category->description); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section class="bg-light-tan text-dark-blue py-14 lg:py-20">
      <div class="fire-container relative z-[1]">
        <div class="flex flex-col lg:flex-row gap-10 lg:gap-20">
          <div class="lg:w-3/4">
            <?php if (have_posts()) : ?>
              <div class="flex flex-col gap-6 lg:gap-10">
                <?php $blog_feed_card = true; while (have_posts()) : the_post(); ?>
                  <?php include get_template_directory() . '/templates/partials/blog-card.php'; ?>
                <?php endwhile; ?>
              </div>

              <?php if ($wp_query->max_num_pages > 1) : ?>
                <nav class="blog-pagination mt-10 lg:mt-20 flex justify-center items-center gap-2 flex-wrap" aria-label="Category pagination">
                  <?php
                    echo paginate_links(array(
                      'total'     => $wp_query->max_num_pages,
                      'current'   => $paged,
                      'prev_text' => '&laquo;',
                      'next_text' => '&raquo;',
                      'type'      => 'list',
                    ));
                  ?>
                </nav>
              <?php endif; ?>
            <?php else : ?>
              <p class="base-normal text-center text-dark-blue opacity-70">No posts found in this category.</p>
            <?php endif; ?>
          </div>

          <div class="hidden lg:block lg:w-1/4">
            <?php
              $current_category_id = $category->term_id;
              $text_color = 'text-dark-blue';
              include get_template_directory() . '/templates/partials/category-sidebar.php';
            ?>
          </div>
        </div>
      </div>
    </section>

  </main>

<?php
get_footer();
