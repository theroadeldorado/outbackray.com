<?php
/**
 * Template for displaying single blog posts.
 *
 * @package Fire
 */

$card_colors = ['bg-green', 'bg-orange', 'bg-tan', 'bg-navy', 'bg-purple'];
$categories = get_the_category();
$hero_color = get_field('background_colors') ?: $card_colors[get_the_ID() % count($card_colors)];
$hero_texture = get_field('background_texture') ?: 'bg-texture-croc';
$text_color = get_text_color($hero_color);
$thumbnail_id = get_post_thumbnail_id();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

  <section class="<?php echo $hero_color; ?> <?php echo $hero_texture; ?> <?php echo $text_color; ?> pt-32 lg:pt-44 pb-14 lg:pb-20">
    <div class="fire-container relative z-[1]">
      <div class="text-center col-[main] md:col-[col-2/col-11] lg:col-[col-3/col-10]">
        <?php if (!empty($categories)) : ?>
          <div class="flex justify-center gap-2 flex-wrap mb-4">
            <?php foreach ($categories as $cat) : ?>
              <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="category-pill no-underline"><?php echo esc_html($cat->name); ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <h1 class="heading-2 font-bold mb-4"><?php the_title(); ?></h1>

        <p class="base-sm opacity-80">
          <?php echo get_the_date('F j, Y'); ?>
        </p>
      </div>
    </div>
  </section>

  <?php if ($thumbnail_id) : ?>
    <div class="bg-light-tan">
      <div class="fire-container py-10 lg:py-14">
        <div class="col-[main] md:col-[col-2/col-11] lg:col-[col-2/col-11] overflow-hidden rounded-xl lg:rounded-[30px]">
          <?php echo ResponsivePics::get_picture($thumbnail_id, 'sm:800 533|f, md:1200 800|f, lg:1600 1067|f', 'full-image', false, false); ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <section class="bg-light-tan py-14 lg:py-20">
    <div class="fire-container">
      <div class="col-[main] md:col-[col-2/col-11] lg:col-[col-3/col-10]">
        <div class="wizzy base-normal">
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  </section>

  <?php
    $blog_link = get_field('blog_page_link', 'site_settings');
    if ($blog_link) :
  ?>
  <section class="bg-tan bg-texture-croc py-10 lg:py-14">
    <div class="fire-container">
      <div class="col-[main] md:col-[col-2/col-11] lg:col-[col-3/col-10] text-center">
        <a href="<?php echo esc_url($blog_link['url']); ?>" class="button-outline">Back to Blog</a>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php
    // Related posts from the same category
    if (!empty($categories)) :
      $related_query = new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post__not_in'   => array(get_the_ID()),
        'category__in'   => array($categories[0]->term_id),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
      ));

      if ($related_query->have_posts()) :
        $other_colors = array_values(array_diff($card_colors, [$hero_color]));
        $related_color = $other_colors[(get_the_ID() + 2) % count($other_colors)];
        $related_text_color = get_text_color($related_color);
        $textures = array('bg-texture-waves', 'bg-texture-croc', 'bg-texture-snake', 'bg-texture-leaves', 'bg-texture-leaves-2');
        $related_texture = $textures[get_the_ID() % count($textures)];
  ?>
    <section class="<?php echo $related_color; ?> <?php echo $related_texture; ?> <?php echo $related_text_color; ?> py-14 lg:py-20">
      <div class="fire-container relative z-[1]">
        <h2 class="text-center heading-3 font-bold mb-10 lg:mb-20">Related Posts</h2>
        <div class="flex flex-col items-center gap-6 lg:gap-10">
          <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
            <?php include get_template_directory() . '/templates/partials/blog-card.php'; ?>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      </div>
    </section>
  <?php
      endif;
    endif;
  ?>

</article>
