<?php
/**
 * Template helpers.
 */

/**
 * Inline stroke icon on a 24×24 grid.
 */
function osteza_icon( $name, $size = 24, $stroke = 'currentColor', $width = '1.7', $attrs = '' ) {
	static $icons = array(
		'pin'       => '<path d="M12 21s-7-6.4-7-12a7 7 0 0 1 14 0c0 5.6-7 12-7 12z"/><circle cx="12" cy="9" r="2.5"/>',
		'phone'     => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
		'whatsapp'  => '<path d="M4 20.5l1.4-4.2A8.5 8.5 0 1 1 8.4 19z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.6-2-1-1 .8a4 4 0 0 1-1.7-1.7l.8-1-1-2z"/>',
		'bubble'    => '<path d="M4 20.5l1.4-4.2A8.5 8.5 0 1 1 8.4 19z"/>',
		'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r=".8" fill="currentColor"/>',
		'facebook'  => '<path d="M14 8.5h3V5h-3a4 4 0 0 0-4 4v2.5H7.5V15H10v6h3.5v-6h3l.5-3.5h-3.5V9a.5.5 0 0 1 .5-.5z"/>',
		'telegram'  => '<path d="M21 4 3 11l6 2.5L19 7l-8 8 .5 5 3-4 4 3z"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'info'      => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5v.5"/>',
		'house'     => '<path d="M4 20h16M6 20V9l6-5 6 5v11"/><path d="M10 20v-6h4v6"/>',
		'doc'       => '<rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M8.5 8h7M8.5 12h7M8.5 16h4"/>',
		'doc-check' => '<rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M8.5 8h7M8.5 12h7"/><path d="m9 16 2 2 4-4"/>',
		'chat'      => '<path d="M4 5h13v10H9l-5 4z"/><path d="M20 9v10l-3-2.5h-6"/>',
		'marker'    => '<path d="M12 3a6 6 0 0 0-6 6c0 4 6 11 6 11s6-7 6-11a6 6 0 0 0-6-6z"/><circle cx="12" cy="9" r="2"/>',
		'burger'    => '<path d="M3 7h18M3 12h18M9 17h12"/>',
		'close'     => '<path d="M6 6l12 12M18 6 6 18"/>',
		'prev'      => '<path d="M19 12H5M11 6l-6 6 6 6"/>',
		'next'      => '<path d="M5 12h14M13 6l6 6-6 6"/>',
	);

	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="%2$s" stroke-width="%3$s" aria-hidden="true"%4$s>%5$s</svg>',
		$size,
		esc_attr( $stroke ),
		esc_attr( $width ),
		$attrs ? ' ' . $attrs : '',
		$icons[ $name ]
	);
}

/**
 * Large line illustration on a 48×48 grid (services, article covers).
 */
function osteza_art( $inner, $size = 48, $stroke = 'currentColor' ) {
	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 48 48" fill="none" stroke="%2$s" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%3$s</svg>',
		$size,
		esc_attr( $stroke ),
		$inner
	);
}

function osteza_chevron( $size = 12 ) {
	return sprintf( '<svg class="chev" width="%1$d" height="%1$d" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M2.5 4.5 6 8l3.5-3.5"/></svg>', $size );
}

function osteza_arrow() {
	return '<span class="ar" aria-hidden="true">→</span>';
}

function osteza_img( $file ) {
	return esc_url( get_template_directory_uri() . '/assets/img/' . $file );
}

/**
 * Link to a section of the home page; a bare hash when the current page has that section itself.
 */
function osteza_anchor( $id ) {
	if ( is_front_page() || ( 'contacto' === $id && osteza_service() ) ) {
		return '#' . $id;
	}
	return esc_url( osteza_home_url() . '#' . $id );
}

function osteza_home_url() {
	return function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
}

