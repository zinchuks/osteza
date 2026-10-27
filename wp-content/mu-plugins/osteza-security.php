<?php
/**
 * Plugin Name: OSTEZA Security
 * Description: Baseline hardening independent of the active theme.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) { define( 'DISALLOW_FILE_EDIT', true ); }

add_action( 'send_headers', function () {
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(self)' );
} );

// Public pingbacks are unused and can trigger outbound requests to attacker-selected URLs.
add_filter( 'xmlrpc_methods', function ( $methods ) {
	unset( $methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'] );
	return $methods;
} );
add_filter( 'wp_headers', function ( $headers ) { unset( $headers['X-Pingback'] ); return $headers; } );
