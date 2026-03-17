<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Fire
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			if ( get_post_type() === 'post' ) {
				get_template_part( 'templates/content', 'post' );
			} else {
				get_template_part( 'templates/content', get_post_type() );
			}

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();
