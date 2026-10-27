<?php
/**
 * Template Name: Blog
 *
 * Blog archive. Lists posts; the page's own content is not shown.
 */

get_header();
get_template_part(
	'template-parts/blog/archive',
	null,
	array(
		'query' => osteza_blog_query(),
		'base'  => get_permalink(),
	)
);
get_footer();
