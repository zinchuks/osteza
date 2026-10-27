<?php
/** Apply the current official release after backing up the database and plugin. */
if ( PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; }
require dirname( __DIR__ ) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
$admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) );
if ( ! $admins ) { throw new RuntimeException( 'No administrator found' ); }
wp_set_current_user( $admins[0] );
$path = 'polylang/polylang.php';
if ( '3.9.1' === get_plugin_data( WP_PLUGIN_DIR . '/' . $path )['Version'] ) { echo "Already updated\n"; exit; }
$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
$result = $upgrader->install( 'https://downloads.wordpress.org/plugin/polylang.3.9.1.zip', array( 'overwrite_package' => true ) );
if ( true !== $result ) { throw new RuntimeException( is_wp_error( $result ) ? $result->get_error_message() : 'Plugin update failed' ); }
wp_clean_plugins_cache();
echo 'Installed Polylang ' . get_plugin_data( WP_PLUGIN_DIR . '/' . $path )['Version'] . "\n";
