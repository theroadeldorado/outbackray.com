<?php
/**
 * Blog Card Partial
 *
 * Renders a horizontal blog post row: 1/3 image | 2/3 text.
 * Uses each post's own ACF color and texture fields.
 * Must be called inside a WP loop (after the_post()).
 *
 * Expects from parent scope:
 * @param bool $blog_feed_card  (optional) If true, removes max-width constraint.
 */

$_card_post_id = get_the_ID();
$thumbnail_id = get_post_thumbnail_id($_card_post_id);
$categories = get_the_category($_card_post_id);
$category_name = !empty($categories) ? $categories[0]->name : '';
$card_color = get_field('background_colors', $_card_post_id) ?: 'bg-green';
$card_texture = get_field('background_texture', $_card_post_id) ?: 'bg-texture-croc';
$card_text_color = get_text_color($card_color);
$_max_width = !empty($blog_feed_card) ? '' : 'max-w-[700px]';
?>

<a href="<?php echo esc_url(get_the_permalink()); ?>"
  class="blog-card w-full <?php echo $_max_width; ?> no-underline <?php echo $card_texture; ?> overflow-hidden flex flex-col md:flex-row rounded-xl lg:rounded-[30px] hover:scale-[1.02] transition-all duration-300 ease-bounce <?php echo $card_color; ?> <?php echo empty($blog_feed_card) ? 'ring-2 ring-white/50' : ''; ?>">
  <div class="blog-card__image md:w-1/3 shrink-0 relative z-[1] pointer-events-none overflow-hidden rounded-b-xl md:rounded-b-none md:rounded-r-xl lg:rounded-b-none lg:rounded-r-[30px]">
    <?php if ($thumbnail_id) : ?>
      <?php echo ResponsivePics::get_picture($thumbnail_id, 'sm:600 400|f', 'lazyload-effect full-image', false, false); ?>
    <?php else : ?>
      <div class="w-full h-full flex items-center justify-center <?php echo $card_color; ?> opacity-50">
        <?php echo ResponsivePics::get_picture(7, 'sm:200', 'opacity-30', false, false); ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="md:w-2/3 p-4 lg:p-6 flex flex-col justify-center relative z-[1] pointer-events-none <?php echo $card_text_color; ?>">
    <?php if ($category_name) : ?>
      <span class="category-pill self-start"><?php echo esc_html($category_name); ?></span>
    <?php endif; ?>
    <h3 class="heading-6 font-bold mt-2 mb-1.5"><?php echo esc_html(get_the_title()); ?></h3>
    <p class="base-normal"><?php echo get_the_date('F j, Y'); ?></p>
  </div>
</a>
