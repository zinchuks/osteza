<?php
/**
 * Posts of one category, in the blog layout.
 */

get_header();
get_template_part(
	'template-parts/blog/archive',
	null,
	array(
		'query'    => $wp_query,
		'category' => get_queried_object(),
		'base'     => get_category_link( get_queried_object_id() ),
	)
);
get_footer();
