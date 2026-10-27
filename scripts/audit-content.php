<?php
/** Run inside the WordPress container: php scripts/audit-content.php */
if ( PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; }
require dirname( __DIR__ ) . '/wp-load.php';
$rows = array();
foreach ( get_posts( array( 'post_type' => array( 'page', 'post' ), 'post_status' => 'publish', 'numberposts' => -1, 'lang' => '' ) ) as $post ) {
	$values = array();
	$walk = function ( $blocks ) use ( &$walk, &$values ) {
		foreach ( $blocks as $block ) {
			if ( false === ( $block['attrs']['metadata']['blockVisibility'] ?? true ) ) { continue; }
			$attrs = $block['attrs'] ?? array();
			array_walk_recursive( $attrs, function ( $v ) use ( &$values ) { if ( is_string( $v ) ) { $values[] = $v; } } );
			$values[] = $block['innerHTML'];
			$walk( $block['innerBlocks'] );
		}
	};
	$walk( parse_blocks( $post->post_content ) );
	$text = html_entity_decode( wp_strip_all_tags( implode( "\n", $values ) ), ENT_QUOTES, 'UTF-8' );
	preg_match_all( '/\[(?!osteza_cta\b)[^\]\n]{2,180}\]/u', $text, $matches );
	$rows[] = array( 'id' => $post->ID, 'type' => $post->post_type, 'title' => $post->post_title, 'lang' => pll_get_post_language( $post->ID ), 'url' => get_permalink( $post ), 'template' => get_page_template_slug( $post ), 'description' => osteza_seo_description( $post ), 'placeholders' => $matches[0] );
}
echo wp_json_encode( $rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
