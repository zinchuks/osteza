<?php
/**
 * OSTEZA theme bootstrap.
 */

define( 'OSTEZA_VERSION', wp_get_theme()->get( 'Version' ) );

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/icons.php';
require get_template_directory() . '/inc/data.php';
require get_template_directory() . '/inc/review-examples.php';
require get_template_directory() . '/inc/blocks.php';
require get_template_directory() . '/inc/layouts.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/blog.php';
require get_template_directory() . '/inc/projects.php';
require get_template_directory() . '/inc/chat.php';
require get_template_directory() . '/inc/seo.php';

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'osteza', get_template_directory() . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$uri = get_template_directory_uri();
		$dir = get_template_directory();

		// Leaflet is loaded only on the pages that show the zones map.
		$has_map = osteza_page_has_block( 'zones-head' ) || osteza_page_has_block( 'area' );
		if ( $has_map ) {
			wp_enqueue_style( 'leaflet', $uri . '/assets/vendor/leaflet/leaflet.css', array(), '1.9.4' );
			wp_enqueue_script( 'leaflet', $uri . '/assets/vendor/leaflet/leaflet.js', array(), '1.9.4', array( 'strategy' => 'defer' ) );
		}

		wp_enqueue_style( 'osteza', $uri . '/assets/css/main.css', $has_map ? array( 'leaflet' ) : array(), filemtime( $dir . '/assets/css/main.css' ) );
		wp_enqueue_script( 'osteza', $uri . '/assets/js/main.js', $has_map ? array( 'leaflet' ) : array(), filemtime( $dir . '/assets/js/main.js' ), array( 'strategy' => 'defer' ) );

		wp_localize_script(
			'osteza',
			'OSTEZA',
			array(
				'waBase'  => osteza_whatsapp_url(),
				/* translators: message prepared for WhatsApp/Telegram. Keep the {placeholders}. */
				'message' => __( "Hola, OSTEZA. Me llamo {name}.\nServicio: {service}\nLocalidad: {town}\nTeléfono: {phone}\n\n{desc}", 'osteza' ),
				'errors'  => array(
					'name'    => __( 'Escribe tu nombre.', 'osteza' ),
					'phone'   => __( 'Revisa el teléfono: necesitamos al menos 9 dígitos.', 'osteza' ),
					'town'    => __( 'Indica tu localidad.', 'osteza' ),
					'service' => __( 'Elige un servicio.', 'osteza' ),
					'desc'    => __( 'Cuéntanos un poco más (mínimo 10 caracteres).', 'osteza' ),
				),
				'fields'  => array(
					'name'    => __( 'Nombre', 'osteza' ),
					'phone'   => __( 'Teléfono', 'osteza' ),
					'town'    => __( 'Localidad', 'osteza' ),
					'service' => __( 'Servicio', 'osteza' ),
					'desc'    => __( 'Mensaje', 'osteza' ),
				),
				/* translators: {n} is the number of invalid form fields. */
				'review'  => __( 'Revisa {n} campo(s):', 'osteza' ),
				'copy'    => __( 'Copiar mensaje', 'osteza' ),
				'copied'  => __( 'Mensaje copiado ✓', 'osteza' ),
				'linked'  => __( 'Enlace copiado ✓', 'osteza' ),
				'chat'    => osteza_chat_enabled() ? array(
					'api'         => esc_url_raw( rest_url( 'osteza/v1/chat' ) ),
					'lang'        => function_exists( 'pll_current_language' ) ? pll_current_language() : '',
					'greeting'    => __( '¡Hola! Soy el asistente virtual de OSTEZA. Cuéntame qué necesitas: montaje, mudanza, un profesional… y te oriento. Cuando quieras, deja tus datos y el equipo te contacta por WhatsApp, Telegram o teléfono.', 'osteza' ),
					'error'       => __( 'No se ha podido enviar. Inténtalo de nuevo o escríbenos por WhatsApp.', 'osteza' ),
					'submit'      => __( 'Enviar solicitud', 'osteza' ),
					'sending'     => __( 'Enviando…', 'osteza' ),
					'removePhoto' => __( 'Quitar la foto', 'osteza' ),
					'geoWait'     => __( 'Buscando…', 'osteza' ),
					'geoOk'       => __( 'Ubicación añadida ✓', 'osteza' ),
					'geoFail'     => __( 'No se pudo obtener. Escribe la dirección.', 'osteza' ),
				) : null,
			)
		);
	}
);

// Favicons from assets/img/icons, unless a site icon is set in the Customizer.
add_action(
	'wp_head',
	function () {
		if ( has_site_icon() ) {
			return;
		}
		$icons = get_template_directory_uri() . '/assets/img/icons/';
		echo '<link rel="icon" href="' . esc_url( $icons . 'favicon.svg' ) . '" type="image/svg+xml">' . "\n";
		foreach ( array( 16, 32, 48 ) as $size ) {
			echo '<link rel="icon" href="' . esc_url( $icons . "favicon-$size.png" ) . '" sizes="' . $size . 'x' . $size . '" type="image/png">' . "\n";
		}
		echo '<link rel="apple-touch-icon" href="' . esc_url( $icons . 'apple-touch-icon.png' ) . '">' . "\n";
		echo '<link rel="manifest" href="' . esc_url( $icons . 'site.webmanifest' ) . '">' . "\n";
	}
);

// The design ships its own complete stylesheet; core block styles are not used on the front page.
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( is_front_page() ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'global-styles' );
			wp_dequeue_style( 'classic-theme-styles' );
		}
	},
	100
);
