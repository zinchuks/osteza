<?php
/**
 * Default sections of each kind of page: list of ( block name, attributes ).
 *
 * A page shows its own blocks once it has any. While its content is empty it
 * shows the default sections of its template, which are also the starting
 * content written into new pages (see osteza_seed_page()).
 */

function osteza_layout( $type, $post = null ) {
	switch ( $type ) {
		case 'home':
			return array(
				array( 'hero', array() ),
				array( 'services', array() ),
				array( 'approach', array() ),
				array( 'paths', array() ),
				array( 'steps', array() ),
				array( 'team-teaser', array() ),
				array( 'review-examples', array() ),
				array( 'zones-list', array() ),
				array( 'blog-latest', array() ),
				array( 'faq', array() ),
				array( 'contact', array() ),
			);

		case 'services':
			return osteza_services_layout();

		case 'service':
			return osteza_service_layout( $post ? get_the_title( $post ) : '' );

		case 'zones':
			return array(
				array( 'zones-head', array( 'selected' => osteza_pid( 'altea' ) ) ),
				array( 'zones-grid', array() ),
				array(
					'cta',
					array(
						'title'  => __( '¿Tu localidad no aparece?', 'osteza' ),
						'text'   => __( 'Escríbenos igualmente. Te diremos con claridad si podemos llegar.', 'osteza' ),
						'button' => __( 'Consultar mi zona', 'osteza' ),
					),
				),
			);

		case 'zone':
			return osteza_zone_layout( $post );

		case 'contact':
			return osteza_contact_layout();

		case 'about':
			return osteza_about_layout();
	}
	return array();
}

function osteza_services_layout() {
	$finder = array(
		'montaje'    => array( 'kitchen', __( 'Montar mi cocina o mis muebles', 'osteza' ), __( 'Cocinas por módulos, armarios, camas, TV', 'osteza' ) ),
		'transporte' => array( 'truck', __( 'Mudarme o transportar algo', 'osteza' ), __( 'Mudanzas, muebles, retirada de lo viejo', 'osteza' ) ),
		'acabados'   => array( 'paint', __( 'Mejorar paredes y suelos', 'osteza' ), __( 'Pintura, pladur, suelos, alicatado', 'osteza' ) ),
		'reformas'   => array( 'house', __( 'Renovar mi vivienda', 'osteza' ), __( 'Toda la casa o varias estancias', 'osteza' ) ),
	);
	$looks  = array(
		'montaje'    => array( 'rows', __( 'Cómo montamos una cocina', 'osteza' ), osteza_pid( 'montaje-de-cocinas' ) ),
		'transporte' => array( 'list', __( 'Ver mudanzas y transporte', 'osteza' ), osteza_pid( 'mudanzas-y-transporte' ) ),
		'acabados'   => array( 'cards', '', 0 ),
		'reformas'   => array( 'list', __( 'Hablar de mi reforma', 'osteza' ), 0 ),
	);

	$options = array();
	$groups  = array();
	$n       = 0;
	foreach ( osteza_catalog() as $key => $group ) {
		$look = $looks[ $key ] ?? array( 'rows', '', 0 );
		if ( isset( $finder[ $key ] ) ) {
			$options[] = array(
				'group' => $group['id'],
				'icon'  => $finder[ $key ][0],
				'label' => $finder[ $key ][1],
				'sub'   => $finder[ $key ][2],
			);
		}
		$groups[] = array(
			'catalog-group',
			array(
				'group'  => $group['id'],
				'layout' => $look[0],
				'number' => sprintf( '%02d', ++$n ),
				'button' => $look[1],
				'page'   => $look[2],
			),
		);
	}

	return array_merge(
		array( array( 'catalog-head', array( 'finder' => $options ) ) ),
		$groups,
		array(
			array( 'ask', array() ),
			array( 'contact', array() ),
		)
	);
}

