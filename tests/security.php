<?php
/** Local security regressions. No external HTTP and no real conversations are inspected. */
if ( PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; }
require dirname( __DIR__ ) . '/wp-load.php';
function security_check( $ok, $label ) { if ( ! $ok ) { throw new RuntimeException( $label ); } echo "PASS $label\n"; }
$mock = function () { throw new RuntimeException( 'Unexpected outbound HTTP blocked' ); };
add_filter( 'pre_http_request', $mock, PHP_INT_MAX );
$ids = array(); $original_user = get_current_user_id();
try {
	foreach ( array( str_repeat( 'a', 32 ), str_repeat( 'b', 32 ), '' ) as $token ) {
		$id = wp_insert_post( array( 'post_type' => OSTEZA_CHAT, 'post_status' => 'private', 'post_title' => 'Disposable security test' ) );
		if ( $token ) { update_post_meta( $id, '_oc_token', $token ); }
		$ids[] = $id;
	}
	$make = function ( $method, $path = '/osteza/v1/chat', $params = array(), $token = '' ) {
		$r = new WP_REST_Request( $method, $path );
		foreach ( $params as $k => $v ) { $r->set_param( $k, $v ); }
		if ( $token ) { $r->set_header( 'X-OSTEZA-Token', $token ); }
		return $r;
	};
	$r = $make( 'GET', '/osteza/v1/chat', array( 'id' => $ids[0] ) );
	security_check( 404 === rest_do_request( $r )->get_status(), 'History requires a token' );
	$r->set_param( 'token', str_repeat( 'a', 32 ) );
	security_check( 404 === rest_do_request( $r )->get_status(), 'GET query tokens are rejected' );
	$r->set_header( 'X-OSTEZA-Token', str_repeat( 'a', 32 ) );
	security_check( 200 === rest_do_request( $r )->get_status(), 'Header token grants access to its own conversation' );
	$r->set_param( 'id', $ids[1] );
	security_check( 404 === rest_do_request( $r )->get_status(), 'A token cannot read another conversation' );
	$r = $make( 'GET', '/osteza/v1/chat', array( 'id' => $ids[2] ) );
	security_check( null === osteza_chat_from_request( $r ), 'An empty stored token never authenticates' );
	$r = $make( 'POST', '/osteza/v1/chat/request', array( 'id' => $ids[0] ), str_repeat( 'b', 32 ) );
	security_check( 404 === rest_do_request( $r )->get_status(), 'Wrong token cannot mutate an existing request' );
	$r = $make( 'POST' ); $r->set_header( 'Origin', 'https://untrusted.example' );
	security_check( 403 === rest_do_request( $r )->get_status(), 'Cross-origin browser POST is blocked' );
	$r = $make( 'POST' ); $r->set_header( 'Sec-Fetch-Site', 'cross-site' );
	security_check( 403 === rest_do_request( $r )->get_status(), 'Cross-site browser metadata is rejected' );
	$r = $make( 'POST', '/osteza/v1/chat', array( 'text' => array( 'unexpected' ) ) );
	security_check( 400 === rest_do_request( $r )->get_status(), 'Array text rejected before casting or external calls' );
	$r = $make( 'GET', '/osteza/v1/chat', array( 'id' => array( 'unexpected' ) ) );
	security_check( 400 === rest_do_request( $r )->get_status(), 'Array id rejected' );
	$r = $make( 'POST', '/osteza/v1/chat/request', array( 'channel' => array( 'unexpected' ) ) );
	security_check( 400 === rest_do_request( $r )->get_status(), 'Array channel rejected' );
	$r = $make( 'POST', '/osteza/v1/chat', array( 'text' => str_repeat( 'a', 1001 ) ) );
	security_check( 400 === rest_do_request( $r )->get_status(), 'Oversized text rejected' );
	$r = $make( 'POST' ); $r->set_file_params( array( 'photos' => array( 'tmp_name' => array( array( 'nested' ) ), 'error' => array( 0 ) ) ) );
	security_check( is_wp_error( osteza_chat_uploaded_photos( $r ) ), 'Malformed upload arrays rejected without TypeError' );
	$r->set_file_params( array( 'photos' => array( 'tmp_name' => array( '/tmp/not-an-upload' ), 'error' => array( 0 ) ) ) );
	security_check( is_wp_error( osteza_chat_uploaded_photos( $r ) ), 'Arbitrary server paths cannot be uploaded' );
	$r->set_file_params( array( 'photos' => array( 'tmp_name' => array_fill( 0, 6, '/tmp/not-an-upload' ), 'error' => array_fill( 0, 6, 0 ) ) ) );
	security_check( is_wp_error( osteza_chat_uploaded_photos( $r ) ), 'More than five photos rejected' );
	$log = array(); for ( $n = 0; $n < 40; $n++ ) { $log[] = array( 'n' => $n + 1, 'from' => 'user', 'text' => 'test', 't' => time() ); }
	update_post_meta( $ids[0], '_oc_log', $log );
	$r = $make( 'POST', '/osteza/v1/chat', array( 'id' => $ids[0], 'text' => 'Another message' ), str_repeat( 'a', 32 ) );
	// Direct callback avoids checking provider configuration; the history limit must stop before any API call.
	if ( osteza_chat_has_key() ) {
		$result = osteza_chat_rest_send( $r );
		security_check( is_array( $result ) && count( osteza_chat_log( $ids[0] ) ) === 40, 'Turn cap prevents unbounded history growth' );
	}
	$editor = new WP_User(); $editor->ID = 999999999; $editor->allcaps = get_role( 'editor' )->capabilities;
	security_check( ! user_can( $editor, 'edit_post', $ids[0] ) && ! user_can( $editor, 'read_post', $ids[0] ), 'Editor cannot read or edit private chats' );
	$admin = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) )[0];
	security_check( user_can( $admin, 'edit_post', $ids[0] ) && user_can( $admin, 'read_post', $ids[0] ), 'Administrator retains chat access' );
	security_check( defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT, 'Dashboard PHP file editing disabled' );
	$methods = apply_filters( 'xmlrpc_methods', array( 'pingback.ping' => 'test', 'system.multicall' => 'test' ) );
	security_check( ! apply_filters( 'xmlrpc_enabled', true ) && ! $methods, 'XML-RPC disabled with all its methods' );
	security_check( ! wp_is_application_passwords_available(), 'Application passwords disabled' );
	security_check( ! comments_open( get_posts( array( 'numberposts' => 1, 'fields' => 'ids' ) )[0] ?? 0 ), 'Comments closed' );
	wp_set_current_user( 0 );
	security_check( 404 === rest_do_request( new WP_REST_Request( 'GET', '/wp/v2/users' ) )->get_status(), 'Anonymous REST cannot list users' );
	wp_set_current_user( 1 );
	security_check( 200 === rest_do_request( new WP_REST_Request( 'GET', '/wp/v2/users/me' ) )->get_status(), 'Logged-in REST keeps the users endpoint for the editor' );
	wp_set_current_user( $original_user );
	$key = osteza_login_key(); delete_transient( $key );
	for ( $n = 0; $n < OSTEZA_LOGIN_TRIES; $n++ ) { wp_authenticate( 'no-such-user-' . $n, 'wrong' ); }
	$locked = wp_authenticate( 'no-such-user', 'wrong' );
	delete_transient( $key );
	security_check( is_wp_error( $locked ) && 'osteza_login_locked' === $locked->get_error_code(), 'Repeated failed logins lock the IP out' );
	$wrong = wp_authenticate( 'no-such-user', 'wrong' );
	delete_transient( $key );
	security_check( is_wp_error( $wrong ) && 'osteza_login_failed' === $wrong->get_error_code(), 'Failed login does not reveal whether the user exists' );
	$failed = false;
} catch ( Throwable $e ) { fwrite( STDERR, 'FAIL ' . $e->getMessage() . "\n" ); $failed = true;
} finally {
	wp_set_current_user( $original_user );
	foreach ( $ids as $id ) { wp_delete_post( $id, true ); }
	remove_filter( 'pre_http_request', $mock, PHP_INT_MAX );
}
exit( $failed ? 1 : 0 );
