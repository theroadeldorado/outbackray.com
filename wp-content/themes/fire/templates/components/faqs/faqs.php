<?php
$background_texture = get_sub_field('background_texture');
$background_color = get_sub_field('background_colors');
$text_color = get_text_color($background_color);
$heading = get_sub_field('heading');

$section->add_classes([
  $background_color,
  $background_texture,
  $text_color,
]);
?>

<?php $section->start(); ?>
  <?php if( have_rows('faqs') ):?>
    <div class="fire-container py-20 lg:py-28 base-lg">
      <div class="wizzy text-center col-[main] md:col-[col-2/col-11] lg:col-[col-3/col-10] relative z-[1]">
      <?php if ($heading) : ?>
        <h2 class="text-center heading-3 font-bold mb-10 lg:mb-20"><?php echo $heading; ?></h2>
      <?php endif; ?>
        <?php while( have_rows('faqs') ) : the_row();
          $question = get_sub_field('question');
          $answer = get_sub_field('answer');
          $index = get_row_index();
          ?>
          <div
            x-data="{
              open: window.location.hash === '#faq-<?php echo $index; ?>'
            }"
            class="mb-4 p-4 border border-white/50 rounded-lg <?php echo $background_color;?>"
          >
            <button
              @click="open = !open"
              class="w-full text-left font-bold heading-6 py-3 flex justify-between items-center scroll-mt-10"
              id="faq-<?php echo $index; ?>"
            >
              <span><?php echo $question; ?></span>
              <svg
                class="w-5 h-5 shrink-0 transform transition-transform duration-300"
                :class="{ 'rotate-180': open }"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div
              x-collapse
              x-show="open"
              class="text-left wizzy"
            >
              <?php echo $answer; ?>
            </div>
          </div>
        <?php endwhile;?>
      </div>
    </div>
  <?php endif;?>
<?php $section->end(); ?>