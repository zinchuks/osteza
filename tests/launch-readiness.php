<?php
/** Integration checks. All outbound HTTP is intercepted; only disposable test data is created. */
if ( PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; }
define( 'OSTEZA_TG_TOKEN', 'test-only' );
define( 'OSTEZA_TG_CHAT', 'test-only' );
require dirname( __DIR__ ) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
function check( $ok, $message ) { if ( ! $ok ) { throw new RuntimeException( $message ); } echo "PASS $message\n"; }
$photo_ok = false;
$calls = array();
$mock = function ( $pre, $args, $url ) use ( &$photo_ok, &$calls ) {
	if ( strpos( $url, 'https://api.telegram.org/bottest-only/' ) !== 0 ) { throw new RuntimeException( 'Unexpected external HTTP blocked' ); }
	$method = basename( $url ); $calls[] = $method;
	$ok = 'sendMessage' === $method || $photo_ok;
	return array( 'headers' => array(), 'body' => wp_json_encode( array( 'ok' => $ok, 'result' => array( 'message_id' => 1 ) ) ), 'response' => array( 'code' => $ok ? 200 : 500, 'message' => '' ) );
};
add_filter( 'pre_http_request', $mock, PHP_INT_MAX, 3 );
$id = 0; $tmp = null; $tmp2 = null;
$limits_before = get_option( 'osteza_chat_limits', null );
$counter_name = 'test_' . wp_generate_password( 12, false );
try {
	$id = wp_insert_post( array( 'post_type' => OSTEZA_CHAT, 'post_status' => 'private', 'post_title' => 'OSTEZA disposable launch test' ) );
	check( $id && ! is_wp_error( $id ), 'Create isolated test conversation' );
	$lead = array( 'name' => 'Test', 'phone' => '34600000000', 'town' => 'Benidorm', 'channel' => 'whatsapp', 'telegram' => '', 'geo' => '', 'request' => 'Disposable test request' );
	$tmp = wp_tempnam( 'osteza-test.png' ); file_put_contents( $tmp, base64_decode( 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII=' ) );
	check( ! osteza_chat_send_lead( $id, $lead, array( $tmp => 'image/png' ) ), 'Photo failure must not report successful delivery' );
	$photo_ok = true;
	check( osteza_chat_send_lead( $id, $lead, array( $tmp => 'image/png' ) ), 'Retry delivers photos' );
	check( count( array_filter( $calls, function ( $m ) { return 'sendMessage' === $m; } ) ) === 1, 'Retry avoids duplicate Telegram text' );
	$tmp2 = wp_tempnam( 'osteza-test-2.png' ); copy( $tmp, $tmp2 );
	check( osteza_chat_send_lead( $id, $lead, array( $tmp => 'image/png', $tmp2 => 'image/png' ) ) === true, 'Album response is checked' );
	check( in_array( 'sendMediaGroup', $calls, true ), 'Album upload path exercised' );
	$photo_ok = false;
	check( ! osteza_chat_send_lead( $id, $lead, array( $tmp => 'image/png' ) ), 'Subsequent photo error remains visible' );
	$lead['request'] = 'Changed request details';
	check( osteza_chat_send_lead( $id, $lead, array() ), 'Edited request sends updated text' );
	check( count( array_filter( $calls, function ( $m ) { return 'sendMessage' === $m; } ) ) === 2, 'Updated text not mistaken for a duplicate' );
	$lock = osteza_chat_lock( 'test' );
	check( (bool) $lock, 'Acquire database advisory lock' );
	$other = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
	check( '0' === (string) $other->get_var( $other->prepare( 'SELECT GET_LOCK(%s, 0)', $lock ) ), 'Second connection cannot acquire held lock' );
	osteza_chat_unlock( $lock );
	check( '1' === (string) $other->get_var( $other->prepare( 'SELECT GET_LOCK(%s, 0)', $lock ) ), 'Lock released after operation' );
	$other->get_var( $other->prepare( 'SELECT RELEASE_LOCK(%s)', $lock ) ); $other->close();
	$post = (object) array( 'ID' => $id, 'post_excerpt' => '', 'post_content' => '<!-- wp:osteza/hero {"lead":"Kitchen fitting in Costa Blanca."} /-->' );
	check( 'Kitchen fitting in Costa Blanca.' === osteza_seo_description( $post ), 'SEO description extracted from custom block attributes' );
	$post->post_content = '<!-- wp:paragraph --><p>A &amp; B <strong>test</strong>.</p><!-- /wp:paragraph -->';
	check( 'A & B test.' === osteza_seo_description( $post ), 'SEO description strips markup and decodes entities' );
	$post->post_excerpt = str_repeat( 'а', 180 );
	check( mb_strlen( osteza_seo_description( $post ) ) <= 160, 'Descriptions truncate Unicode safely' );
	check( osteza_chat_count( $counter_name, 2, 60, false ), 'Rate counter allows first request' );
	check( osteza_chat_count( $counter_name, 2, 60, false ), 'Rate counter allows second request' );
	check( ! osteza_chat_count( $counter_name, 2, 60, false ), 'Rate counter blocks requests at the limit' );
	update_option( 'osteza_chat_limits', array( 'budget' => 0 ), false );
	$result = osteza_chat_request( array() );
	check( is_wp_error( $result ) && 'osteza_chat_budget' === $result->get_error_code(), 'Budget exhaustion prevents outbound paid request' );
	$lock = osteza_chat_lock( 'paid_request' );
	check( (bool) $lock, 'Paid request lock released after budget refusal' );
	osteza_chat_unlock( $lock );
	$failed = false;
} catch ( Throwable $e ) { fwrite( STDERR, 'FAIL ' . $e->getMessage() . "\n" ); $failed = true;
} finally {
	if ( null === $limits_before ) { delete_option( 'osteza_chat_limits' ); } else { update_option( 'osteza_chat_limits', $limits_before, false ); }
	delete_transient( 'osteza_chat_' . $counter_name );
	if ( $id ) { wp_delete_post( $id, true ); }
	if ( $tmp && file_exists( $tmp ) ) { unlink( $tmp ); }
	if ( $tmp2 && file_exists( $tmp2 ) ) { unlink( $tmp2 ); }
	remove_filter( 'pre_http_request', $mock, PHP_INT_MAX );
}
exit( $failed ? 1 : 0 );
