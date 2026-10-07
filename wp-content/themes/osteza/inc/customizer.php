<?php
/**
 * Business contact details, editable in Appearance → Customize → OSTEZA.
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

add_action(
	'customize_register',
	function ( $wp_customize ) {
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
