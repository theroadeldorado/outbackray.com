<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fire
 */
if (!function_exists('get_field')) {
  wp_die('This theme requires the Advanced Custom Fields plugin to be installed and active. <a href="/wp-admin/plugins.php">Plugins Page</a>');
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php
    $bugherd_api_key = get_field('bugherd_api_key', 'site_settings');
    $bugherd_enabled = get_field('bugherd_enabled', 'site_settings');
    if ($bugherd_api_key && $bugherd_enabled) :
  ?>
    <script type="text/javascript" src="https://www.bugherd.com/sidebarv2.js?apikey=<?php print $bugherd_api_key; ?>" async="true"></script>
  <?php endif; ?>

  <?php

    $global_scripts = function_exists('get_field') ? get_field('scripts', 'site_settings') : false;


    fire_print_scripts_at_location($global_scripts, 'head-before');

    wp_head();

    fire_print_scripts_at_location($global_scripts, 'head-after');

   ?>
</head>

<body <?php body_class(); ?>>

<?php
  fire_print_scripts_at_location($global_scripts, 'body-before');
?>

<?php wp_body_open(); ?>
<div id="page" class="site">
  <a class="sr-only skip-link focus:not-sr-only" href="#primary"><?php esc_html_e( 'Skip to content', 'fire' ); ?></a>

  <header class="site-header fire-container my-4 absolute <?php echo is_admin_bar_showing() ? 'top-[var(--wp-admin--admin-bar--height)]' : 'top-0' ?> left-0 right-0 z-[1000] w-full"
    x-data="{ mobileNavOpen: false }">
    <div class="flex justify-between items-center">
      <a class="block w-[200px] lg:w-[400px]" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
        <?php echo ResponsivePics::get_picture(7, 'sm:400, lg:800', '', false, false); ?>
        <span class="sr-only"><?php bloginfo( 'name' ); ?></span>
      </a>

      <!-- Hamburger Button -->
      <button
        class="lg:hidden z-50 relative w-10 h-10 text-white"
        @click="mobileNavOpen = !mobileNavOpen"
        :aria-expanded="mobileNavOpen"
        aria-label="Toggle Menu">
        <span
          class="absolute block w-8 h-0.5 bg-current transform transition duration-500 ease-in-out -translate-x-1/2 left-1/2 top-1/2"
          :class="mobileNavOpen ? 'rotate-45 translate-y-0' : '-translate-y-2'"></span>
        <span
          class="absolute block w-8 h-0.5 bg-current transform transition duration-500 ease-in-out -translate-x-1/2 left-1/2 top-1/2"
          :class="mobileNavOpen && 'opacity-0'"></span>
        <span
          class="absolute block w-8 h-0.5 bg-current transform transition duration-500 ease-in-out -translate-x-1/2 left-1/2 top-1/2"
          :class="mobileNavOpen ? '-rotate-45 -translate-y-0' : 'translate-y-2'"></span>
      </button>

      <!-- Desktop Navigation -->
      <nav id="site-navigation" class="main-navigation hidden lg:block">
        <?php
          wp_nav_menu([
            'container' => false,
            'depth' => 1,
            'theme_location' => 'primary',
            'menu_class' => 'flex gap-[3.5rem]',
            'item_0' => 'item_class',
            'link_0' => 'font-semibold text-[2rem] text-white no-underline',
            'submenu_0' => 'submenu',
          ]);
        ?>
      </nav>

      <!-- Mobile Navigation Overlay -->
      <div
        class="fixed inset-0 bg-black bg-opacity-95 z-40 lg:hidden transition-opacity duration-500"
        :class="{'opacity-100 pointer-events-auto': mobileNavOpen, 'opacity-0 pointer-events-none': !mobileNavOpen}"
        @click.self="mobileNavOpen = false">
        <nav class="h-full flex items-center justify-center">
          <?php
            wp_nav_menu([
              'container' => false,
              'depth' => 1,
              'theme_location' => 'primary',
              'menu_class' => 'flex flex-col items-center gap-8',
              'item_0' => 'item_class',
              'link_0' => 'font-semibold text-[2rem] text-white no-underline hover:text-gray-300 transition-colors duration-300',
              'submenu_0' => 'submenu',
            ]);
          ?>
        </nav>
      </div>
    </div>
  </header>

