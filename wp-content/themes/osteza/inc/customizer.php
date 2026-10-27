<?php
/**
 * Business contact details and the texts shared by every page (header menu box,
 * footer), editable in Appearance → Customize → OSTEZA. The texts are also
 * registered with Polylang, so they are translated in Languages → Translations.
 */

function osteza_contact_fields() {
	return array(
		'address'   => array( 'label' => __( 'Dirección', 'osteza' ), 'placeholder' => '[DIRECCIÓN]' ),
		'phone'     => array( 'label' => __( 'Teléfono', 'osteza' ), 'placeholder' => '[TELÉFONO]' ),
		'whatsapp'  => array( 'label' => __( 'Número de WhatsApp (con prefijo)', 'osteza' ), 'placeholder' => '[NÚMERO WHATSAPP]' ),
		'telegram'  => array( 'label' => __( 'Usuario de Telegram', 'osteza' ), 'placeholder' => '[USUARIO]' ),
		'email'     => array( 'label' => __( 'Email', 'osteza' ), 'placeholder' => '[EMAIL]' ),
		'hours'     => array( 'label' => __( 'Horario', 'osteza' ), 'placeholder' => '[HORARIO]' ),
		'instagram' => array( 'label' => __( 'URL de Instagram', 'osteza' ), 'placeholder' => '' ),
		'facebook'  => array( 'label' => __( 'URL de Facebook', 'osteza' ), 'placeholder' => '' ),
		'legal'     => array( 'label' => __( 'Titular legal', 'osteza' ), 'placeholder' => '[TITULAR LEGAL]' ),
	);
}

/**
 * Shared texts: key => ( label, default, multiline ).
 */
function osteza_text_fields() {
	return array(
		'footer_claim' => array( __( 'Pie: frase grande (admite <span>)', 'osteza' ), __( 'Cuidamos tu espacio. <span>Construimos confianza.</span>', 'osteza' ), false ),
		'footer_about' => array( __( 'Pie: texto bajo el logotipo', 'osteza' ), __( 'Montaje de cocinas y muebles en la Costa Blanca, mudanzas y transporte por España y Europa. Y también reformas y acabados, con el mismo cuidado.', 'osteza' ), true ),
		'mega_title'   => array( __( 'Menú de servicios: título del recuadro', 'osteza' ), __( '¿No sabes qué servicio elegir?', 'osteza' ), false ),
		'mega_text'    => array( __( 'Menú de servicios: texto del recuadro', 'osteza' ), __( 'Descríbenos el trabajo y te orientamos.', 'osteza' ), false ),
		'mega_button'  => array( __( 'Menú de servicios: botón del recuadro', 'osteza' ), __( 'Cuéntanos qué necesitas', 'osteza' ), false ),
	);
}

/**
 * Shared text in the current language.
 */
function osteza_text( $key ) {
	$field = osteza_text_fields()[ $key ];
	$value = trim( (string) get_theme_mod( 'osteza_text_' . $key, '' ) );
	if ( '' === $value ) {
		return $field[1];
	}
	return function_exists( 'pll__' ) ? pll__( $value ) : $value;
}

add_action(
	'init',
	function () {
		if ( ! function_exists( 'pll_register_string' ) ) {
			return;
		}
		foreach ( osteza_text_fields() as $key => $field ) {
			$value = trim( (string) get_theme_mod( 'osteza_text_' . $key, '' ) );
			if ( '' !== $value ) {
				pll_register_string( 'osteza_text_' . $key, $value, 'OSTEZA', $field[2] );
			}
		}
		foreach ( array( 'address', 'hours' ) as $key ) {
			$value = trim( (string) get_theme_mod( 'osteza_' . $key, '' ) );
			if ( '' !== $value ) {
				pll_register_string( 'osteza_' . $key, $value, 'OSTEZA' );
			}
		}
	}
);

/**
 * Pages linked at the bottom of the footer: default-language page ids.
 */
function osteza_legal_pages() {
	$ids = array( osteza_pid( 'aviso-legal' ), (int) get_option( 'wp_page_for_privacy_policy' ), osteza_pid( 'politica-de-cookies' ) );
	return array_values(
		array_filter(
			$ids,
			function ( $id ) {
				return $id && 'publish' === get_post_status( $id );
			}
		)
	);
}

add_action(
	'customize_register',
	function ( $wp_customize ) {
		$wp_customize->add_section( 'osteza_texts', array( 'title' => 'OSTEZA — ' . __( 'Textos generales', 'osteza' ) ) );
		foreach ( osteza_text_fields() as $key => $field ) {
			$wp_customize->add_setting( 'osteza_text_' . $key, array( 'sanitize_callback' => 'wp_kses_post' ) );
			$wp_customize->add_control(
				'osteza_text_' . $key,
				array(
					'label'       => $field[0],
					'description' => __( 'Vacío: ', 'osteza' ) . wp_strip_all_tags( $field[1] ),
					'section'     => 'osteza_texts',
					'type'        => $field[2] ? 'textarea' : 'text',
				)
			);
		}

		$wp_customize->add_section( 'osteza_contact', array( 'title' => 'OSTEZA — ' . __( 'Contacto', 'osteza' ) ) );

		foreach ( osteza_contact_fields() as $key => $field ) {
			$wp_customize->add_setting( 'osteza_' . $key, array( 'sanitize_callback' => 'sanitize_text_field' ) );
			$wp_customize->add_control(
				'osteza_' . $key,
				array(
					'label'   => $field['label'],
					'section' => 'osteza_contact',
					'type'    => 'text',
				)
			);
		}
	}
);

// Shortcut in the admin menu straight to those settings.
add_action(
	'admin_menu',
	function () {
		add_menu_page(
			'OSTEZA — ' . __( 'Contacto', 'osteza' ),
			'OSTEZA — ' . __( 'Contacto', 'osteza' ),
			'edit_theme_options',
			'customize.php?autofocus[section]=osteza_contact',
			'',
			'dashicons-phone',
			59
		);
	}
);
