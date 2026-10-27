<?php
/**
 * Line illustrations on a 48×48 grid, chosen by name in the block and page editors.
 */

function osteza_art_icons() {
	static $icons = null;
	if ( null === $icons ) {
		$paths = array(
			'kitchen'     => array( __( 'Cocina', 'osteza' ), 'M6 10h36v10H6zM6 26h36v14H6zM18 26v14M30 26v14' ),
			'furniture'   => array( __( 'Mueble', 'osteza' ), 'M10 8h22v32H10zM21 8v32M18 22v4M24 22v4M36 30l5-5' ),
			'wardrobe'    => array( __( 'Armario', 'osteza' ), 'M8 6h32v36H8zM24 6v36M20 22v4M28 22v4' ),
			'shelves'     => array( __( 'Estantería y TV', 'osteza' ), 'M6 12h36M6 24h36M10 12v24M38 12v24M16 30h16v6H16z' ),
			'countertop'  => array( __( 'Encimera', 'osteza' ), 'M6 18h36M10 18v22M38 18v22M16 26h16v8H16zM18 10h12' ),
			'tool'        => array( __( 'Llave / ajustes', 'osteza' ), 'M30 8a8 8 0 0 0-9.6 10.4L8 30.8V40h9.2l12.4-12.4A8 8 0 0 0 40 18l-5 5-6-6 5-5' ),
			'truck'       => array( __( 'Camión', 'osteza' ), 'M4 12h24v22H4zM28 18h9l7 8v8H28M10 38a4 4 0 1 0 8 0 4 4 0 1 0-8 0M30 38a4 4 0 1 0 8 0 4 4 0 1 0-8 0' ),
			'box'         => array( __( 'Caja / mercancía', 'osteza' ), 'M6 20h36v20H6zM6 20l6-10h24l6 10M24 20v20' ),
			'lift'        => array( __( 'Carga', 'osteza' ), 'M8 40h32M14 40V22h20v18M20 22V12h8v10M24 6v6' ),
			'trash'       => array( __( 'Retirada', 'osteza' ), 'M10 14h28M18 14V8h12v6M14 14l2 28h16l2-28M21 22v12M27 22v12' ),
			'house'       => array( __( 'Casa', 'osteza' ), 'M7 22 24 9l17 13M11 19v21h26V19M20 40V29h8v11' ),
			'apartment'   => array( __( 'Edificio', 'osteza' ), 'M10 6h28v36H10zM16 12h6M26 12h6M16 20h6M26 20h6M16 28h6M26 28h6M21 42v-6h6v6' ),
			'villa'       => array( __( 'Villa', 'osteza' ), 'M4 24 24 8l20 16M8 21v19h32V21M18 40V30h12v10M34 12v-4h4v7' ),
			'bath'        => array( __( 'Baño', 'osteza' ), 'M12 40V15a6 6 0 0 1 12 0v2M20 17h8M22 23v3M26 23v3M30 23v3M8 40h32' ),
			'paint'       => array( __( 'Pintura', 'osteza' ), 'M10 8h24v10H10zM34 13h4v9H22v6M19 28h6v12h-6z' ),
			'smooth'      => array( __( 'Alisado', 'osteza' ), 'M8 40 40 8M14 40l26-26M8 30 30 8M20 40l20-20' ),
			'ceiling'     => array( __( 'Pladur y techos', 'osteza' ), 'M6 12h36M6 12v6h36v-6M12 18v22M36 18v22M18 30h12' ),
			'floor'       => array( __( 'Suelo', 'osteza' ), 'M6 38h36M10 30h28M14 22h20M18 22l-6 16M30 22l6 16M24 22v16' ),
			'tiles'       => array( __( 'Alicatado', 'osteza' ), 'M8 8h32v32H8zM8 19h32M8 30h32M19 8v32M30 8v32' ),
			'door'        => array( __( 'Puerta', 'osteza' ), 'M12 6h24v36H12zM30 25v.5M8 42h32' ),
		);
		$icons = array();
		foreach ( $paths as $key => $icon ) {
			$icons[ $key ] = array( $icon[0], '<path d="' . $icon[1] . '"/>' );
		}
		$icons += array(
			'office'      => array( __( 'Oficina', 'osteza' ), '<rect x="6" y="10" width="36" height="30" rx="1"/><path d="M6 18h36M14 26h8M26 26h8M14 32h8M26 32h8"/>' ),
			'calendar'    => array( __( 'Calendario / artículo', 'osteza' ), '<rect x="8" y="12" width="32" height="26" rx="2"/><path d="M8 20h32M16 8v8M32 8v8"/>' ),
			'demolition'  => array( __( 'Demolición', 'osteza' ), '<path d="M8 40h32M12 40V20l12-10 12 10v20"/><path d="m18 26 12 8M30 26l-12 8"/>' ),
			'layout'      => array( __( 'Distribución', 'osteza' ), '<rect x="8" y="8" width="32" height="32"/><path d="M8 24h18v16M26 8v8"/>' ),
			'bolt'        => array( __( 'Instalaciones', 'osteza' ), '<path d="M26 6 14 26h10l-2 16 12-20H24z"/>' ),
			'bath-module' => array( __( 'Baño y mueble', 'osteza' ), '<path d="M12 40V15a6 6 0 0 1 12 0v2M20 17h8M8 40h32"/><path d="M30 28h10v12H30z"/>' ),
			'surfaces'    => array( __( 'Suelos y paredes', 'osteza' ), '<path d="M6 38h36M10 30h28M14 22h20M18 22l-6 16M30 22l6 16M24 22v16"/><path d="M14 8h20v8H14z"/>' ),
			'carpentry'   => array( __( 'Carpintería', 'osteza' ), '<rect x="12" y="6" width="24" height="36" rx="1"/><circle cx="30" cy="25" r="1.5" fill="currentColor"/><path d="M8 42h32"/>' ),
			'wall-check'  => array( __( 'Revisión de pared', 'osteza' ), '<path d="M8 40h32M8 40V12h32v28"/><path d="M8 22h32M20 12v10"/>' ),
			'drawers'     => array( __( 'Cajones', 'osteza' ), '<rect x="12" y="6" width="24" height="36" rx="1"/><path d="M20 16h8M20 26h8M20 36h8"/>' ),
			'appliance'   => array( __( 'Electrodoméstico', 'osteza' ), '<rect x="10" y="8" width="28" height="32" rx="2"/><circle cx="24" cy="26" r="7"/><path d="M16 14h4"/>' ),
			'finish'      => array( __( 'Remate / revisión', 'osteza' ), '<path d="M6 38h36"/><path d="M10 30h28v8H10z"/><path d="m18 16 4 4 8-8"/>' ),
			'handshake'   => array( __( 'Acuerdo / intermediación', 'osteza' ), '<path d="M4 18l8-6 6 3 6-4 6 4 6-3 8 6"/><path d="M12 12v14l10 10 4-4M36 12v14l-8 8"/><path d="m18 28 6 6M22 24l6 6"/>' ),
			'team-pick'   => array( __( 'Profesionales a elegir', 'osteza' ), '<circle cx="12" cy="14" r="5"/><circle cx="24" cy="12" r="5"/><circle cx="36" cy="14" r="5"/><path d="M4 34c0-5 4-8 8-8s8 3 8 8M16 32c0-5 4-9 8-9s8 4 8 9M28 34c0-5 4-8 8-8s8 3 8 8"/><path d="m19 42 4 3 7-7"/>' ),
			'clipboard'   => array( __( 'Gestión de obra', 'osteza' ), '<rect x="10" y="8" width="28" height="34" rx="2"/><path d="M18 8V5h12v3M16 18h16M16 26h16M16 34h10"/>' ),
			'home-plan'   => array( __( 'Casa con plano', 'osteza' ), '<path d="M6 22 24 8l18 14"/><path d="M10 19v21h28V19"/><path d="M10 30h12v10M22 24h16"/>' ),
		);
	}
	return $icons;
}

