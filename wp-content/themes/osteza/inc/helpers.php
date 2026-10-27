<?php
/**
 * Template helpers.
 */

/**
 * Inline icon in the given colour.
 *
 * Line icons are drawn on a 24×24 grid with the given stroke width. The brand
 * icons (Instagram, WhatsApp) are solid shapes: the colour fills them and the
 * width is ignored. The artwork of phone, mail, Telegram (send.svg), Facebook, Instagram and WhatsApp comes
 * from the SVG files in assets/img/, inlined here so that it takes the text colour.
 */
function osteza_icon( $name, $size = 24, $stroke = 'currentColor', $width = '1.7', $attrs = '' ) {
	static $icons = array(
		'pin'       => '<path d="M12 21s-7-6.4-7-12a7 7 0 0 1 14 0c0 5.6-7 12-7 12z"/><circle cx="12" cy="9" r="2.5"/>',
		'telegram'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13"/><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L15 22L11 13L2 9L22 2Z"/>',
		'mail'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M22 6L12 13L2 6"/>',
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
		'up'        => '<path d="M12 19V5M6 11l6-6 6 6"/>',
		'phone'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92V19.92C22.0011 20.1985 21.9441 20.4741 21.8325 20.7293C21.7209 20.9845 21.5573 21.2136 21.3521 21.4018C21.1468 21.5901 20.9046 21.7335 20.6407 21.8227C20.3769 21.9119 20.0974 21.945 19.82 21.92C16.7428 21.5856 13.787 20.5341 11.19 18.85C8.77382 17.3146 6.72533 15.2661 5.18999 12.85C3.49997 10.2412 2.44824 7.27097 2.11999 4.17997C2.095 3.90344 2.12787 3.62474 2.21649 3.3616C2.30512 3.09846 2.44756 2.85666 2.63476 2.6516C2.82196 2.44653 3.0498 2.28268 3.30379 2.1705C3.55777 2.05831 3.83233 2.00024 4.10999 1.99997H7.10999C7.5953 1.9952 8.06579 2.16705 8.43376 2.48351C8.80173 2.79996 9.04207 3.23942 9.10999 3.71997C9.23662 4.68004 9.47144 5.6227 9.80999 6.52997C9.94454 6.8879 9.97366 7.27689 9.8939 7.65086C9.81415 8.02482 9.62886 8.36809 9.35999 8.63998L8.08999 9.90997C9.51355 12.4135 11.5864 14.4864 14.09 15.91L15.36 14.64C15.6319 14.3711 15.9751 14.1858 16.3491 14.1061C16.7231 14.0263 17.1121 14.0554 17.47 14.19C18.3773 14.5285 19.3199 14.7634 20.28 14.89C20.7658 14.9585 21.2094 15.2032 21.5265 15.5775C21.8437 15.9518 22.0122 16.4296 22 16.92Z"/>',
		'facebook'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 2H15C13.6739 2 12.4021 2.52678 11.4645 3.46447C10.5268 4.40215 10 5.67392 10 7V10H7V14H10V22H14V14H17L18 10H14V7C14 6.73478 14.1054 6.48043 14.2929 6.29289C14.4804 6.10536 14.7348 6 15 6H18V2Z"/>',
	);
	static $solid = array(
		'instagram' => array( '0 0 24 24', '<path d="M12 2.16094C15.2063 2.16094 15.5859 2.175 16.8469 2.23125C18.0188 2.28281 18.6516 2.47969 19.0734 2.64375C19.6313 2.85938 20.0344 3.12188 20.4516 3.53906C20.8734 3.96094 21.1313 4.35938 21.3469 4.91719C21.5109 5.33906 21.7078 5.97656 21.7594 7.14375C21.8156 8.40937 21.8297 8.78906 21.8297 11.9906C21.8297 15.1969 21.8156 15.5766 21.7594 16.8375C21.7078 18.0094 21.5109 18.6422 21.3469 19.0641C21.1313 19.6219 20.8687 20.025 20.4516 20.4422C20.0297 20.8641 19.6313 21.1219 19.0734 21.3375C18.6516 21.5016 18.0141 21.6984 16.8469 21.75C15.5813 21.8062 15.2016 21.8203 12 21.8203C8.79375 21.8203 8.41406 21.8062 7.15313 21.75C5.98125 21.6984 5.34844 21.5016 4.92656 21.3375C4.36875 21.1219 3.96563 20.8594 3.54844 20.4422C3.12656 20.0203 2.86875 19.6219 2.65313 19.0641C2.48906 18.6422 2.29219 18.0047 2.24063 16.8375C2.18438 15.5719 2.17031 15.1922 2.17031 11.9906C2.17031 8.78438 2.18438 8.40469 2.24063 7.14375C2.29219 5.97187 2.48906 5.33906 2.65313 4.91719C2.86875 4.35938 3.13125 3.95625 3.54844 3.53906C3.97031 3.11719 4.36875 2.85938 4.92656 2.64375C5.34844 2.47969 5.98594 2.28281 7.15313 2.23125C8.41406 2.175 8.79375 2.16094 12 2.16094ZM12 0C8.74219 0 8.33438 0.0140625 7.05469 0.0703125C5.77969 0.126563 4.90313 0.332812 4.14375 0.628125C3.35156 0.9375 2.68125 1.34531 2.01563 2.01562C1.34531 2.68125 0.9375 3.35156 0.628125 4.13906C0.332812 4.90313 0.126563 5.775 0.0703125 7.05C0.0140625 8.33437 0 8.74219 0 12C0 15.2578 0.0140625 15.6656 0.0703125 16.9453C0.126563 18.2203 0.332812 19.0969 0.628125 19.8563C0.9375 20.6484 1.34531 21.3188 2.01563 21.9844C2.68125 22.65 3.35156 23.0625 4.13906 23.3672C4.90313 23.6625 5.775 23.8687 7.05 23.925C8.32969 23.9812 8.7375 23.9953 11.9953 23.9953C15.2531 23.9953 15.6609 23.9812 16.9406 23.925C18.2156 23.8687 19.0922 23.6625 19.8516 23.3672C20.6391 23.0625 21.3094 22.65 21.975 21.9844C22.6406 21.3188 23.0531 20.6484 23.3578 19.8609C23.6531 19.0969 23.8594 18.225 23.9156 16.95C23.9719 15.6703 23.9859 15.2625 23.9859 12.0047C23.9859 8.74688 23.9719 8.33906 23.9156 7.05938C23.8594 5.78438 23.6531 4.90781 23.3578 4.14844C23.0625 3.35156 22.6547 2.68125 21.9844 2.01562C21.3188 1.35 20.6484 0.9375 19.8609 0.632812C19.0969 0.3375 18.225 0.13125 16.95 0.075C15.6656 0.0140625 15.2578 0 12 0Z"/><path d="M12 5.83594C8.59688 5.83594 5.83594 8.59688 5.83594 12C5.83594 15.4031 8.59688 18.1641 12 18.1641C15.4031 18.1641 18.1641 15.4031 18.1641 12C18.1641 8.59688 15.4031 5.83594 12 5.83594ZM12 15.9984C9.79219 15.9984 8.00156 14.2078 8.00156 12C8.00156 9.79219 9.79219 8.00156 12 8.00156C14.2078 8.00156 15.9984 9.79219 15.9984 12C15.9984 14.2078 14.2078 15.9984 12 15.9984Z"/><path d="M19.8469 5.5922C19.8469 6.38908 19.2 7.03127 18.4078 7.03127C17.6109 7.03127 16.9688 6.38439 16.9688 5.5922C16.9688 4.79533 17.6156 4.15314 18.4078 4.15314C19.2 4.15314 19.8469 4.80001 19.8469 5.5922Z"/>' ),
		'whatsapp'  => array( '0 0 510 512.459', '<path fill-rule="evenodd" clip-rule="evenodd" d="M435.689 74.468C387.754 26.471 324 .025 256.071 0 116.098 0 2.18 113.906 2.131 253.916c-.024 44.758 11.677 88.445 33.898 126.946L0 512.459l134.617-35.311c37.087 20.238 78.85 30.891 121.345 30.903h.109c139.949 0 253.88-113.917 253.928-253.928.024-67.855-26.361-131.645-74.31-179.643v-.012zm-179.618 390.7h-.085c-37.868-.011-75.016-10.192-107.428-29.417l-7.707-4.577-79.886 20.953 21.32-77.889-5.017-7.987c-21.125-33.605-32.29-72.447-32.266-112.322.049-116.366 94.729-211.046 211.155-211.046 56.373.025 109.364 22.003 149.214 61.903 39.853 39.888 61.781 92.927 61.757 149.313-.05 116.377-94.728 211.058-211.057 211.058v.011zm115.768-158.067c-6.344-3.178-37.537-18.52-43.358-20.639-5.82-2.119-10.044-3.177-14.27 3.178-4.225 6.357-16.388 20.651-20.09 24.875-3.702 4.238-7.403 4.762-13.747 1.583-6.343-3.178-26.787-9.874-51.029-31.487-18.86-16.827-31.597-37.598-35.297-43.955-3.702-6.355-.39-9.789 2.775-12.943 2.849-2.848 6.344-7.414 9.522-11.116s4.225-6.355 6.343-10.581c2.12-4.238 1.06-7.937-.522-11.117-1.584-3.177-14.271-34.409-19.568-47.108-5.151-12.37-10.385-10.69-14.269-10.897-3.703-.183-7.927-.219-12.164-.219s-11.105 1.582-16.925 7.939c-5.82 6.354-22.209 21.709-22.209 52.927 0 31.22 22.733 61.405 25.911 65.642 3.177 4.237 44.745 68.318 108.389 95.812 15.135 6.538 26.957 10.446 36.175 13.368 15.196 4.834 29.027 4.153 39.96 2.52 12.19-1.825 37.54-15.353 42.824-30.172 5.283-14.818 5.283-27.529 3.701-30.172-1.582-2.641-5.819-4.237-12.163-7.414l.011-.024z"/>' ),
	);

	if ( isset( $solid[ $name ] ) ) {
		return sprintf(
			'<svg width="%1$d" height="%1$d" viewBox="%2$s" fill="%3$s" aria-hidden="true"%4$s>%5$s</svg>',
			$size,
			$solid[ $name ][0],
			esc_attr( $stroke ),
			$attrs ? ' ' . $attrs : '',
			$solid[ $name ][1]
		);
	}

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
 * Link to a section by id: a bare hash when the current page has that section,
 * otherwise the page that does (the contact page for "contacto", else the home page).
 */
function osteza_anchor( $id ) {
	if ( is_front_page() || ( 'contacto' === $id && osteza_has_contact_section() ) ) {
		return '#' . $id;
	}
	if ( 'contacto' === $id && osteza_template_page_url( 'contact' ) ) {
		return osteza_template_page_url( 'contact' ) . '#contacto';
	}
	return esc_url( osteza_home_url() . '#' . $id );
}

function osteza_has_contact_section() {
	return osteza_page_has_block( 'contact' );
}

function osteza_home_url() {
	return function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
}

/**
 * URL, in the current language, of the page that uses page-templates/<name>.php; '' if there is none.
 */
function osteza_template_page_url( $name ) {
	static $urls = array();
	if ( ! isset( $urls[ $name ] ) ) {
		$pages = get_pages(
			array(
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-templates/' . $name . '.php',
				'lang'       => '',
			)
		);
		$id    = $pages ? $pages[0]->ID : 0;
		if ( $id && function_exists( 'pll_get_post' ) ) {
			$id = pll_get_post( $id ) ?: 0;
		}
		$urls[ $name ] = $id ? esc_url( get_permalink( $id ) ) : '';
	}
	return $urls[ $name ];
}

/**
 * Services catalogue, optionally at one of its groups (slug of an osteza_group term).
 */
function osteza_catalog_url( $group = '' ) {
	$url = osteza_template_page_url( 'services' );
	if ( ! $url ) {
		return osteza_anchor( 'servicios' );
	}
	return $url . ( $group ? '#g-' . $group : '' );
}

/**
 * About page, optionally at one of its sections; the home page's team section until that page exists.
 */
function osteza_about_url( $section = '' ) {
	$url = osteza_template_page_url( 'about' );
	if ( ! $url ) {
		return osteza_anchor( 'equipo' );
	}
	return $url . ( $section ? '#' . $section : '' );
}

function osteza_contact_url() {
	return osteza_template_page_url( 'contact' ) ?: osteza_anchor( 'contacto' );
}

/**
 * Service of the current page (see osteza_all_services()), or null.
 */
function osteza_current_service() {
	$post = get_post();
	if ( ! $post || 'page' !== $post->post_type ) {
		return null;
	}
	$id = function_exists( 'pll_get_post' ) ? ( pll_get_post( $post->ID, osteza_default_lang() ) ?: $post->ID ) : $post->ID;
	return osteza_service_by( $id );
}

/**
 * Permalink, in the current language, of the published page with this slug in the
 * default language; "#" while there is no such page.
 */
function osteza_page_url( $slug ) {
	$pages = $slug ? get_posts(
		array(
			'post_type' => 'page',
			'name'      => $slug,
			'lang'      => '',
		)
	) : array();
	$page  = $pages ? $pages[0] : null;
	if ( $page && function_exists( 'pll_get_post' ) ) {
		$translated = pll_get_post( $page->ID );
		$page       = $translated ? get_post( $translated ) : null;
	}
	return $page ? esc_url( get_permalink( $page ) ) : '#';
}

/**
 * @param string|null $key Slug of the service page in the default language.
 */
function osteza_service_url( $key ) {
	return osteza_page_url( $key );
}

/**
 * @param string $slug Key of osteza_zones().
 */
function osteza_zone_url( $slug ) {
	return osteza_page_url( $slug );
}

/**
 * Zones index; the home page's zones section until that page exists.
 */
function osteza_zones_url() {
	return osteza_template_page_url( 'zones' ) ?: osteza_anchor( 'zonas' );
}

/**
 * Contact detail set in Appearance → Customize → OSTEZA. Until it is filled in,
 * the bracketed placeholder from the design is shown.
 */
function osteza_contact( $key ) {
	$value = trim( (string) get_theme_mod( 'osteza_' . $key, '' ) );
	if ( '' !== $value ) {
		// Address and hours are text: translated in Languages → Translations (Polylang).
		return in_array( $key, array( 'address', 'hours' ), true ) && function_exists( 'pll__' ) ? pll__( $value ) : $value;
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