/**
 * Sections of a service page.
 *
 * @param string $name Service name.
 * @param array  $s    Content: title, lead, summary (term => value), includes (title, note,
 *                     items of ( icon, title, text )), yes, no, types, process (title, lead,
 *                     steps), price (lead, factors), faq (title, items), related (cards),
 *                     projects, contact. Missing keys get generic texts.
 */
function osteza_service_layout( $name, $s = array() ) {
	$pairs = function ( $rows, $a, $b ) {
		$out = array();
		foreach ( $rows as $row ) {
			$out[] = array(
				$a => $row[0],
				$b => $row[1],
			);
		}
		return $out;
	};
	$items = function ( $rows ) {
		$out = array();
		foreach ( $rows as $row ) {
			$out[] = array(
				'icon'  => $row[0],
				'title' => $row[1],
				'text'  => $row[2],
			);
		}
		return $out;
	};

	/* translators: %s: service name. */
	$s += array(
		'title'    => sprintf( __( '%s en la <span>Costa Blanca</span>.', 'osteza' ), $name ),
		'lead'     => __( '[Descripción del servicio en dos frases: qué hacemos, para quién y qué recibe el cliente.]', 'osteza' ),
		'summary'  => array(
			__( 'Para quién', 'osteza' )  => __( '[PARA QUIÉN]', 'osteza' ),
			__( 'Qué hacemos', 'osteza' ) => __( '[QUÉ HACEMOS]', 'osteza' ),
			__( 'Presupuesto', 'osteza' ) => __( 'Por escrito, con fotos y medidas o tras una visita', 'osteza' ),
			__( 'Zona', 'osteza' )        => __( '[LOCALIDADES CONFIRMADAS]', 'osteza' ),
		),
		'includes' => array(),
		'yes'      => array(
			__( 'Revisión del trabajo y de las medidas', 'osteza' ),
			__( 'Presupuesto detallado por escrito', 'osteza' ),
			__( 'Protección de la vivienda', 'osteza' ),
			__( 'Limpieza al terminar', 'osteza' ),
			__( 'Revisión final contigo', 'osteza' ),
		),
		'no'       => array(
			__( 'Materiales, si los compras tú', 'osteza' ),
			__( 'Trabajos fuera del alcance acordado', 'osteza' ),
		),
		'types'    => array( __( 'Pisos', 'osteza' ), __( 'Apartamentos', 'osteza' ), __( 'Villas y casas', 'osteza' ), __( 'Viviendas de alquiler', 'osteza' ), __( 'Segundas residencias', 'osteza' ) ),
		'process'  => array(),
		'price'    => array(),
		'faq'      => array(),
		'related'  => array(),
		/* translators: %s: service name. */
		'projects' => sprintf( __( '<strong>Proyectos de %s:</strong> aquí aparecerán trabajos reales con fotos propias en cuanto los publiquemos.', 'osteza' ), mb_strtolower( $name ) ),
		'contact'  => __( 'Cuéntanos qué necesitas.', 'osteza' ),
	);
	$s['includes'] += array(
		/* translators: %s: service name. */
		'title' => sprintf( __( 'Qué incluye %s.', 'osteza' ), mb_strtolower( $name ) ),
		'note'  => __( 'Cada trabajo es distinto. Esto es lo que solemos hacer; el alcance exacto se acuerda contigo.', 'osteza' ),
		'items' => array(
			array( 'wall-check', __( '[Trabajo incluido 1]', 'osteza' ), __( '[Descripción breve]', 'osteza' ) ),
			array( 'tool', __( '[Trabajo incluido 2]', 'osteza' ), __( '[Descripción breve]', 'osteza' ) ),
			array( 'finish', __( '[Trabajo incluido 3]', 'osteza' ), __( '[Descripción breve]', 'osteza' ) ),
		),
	);
	$s['process'] += array(
		/* translators: %s: service name. */
		'title' => __( 'Cómo trabajamos.', 'osteza' ),
		'lead'  => __( 'Sabes qué se va a hacer, cuándo y cuánto cuesta antes de empezar.', 'osteza' ),
		'steps' => array(
			array( __( 'Nos cuentas qué necesitas', 'osteza' ), __( 'Por WhatsApp, Telegram o teléfono, con fotos y medidas si las tienes.', 'osteza' ) ),
			array( __( 'Presupuesto por escrito', 'osteza' ), __( 'Qué incluye, qué no, precio y plazo. Si hace falta, antes visitamos la vivienda.', 'osteza' ) ),
			array( __( 'Fecha', 'osteza' ), __( 'Acordamos el día de inicio y lo que necesitamos encontrar preparado.', 'osteza' ) ),
			array( __( 'Trabajo', 'osteza' ), __( 'Con una persona de contacto que te informa del avance.', 'osteza' ) ),
			array( __( 'Revisión final', 'osteza' ), __( 'Lo revisamos contigo antes de dar el trabajo por terminado.', 'osteza' ) ),
		),
	);
	$s['price'] += array(
		'lead'    => __( 'Depende de cada caso, por eso no publicamos precios cerrados. Con fotos y medidas, o tras una visita, te damos un presupuesto por escrito. [CONFIRMAR: tarifas orientativas]', 'osteza' ),
		'factors' => array(
			array( __( 'Tamaño', 'osteza' ), __( 'Metros, unidades o volumen del trabajo.', 'osteza' ) ),
			array( __( 'Estado actual', 'osteza' ), __( 'Lo que hay que retirar, reparar o preparar antes.', 'osteza' ) ),
			array( __( 'Materiales', 'osteza' ), __( 'Si los ponemos nosotros o los compras tú, y su gama.', 'osteza' ) ),
			array( __( 'Acceso', 'osteza' ), __( 'Planta, ascensor, aparcamiento y normas de la comunidad.', 'osteza' ) ),
		),
	);
	$s['faq'] += array(
		/* translators: %s: service name. */
		'title' => sprintf( __( 'Sobre %s.', 'osteza' ), mb_strtolower( $name ) ),
		'items' => array(
			array( __( '¿Cuánto tarda?', 'osteza' ), __( 'Depende del alcance. Te damos un plazo estimado junto con el presupuesto.', 'osteza' ) ),
			array( __( '¿Quién compra los materiales?', 'osteza' ), __( 'Podemos encargarnos nosotros o puedes elegirlos tú. Lo dejamos claro en el presupuesto. [CONFIRMAR]', 'osteza' ) ),
			array( __( '¿Puedo enviar fotos para un precio?', 'osteza' ), __( 'Sí. Fotos y medidas aproximadas nos ayudan mucho; para algunos trabajos necesitaremos verlo en persona.', 'osteza' ) ),
		),
	);

	$summary = array();
	foreach ( $s['summary'] as $term => $value ) {
		$summary[] = array(
			'term'  => $term,
			'value' => $value,
		);
	}
	$related = array();
	foreach ( $s['related'] as $card ) {
		$related[] = $card + array(
			'page'  => 0,
			'style' => 'rc-w',
			'icon'  => '',
			'cat'   => '',
			'name'  => '',
			'desc'  => '',
		);
	}

	return array(
		array(
			'service-hero',
			array(
				'title'   => $s['title'],
				'lead'    => $s['lead'],
				'summary' => $summary,
			),
		),
		array(
			'includes',
			array(
				'title' => $s['includes']['title'],
				'note'  => $s['includes']['note'],
				'items' => $items( $s['includes']['items'] ),
			),
		),
		array(
			'scope',
			array(
				'yes'   => implode( "\n", $s['yes'] ),
				'no'    => implode( "\n", $s['no'] ),
				'types' => implode( "\n", $s['types'] ),
			),
		),
		array(
			'steps',
			array(
				'layout' => 'timeline',
				'anchor' => 'proceso',
				'kicker' => __( 'Proceso', 'osteza' ),
				'title'  => $s['process']['title'],
				'lead'   => $s['process']['lead'],
				'steps'  => $pairs( $s['process']['steps'], 'title', 'text' ),
			),
		),
		array(
			'price',
			array(
				'lead'    => $s['price']['lead'],
				'factors' => $pairs( $s['price']['factors'], 'title', 'text' ),
			),
		),
		array(
			'faq',
			array(
				'anchor'  => 'preguntas',
				'variant' => 'sfaq',
				'kicker'  => __( 'Preguntas', 'osteza' ),
				'title'   => $s['faq']['title'],
				'lead'    => '',
				'items'   => $pairs( $s['faq']['items'], 'q', 'a' ),
			),
		),
		array(
			'related',
			array(
				'cards' => $related,
				'note'  => $s['projects'],
			),
		),
		array(
			'contact',
			array( 'title' => $s['contact'] ),
		),
	);
}

