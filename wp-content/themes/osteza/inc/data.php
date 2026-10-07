<?php
/**
 * Home page content lists.
 */

function osteza_services() {
	return array(
		array(
			'cls'  => 'c-g',
			'key'  => 'reformas-integrales',
			'name' => __( 'Reformas integrales', 'osteza' ),
			'desc' => __( 'Pisos, apartamentos y villas, de principio a fin.', 'osteza' ),
			'tags' => array( __( 'Redistribución', 'osteza' ), __( 'Instalaciones', 'osteza' ), __( 'Acabados completos', 'osteza' ) ),
			'icon' => 'M7 22 24 9l17 13M11 19v21h26V19M20 40V29h8v11',
		),
		array(
			'cls'  => 'c-w',
			'name' => __( 'Baños y cocinas', 'osteza' ),
			'desc' => __( 'Renovación completa o parcial, con materiales que elegimos contigo.', 'osteza' ),
			'tags' => array( __( 'Plato de ducha', 'osteza' ), __( 'Alicatado', 'osteza' ), __( 'Muebles y encimeras', 'osteza' ) ),
			'icon' => 'M12 40V15a6 6 0 0 1 12 0v2M20 17h8M22 23v3M26 23v3M30 23v3M8 40h32',
		),
		array(
			'cls'  => 'c-s',
			'name' => __( 'Pintura y acabados', 'osteza' ),
			'desc' => __( 'Paredes y techos impecables, dentro y fuera.', 'osteza' ),
			'tags' => array( __( 'Alisado de paredes', 'osteza' ), __( 'Pintura interior y exterior', 'osteza' ), __( 'Pladur y techos', 'osteza' ) ),
			'icon' => 'M10 8h24v10H10zM34 13h4v9H22v6M19 28h6v12h-6z',
		),
		array(
			'cls'  => 'c-w',
			'name' => __( 'Suelos y revestimientos', 'osteza' ),
			'desc' => __( 'Superficies duraderas para el clima de la costa.', 'osteza' ),
			'tags' => array( __( 'Porcelánico', 'osteza' ), __( 'Tarima y vinílico', 'osteza' ), __( 'Revestimiento de paredes', 'osteza' ) ),
			'icon' => 'M6 38h36M10 30h28M14 22h20M18 22l-6 16M30 22l6 16M24 22v16',
		),
		array(
			'cls'  => 'c-t',
			'name' => __( 'Montaje de muebles', 'osteza' ),
			'desc' => __( 'Armarios, camas y estanterías de cualquier marca.', 'osteza' ),
			'tags' => array( __( 'Armarios y vestidores', 'osteza' ), __( 'Estanterías y TV', 'osteza' ), __( 'Ajustes y nivelado', 'osteza' ) ),
			'icon' => 'M10 8h22v32H10zM21 8v32M18 22v4M24 22v4M36 30l5-5',
		),
		array(
			'cls'  => 'c-g',
			'name' => __( 'Montaje de cocinas', 'osteza' ),
			'desc' => __( 'Muebles de cocina por módulos, bien nivelados y alineados.', 'osteza' ),
			'tags' => array( __( 'Módulos y encimeras', 'osteza' ), __( 'Electrodomésticos integrados', 'osteza' ), __( 'Ajuste final', 'osteza' ) ),
			'icon' => 'M6 10h36v10H6zM6 26h36v14H6zM18 26v14M30 26v14',
		),
	);
}

/**
 * Mega menu columns: heading => ( label => service page slug, null while the page does not exist ).
 */
function osteza_service_menu() {
	return array(
		__( 'Reformas', 'osteza' ) => array(
			__( 'Reformas integrales', 'osteza' )  => 'reformas-integrales',
			__( 'Baños y cocinas', 'osteza' )      => null,
			__( 'Pisos y apartamentos', 'osteza' ) => null,
			__( 'Villas y casas', 'osteza' )       => null,
		),
		__( 'Acabados', 'osteza' ) => array(
			__( 'Pintura y acabados', 'osteza' )      => null,
			__( 'Suelos y revestimientos', 'osteza' ) => null,
			__( 'Pladur y techos', 'osteza' )         => null,
			__( 'Carpintería interior', 'osteza' )    => null,
		),
		__( 'Montaje', 'osteza' )  => array(
			__( 'Montaje de muebles', 'osteza' )         => null,
			__( 'Montaje de cocinas', 'osteza' )         => null,
			__( 'Armarios', 'osteza' )                   => null,
			__( 'Estanterías, cortinas y TV', 'osteza' ) => null,
		),
	);
}

/**
 * Short service list for the mobile menu and footer: label => service page slug.
 */
function osteza_main_services() {
	return array(
		__( 'Reformas integrales', 'osteza' )     => 'reformas-integrales',
		__( 'Baños y cocinas', 'osteza' )         => null,
		__( 'Pintura y acabados', 'osteza' )      => null,
		__( 'Suelos y revestimientos', 'osteza' ) => null,
		__( 'Montaje de muebles', 'osteza' )      => null,
		__( 'Montaje de cocinas', 'osteza' )      => null,
	);
}

/**
 * Primary navigation: label => href.
 */
function osteza_nav() {
	return array(
		__( 'Proyectos', 'osteza' )      => '#',
		__( 'Zonas', 'osteza' )          => osteza_anchor( 'zonas' ),
		__( 'Sobre nosotros', 'osteza' ) => osteza_anchor( 'equipo' ),
		__( 'Blog', 'osteza' )           => osteza_anchor( 'blog' ),
		__( 'Contacto', 'osteza' )       => osteza_anchor( 'contacto' ),
	);
}

// Provisional list: publish only the confirmed towns.
function osteza_towns() {
	return array( 'Alicante', 'Benidorm', 'Altea', 'Calpe', 'Dénia', 'Jávea', 'Torrevieja', 'Orihuela Costa' );
}

function osteza_faq() {
	return array(
		array(
			__( '¿Qué trabajos podéis realizar?', 'osteza' ),
			__( 'Reformas integrales y parciales, baños y cocinas, pintura y acabados, suelos y revestimientos, y montaje de muebles y cocinas. Si tu trabajo no encaja, pregúntanos y te diremos con claridad si podemos hacerlo.', 'osteza' ),
		),
		array(
			__( '¿En qué zonas trabajáis?', 'osteza' ),
			__( 'En [LOCALIDADES CONFIRMADAS] de la Costa Blanca. Si la tuya no aparece, consúltanos.', 'osteza' ),
		),
		array(
			__( '¿Puedo enviar fotos de lo que necesito?', 'osteza' ),
			__( 'Sí. Fotos y medidas aproximadas nos ayudan mucho. Puedes enviarlas por WhatsApp o Telegram después del primer mensaje.', 'osteza' ),
		),
		array(
			__( '¿Es necesario visitar la vivienda?', 'osteza' ),
			__( 'Depende del trabajo. Para una reforma normalmente necesitamos ver el espacio antes de dar un presupuesto final; para trabajos pequeños a veces bastan fotos y medidas.', 'osteza' ),
		),
		array(
			__( '¿Puedo contratar un trabajo pequeño?', 'osteza' ),
			__( 'Sí. Montar un mueble o pintar una habitación también es trabajo para nosotros. [CONFIRMAR SI HAY UN MÍNIMO]', 'osteza' ),
		),
	);
}
