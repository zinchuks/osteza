<?php
/**
 * Home page: the blocks of the page set as front page (Settings → Reading), in the
 * current language; the default home sections while there is none or it is empty.
 */

get_header();

$front = (int) get_option( 'page_on_front' );
if ( $front && function_exists( 'pll_get_post' ) ) {
	$front = (int) pll_get_post( $front );
}
if ( $front && 'page' === get_option( 'show_on_front' ) ) {
	$GLOBALS['post'] = get_post( $front ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride -- the page whose blocks are rendered.
	setup_postdata( $GLOBALS['post'] );
}
osteza_the_sections( 'home' );
wp_reset_postdata();

get_footer();
