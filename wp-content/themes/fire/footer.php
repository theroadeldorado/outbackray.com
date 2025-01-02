<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fire
 */

$global_scripts = function_exists('get_field') ? get_field('scripts', 'site_settings') : false;
$social_links = get_field('social_links', 'site_settings');
$phone_number = get_field('phone_number', 'site_settings');
$phone_number_stripped = preg_replace('/[^0-9]/', '', $phone_number);
?>

  <footer class="bg-dark-blue py-14 lg:py-20 bg-texture-croc text-white fire-container">
     <?php if($phone_number):?>
      <div class="text-center mb-10 lg:overflow-hidden">
        <a href="tel:<?php echo $phone_number_stripped;?>" class=" heading-4 text-white no-underline hover:text-purple"><?php echo $phone_number;?></a>
      </div>
     <?php endif;?>
    <?php
      wp_nav_menu(
        array(
          'container'       => false,
          'depth'           => 1,
          'theme_location'  => 'primary',
          'menu_class' => 'hidden lg:flex gap-[1.5rem] xl:gap-[3.5rem] mb-12 lg:mb-10 justify-center flex-wrap',
          'item_0' => 'item_class',
          'link_0' => 'font-semibold text-[1.5rem] xl:text-[2rem] block text-white no-underline hover:text-purple hover:scale-110 transition-all duration-300 ease-bounce hover:rotate-3',
          'sub_menu_class' => 'sub_menu_class',
        )
      );
    ?>

    <?php if(!empty($social_links)):?>
      <div class="flex items-center justify-center gap-x-8 gap-y-4 flex-wrap mb-10">
        <?php foreach ($social_links as $platform => $link) :
          if($link):?>
            <a class="block w-10 text-current no-underline hover:text-purple hover:scale-110 hover:-rotate-3 transition-all duration-300 ease-bounce" target="_blank" href="<?php echo $link;?>">
              <?php new Fire_SVG('icon--social-' . $platform); ?>
              <span class="sr-only"><?php echo $platform; ?></span>
            </a>
          <?php endif;
        endforeach;?>
      </div>
    <?php endif;?>

    <div class="flex flex-col md:flex-row justify-center items-center text-center gap-x-3">
      <?php echo sprintf('© %s %s', date('Y'), get_bloginfo('name')); ?>
    </div>
  </footer>
</div><!-- #page -->

<?php
  // Check if environment is local
  if (!function_exists('is_wpe')) {
    require get_template_directory() . '/templates/components/grid-debug/grid-debug.php';
  }

  wp_footer();

  fire_print_scripts_at_location($global_scripts, 'body-after');

  ?>

</body>
</html>
