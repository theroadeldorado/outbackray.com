<?php
  $images = get_sub_field('image');
  $full_screen = get_sub_field('full_screen');
  $copy = get_sub_field('copy');
  $show_ray = get_sub_field('show_ray');
  $video = get_sub_field('video');
  $add_black_separation_bar = get_sub_field('add_black_separation_bar');

  $crops = $full_screen ? 'sm:600 600|f, md:900 506|f, lg:1200 675|f, xl:1920 1080|f, xxl:2560 1440|f' : 'sm:900 506|f, lg:1200 675|f, xl:1920 1080|f, xxl:2560 1440|f';

  $section->add_classes([
    ''
  ]);
?>

<?php $section->start(); ?>

  <div class="fire-container relative overflow-hidden <?php echo $full_screen ? 'min-h-screen' : 'min-h-[350px] lg:min-h-[550px] xl:min-h-[650px]'; ?>" x-data="heroSlideshow">
    <div class="full-width absolute inset-0">
      <div class="absolute w-full h-full bg-gradient-to-b from-black/70 via-black/0 to-black/0 z-[1] inset-0" aria-hidden></div>
      <div class="absolute w-full h-full bg-gradient-to-tr from-black/70 via-black/0 to-black/0 z-[1] inset-0" aria-hidden></div>
      <?php if($video): ?>
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover" <?php echo ($images && is_array($images)) ? 'poster="' . wp_get_attachment_image_url($images[0]['id'], 'full') . '"' : ''; ?>>
          <source src="<?php echo $video['url']; ?>" type="<?php echo $video['mime_type']; ?>">
        </video>
      <?php elseif($images && is_array($images)):?>
        <?php foreach($images as $index => $image): ?>
          <div
            x-cloak
            :class="currentSlide !== <?php echo $index; ?> ? 'opacity-0' : 'opacity-100'"
            class="absolute inset-0 pointer-events-none transition-opacity duration-500 ease-in-out"
            data-slide="<?php echo $index; ?>">
            <?php echo ResponsivePics::get_picture($image['id'], $crops, 'lazyload-effect full-image', false, false); ?>
          </div>
        <?php endforeach; ?>
      <?php elseif($images): ?>
        <?php echo ResponsivePics::get_picture($images['id'], $crops, 'lazyload-effect full-image', false, false); ?>
      <?php endif; ?>
    </div>

    <?php if($copy):?>
      <div class="col-[main] row-start-1 relative flex z-[2] h-full items-end <?php echo $show_ray ? 'md:col-[col-3/col-12] xl:col-[col-4/col-12]' : '';?> ">
        <div class="wizzy text-white text-shadow-heros <?php echo $full_screen ? 'pt-48 pb-12' : 'pb-12 w-full pt-32 lg:pt-48 xl:pt-52'; ?>">
          <?php echo $copy; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if($show_ray):?>
      <div class="hidden lg:flex -translate-x-10 col-[main] md:col-[full-width/col-2] xl:col-[full-width/col-3] row-start-1 h-full relative items-end z-[2] <?php echo $full_screen ? 'pt-24 ' : 'mt-20 items-start'; ?>">
        <?php echo ResponsivePics::get_picture(72, 'sm:550', 'lazyload-effect', false, false); ?>
      </div>
    <?php endif; ?>
  </div>

  <?php if($add_black_separation_bar):?>
    <div class="w-full h-2 lg:h-4 bg-gray-900" aria-hidden></div>
  <?php endif; ?>

<?php $section->end(); ?>
