<?php
/**
 * Default page template: the page's blocks; a plain header and text for pages
 * written without OSTEZA blocks.
 */

get_header();
the_post();

$content = get_the_content();
if ( has_blocks( $content ) && false !== strpos( $content, '<!-- wp:osteza/' ) ) {
	osteza_the_sections( 'page' );
} else {
	echo osteza_render_block( 'page-head', array( 'title' => esc_html( get_the_title() ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput
	?>
	<section class="sec sec-prose"><div class="wrap"><div class="prose"><?php the_content(); ?></div></div></section>
	<?php
}

get_footer();
