<?php
/**
 * Fallback template for everything that is not the home page.
 */

get_header();
?>
<section class="sec">
<div class="wrap prose">
<?php
while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class(); ?>>
	<h1 class="disp h2"><?php is_singular() ? the_title() : printf( '<a href="%s">%s</a>', esc_url( get_permalink() ), esc_html( get_the_title() ) ); ?></h1>
	<?php is_singular() ? the_content() : the_excerpt(); ?>
	</article>
	<?php
endwhile;
the_posts_pagination();
?>
</div>
</section>
<?php
get_footer();