/**
 * Inner SVG markup of a named illustration ('' for an unknown name).
 */
function osteza_art_inner( $name ) {
	$icons = osteza_art_icons();
	return isset( $icons[ $name ] ) ? $icons[ $name ][1] : '';
}

/**
 * A named illustration as a complete SVG.
 */
function osteza_art_icon( $name, $size = 48, $stroke = 'currentColor', $width = '1.6' ) {
	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 48 48" fill="none" stroke="%2$s" stroke-width="%3$s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%4$s</svg>',
		$size,
		esc_attr( $stroke ),
		esc_attr( $width ),
		osteza_art_inner( $name )
	);
}

/**
 * Names of the small UI icons of osteza_icon() that make sense in content.
 */
function osteza_ui_icons() {
	return array(
		'house'     => __( 'Casa', 'osteza' ),
		'doc'       => __( 'Documento', 'osteza' ),
		'doc-check' => __( 'Documento revisado', 'osteza' ),
		'chat'      => __( 'Conversación', 'osteza' ),
		'marker'    => __( 'Ubicación', 'osteza' ),
		'clock'     => __( 'Reloj', 'osteza' ),
		'info'      => __( 'Información', 'osteza' ),
		'phone'     => __( 'Teléfono', 'osteza' ),
		'mail'      => __( 'Email', 'osteza' ),
	);
}
