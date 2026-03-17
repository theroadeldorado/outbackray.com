<?php
/**
 * Category Sidebar Partial
 *
 * Renders a vertical list of category links with post counts.
 *
 * @param int|null $current_category_id  The active category ID (for highlighting).
 * @param string   $text_color           Text color class (e.g. 'text-dark-blue' or 'text-white').
 */

$current_category_id = isset($current_category_id) ? $current_category_id : null;
$text_color = isset($text_color) ? $text_color : 'text-dark-blue';
$blog_link = get_field('blog_page_link', 'site_settings');
$blog_url = $blog_link ? $blog_link['url'] : site_url('/blog/');

$categories = get_categories(array(
  'orderby' => 'name',
  'order'   => 'ASC',
  'hide_empty' => true,
));

if (empty($categories)) {
  return;
}
?>

<aside class="category-sidebar <?php echo $text_color; ?>">
  <h3 class="heading-6 font-bold mb-4 lg:mb-6">Categories</h3>
  <ul class="list-none m-0 p-0 flex flex-row flex-wrap gap-2 lg:flex-col lg:gap-0">
    <li>
      <a href="<?php echo esc_url($blog_url); ?>"
        class="category-sidebar__link no-underline block py-2 lg:py-3 base-normal font-medium transition-colors duration-200 <?php echo is_null($current_category_id) ? 'font-bold opacity-100' : 'opacity-70 hover:opacity-100'; ?>">
        All Posts
      </a>
    </li>
    <?php foreach ($categories as $category) :
      $is_active = ($current_category_id === $category->term_id);
    ?>
      <li>
        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
          class="category-sidebar__link no-underline block py-2 lg:py-3 base-normal font-medium transition-colors duration-200 <?php echo $is_active ? 'font-bold opacity-100' : 'opacity-70 hover:opacity-100'; ?>">
          <?php echo esc_html($category->name); ?>
          <span class="opacity-60">(<?php echo $category->count; ?>)</span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</aside>
