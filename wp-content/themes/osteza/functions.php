<?php
/**
 * OSTEZA theme bootstrap.
 */

define( 'OSTEZA_VERSION', wp_get_theme()->get( 'Version' ) );

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/data.php';
require get_template_directory() . '/inc/customizer.php';

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'osteza', get_template_directory() . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$uri = get_template_directory_uri();
		$dir = get_template_directory();

		wp_enqueue_style( 'osteza', $uri . '/assets/css/main.css', array(), filemtime( $dir . '/assets/css/main.css' ) );
		wp_enqueue_script( 'osteza', $uri . '/assets/js/main.js', array(), filemtime( $dir . '/assets/js/main.js' ), array( 'strategy' => 'defer' ) );

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
			)
		);
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