/**
 * Sections of a zone page, from the town name and the page's "Datos de la zona".
 *
 * @param WP_Post|null $post Zone page.
 * @param array        $own  'near' (zone page ids) and 'municipality' (when the town is not a municipality itself).
 */
function osteza_zone_layout( $post, $own = array() ) {
	$name = $post ? get_the_title( $post ) : '';
	$area = $post ? (string) get_post_meta( $post->ID, 'osteza_z_area', true ) : '';
	$town = $own['municipality'] ?? $name;

	$near = array();
	foreach ( $own['near'] ?? array() as $id ) {
		$near[] = array( 'zone' => $id );
	}
	$links = array();
	foreach ( $own['near'] ?? array() as $id ) {
		$links[] = array(
			'label' => '',
			'page'  => $id,
		);
	}
	$links[] = array(
		'label' => __( 'Todas las zonas', 'osteza' ),
		'page'  => osteza_pid( 'zonas' ),
	);

	/* translators: %s: town name (all the strings of this function). */
	return array(
		array(
			'zone-hero',
			array(
				/* translators: %s: comarca; Alicante is the province. */
				'kicker'       => $area ? sprintf( __( '%s · Alicante', 'osteza' ), $area ) : __( 'Alicante', 'osteza' ),
				'title'        => sprintf( __( 'Montaje de cocinas y muebles <span>en %s.</span>', 'osteza' ), $name ),
				'lead'         => sprintf( __( 'Montamos cocinas y muebles en %s: pisos, apartamentos y villas. También mudanzas y transporte, y cuando hace falta más, reformas y acabados. Una persona de contacto y presupuesto por escrito.', 'osteza' ), $name ),
				'button'       => sprintf( __( 'Solicitar presupuesto en %s', 'osteza' ), $name ),
				'summaryTitle' => sprintf( __( 'En %s', 'osteza' ), $name ),
				'summary'      => array(
					array( 'term' => __( 'Cobertura', 'osteza' ), 'value' => __( '[CONFIRMAR: todo el municipio o zonas concretas]', 'osteza' ) ),
					array( 'term' => __( 'Servicios', 'osteza' ), 'value' => __( 'Montaje de cocinas y muebles, mudanzas, reformas', 'osteza' ) ),
					array( 'term' => __( 'Visitas', 'osteza' ), 'value' => __( '[CONDICIONES DE LA VISITA]', 'osteza' ) ),
				),
				'near'         => $near,
			),
		),
		array(
			'zone-services',
			array(
				'kicker' => sprintf( __( 'Servicios en %s', 'osteza' ), $name ),
				'title'  => sprintf( __( 'Lo que hacemos en %s.', 'osteza' ), $name ),
			),
		),
		array(
			'points',
			array(
				'kicker' => sprintf( __( 'Viviendas en %s', 'osteza' ), $name ),
				'note'   => __( '[Revisar y completar con experiencia local real.]', 'osteza' ),
				'points' => array(
					array( 'title' => __( 'Acceso y aparcamiento', 'osteza' ), 'text' => __( 'En calles estrechas o con acceso limitado, coordinamos el día del montaje con la entrega de los muebles y la retirada de embalajes.', 'osteza' ) ),
					array( 'title' => __( 'Cerca del mar', 'osteza' ), 'text' => __( 'Humedad y salitre: elegimos materiales y acabados que resistan mejor el ambiente costero.', 'osteza' ) ),
					array( 'title' => __( 'Comunidades y urbanizaciones', 'osteza' ), 'text' => __( 'Revisamos horarios y normas de la comunidad antes de empezar.', 'osteza' ) ),
					array( 'title' => __( 'Propietarios que no viven aquí', 'osteza' ), 'text' => __( '[CONFIRMAR: seguimiento a distancia con fotos de avance y gestión de llaves]', 'osteza' ) ),
				),
			),
		),
		array(
			'projects-note',
			array(
				'kicker' => sprintf( __( 'Proyectos en %s', 'osteza' ), $name ),
				'text'   => sprintf( __( 'Aquí publicaremos trabajos reales realizados en %s, con fotos propias, en cuanto los tengamos documentados.', 'osteza' ), $name ),
			),
		),
		array(
			'faq',
			array(
				'variant'    => 'sfaq',
				'kicker'     => __( 'Preguntas', 'osteza' ),
				'title'      => sprintf( __( 'Sobre trabajar en %s.', 'osteza' ), $name ),
				'lead'       => '',
				'items'      => array(
					array( 'q' => sprintf( __( '¿Trabajáis en todo el municipio de %s?', 'osteza' ), $town ), 'a' => __( '[CONFIRMAR: casco urbano, urbanizaciones y zonas cubiertas]', 'osteza' ) ),
					array( 'q' => sprintf( __( '¿Montáis cocinas compradas en tiendas de %s?', 'osteza' ), $name ), 'a' => __( 'Sí. Montamos cocinas y muebles de cualquier tienda o fabricante; si hace falta, también los recogemos y los traemos.', 'osteza' ) ),
					array( 'q' => sprintf( __( '¿Necesito licencia para reformar en %s?', 'osteza' ), $name ), 'a' => sprintf( __( 'Depende del alcance de la obra. Lo revisamos contigo; los requisitos concretos se consultan con el Ayuntamiento de %s. [VERIFICAR]', 'osteza' ), $town ) ),
					array( 'q' => sprintf( __( '¿Podéis hacer trabajos pequeños en %s?', 'osteza' ), $name ), 'a' => __( 'Sí: un armario, una cama o unos pocos módulos de cocina también son trabajo para nosotros. [CONFIRMAR SI HAY UN MÍNIMO]', 'osteza' ) ),
				),
				'linksLabel' => __( 'Zonas cercanas:', 'osteza' ),
				'links'      => $links,
			),
		),
		array(
			'contact',
			array(
				'title' => sprintf( __( 'Tu proyecto en %s.', 'osteza' ), $name ),
				'town'  => $name,
			),
		),
	);
}