/**
 * Content of the service shown on the current page, or null when the page is not a service.
 *
 * A service is a page using the "Servicio" template whose slug, in the default language,
 * names a file in inc/services/. Translated pages share the file of the page they translate.
 */
function osteza_service() {
	static $service = false;
	if ( false !== $service ) {
		return $service;
	}
	$service = null;
	if ( is_page_template( 'page-templates/service.php' ) ) {
		$id = get_queried_object_id();
		if ( function_exists( 'pll_get_post' ) ) {
			$id = pll_get_post( $id, pll_default_language() ) ?: $id;
		}
		$file = get_template_directory() . '/inc/services/' . sanitize_file_name( get_post_field( 'post_name', $id ) ) . '.php';
		if ( is_file( $file ) ) {
			$service = require $file;
		}
	}
	return $service;
}

/**
 * Permalink of a service page in the current language; "#" until that page is published.
 *
 * @param string|null $key Slug of the page in the default language.
 */
function osteza_service_url( $key ) {
	$page = $key ? get_page_by_path( $key ) : null;
	if ( $page && function_exists( 'pll_get_post' ) ) {
		$translated = pll_get_post( $page->ID );
		$page       = $translated ? get_post( $translated ) : null;
	}
	return $page && 'publish' === $page->post_status ? esc_url( get_permalink( $page ) ) : '#';
}

/**
 * Contact detail set in Appearance → Customize → OSTEZA. Until it is filled in,
 * the bracketed placeholder from the design is shown.
 */
function osteza_contact( $key ) {
	$value = trim( (string) get_theme_mod( 'osteza_' . $key, '' ) );
	if ( '' !== $value ) {
		return $value;
	}
	$fields = osteza_contact_fields();
	return $fields[ $key ]['placeholder'];
}

function osteza_has_contact( $key ) {
	return '' !== trim( (string) get_theme_mod( 'osteza_' . $key, '' ) );
}

function osteza_phone_url() {
	return 'tel:' . preg_replace( '/[^\d+]/', '', (string) get_theme_mod( 'osteza_phone', '' ) );
}

function osteza_whatsapp_url() {
	return 'https://wa.me/' . preg_replace( '/\D/', '', (string) get_theme_mod( 'osteza_whatsapp', '' ) );
}

function osteza_telegram_url() {
	return 'https://t.me/' . ltrim( (string) get_theme_mod( 'osteza_telegram', '' ), '@' );
}

function osteza_social_url( $network ) {
	$url = trim( (string) get_theme_mod( 'osteza_' . $network, '' ) );
	return '' !== $url ? esc_url( $url ) : '#';
}

/**
 * Site languages from Polylang: code, name, url, current.
 */
function osteza_languages() {
	$langs = array();
	if ( function_exists( 'pll_the_languages' ) ) {
		$raw = pll_the_languages(
			array(
				'raw'           => 1,
				'hide_if_empty' => 0,
			)
		);
		foreach ( (array) $raw as $l ) {
			$langs[] = array(
				'code'    => $l['slug'],
				'name'    => $l['name'],
				'url'     => $l['url'],
				'current' => (bool) $l['current_lang'],
			);
		}
	}
	if ( ! $langs ) {
		$code    = substr( get_bloginfo( 'language' ), 0, 2 );
		$langs[] = array(
			'code'    => $code,
			'name'    => strtoupper( $code ),
			'url'     => home_url( '/' ),
			'current' => true,
		);
	}
	return $langs;
}

function osteza_current_language() {
	foreach ( osteza_languages() as $l ) {
		if ( $l['current'] ) {
			return $l;
		}
	}
	return osteza_languages()[0];
}

function osteza_language_options() {
	foreach ( osteza_languages() as $l ) {
		printf(
			'<option value="%s" lang="%s"%s>%s</option>',
			esc_url( $l['url'] ),
			esc_attr( $l['code'] ),
			selected( $l['current'], true, false ),
			esc_html( $l['name'] )
		);
	}
}
