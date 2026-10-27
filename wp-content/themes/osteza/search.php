<?php
/**
 * Search results, in the blog layout (the search covers posts only).
 */

get_header();
get_template_part(
	'template-parts/blog/archive',
	null,
	array(
		'query' => $wp_query,
		'base'  => get_search_link(),
	)
);
get_footer();
