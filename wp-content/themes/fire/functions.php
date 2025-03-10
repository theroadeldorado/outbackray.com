<?php

/**
 * Fire functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Fire
 */


if (!defined('FIRE_VERSION')) {
  // Replace the version number of the theme on each release.
  define('FIRE_VERSION', '1.0.0');
}

if (!function_exists('fire_setup')) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which
   * runs before the init hook. The init hook is too late for some features, such
   * as indicating support for post thumbnails.
   */
  function fire_setup() {
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on Fire, use a find and replace
     * to change 'fire' to the name of your theme in all the template files.
     */
    load_theme_textdomain('fire', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support(
      'html5',
      array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
      )
    );

    // Set up the WordPress core custom background feature.
    add_theme_support(
      'custom-background',
      apply_filters(
        'fire_custom_background_args',
        array(
          'default-color' => 'ffffff',
          'default-image' => '',
        )
      )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
      'custom-logo',
      array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
      )
    );
  }
endif;
add_action('after_setup_theme', 'fire_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function fire_content_width() {
  // This variable is intended to be overruled from themes.
  // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
  // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
  $GLOBALS['content_width'] = apply_filters('fire_content_width', 640);
}
add_action('after_setup_theme', 'fire_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function fire_widgets_init() {
  register_sidebar(
    array(
      'name'          => esc_html__('Sidebar', 'fire'),
      'id'            => 'sidebar-1',
      'description'   => esc_html__('Add widgets here.', 'fire'),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    )
  );
}
add_action('widgets_init', 'fire_widgets_init');

/**
 * Pagination Fix for custom post types
 */
add_action('init', function () {
  add_rewrite_rule('(.?.+?)/page/?([0-9]{1,})/?$', 'index.php?pagename=$matches[1]&paged=$matches[2]', 'top');
});

/**
 * Enqueue scripts and styles.
 */
function fire_scripts() {
  $css = get_template_directory() . '/dist/styles.css';
  $css_updated_on = date("YmdGis", filemtime($css));

  $js = get_template_directory() . '/dist/scripts.js';
  $js_updated_on = date("YmdGis", filemtime($js));

  wp_enqueue_style('default', get_template_directory_uri() . '/dist/styles.css', array(), $css_updated_on);
  wp_enqueue_script('fire-js', get_template_directory_uri() . '/dist/scripts.js', array(), $js_updated_on, true);

  // Localize the script with ajax_object
  wp_localize_script(
    'fire-js',
    'ajax_object',
    array(
      'ajax_url' => admin_url('admin-ajax.php')
    )
  );
}
add_action('wp_enqueue_scripts', 'fire_scripts');

function admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri() . '/dist/admin.css', array(), FIRE_VERSION);
}

add_action('admin_enqueue_scripts', 'admin_style');

//include all php files in inc folder
array_map(function ($filename) {
  include $filename;
}, array_merge(glob(get_template_directory() . "/inc/*.php"), glob(get_template_directory() . "/inc/**/*.php")));


// Removes Theme File Editor
add_action('init', 'disable_theme_file_editor');
function disable_theme_file_editor() {
  define('DISALLOW_FILE_EDIT', true);
}

// disable gravity forms styles by default
add_filter('gform_disable_css', '__return_true');

// Force the Yoast SEO metabox to be low priority
add_filter('wpseo_metabox_prio', function () {
  return 'low';
});

// hide customize link
function remove_customize_menu() {
  global $submenu;

  // WordPress 4.5 or later
  remove_submenu_page('themes.php', 'customize.php');
  unset($submenu['themes.php'][6]); // This removes 'Customize' under 'Appearance'
}
add_action('admin_menu', 'remove_customize_menu', 999);
function remove_customize_link_from_admin_bar($wp_admin_bar) {
  $wp_admin_bar->remove_node('customize');
}
add_action('admin_bar_menu', 'remove_customize_link_from_admin_bar', 999);


// Adds Site Settings scripts to header or footer
function fire_print_scripts_at_location($scripts, $location)
{
  if (empty($scripts)) {
    return;
  }

  $scripts = array_filter($scripts, function ($script) use ($location) {
    return $script['location'] === $location;
  });

  foreach ($scripts as $script) {
    print $script['script'];
  }
}

