<?php
/** Local legal-page visibility maintenance. No content changes. Default: inspect only. */
if ( PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; }
require dirname( __DIR__ ) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/post.php';
if ( ! in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1' ), true ) ) {
	fwrite( STDERR, "Restricted to local WordPress.\n" ); exit( 1 );
}
$apply = in_array( '--apply', $argv, true );
$roots = array( osteza_pid( 'aviso-legal' ), (int) get_option( 'wp_page_for_privacy_policy' ), osteza_pid( 'politica-de-cookies' ) );
$ids = array();
foreach ( $roots as $root ) {
	if ( ! $root ) { throw new RuntimeException( 'Missing legal page root; stopped' ); }
	$ids[] = $root;
	if ( function_exists( 'pll_get_post_translations' ) ) { $ids = array_merge( $ids, array_values( pll_get_post_translations( $root ) ) ); }
}
$ids = array_values( array_unique( array_map( 'intval', $ids ) ) );
if ( count( $ids ) !== 15 ) { throw new RuntimeException( 'Expected three legal pages in five languages; stopped' ); }
$result = array();
foreach ( $ids as $id ) {
	$page = get_post( $id );
	if ( ! $page || 'page' !== $page->post_type ) { throw new RuntimeException( 'Unexpected legal page; stopped' ); }
	$hash = hash( 'sha256', $page->post_content );
	$status = $page->post_status;
	$sample = get_sample_permalink( $id );
	$url = str_replace( array( '%pagename%', '%postname%' ), $sample[1], $sample[0] );
	if ( $apply && 'draft' !== $status ) {
		$updated = wp_update_post( array( 'ID' => $id, 'post_status' => 'draft' ), true );
		if ( is_wp_error( $updated ) ) { throw new RuntimeException( 'Draft update failed' ); }
	}
	$after = get_post( $id );
	if ( hash( 'sha256', $after->post_content ) !== $hash ) { throw new RuntimeException( 'Legal content changed unexpectedly' ); }
	$result[] = array( 'id' => $id, 'lang' => function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $id ) : 'es', 'title' => $page->post_title, 'url' => $url, 'previous_status' => $status, 'status' => $after->post_status, 'content_sha256' => $hash, 'content_unchanged' => true );
}
// Query the actual WordPress sitemap provider without changing the site's indexing setting.
$sitemap_urls = array();
$server = wp_sitemaps_get_server();
$provider = $server->registry->get_provider( 'posts' ) ?: new WP_Sitemaps_Posts();
if ( $provider ) {
	for ( $n = 1, $max = $provider->get_max_num_pages( 'page' ); $n <= $max; $n++ ) {
		foreach ( $provider->get_url_list( $n, 'page' ) as $entry ) { $sitemap_urls[] = $entry['loc']; }
	}
}
$search_ids = get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 's' => 'legal', 'numberposts' => -1, 'lang' => '', 'fields' => 'ids' ) );
$leaked_urls = array_values( array_intersect( array_column( $result, 'url' ), $sitemap_urls ) );
$leaked_search = array_values( array_intersect( $ids, array_map( 'intval', $search_ids ) ) );
echo wp_json_encode( array( 'apply' => $apply, 'pages' => $result, 'sitemap_legal_urls' => $leaked_urls, 'published_search_legal_ids' => $leaked_search ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ), "\n";
