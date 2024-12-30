<?php
  $image = get_sub_field('image');
  $full_screen = get_sub_field('full_screen');
  $copy = get_sub_field('copy');
  $show_ray = get_sub_field('show_ray');


  $section->add_classes([
    ''
  ]);
?>

<?php $section->start(); ?>

  <div class="fire-container relative overflow-hidden <?php echo $full_screen ? 'min-h-screen' : ''; ?>">
    <div class="full-width relative row-start-1">
      <div class="absolute w-full h-full bg-gradient-to-b from-black/50 to-black/45 inset-0" aria-hidden></div>
      <?php if($image):?>
        <?php echo ResponsivePics::get_picture($image['id'], 'sm:600 800|f, md:900 506|f, lg:1200 675|f, xl:1920 1080|f', 'lazyload-effect full-image', false, false); ?>
      <?php endif; ?>
    </div>

    <?php if($copy):?>
      <div class="col-[main] md:col-[col-1/col-8] row-start-1 relative flex items-center z-[2] h-full">
        <div class="wizzy text-white <?php echo $full_screen ? 'py-24' : 'pt-20 pb-8'; ?>">
          <?php echo $copy; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if($show_ray):?>
      <div class="hidden lg:flex col-[main] md:col-[col-9/full-width] xl:col-[col-10/full-width] row-start-1 h-full relative items-end  <?php echo $full_screen ? 'pt-24 ' : 'mt-20 items-start'; ?>">
        <?php echo ResponsivePics::get_picture(75, 'sm:550', 'lazyload-effect', false, false); ?>
      </div>
    <?php endif; ?>
  </div>

<?php $section->end(); ?>
