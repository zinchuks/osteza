<?php
/** Search and sharing metadata. Yields to established SEO plugins. */
function osteza_seo_external() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

add_action( 'add_meta_boxes', function () {
	foreach ( array( 'page', 'post', OSTEZA_PROJECT ) as $type ) {
		add_meta_box( 'osteza-seo', 'SEO', 'osteza_seo_box', $type, 'normal' );
	}
} );
function osteza_seo_box( $post ) {
	wp_nonce_field( 'osteza_seo', 'osteza_seo_nonce' );
	foreach ( array( 'title' => __( 'Título SEO', 'osteza' ), 'description' => __( 'Descripción SEO', 'osteza' ) ) as $key => $label ) {
		echo '<p><label for="osteza-seo-' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label><br><input class="widefat" id="osteza-seo-' . esc_attr( $key ) . '" name="osteza_seo_' . esc_attr( $key ) . '" value="' . esc_attr( get_post_meta( $post->ID, '_osteza_seo_' . $key, true ) ) . '"></p>';
	}
}
add_action( 'save_post', function ( $id ) {
	if ( ! isset( $_POST['osteza_seo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['osteza_seo_nonce'] ) ), 'osteza_seo' ) || ! current_user_can( 'edit_post', $id ) || wp_is_post_revision( $id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return;
	}
	foreach ( array( 'title', 'description' ) as $key ) {
		if ( isset( $_POST[ 'osteza_seo_' . $key ] ) ) {
			update_post_meta( $id, '_osteza_seo_' . $key, sanitize_text_field( wp_unslash( $_POST[ 'osteza_seo_' . $key ] ) ) );
		}
	}
} );
add_filter( 'pre_get_document_title', function ( $title ) {
	if ( ! osteza_seo_external() && is_singular() ) {
		$custom = get_post_meta( get_queried_object_id(), '_osteza_seo_title', true );
		if ( $custom ) {
			return $custom;
		}
	}
	return $title;
} );

/** Extract introductory copy without rendering blocks, forms or shortcodes. */
function osteza_seo_intro( $blocks ) {
	foreach ( $blocks as $block ) {
		foreach ( array( 'lead', 'intro', 'text' ) as $key ) {
			$text = $block['attrs'][ $key ] ?? '';
			if ( is_string( $text ) && trim( $text ) && ! preg_match( '/\[[^\]]+\]/u', $text ) ) {
				return $text;
			}
		}
		if ( 'core/paragraph' === $block['blockName'] && trim( wp_strip_all_tags( $block['innerHTML'] ) ) ) {
			return $block['innerHTML'];
		}
		$inner = osteza_seo_intro( $block['innerBlocks'] ?? array() );
		if ( $inner ) {
			return $inner;
		}
	}
	return '';
}
function osteza_seo_description( $post ) {
	$text = get_post_meta( $post->ID, '_osteza_seo_description', true );
	if ( ! $text ) {
		$text = $post->post_excerpt ?: osteza_seo_intro( parse_blocks( $post->post_content ) );
	}
	$text = html_entity_decode( wp_strip_all_tags( strip_shortcodes( $text ) ), ENT_QUOTES, 'UTF-8' );
	$text = trim( preg_replace( '/\s+/u', ' ', $text ) );
	if ( mb_strlen( $text ) > 160 ) {
		$short = mb_substr( $text, 0, 157 );
		$text = ( preg_replace( '/\s+\S*$/u', '', $short ) ?: $short ) . '…';
	}
	return $text;
}
add_filter( 'wp_robots', function ( $robots ) {
	if ( osteza_seo_external() ) {
		return $robots;
	}
	$empty_projects = is_post_type_archive( OSTEZA_PROJECT ) && ! have_posts();
	if ( is_search() || is_attachment() || $empty_projects || ( is_singular() && get_post_meta( get_queried_object_id(), 'osteza_sample', true ) ) ) {
		$robots['noindex'] = true;
		unset( $robots['index'] );
	}
	return $robots;
} );
add_action( 'wp_head', function () {
	if ( osteza_seo_external() || is_404() || is_search() || is_feed() || is_preview() || is_attachment() ) {
		return;
	}
	$post  = is_singular() ? get_queried_object() : null;
	$url   = $post ? wp_get_canonical_url( $post ) : get_pagenum_link( max( 1, get_query_var( 'paged' ) ) );
	$title = wp_get_document_title();
	$desc  = $post ? osteza_seo_description( $post ) : '';
	if ( ! is_singular() ) {
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	}
	$meta = array( 'og:type' => is_singular( 'post' ) ? 'article' : 'website', 'og:title' => $title, 'og:url' => $url, 'og:site_name' => get_bloginfo( 'name' ), 'og:locale' => get_locale() );
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		$meta['og:description'] = $desc;
	}
	$image = $post ? wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'full' ) : false;
	$large_image = (bool) $image;
	if ( ! $image ) {
		$image = array( osteza_img( 'icons/icon-512.png' ), 512, 512 );
	}
	if ( $image ) {
		$meta['og:image'] = $image[0];
		$meta['og:image:width'] = $image[1];
		$meta['og:image:height'] = $image[2];
		$meta['og:image:alt'] = $large_image ? get_post_meta( get_post_thumbnail_id( $post ), '_wp_attachment_image_alt', true ) : 'OSTEZA';
	}
	foreach ( $meta as $property => $value ) {
		echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $value ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="' . ( $large_image ? 'summary_large_image' : 'summary' ) . '">' . "\n";
	$home = osteza_home_url();
	$org  = array( '@type' => 'Organization', '@id' => home_url( '/#organization' ), 'name' => get_bloginfo( 'name' ), 'url' => home_url( '/' ), 'logo' => osteza_img( 'logo-dark.svg' ) );
	foreach ( array( 'phone' => 'telephone', 'email' => 'email' ) as $key => $property ) {
		if ( osteza_has_contact( $key ) ) {
			$org[ $property ] = get_theme_mod( 'osteza_' . $key );
		}
	}
	$org['sameAs'] = array_values( array_filter( array( get_theme_mod( 'osteza_instagram' ), get_theme_mod( 'osteza_facebook' ) ) ) );
	$graph = array( $org, array( '@type' => 'WebSite', '@id' => $home . '#website', 'url' => $home, 'name' => get_bloginfo( 'name' ), 'inLanguage' => get_bloginfo( 'language' ), 'publisher' => array( '@id' => $org['@id'] ) ) );
	if ( $post && ! is_front_page() ) {
		$crumbs = array( array( '@type' => 'ListItem', 'position' => 1, 'name' => __( 'Inicio', 'osteza' ), 'item' => $home ) );
		foreach ( array_reverse( get_post_ancestors( $post ) ) as $id ) {
			$crumbs[] = array( '@type' => 'ListItem', 'position' => count( $crumbs ) + 1, 'name' => get_the_title( $id ), 'item' => get_permalink( $id ) );
		}
		$crumbs[] = array( '@type' => 'ListItem', 'position' => count( $crumbs ) + 1, 'name' => get_the_title( $post ), 'item' => $url );
		$graph[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $crumbs );
	}
	if ( is_singular( 'post' ) ) {
		$article = array( '@type' => 'BlogPosting', '@id' => $url . '#article', 'headline' => get_the_title( $post ), 'mainEntityOfPage' => $url, 'datePublished' => get_the_date( DATE_W3C, $post ), 'dateModified' => get_the_modified_date( DATE_W3C, $post ), 'inLanguage' => get_bloginfo( 'language' ), 'author' => array( '@type' => 'Person', 'name' => get_the_author_meta( 'display_name', $post->post_author ) ), 'publisher' => array( '@id' => $org['@id'] ) );
		if ( $large_image ) {
			$article['image'] = $image[0];
		}
		$graph[] = $article;
	}
	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}, 20 );

// The blog is a normal page with its own paginated query; core canonical only handles multipage posts.
add_filter( 'get_canonical_url', function ( $url, $post ) {
	if ( ! osteza_seo_external() && get_queried_object_id() === $post->ID && is_page_template( 'page-templates/blog.php' ) && get_query_var( 'paged' ) > 1 ) {
		return get_pagenum_link( (int) get_query_var( 'paged' ) );
	}
	return $url;
}, 10, 2 );
add_filter( 'pll_rel_hreflang_attributes', function ( $links ) {
	if ( ! osteza_seo_external() ) {
		$default = function_exists( 'pll_default_language' ) ? pll_default_language() : 'es';
		if ( isset( $links[ $default ] ) ) {
			$links['x-default'] = $links[ $default ];
		}
	}
	return $links;
} );
// Sample copy must not enter the sitemap when indexing is enabled on production.
add_filter( 'wp_sitemaps_posts_query_args', function ( $args ) {
	$args['meta_query'][] = array( 'relation' => 'OR', array( 'key' => 'osteza_sample', 'compare' => 'NOT EXISTS' ), array( 'key' => 'osteza_sample', 'value' => array( '', '0' ), 'compare' => 'IN' ) );
	return $args;
} );

add_action( 'admin_notices', function () {
	if ( current_user_can( 'manage_options' ) && '#' === osteza_privacy_url() ) {
		echo '<div class="notice notice-warning"><p>' . esc_html__( 'Antes del lanzamiento: publica la información legal y una política de privacidad que explique el chat, OpenAI y Telegram.', 'osteza' ) . '</p></div>';
	}
} );
