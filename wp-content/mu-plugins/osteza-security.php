<?php
/**
 * Plugin Name: OSTEZA Security
 * Description: Baseline hardening independent of the active theme.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) { define( 'DISALLOW_FILE_EDIT', true ); }

add_action( 'send_headers', function () {
	header_remove( 'X-Powered-By' );
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(self)' );
	// Only directives that cannot break scripts, styles, the map or the chat.
	header( "Content-Security-Policy: frame-ancestors 'self'; base-uri 'self'; object-src 'none'" );
	if ( is_ssl() ) {
		header( 'Strict-Transport-Security: max-age=31536000' );
	}
} );

// The version is not advertised in the page head or in feeds.
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

// XML-RPC and application passwords are unused; both accept password logins outside wp-login.php.
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'xmlrpc_methods', function () { return array(); } );
add_filter( 'wp_headers', function ( $headers ) { unset( $headers['X-Pingback'] ); return $headers; } );
add_filter( 'wp_is_application_passwords_available', '__return_false' );

// Comments are not part of the site: nothing is shown, so nothing may be posted.
add_filter( 'comments_open', '__return_false' );
add_filter( 'pings_open', '__return_false' );
add_action( 'pre_comment_on_post', function () {
	wp_die( '', '', array( 'response' => 403 ) );
} );

/*
 * Usernames stay private: no author archives (?author=N redirected to them),
 * no public user list in REST, sitemaps or oEmbed.
 */
add_action( 'template_redirect', function () {
	if ( is_author() || isset( $_GET['author'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}, 0 );
add_filter( 'rest_endpoints', function ( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		foreach ( array_keys( $endpoints ) as $route ) {
			if ( 0 === strpos( $route, '/wp/v2/users' ) ) {
				unset( $endpoints[ $route ] );
			}
		}
	}
	return $endpoints;
} );
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}, 10, 2 );
add_filter( 'oembed_response_data', function ( $data ) {
	unset( $data['author_name'], $data['author_url'] );
	return $data;
} );

/*
 * Login: one message for a wrong user or password, and an IP is locked out
 * after OSTEZA_LOGIN_TRIES failures until OSTEZA_LOGIN_WINDOW passes without one.
 * REMOTE_ADDR must be the visitor's address: behind a proxy, the host has to pass it on.
 */
const OSTEZA_LOGIN_TRIES  = 5;
const OSTEZA_LOGIN_WINDOW = 15 * MINUTE_IN_SECONDS;

function osteza_login_key() {
	return 'osteza_login_' . md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
}

add_filter( 'authenticate', function ( $user ) {
	$error = '<strong>' . __( 'Error:' ) . '</strong> ';
	if ( (int) get_transient( osteza_login_key() ) >= OSTEZA_LOGIN_TRIES ) {
		/* translators: %d: minutes. */
		return new WP_Error( 'osteza_login_locked', $error . sprintf( __( 'Demasiados intentos fallidos. Vuelve a intentarlo dentro de %d minutos.', 'osteza' ), OSTEZA_LOGIN_WINDOW / MINUTE_IN_SECONDS ) );
	}
	if ( is_wp_error( $user ) && array_intersect( $user->get_error_codes(), array( 'invalid_username', 'invalid_email', 'incorrect_password' ) ) ) {
		return new WP_Error( 'osteza_login_failed', $error . __( 'El usuario o la contraseña no son correctos.', 'osteza' ) );
	}
	return $user;
}, 100 );
add_action( 'wp_login_failed', function () {
	$key = osteza_login_key();
	set_transient( $key, (int) get_transient( $key ) + 1, OSTEZA_LOGIN_WINDOW );
} );