function osteza_contact_layout() {
	return array(
		array( 'contact-head', array() ),
		array(
			'contact',
			array(
				'kicker'   => __( 'Formulario', 'osteza' ),
				'title'    => __( 'Envíanos tu solicitud.', 'osteza' ),
				'lead'     => __( 'Rellena cinco datos y elige cómo seguir: preparamos el mensaje y tú decides cuándo enviarlo.', 'osteza' ),
				'channels' => false,
			),
		),
		array(
			'steps',
			array(
				'layout' => 'numbers',
				'kicker' => __( 'Después de escribirnos', 'osteza' ),
				'title'  => __( 'Qué pasa a continuación.', 'osteza' ),
				'lead'   => __( 'Sin compromiso: hasta que no aceptes un presupuesto por escrito, no hay ningún encargo.', 'osteza' ),
				'steps'  => array(
					array( 'title' => __( 'Revisamos tu mensaje', 'osteza' ), 'text' => __( 'Leemos lo que necesitas y, si falta algo, te preguntamos por el canal que hayas elegido.', 'osteza' ) ),
					array( 'title' => __( 'Aclaramos el alcance', 'osteza' ), 'text' => __( 'Con fotos y medidas, o con una visita si el trabajo lo requiere.', 'osteza' ) ),
					array( 'title' => __( 'Recibes el presupuesto', 'osteza' ), 'text' => __( 'Detallado y por escrito. Tú decides si seguimos adelante.', 'osteza' ) ),
				),
			),
		),
		array( 'area', array() ),
		array(
			'faq',
			array(
				'variant' => 'sfaq',
				'kicker'  => __( 'Antes de escribirnos', 'osteza' ),
				'title'   => __( 'Preguntas sobre el contacto.', 'osteza' ),
				'lead'    => '',
				'items'   => array(
					array( 'q' => __( '¿Qué información os envío?', 'osteza' ), 'a' => __( 'Qué quieres hacer, en qué localidad, medidas aproximadas, fotos del espacio y si tienes alguna fecha en mente. Para una cocina, el plano o la lista de módulos de la tienda.', 'osteza' ) ),
					array( 'q' => __( '¿El presupuesto tiene coste?', 'osteza' ), 'a' => __( '[CONFIRMAR: presupuesto y visita gratuitos o con coste, y en qué casos]', 'osteza' ) ),
					array( 'q' => __( '¿En qué idiomas puedo escribiros?', 'osteza' ), 'a' => __( '[CONFIRMAR: idiomas en los que atiende el equipo]', 'osteza' ) ),
					array( 'q' => __( '¿Qué hacéis con mis datos?', 'osteza' ), 'a' => __( 'Los usamos solo para responder a tu solicitud. Tienes todos los detalles en la <a href="{privacidad}">Política de privacidad</a>.', 'osteza' ) ),
				),
			),
		),
	);
}

