<?php
/**
 * Home page.
 */

get_header();

foreach ( array( 'hero', 'services', 'approach', 'paths', 'process', 'team', 'zones', 'blog', 'faq' ) as $section ) {
	get_template_part( 'template-parts/home/' . $section );
}
get_template_part( 'template-parts/contact' );

get_footer();
