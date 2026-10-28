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
add_filter( 'document_title_separator', function () {
	return '|';
} );

/** Reuse the actual zone heading instead of advertising a different service in search. */
function osteza_seo_zone_heading( $post ) {
	$find = function ( $blocks ) use ( &$find ) {
		foreach ( $blocks as $block ) {
			if ( 'osteza/zone-hero' === $block['blockName'] ) {
				return trim( wp_strip_all_tags( $block['attrs']['title'] ?? '' ) );
			}
			$inner = $find( $block['innerBlocks'] ?? array() );
			if ( $inner ) {
				return $inner;
			}
		}
		return '';
	};
	if ( has_blocks( $post->post_content ) ) {
		return $find( parse_blocks( $post->post_content ) ) ?: get_the_title( $post );
	}
	foreach ( osteza_layout( 'zone', $post ) as $section ) {
		if ( 'zone-hero' === $section[0] ) {
			return trim( wp_strip_all_tags( $section[1]['title'] ?? '' ) ) ?: get_the_title( $post );
		}
	}
	return get_the_title( $post );
}
// Services and zones without their own SEO title name the place, while the title fits in search results.
add_filter( 'document_title_parts', function ( $parts ) {
	if ( osteza_seo_external() || ! is_page() || ! isset( $parts['title'] ) ) {
		return $parts;
	}
	$type = osteza_page_type( get_queried_object() );
	if ( 'service' === $type ) {
		/* translators: %s: service name. */
		$title = sprintf( __( '%s en la Costa Blanca', 'osteza' ), $parts['title'] );
	} elseif ( 'zone' === $type ) {
		$title = osteza_seo_zone_heading( get_queried_object() );
	}
	if ( isset( $title ) && ( 'zone' === $type || mb_strlen( $title . ' | ' . ( $parts['site'] ?? '' ) ) <= 60 ) ) {
		$parts['title'] = $title;
	}
	return $parts;
} );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'template_redirect', 'wp_shortlink_header', 11 );

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
/** Questions and answers of the visible FAQ sections of a page: ( q, a ). */
function osteza_seo_faq( $post ) {
	$sections = array();
	if ( has_blocks( $post->post_content ) ) {
		$walk = function ( $blocks ) use ( &$walk, &$sections ) {
			foreach ( $blocks as $block ) {
				if ( 'osteza/faq' === $block['blockName'] && false !== ( $block['attrs']['metadata']['blockVisibility'] ?? true ) ) {
					$sections[] = $block['attrs'];
				}
				$walk( $block['innerBlocks'] );
			}
		};
		$walk( parse_blocks( $post->post_content ) );
	} else {
		foreach ( osteza_layout( is_front_page() ? 'home' : osteza_page_type( $post ), $post ) as $section ) {
			if ( 'faq' === $section[0] ) {
				$sections[] = $section[1];
			}
		}
	}
	$default = osteza_blocks()['faq']['fields']['items']['default'];
	$faq     = array();
	foreach ( $sections as $attrs ) {
		foreach ( (array) ( $attrs['items'] ?? $default ) as $item ) {
			$q = trim( wp_strip_all_tags( $item['q'] ?? '' ) );
			$a = trim( wp_strip_all_tags( osteza_html( $item['a'] ?? '' ) ) );
			if ( $q && $a && ! preg_match( '/\[[^\]]+\]/u', $q . $a ) ) {
				$faq[] = array( 'q' => $q, 'a' => $a );
			}
		}
	}
	return $faq;
}