// Remove delete button for logo.png in media library
add_action('admin_footer', function() {
  ?>
  <script>
    jQuery(document).ready(function($) {
      // Check if we're in the media library
      if (typeof wp !== 'undefined' && wp.media) {
        var AttachmentDetails = wp.media.view.Attachment.Details;
        var AttachmentDetailsTwoColumn = wp.media.view.Attachment.Details.TwoColumn;

        if (AttachmentDetailsTwoColumn) {
          wp.media.view.Attachment.Details.TwoColumn = AttachmentDetailsTwoColumn.extend({
            render: function() {
              // Call the parent render method
              AttachmentDetailsTwoColumn.prototype.render.apply(this, arguments);

              // Get the filename
              var filename = this.model.get('filename');

              // If this is logo.png, remove the delete button
              if (filename === 'logo.png' || filename === 'ray.png') {
                this.$el.find('.delete-attachment').remove();
              }

              return this;
            }
          });
        }
      }
    });

    jQuery(document).ready(function($) {
      // Function to update the texture labels' background color within a specific layout
      function updateTextureLabelBackground($layout, colorClass) {
        // Select texture labels only within this layout and update their background
        $layout.find('.acf-field[data-name="background_texture"] .acf-button-group label span').each(function() {
          // Remove only color classes, not texture classes
          $(this).removeClass(function(index, className) {
            return (className.match(/(^|\s)bg-(green|light-green|orange|light-orange|tan|light-tan|navy|light-navy|purple|light-purple|none)\b/g) || []).join(' ');
          });
          // Add the new background color class
          $(this).addClass(colorClass);
        });
      }

      // Listen for changes on background color radio buttons within each layout
      $('.layout').each(function() {
        const $layout = $(this);

        // Handle radio button changes
        $layout.find('.acf-field[data-name="background_colors"] .acf-button-group input[type="radio"]').on('change', function() {
          const selectedColor = $(this).val();
          updateTextureLabelBackground($layout, selectedColor);
        });

        // Set initial colors
        const initialColor = $layout.find('.acf-field[data-name="background_colors"] .acf-button-group input[type="radio"]:checked').val();
        if (initialColor) {
          updateTextureLabelBackground($layout, initialColor);
        }
      });

      // Handle newly added layouts
      acf.addAction('append_field/type=flexible_content', function($el) {
        const $newLayout = $el.find('.layout').last();

        // Set up event handlers for the new layout
        $newLayout.find('.acf-field[data-name="background_colors"] .acf-button-group input[type="radio"]').on('change', function() {
          const selectedColor = $(this).val();
          updateTextureLabelBackground($newLayout, selectedColor);
        });

        // Set initial colors for new layout
        const initialColor = $newLayout.find('.acf-field[data-name="background_colors"] .acf-button-group input[type="radio"]:checked').val();
        if (initialColor) {
          updateTextureLabelBackground($newLayout, initialColor);
        }
      });
    });
  </script>
  <?php
});


function get_text_color($bg_color) {
  if (strpos($bg_color, 'light') !== false ) {
    return 'text-dark-blue';
  }
  return 'text-white';
}

function get_shadow_color($bg_color) {
  // Color pairs mapping
  $color_pairs = [
    'bg-green' => '#1e511f',
    'bg-light-green' => '#1e511f',
    'bg-orange' => '#b06001',
    'bg-light-orange' => '#b06001',
    'bg-tan' => '#6d6856',
    'bg-light-tan' => '#6d6856',
    'bg-navy' => '#1d2935',
    'bg-light-navy' => '#1d2935',
    'bg-purple' => '#532764',
    'bg-light-purple' => '#532764',
  ];

  return isset($color_pairs[$bg_color]) ? $color_pairs[$bg_color] : '';
}

function get_secondary_color($bg_color) {
  $color_pairs = [
    'bg-green' => '#4CAF50',
    'bg-orange' => '#FF9800',
    'bg-navy' => '#2C3E50',
    'bg-purple' => '#8E44AD',
  ];

  // Remove the original color from the array
  unset($color_pairs[$bg_color]);

  // Get random color from remaining colors
  $random_key = array_rand($color_pairs);
  return $color_pairs[$random_key];
}


function get_youtube_id($url) {
    $pattern =
        '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme
        (?:www\.)?      # Optional www
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )            # End path alternatives.
        )              # End host alternatives.
        ([\w-]{10,12}) # Allow 10-12 for 11 char youtube id.
        $%x';
    $result = preg_match($pattern, $url, $matches);
    return ($result) ? $matches[1] : false;
}

add_action('init', function() {
    // Unregister the post type before admin menu loads
    unregister_post_type('post');
}, 0); // Priority 0 to run early

add_action('admin_menu', function() {
    // Only try to remove menu if post type still exists
    if (post_type_exists('post')) {
        remove_menu_page('edit.php');
    }
});


add_action('admin_init', function() {
    // Get the editor role
    $editor = get_role('editor');

    // Check if editor role exists before modifying
    if (!empty($editor)) {
        // Add Gravity Forms capabilities to editor role
        $editor->add_cap('gform_full_access');
    }
});


// Allow editors to have access to the menu items
add_action('admin_init', function() {
    $editor = get_role('editor');

    // Check if editor role exists before modifying
    if (!empty($editor)) {
        // Add menu editing capability
        $editor->add_cap('edit_nav_menus');
        $editor->add_cap('edit_theme_options'); // Need this for menus to work properly

        // Remove other appearance menu capabilities
        $editor->remove_cap('customize');
        $editor->remove_cap('switch_themes');

        // Add this to ensure editors can access Menus
        add_filter('map_meta_cap', function($caps, $cap, $user_id, $args) {
            if ($cap === 'edit_theme_options' && in_array('editor', get_userdata($user_id)->roles)) {
                // Only allow for menus
                if (isset($_GET['menu']) || strpos($_SERVER['REQUEST_URI'], 'nav-menus.php') !== false) {
                    return ['edit_theme_options'];
                }
            }
            return $caps;
        }, 10, 4);
    }
});

// Hide specific appearance submenu items for editors
add_action('admin_menu', function() {
    if (current_user_can('editor')) {
        // Remove unwanted submenu items from Appearance menu for editors
        remove_submenu_page('themes.php', 'themes.php'); // Themes
        remove_submenu_page('themes.php', 'widgets.php'); // Widgets
        remove_submenu_page('themes.php', 'custom-background'); // Background

        // Don't remove customize.php as it's needed for menu editing
    }
}, 999); // High priority to ensure it runs after the menu is built

// Block direct access to customize.php except for menu editing
add_action('admin_init', function() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        $current_url = $_SERVER['REQUEST_URI'];

        // Check if we're on the customize page
        if (strpos($current_url, 'customize.php') !== false) {
            // Only allow if it's for menu editing
            if (strpos($current_url, 'autofocus%5Bpanel%5D=nav_menus') === false &&
                strpos($current_url, 'return=%2Fwp-admin%2Fnav-menus.php') === false) {
                wp_redirect(admin_url());
                exit;
            }
        }
    }
});