function osteza_about_layout() {
	return array(
		array( 'about-head', array() ),
		array( 'story', array() ),
		array(
			'steps',
			array(
				'layout' => 'principles',
				'kicker' => __( 'Cómo trabajamos', 'osteza' ),
				'title'  => __( 'Cuatro principios que no negociamos.', 'osteza' ),
				'lead'   => '',
				'steps'  => array(
					array( 'title' => __( 'Claridad', 'osteza' ), 'text' => __( 'Alcance, materiales y plazo por escrito antes de empezar. Si algo cambia, lo hablamos antes de hacerlo.', 'osteza' ) ),
					array( 'title' => __( 'Cuidado', 'osteza' ), 'text' => __( 'Trabajamos en tu casa como si fuera la nuestra: protección, orden y limpieza al terminar cada jornada.', 'osteza' ) ),
					array( 'title' => __( 'Una persona de contacto', 'osteza' ), 'text' => __( 'Siempre sabes a quién preguntar y en qué punto está tu trabajo.', 'osteza' ) ),
					array( 'title' => __( 'Responsabilidad', 'osteza' ), 'text' => __( 'Revisamos el resultado contigo y respondemos de lo que hacemos. [CONDICIONES DE GARANTÍA POR CONFIRMAR]', 'osteza' ) ),
				),
			),
		),
		array( 'team', array() ),
		array( 'promise', array() ),
		array( 'cta', array() ),
	);
}

/**
 * Serialized block markup of a layout, for a page's content.
 */
function osteza_serialize_layout( $layout ) {
	$out = '';
	foreach ( $layout as $section ) {
		$inner = $section[2] ?? '';
		$out  .= serialize_block(
			array(
				'blockName'    => 'osteza/' . $section[0],
				'attrs'        => osteza_full_attrs( $section[0], $section[1] ),
				'innerBlocks'  => array(),
				'innerHTML'    => $inner,
				'innerContent' => $inner ? array( $inner ) : array(),
			)
		) . "\n\n";
	}
	return $out;
}

/**
 * Attributes of a block with every field set, so that the content does not depend
 * on the defaults of the code.
 */
function osteza_full_attrs( $name, $attrs ) {
	$full = array();
	foreach ( osteza_blocks()[ $name ]['fields'] as $key => $field ) {
		$full[ $key ] = $attrs[ $key ] ?? $field['default'];
	}
	return $full;
}
