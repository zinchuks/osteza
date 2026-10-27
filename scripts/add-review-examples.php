<?php
/** Insert labelled fictional review examples on every translated home page, once. */
if ( PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; }
require dirname( __DIR__ ) . '/wp-load.php';
$front = (int) get_option( 'page_on_front' );
foreach ( array( 'es', 'en', 'uk', 'ru', 'pl' ) as $lang ) {
	$id = pll_get_post( $front, $lang );
	if ( ! $id ) { throw new RuntimeException( 'Missing home: ' . $lang ); }
	$post = get_post( $id );
	if ( has_block( 'osteza/review-examples', $post ) ) { echo "$lang: already present\n"; continue; }
	osteza_chat_use_language( $lang );
	$block = array(
		'blockName' => 'osteza/review-examples',
		'attrs' => array( 'title' => __( 'Así se verán las opiniones.', 'osteza' ), 'items' => osteza_review_examples() ),
		'innerBlocks' => array(), 'innerHTML' => '', 'innerContent' => array(),
	);
	$blocks = parse_blocks( $post->post_content );
	if ( ! has_blocks( $post->post_content ) ) { throw new RuntimeException( 'Home has no block content: ' . $lang ); }
	$position = count( $blocks );
	foreach ( $blocks as $index => $existing ) {
		if ( 'osteza/team-teaser' === $existing['blockName'] ) { $position = $index + 1; break; }
		if ( 'osteza/contact' === $existing['blockName'] ) { $position = $index; break; }
	}
	array_splice( $blocks, $position, 0, array( $block ) );
	$result = wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => serialize_blocks( $blocks ) ) ), true );
	if ( is_wp_error( $result ) ) { throw new RuntimeException( $result->get_error_message() ); }
	echo "$lang: inserted 10 examples on page $id\n";
}
