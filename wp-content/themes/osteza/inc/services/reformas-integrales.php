<?php
/**
 * Service: Reformas integrales.
 *
 * Returned to page-templates/service.php through osteza_service(). The file name
 * is the slug of the service page in the default language.
 */

return array(
	'group'        => __( 'Reformas', 'osteza' ),
	'name'         => __( 'Reformas integrales', 'osteza' ),
	'title'        => __( 'Reformas integrales en la <span>Costa Blanca</span>.', 'osteza' ),
	'lead'         => __( 'Renovamos pisos, apartamentos y villas de principio a fin: redistribución, instalaciones y acabados, con una sola persona de contacto y un alcance definido por escrito.', 'osteza' ),
	'summary'      => array(
		__( 'Para quién', 'osteza' )  => __( 'Propietarios de pisos, apartamentos y villas', 'osteza' ),
		__( 'Qué hacemos', 'osteza' ) => __( 'Toda la vivienda o varias estancias a la vez', 'osteza' ),
		__( 'Presupuesto', 'osteza' ) => __( 'Detallado y por escrito, después de ver el espacio', 'osteza' ),
		__( 'Zona', 'osteza' )        => __( '[LOCALIDADES CONFIRMADAS]', 'osteza' ),
	),

	'includes'     => array(
		'title' => __( 'Todo lo que necesita tu vivienda, coordinado.', 'osteza' ),
		'note'  => __( 'Cada reforma es distinta. Estos son los trabajos que solemos combinar; el alcance exacto se define contigo.', 'osteza' ),
		'items' => array(
			array(
				'<path d="M8 40h32M12 40V20l12-10 12 10v20"/><path d="m18 26 12 8M30 26l-12 8"/>',
				__( 'Demolición y preparación', 'osteza' ),
				__( 'Retirada de lo existente, protección de zonas comunes y preparación de superficies.', 'osteza' ),
			),
			array(
				'<rect x="8" y="8" width="32" height="32"/><path d="M8 24h18v16M26 8v8"/>',
				__( 'Redistribución', 'osteza' ),
				__( 'Nuevos espacios con tabiquería y pladur: abrir la cocina, ganar un baño, unir estancias.', 'osteza' ),
			),
			array(
				'<path d="M26 6 14 26h10l-2 16 12-20H24z"/>',
				__( 'Instalaciones', 'osteza' ),
				__( 'Fontanería, electricidad e iluminación renovadas. [CONFIRMAR: equipo propio o profesionales habilitados]', 'osteza' ),
			),
			array(
				'<path d="M12 40V15a6 6 0 0 1 12 0v2M20 17h8M8 40h32"/><path d="M30 28h10v12H30z"/>',
				__( 'Cocina y baños', 'osteza' ),
				__( 'Distribución, alicatado, platos de ducha, muebles, encimeras y montaje final.', 'osteza' ),
			),
			array(
				'<path d="M6 38h36M10 30h28M14 22h20M18 22l-6 16M30 22l6 16M24 22v16"/><path d="M14 8h20v8H14z"/>',
				__( 'Suelos, paredes y pintura', 'osteza' ),
				__( 'Porcelánico, tarima o vinílico, alisado de paredes, techos y pintura.', 'osteza' ),
			),
			array(
				'<rect x="12" y="6" width="24" height="36" rx="1"/><circle cx="30" cy="25" r="1.5" fill="currentColor"/><path d="M8 42h32"/>',
				__( 'Carpintería y acabados', 'osteza' ),
				__( 'Puertas, rodapiés, armarios y los detalles que hacen que todo encaje.', 'osteza' ),
			),
		),
	),

	'scope'        => array(
		'yes'   => array(
			__( 'Visita, medición y definición del alcance', 'osteza' ),
			__( 'Presupuesto detallado por partidas', 'osteza' ),
			__( 'Coordinación de todos los trabajos', 'osteza' ),
			__( 'Protección de la vivienda y zonas comunes', 'osteza' ),
			__( 'Retirada de escombros [CONFIRMAR]', 'osteza' ),
			__( 'Revisión final contigo antes de cerrar', 'osteza' ),
		),
		'maybe' => array(
			__( 'Proyecto técnico, cuando la normativa lo exija', 'osteza' ),
			__( 'Licencias y trámites municipales [CONFIRMAR]', 'osteza' ),
			__( 'Muebles, electrodomésticos y decoración', 'osteza' ),
			__( 'Trabajos fuera de la vivienda o en fachada', 'osteza' ),
		),
		'types' => array(
			__( 'Pisos', 'osteza' ),
			__( 'Apartamentos', 'osteza' ),
			__( 'Villas y casas', 'osteza' ),
			__( 'Viviendas de alquiler', 'osteza' ),
			__( 'Segundas residencias', 'osteza' ),
		),
	),

	'process'      => array(
		'title' => __( 'Cómo es una reforma integral con OSTEZA.', 'osteza' ),
		'lead'  => __( 'Sabes en qué punto está tu obra en cada momento y con quién hablar.', 'osteza' ),
		'steps' => array(
			array( __( 'Primer contacto', 'osteza' ), __( 'Nos cuentas qué quieres cambiar. Con fotos o planos, si los tienes.', 'osteza' ) ),
			array( __( 'Visita y medición', 'osteza' ), __( 'Vemos la vivienda, revisamos instalaciones y aclaramos prioridades. [CONDICIONES DE LA VISITA]', 'osteza' ) ),
			array( __( 'Presupuesto detallado', 'osteza' ), __( 'Partidas, materiales, qué incluye, qué no y plazo estimado.', 'osteza' ) ),
			array( __( 'Planificación', 'osteza' ), __( 'Orden de los trabajos, fechas y elección final de materiales.', 'osteza' ) ),
			array( __( 'Obra y entrega', 'osteza' ), __( 'Avances periódicos y revisión final juntos, estancia por estancia.', 'osteza' ) ),
		),
	),

	'price'        => array(
		'lead'    => __( 'Depende de cada vivienda, por eso no publicamos precios cerrados. Después de la visita recibes un presupuesto por partidas y un plazo estimado.', 'osteza' ),
		'factors' => array(
			array( __( 'Superficie', 'osteza' ), __( 'Metros a reformar y número de estancias.', 'osteza' ) ),
			array( __( 'Estado actual', 'osteza' ), __( 'Lo que hay que retirar, reparar o sanear.', 'osteza' ) ),
			array( __( 'Distribución', 'osteza' ), __( 'Si se mueven tabiques, cocina o baños.', 'osteza' ) ),
			array( __( 'Instalaciones', 'osteza' ), __( 'Renovación parcial o completa.', 'osteza' ) ),
			array( __( 'Materiales', 'osteza' ), __( 'Gama y acabados que elijas.', 'osteza' ) ),
			array( __( 'Acceso y comunidad', 'osteza' ), __( 'Ascensor, horarios y normas del edificio.', 'osteza' ) ),
		),
	),

	'faq'          => array(
		'title' => __( 'Sobre reformas integrales.', 'osteza' ),
		'items' => array(
			array( __( '¿Necesito licencia para una reforma integral?', 'osteza' ), __( 'Depende del municipio y del alcance de la obra. Lo revisamos contigo antes de empezar. [TEXTO A VERIFICAR CON LA NORMATIVA LOCAL]', 'osteza' ) ),
			array( __( '¿Puedo vivir en casa durante la obra?', 'osteza' ), __( 'En una reforma integral normalmente no es recomendable. Si se reforma por fases, a veces es posible; lo planificamos juntos.', 'osteza' ) ),
			array( __( '¿Cuánto dura una reforma integral?', 'osteza' ), __( 'Depende de la superficie y del alcance. Te damos un plazo estimado junto con el presupuesto.', 'osteza' ) ),
			array( __( '¿Quién compra los materiales?', 'osteza' ), __( 'Podemos encargarnos nosotros o puedes elegirlos tú. Lo dejamos claro en el presupuesto. [CONFIRMAR]', 'osteza' ) ),
			array( __( '¿Trabajáis con propietarios que no viven aquí?', 'osteza' ), __( '[CONFIRMAR: seguimiento a distancia, fotos de avance, gestión de llaves]', 'osteza' ) ),
		),
	),

	// Related cards: 'key' is a service page slug; without it the card is an article.
	'related'      => array(
		array(
			'cls'  => 'rc-w',
			'key'  => 'banos-y-cocinas',
			'icon' => '<path d="M12 40V15a6 6 0 0 1 12 0v2M20 17h8M22 23v3M26 23v3M30 23v3M8 40h32"/>',
			'name' => __( 'Baños y cocinas', 'osteza' ),
			'desc' => __( 'Cuando solo quieres renovar una estancia.', 'osteza' ),
		),
		array(
			'cls'  => 'rc-s',
			'key'  => 'pintura-y-acabados',
			'icon' => '<path d="M10 8h24v10H10zM34 13h4v9H22v6M19 28h6v12h-6z"/>',
			'name' => __( 'Pintura y acabados', 'osteza' ),
			'desc' => __( 'Paredes y techos renovados sin obra mayor.', 'osteza' ),
		),
		array(
			'cls'  => 'rc-g',
			'icon' => '<rect x="8" y="12" width="32" height="26" rx="2"/><path d="M8 20h32M16 8v8M32 8v8"/>',
			'cat'  => __( 'Blog · Borrador', 'osteza' ),
			'name' => __( 'Cómo preparar tu vivienda antes de una reforma', 'osteza' ),
		),
	),
	'projects'     => __( '<strong>Proyectos de reformas integrales:</strong> aquí aparecerán trabajos reales con fotos propias en cuanto los publiquemos.', 'osteza' ),

	'contact'      => __( 'Cuéntanos sobre tu reforma.', 'osteza' ),
	// Must match an option of the "Servicio" select in template-parts/contact.php.
	'form_service' => __( 'Reforma integral', 'osteza' ),
);