/** Facebook locale (ll_CC) of a Polylang language slug, or of the site. */
function osteza_seo_locale( $slug = '' ) {
	$lang = function_exists( 'PLL' ) && $slug ? PLL()->model->get_language( $slug ) : null;
	return $lang && $lang->facebook ? $lang->facebook : get_locale();
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
	$meta = array( 'og:type' => is_singular( 'post' ) ? 'article' : 'website', 'og:title' => $title, 'og:url' => $url, 'og:site_name' => get_bloginfo( 'name' ), 'og:locale' => osteza_seo_locale( function_exists( 'pll_current_language' ) ? pll_current_language() : '' ) );
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		$meta['og:description'] = $desc;
	}
	$image = $post ? wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'full' ) : false;
	$own_image = (bool) $image;
	if ( ! $image ) {
		$image = array( osteza_img( 'og-default.jpg' ), 1200, 630 );
	}
	$meta['og:image'] = $image[0];
	$meta['og:image:width'] = $image[1];
	$meta['og:image:height'] = $image[2];
	$meta['og:image:alt'] = ( $own_image ? get_post_meta( get_post_thumbnail_id( $post ), '_wp_attachment_image_alt', true ) : '' ) ?: get_bloginfo( 'name' );
	foreach ( $meta as $property => $value ) {
		echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $value ) . '">' . "\n";
	}
	if ( $post && function_exists( 'pll_get_post_translations' ) ) {
		foreach ( array_keys( pll_get_post_translations( $post->ID ) ) as $slug ) {
			if ( pll_current_language() !== $slug ) {
				echo '<meta property="og:locale:alternate" content="' . esc_attr( osteza_seo_locale( $slug ) ) . '">' . "\n";
			}
		}
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	$home = osteza_home_url();
	$area = array_values( array_map( function ( $zone ) {
		return array( '@type' => 'City', 'name' => $zone['name'] );
	}, osteza_zones() ) );
	// A service-area business: the base town without a street address, and the zones it covers.
	$org  = array(
		'@type'      => array( 'Organization', 'HomeAndConstructionBusiness' ),
		'@id'        => home_url( '/#organization' ),
		'name'       => get_bloginfo( 'name' ),
		'url'        => home_url( '/' ),
		'logo'       => osteza_img( 'logo-dark.svg' ),
		'image'      => osteza_img( 'og-default.jpg' ),
		'address'    => array( '@type' => 'PostalAddress', 'addressLocality' => 'Benidorm', 'addressRegion' => 'Alicante', 'addressCountry' => 'ES' ),
		'areaServed' => $area,
	);
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
	if ( $post && is_page() && 'service' === osteza_page_type( $post ) ) {
		$service = osteza_service_by( function_exists( 'pll_get_post' ) ? ( pll_get_post( $post->ID, osteza_default_lang() ) ?: $post->ID ) : $post->ID );
		$group   = $service ? osteza_group( $service['group'] ) : null;
		$graph[] = array_filter( array( '@type' => 'Service', '@id' => $url . '#service', 'name' => get_the_title( $post ), 'url' => $url, 'description' => $desc, 'serviceType' => $group ? $group['title'] : '', 'provider' => array( '@id' => $org['@id'] ), 'areaServed' => $area ) );
	}
	$faq = $post && is_page() ? osteza_seo_faq( $post ) : array();
	if ( $faq ) {
		$graph[] = array( '@type' => 'FAQPage', '@id' => $url . '#faq', 'mainEntity' => array_map( function ( $item ) {
			return array( '@type' => 'Question', 'name' => $item['q'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $item['a'] ) );
		}, $faq ) );
	}
	if ( is_singular( 'post' ) ) {
		$author  = array_filter( array( '@type' => 'Person', 'name' => get_the_author_meta( 'display_name', $post->post_author ), 'description' => get_the_author_meta( 'description', $post->post_author ), 'url' => osteza_about_url(), 'worksFor' => array( '@id' => $org['@id'] ) ) );
		$article = array( '@type' => 'BlogPosting', '@id' => $url . '#article', 'headline' => get_the_title( $post ), 'mainEntityOfPage' => $url, 'datePublished' => get_the_date( DATE_W3C, $post ), 'dateModified' => get_the_modified_date( DATE_W3C, $post ), 'inLanguage' => get_bloginfo( 'language' ), 'author' => $author, 'publisher' => array( '@id' => $org['@id'] ) );
		if ( $own_image ) {
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
