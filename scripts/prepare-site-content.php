<?php
/** Explicit local content maintenance. Defaults to a read-only plan; back up before --apply. */
if ( PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; }
require dirname( __DIR__ ) . '/wp-load.php';
if ( ! in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1' ), true ) ) {
	fwrite( STDERR, "This maintenance script is restricted to local WordPress.\n" ); exit( 1 );
}
$apply = in_array( '--apply', $argv, true );
$locales = array(
	'es' => array( 'Aviso legal', 'Política de privacidad', 'Política de cookies' ),
	'en' => array( 'Legal notice', 'Privacy policy', 'Cookie policy' ),
	'uk' => array( 'Юридична інформація', 'Політика конфіденційності', 'Політика cookies' ),
	'ru' => array( 'Юридическая информация', 'Политика конфиденциальности', 'Политика cookies' ),
	'pl' => array( 'Informacje prawne', 'Polityka prywatności', 'Polityka cookies' ),
);
$slugs = array(
	'es' => array( 'aviso-legal', 'politica-de-privacidad', 'politica-de-cookies' ),
	'en' => array( 'legal-notice', 'privacy-policy', 'cookie-policy' ),
	'uk' => array( 'yurydychna-informatsiia', 'polityka-konfidentsiinosti', 'polityka-cookies' ),
	'ru' => array( 'yuridicheskaya-informatsiya', 'politika-konfidentsialnosti', 'politika-cookies' ),
	'pl' => array( 'informacje-prawne', 'polityka-prywatnosci', 'polityka-cookies' ),
);
$roots = array( osteza_pid( 'aviso-legal' ), (int) get_option( 'wp_page_for_privacy_policy' ), osteza_pid( 'politica-de-cookies' ) );
$published = 0;
foreach ( $roots as $kind => $root ) {
	$translations = $root && function_exists( 'pll_get_post_translations' ) ? pll_get_post_translations( $root ) : array();
	if ( $root && ! isset( $translations['es'] ) ) { $translations['es'] = $root; }
	foreach ( $locales as $lang => $titles ) {
		if ( function_exists( 'pll_languages_list' ) && ! in_array( $lang, pll_languages_list(), true ) ) { continue; }
		$id = (int) ( $translations[ $lang ] ?? 0 );
		if ( ! $apply ) { echo "PLAN legal $lang/$kind: " . ( $id ? "existing page $id" : 'create page' ) . "\n"; continue; }
		// Never replace already-published editorial legal text. Existing drafts are backed up and revisioned.
		if ( $id && 'publish' === get_post_status( $id ) ) { continue; }
		if ( $id ) { wp_save_post_revision( $id ); }
		$content = '<!-- wp:paragraph --><p><strong>[TO BE REVIEWED BY LAWYER]</strong></p><!-- /wp:paragraph -->' . "\n" . '<!-- wp:paragraph --><p>OSTEZA · <a href="mailto:osteza.es@gmail.com">osteza.es@gmail.com</a></p><!-- /wp:paragraph -->';
		$data = array( 'post_type' => 'page', 'post_title' => $titles[ $kind ], 'post_status' => 'publish', 'post_content' => $content );
		if ( $id ) { $data['ID'] = $id; $id = wp_update_post( wp_slash( $data ), true ); }
		else { $data['post_name'] = $slugs[ $lang ][ $kind ]; $id = wp_insert_post( wp_slash( $data ), true ); }
		if ( is_wp_error( $id ) ) { throw new RuntimeException( 'Legal page update failed' ); }
		if ( function_exists( 'pll_set_post_language' ) ) { pll_set_post_language( $id, $lang ); }
		update_post_meta( $id, '_osteza_legal_review_required', 1 );
		$translations[ $lang ] = $id;
		$published++;
	}
	if ( $apply && function_exists( 'pll_save_post_translations' ) ) { pll_save_post_translations( $translations ); }
	if ( $apply && 1 === $kind ) { update_option( 'wp_page_for_privacy_policy', $translations['es'] ); }
}
if ( ! $apply ) { echo "PLAN: preserve distinct confirmed phone/WhatsApp; normalize English kitchen terminology and homepage description; keep indexing unchanged.\n"; exit; }
set_theme_mod( 'osteza_email', 'osteza.es@gmail.com' );
set_theme_mod( 'osteza_whatsapp', '+34 666 73 71 39' );
set_theme_mod( 'osteza_phone', '+34 614 886 743' );
update_option( 'blogname', 'OSTEZA' );
$replace = function ( $text ) {
	$text = str_replace( array( 'Kitchen fitting and furniture assembly', 'Kitchen fitting', 'kitchen fitting' ), array( 'Kitchen and furniture assembly', 'Kitchen assembly', 'kitchen assembly' ), $text );
	return $text;
};
$changed = 0;
foreach ( get_posts( array( 'post_type' => array( 'page', 'post' ), 'post_status' => 'publish', 'numberposts' => -1, 'lang' => 'en' ) ) as $post ) {
	$data = array( 'ID' => $post->ID );
	foreach ( array( 'post_title', 'post_content', 'post_excerpt' ) as $field ) {
		$value = $replace( $post->$field );
		if ( 'post_title' === $field ) { $value = preg_replace( '/[ \t]{2,}/u', ' ', $value ); }
		if ( $value !== $post->$field ) { $data[ $field ] = $value; }
	}
	if ( count( $data ) > 1 ) {
		wp_save_post_revision( $post->ID );
		$result = wp_update_post( wp_slash( $data ), true );
		if ( is_wp_error( $result ) ) { throw new RuntimeException( 'English content update failed' ); }
		$changed++;
	}
	foreach ( array( '_osteza_seo_title', '_osteza_seo_description', 'osteza_cover_text' ) as $key ) {
		$old = (string) get_post_meta( $post->ID, $key, true );
		$new = $replace( $old );
		if ( 'osteza_cover_text' === $key ) { $new = preg_replace( '/[ \t]{2,}/u', ' ', $new ); }
		if ( $new !== $old ) { update_post_meta( $post->ID, $key, wp_slash( $new ) ); }
	}
}
$english_home = function_exists( 'pll_get_post' ) ? pll_get_post( (int) get_option( 'page_on_front' ), 'en' ) : 0;
if ( $english_home ) {
	update_post_meta( $english_home, '_osteza_seo_description', 'Kitchen and furniture assembly and moving on the Costa Blanca. One contact from your first enquiry to the finished job.' );
}
echo json_encode( array( 'legal_placeholders_published' => $published, 'english_posts_changed' => $changed, 'english_home_description_updated' => (bool) $english_home, 'indexing_unchanged' => get_option( 'blog_public' ), 'credentials_printed' => false ) ), "\n";
