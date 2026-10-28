<?php
/**
 * Page sections as editor blocks ("OSTEZA" category).
 *
 * Every section of the site is a dynamic block: its fields are block attributes,
 * edited in the block sidebar, and its markup is rendered by blocks/<name>.php
 * with the attributes in $a (and inner blocks, for "prose", in $content).
 * The editor script (assets/js/blocks.js) builds the sidebar from the field
 * definitions below, so adding a field here is enough to make it editable.
 *
 * Field types: text, textarea, html (inline HTML: <span> highlights, <strong>, <a>, <br>;
 * the tokens {contacto} and {privacidad} become those links), lines (one item per line),
 * toggle, number, select (with 'options'), art (illustration, inc/icons.php), icon
 * (small UI icon), image (attachment id), page / link / zone (post ids), group (term id),
 * list (repeater with 'fields').
 */

/**
 * Field definition.
 */
function osteza_f( $type, $label, $default = null, $extra = array() ) {
	$defaults = array(
		'toggle' => false,
		'number' => 0,
		'image'  => 0,
		'page'   => 0,
		'link'   => 0,
		'zone'   => 0,
		'group'  => 0,
		'list'   => array(),
	);
	return array(
		'type'    => $type,
		'label'   => $label,
		'default' => $default ?? ( $defaults[ $type ] ?? '' ),
	) + $extra;
}

/**
 * Repeater field.
 */
function osteza_list( $label, $fields, $default = array() ) {
	return osteza_f( 'list', $label, $default, array( 'fields' => $fields ) );
}

/**
 * Id of the default-language page with this slug, 0 if there is none.
 */
function osteza_pid( $slug ) {
	static $ids = array();
	if ( ! isset( $ids[ $slug ] ) ) {
		$pages        = get_posts(
			array(
				'post_type'   => 'page',
				'name'        => $slug,
				'post_status' => array( 'publish', 'draft' ),
				'lang'        => osteza_default_lang(),
				'fields'      => 'ids',
			)
		);
		$ids[ $slug ] = $pages ? (int) $pages[0] : 0;
	}
	return $ids[ $slug ];
}

/**
 * URL in the current language of a page or post id; "#" when there is none.
 */
function osteza_link( $id ) {
	$id = (int) $id;
	if ( ! $id ) {
		return '#';
	}
	if ( function_exists( 'pll_get_post' ) && pll_get_post_language( $id ) ) {
		$id = pll_get_post( $id );
	}
	return $id && 'publish' === get_post_status( $id ) ? esc_url( get_permalink( $id ) ) : '#';
}

/**
 * Inline HTML of an "html" field.
 */
function osteza_html( $text ) {
	if ( '#' === osteza_privacy_url() ) {
		// While the privacy policy is unpublished, drop the sentence that links to it.
		$text = preg_replace( '/\s*[^.!?>]*<a href="\{privacidad\}"[^>]*>.*?<\/a>[^.!?<]*[.!?]?/u', '', (string) $text );
	}
	$text = strtr(
		(string) $text,
		array(
			'{contacto}'   => osteza_anchor( 'contacto' ),
			'{privacidad}' => osteza_privacy_url(),
		)
	);
	return wp_kses(
		$text,
		array(
			'span'   => array( 'class' => true ),
			'strong' => array(),
			'em'     => array(),
			'br'     => array(),
			'a'      => array( 'href' => true, 'target' => true, 'rel' => true ),
		)
	);
}

/**
 * Items of a "lines" field.
 */
function osteza_lines( $text ) {
	return array_values( array_filter( array_map( 'trim', explode( "\n", (string) $text ) ) ) );
}

/**
 * Unique element id for headings of repeated sections.
 */
function osteza_uid( $base ) {
	static $used = array();
	$used[ $base ] = ( $used[ $base ] ?? 0 ) + 1;
	return 1 === $used[ $base ] ? $base : $base . '-' . $used[ $base ];
}

function osteza_privacy_url() {
	$id = (int) get_option( 'wp_page_for_privacy_policy' );
	return $id ? osteza_link( $id ) : '#';
}

/**
 * Breadcrumb trail of the current page: Inicio › ancestors › page.
 */
function osteza_breadcrumb( $extra = array() ) {
	$post  = get_post();
	$items = array( array( __( 'Inicio', 'osteza' ), osteza_home_url() ) );
	if ( $post ) {
		foreach ( array_reverse( get_post_ancestors( $post ) ) as $ancestor ) {
			$items[] = array( get_the_title( $ancestor ), get_permalink( $ancestor ) );
		}
	}
	$items = array_merge( $items, $extra );
	echo '<nav aria-label="' . esc_attr__( 'Migas de pan', 'osteza' ) . '" class="bc"><ol>';
	foreach ( $items as $item ) {
		echo '<li><a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a></li>';
	}
	echo '<li aria-current="page">' . esc_html( $post ? get_the_title( $post ) : '' ) . '</li></ol></nav>';
}

/**
 * Block definitions: name => ( title, icon, help, fields, inner ).
 */
function osteza_blocks() {
	static $blocks = null;
	if ( null !== $blocks ) {
		return $blocks;
	}

	$kicker = function ( $default ) {
		return osteza_f( 'text', __( 'Antetítulo', 'osteza' ), $default );
	};
	$title  = function ( $default, $html = false ) {
		return osteza_f( $html ? 'html' : 'text', __( 'Título', 'osteza' ), $default );
	};
	$lead   = function ( $default, $label = null ) {
		return osteza_f( 'textarea', $label ?? __( 'Introducción', 'osteza' ), $default );
	};
	$anchor = function ( $default ) {
		return osteza_f( 'text', __( 'Ancla (id de la sección)', 'osteza' ), $default );
	};
	$pair   = function ( $a, $b ) {
		return array(
			'title' => osteza_f( 'text', $a ),
			'text'  => osteza_f( 'textarea', $b ),
		);
	};

	$blocks = array(


		'review-examples' => array(
			'title' => __( 'Reseñas de ejemplo', 'osteza' ),
			'icon' => 'format-quote',
			'fields' => array(
				'title' => $title( __( 'Así se verán las opiniones.', 'osteza' ) ),
				'items' => osteza_list(
					__( 'Reseñas de ejemplo', 'osteza' ),
					array( 'service' => osteza_f( 'text', __( 'Servicio', 'osteza' ) ), 'text' => osteza_f( 'textarea', __( 'Texto', 'osteza' ) ) ),
					osteza_review_examples()
				),
			),
		),

		/* Home page. */

		'hero'          => array(
			'title'  => __( 'Portada: cabecera', 'osteza' ),
			'icon'   => 'cover-image',
			'fields' => array(
				'image'      => osteza_f( 'image', __( 'Imagen de fondo', 'osteza' ) ),
				'overlay'    => osteza_f( 'number', __( 'Oscurecer el fondo (0–100 %)', 'osteza' ), 70 ),
				'kicker'     => $kicker( __( 'Tu hogar, en buenas manos', 'osteza' ) ),
				'title'      => $title( __( 'Montaje de cocinas, muebles y mudanzas <span>en la Costa Blanca</span>', 'osteza' ), true ),
				'lead'       => $lead( __( 'Un solo contacto para montar, mudarte o encontrar al profesional adecuado. Del primer mensaje al trabajo terminado.', 'osteza' ) ),
				'button'     => osteza_f( 'text', __( 'Botón principal', 'osteza' ), __( 'Solicitar presupuesto', 'osteza' ) ),
				'button2'    => osteza_f( 'text', __( 'Botón de WhatsApp', 'osteza' ), __( 'Hablar por WhatsApp', 'osteza' ) ),
				'langs'      => osteza_f( 'text', __( 'Texto delante de los idiomas del sitio (vacío: no se muestran)', 'osteza' ), __( 'Te atendemos en', 'osteza' ) ),
				'quickTitle' => osteza_f( 'text', __( 'Título de los accesos rápidos', 'osteza' ), __( '¿Qué necesitas hoy?', 'osteza' ) ),
				'quick'      => osteza_list(
					__( 'Accesos rápidos', 'osteza' ),
					array(
						'label' => osteza_f( 'text', __( 'Texto', 'osteza' ) ),
						'page'  => osteza_f( 'page', __( 'Página', 'osteza' ) ),
					),
					array(
						array( 'label' => __( 'Montar mi cocina', 'osteza' ), 'page' => osteza_pid( 'montaje-de-cocinas' ) ),
						array( 'label' => __( 'Montar muebles o armarios', 'osteza' ), 'page' => osteza_pid( 'montaje-de-muebles' ) ),
						array( 'label' => __( 'Mudanza o transporte', 'osteza' ), 'page' => osteza_pid( 'mudanzas-y-transporte' ) ),
						array( 'label' => __( 'Encontrar un profesional', 'osteza' ), 'page' => osteza_pid( 'buscamos-tu-profesional' ) ),
						array( 'label' => __( 'Reforma o acabados', 'osteza' ), 'page' => osteza_pid( 'reformas-integrales' ) ),
					)
				),
				'quickOther' => osteza_f( 'text', __( 'Último acceso (al formulario)', 'osteza' ), __( 'Otro trabajo: cuéntanoslo', 'osteza' ) ),
				'facts'      => osteza_list(
					__( 'Ventajas', 'osteza' ),
					array(
						'icon'  => osteza_f( 'icon', __( 'Icono', 'osteza' ) ),
						'title' => osteza_f( 'text', __( 'Título', 'osteza' ) ),
						'text'  => osteza_f( 'text', __( 'Texto', 'osteza' ) ),
					),
					array(
						array( 'icon' => 'house', 'title' => __( 'Cualquier marca', 'osteza' ), 'text' => __( 'IKEA, Leroy Merlin o fabricante: montamos tu cocina.', 'osteza' ) ),
						array( 'icon' => 'doc', 'title' => __( 'Precio cerrado por escrito', 'osteza' ), 'text' => __( 'Con el plano y unas fotos sabes el precio antes.', 'osteza' ) ),
						array( 'icon' => 'chat', 'title' => __( 'Un solo contacto', 'osteza' ), 'text' => __( 'Te informamos durante todo el trabajo.', 'osteza' ) ),
					)
				),
			),
		),

		'services'      => array(
			'title'  => __( 'Carrusel de servicios', 'osteza' ),
			'icon'   => 'screenoptions',
			'help'   => __( 'Las tarjetas son las páginas de servicio con «Mostrar en la portada» marcado, en su orden.', 'osteza' ),
			'fields' => array(
				'kicker'    => $kicker( __( 'Servicios', 'osteza' ) ),
				'title'     => $title( __( 'Primero, montaje. Y todo lo que lo rodea.', 'osteza' ) ),
				'linkLabel' => osteza_f( 'text', __( 'Enlace al catálogo', 'osteza' ), __( 'Ver todos los servicios', 'osteza' ) ),
			),
		),

		'approach'      => array(
			'title'  => __( 'Forma de trabajar', 'osteza' ),
			'icon'   => 'list-view',
			'fields' => array(
				'kicker'    => $kicker( __( 'Nuestra forma de trabajar', 'osteza' ) ),
				'title'     => $title( __( 'Primero escuchamos. Después, todo por escrito.', 'osteza' ) ),
				'lead'      => $lead( __( 'Un trabajo sale bien cuando todos saben qué se va a hacer, cómo y cuándo. Por eso dedicamos tiempo al principio: entender bien el trabajo evita sorpresas al final.', 'osteza' ) ),
				'linkLabel' => osteza_f( 'text', __( 'Texto del enlace', 'osteza' ), __( 'Cómo trabajamos', 'osteza' ) ),
				'linkPage'  => osteza_f( 'page', __( 'Página del enlace', 'osteza' ), osteza_pid( 'como-trabajamos' ) ),
				'steps'     => osteza_list(
					__( 'Puntos', 'osteza' ),
					array( 'icon' => osteza_f( 'icon', __( 'Icono', 'osteza' ) ) ) + $pair( __( 'Título', 'osteza' ), __( 'Texto', 'osteza' ) ),
					array(
						array( 'icon' => 'marker', 'title' => __( 'Entender el trabajo', 'osteza' ), 'text' => __( 'Escuchamos qué necesitas y, si hace falta, vemos el espacio.', 'osteza' ) ),
						array( 'icon' => 'doc-check', 'title' => __( 'Definir el alcance', 'osteza' ), 'text' => __( 'Qué incluye, qué no y con qué materiales. Por escrito.', 'osteza' ) ),
						array( 'icon' => 'chat', 'title' => __( 'Comunicación clara', 'osteza' ), 'text' => __( 'Una persona de contacto que te informa de cada avance.', 'osteza' ) ),
					)
				),
			),
		),

		'paths'         => array(
			'title'  => __( 'Dos caminos', 'osteza' ),
			'icon'   => 'columns',
			'fields' => array(
				'kicker' => $kicker( __( '¿Por dónde empezamos?', 'osteza' ) ),
				'title'  => $title( __( 'Grande o pequeño, cada trabajo cuenta.', 'osteza' ) ),
				'cards'  => osteza_list(
					__( 'Tarjetas', 'osteza' ),
					array(
						'style'  => osteza_f( 'select', __( 'Estilo', 'osteza' ), 'dark', array( 'options' => array( 'dark' => __( 'Oscura', 'osteza' ), 'light' => __( 'Clara', 'osteza' ) ) ) ),
						'icon'   => osteza_f( 'art', __( 'Ilustración', 'osteza' ) ),
						'title'  => osteza_f( 'text', __( 'Título', 'osteza' ) ),
						'text'   => osteza_f( 'textarea', __( 'Texto', 'osteza' ) ),
						'tags'   => osteza_f( 'lines', __( 'Puntos (uno por línea)', 'osteza' ) ),
						'button' => osteza_f( 'text', __( 'Botón', 'osteza' ) ),
						'page'   => osteza_f( 'link', __( 'Destino del botón (vacío: formulario)', 'osteza' ) ),
					),
					array(
						array(
							'style'  => 'dark',
							'icon'   => 'kitchen',
							'title'  => __( 'He comprado una cocina o muebles', 'osteza' ),
							'text'   => __( 'Nos envías el plano y unas fotos, y te damos precio y fecha de montaje.', 'osteza' ),
							'tags'   => implode( "\n", array( __( 'Cocinas por módulos de cualquier marca', 'osteza' ), __( 'Encimeras, fregadero y electrodomésticos integrados', 'osteza' ), __( 'Armarios, camas, estanterías y TV', 'osteza' ) ) ),
							'button' => __( 'Pedir precio de montaje', 'osteza' ),
							'page'   => 0,
						),
						array(
							'style'  => 'light',
							'icon'   => 'home-plan',
							'title'  => __( 'Quiero renovar mi vivienda', 'osteza' ),
							'text'   => __( 'Reformas parciales o integrales de pisos, apartamentos y villas.', 'osteza' ),
							'tags'   => implode( "\n", array( __( 'Preparar la cocina antes de montarla', 'osteza' ), __( 'Baños, pintura y suelos', 'osteza' ), __( 'Un alcance definido desde el principio', 'osteza' ) ) ),
							'button' => __( 'Hablar de mi reforma', 'osteza' ),
							'page'   => 0,
						),
					)
				),
			),
		),

		'steps'         => array(
			'title'  => __( 'Pasos / principios', 'osteza' ),
			'icon'   => 'editor-ol',
			'fields' => array(
				'layout' => osteza_f(
					'select',
					__( 'Diseño', 'osteza' ),
					'cards',
					array(
						'options' => array(
							'cards'      => __( 'Tarjetas (portada)', 'osteza' ),
							'timeline'   => __( 'Línea de tiempo oscura (servicio)', 'osteza' ),
							'numbers'    => __( 'Números (contacto)', 'osteza' ),
							'principles' => __( 'Principios oscuros (sobre nosotros)', 'osteza' ),
						),
					)
				),
				'anchor' => $anchor( '' ),
				'kicker' => $kicker( __( 'Cómo trabajamos', 'osteza' ) ),
				'title'  => $title( __( 'Cuatro pasos. Sin sorpresas.', 'osteza' ) ),
				'lead'   => $lead( __( 'Para algunos trabajos necesitaremos visitar la vivienda o pedirte fotos y medidas antes de darte un presupuesto final.', 'osteza' ), __( 'Nota', 'osteza' ) ),
				'steps'  => osteza_list(
					__( 'Pasos', 'osteza' ),
					$pair( __( 'Título', 'osteza' ), __( 'Texto', 'osteza' ) ),
					array(
						array( 'title' => __( 'Cuéntanos tu idea', 'osteza' ), 'text' => __( 'Por el formulario, WhatsApp, Telegram o teléfono. Con fotos, si las tienes.', 'osteza' ) ),
						array( 'title' => __( 'Definimos el alcance', 'osteza' ), 'text' => __( 'Aclaramos contigo qué hay que hacer y, si hace falta, visitamos la vivienda.', 'osteza' ) ),
						array( 'title' => __( 'Preparamos el presupuesto', 'osteza' ), 'text' => __( 'Detallado y por escrito, para que sepas exactamente qué incluye.', 'osteza' ) ),
						array( 'title' => __( 'Realizamos el trabajo', 'osteza' ), 'text' => __( 'Con una persona de contacto y una revisión final contigo.', 'osteza' ) ),
					)
				),
			),
		),

		'team-teaser'   => array(
			'title'  => __( 'Equipo (resumen)', 'osteza' ),
			'icon'   => 'groups',
			'fields' => array(
				'kicker'    => $kicker( __( 'Sobre nosotros', 'osteza' ) ),
				'title'     => $title( __( 'Las personas detrás de OSTEZA.', 'osteza' ) ),
				'lead'      => $lead( __( '[Presentación real del equipo: quiénes sois y por qué empezasteis OSTEZA. Dos o tres frases, en primera persona.]', 'osteza' ) ),
				'points'    => osteza_list(
					__( 'Puntos', 'osteza' ),
					$pair( __( 'Título', 'osteza' ), __( 'Texto', 'osteza' ) ),
					array(
						array( 'title' => __( 'Oficio en obra', 'osteza' ), 'text' => __( 'Experiencia práctica en montaje de cocinas y muebles, reformas y acabados.', 'osteza' ) ),
						array( 'title' => __( 'Coordinación cercana', 'osteza' ), 'text' => __( 'Alguien que organiza el trabajo y te mantiene informado.', 'osteza' ) ),
					)
				),
				'linkLabel' => osteza_f( 'text', __( 'Texto del enlace', 'osteza' ), __( 'Conocer al equipo', 'osteza' ) ),
				'image'     => osteza_f( 'image', __( 'Foto del equipo', 'osteza' ) ),
				'caption'   => osteza_f( 'text', __( 'Texto sin foto', 'osteza' ), __( 'Espacio reservado para una fotografía real del equipo de OSTEZA.', 'osteza' ) ),
			),
		),

		'zones-list'    => array(
			'title'  => __( 'Zonas (lista)', 'osteza' ),
			'icon'   => 'location',
			'help'   => __( 'Las localidades son las páginas con la plantilla «Zona».', 'osteza' ),
			'fields' => array(
				'kicker' => $kicker( __( 'Zonas', 'osteza' ) ),
				'title'  => $title( __( 'Trabajamos en la Costa Blanca.', 'osteza' ) ),
				'lead'   => $lead( __( 'Si tu localidad no aparece, pregúntanos.', 'osteza' ) ),
				'button' => osteza_f( 'text', __( 'Botón', 'osteza' ), __( 'Consultar disponibilidad en mi zona', 'osteza' ) ),
				'note'   => osteza_f( 'text', __( 'Nota', 'osteza' ), __( '[Lista provisional: publicar solo las localidades confirmadas]', 'osteza' ) ),
			),
		),

		'blog-latest'   => array(
			'title'  => __( 'Últimos artículos', 'osteza' ),
			'icon'   => 'admin-post',
			'help'   => __( 'El artículo destacado (fijado) va primero; después, los más recientes.', 'osteza' ),
			'fields' => array(
				'kicker'    => $kicker( __( 'Blog', 'osteza' ) ),
				'title'     => $title( __( 'Ideas y consejos para tu hogar.', 'osteza' ) ),
				'linkLabel' => osteza_f( 'text', __( 'Enlace al blog', 'osteza' ), __( 'Ir al blog', 'osteza' ) ),
				'count'     => osteza_f( 'number', __( 'Número de artículos', 'osteza' ), 3 ),
			),
		),

		/* Shared sections. */

		'faq'           => array(
			'title'  => __( 'Preguntas frecuentes', 'osteza' ),
			'icon'   => 'editor-help',
			'fields' => array(
				'anchor'     => $anchor( '' ),
				'variant'    => osteza_f( 'select', __( 'Fondo', 'osteza' ), '', array( 'options' => array( '' => __( 'Crema', 'osteza' ), 'sfaq' => __( 'Blanco', 'osteza' ) ) ) ),
				'kicker'     => $kicker( __( 'Preguntas frecuentes', 'osteza' ) ),
				'title'      => $title( __( 'Lo que suelen preguntarnos.', 'osteza' ) ),
				'lead'       => osteza_f( 'html', __( 'Texto bajo el título', 'osteza' ), __( '¿No encuentras tu respuesta? <a href="{contacto}">Escríbenos</a>.', 'osteza' ) ),
				'items'      => osteza_list(
					__( 'Preguntas', 'osteza' ),
					array(
						'q' => osteza_f( 'text', __( 'Pregunta', 'osteza' ) ),
						'a' => osteza_f( 'html', __( 'Respuesta', 'osteza' ) ),
					),
					array(
						array( 'q' => __( '¿Montáis cocinas de cualquier marca?', 'osteza' ), 'a' => __( 'Sí: cocinas de IKEA, Leroy Merlin y otras tiendas o de fabricante, por módulos. Puedes comprarla tú y nosotros la montamos. [CONFIRMAR MARCAS Y CASOS QUE NO ACEPTÁIS]', 'osteza' ) ),
						array( 'q' => __( '¿Qué incluye el montaje de una cocina?', 'osteza' ), 'a' => __( 'Módulos altos y bajos, frentes, tiradores, zócalos y el ajuste final de puertas y cajones. Encimeras, fregadero y electrodomésticos integrados se acuerdan según tu cocina. [CONFIRMAR QUÉ CONEXIONES HACÉIS]', 'osteza' ) ),
						array( 'q' => __( '¿Puedo enviar fotos o el plano de la cocina?', 'osteza' ), 'a' => __( 'Sí. El plano de la tienda, la lista de módulos y unas fotos del espacio nos bastan para darte un precio. Puedes enviarlos por WhatsApp o Telegram.', 'osteza' ) ),
						array( 'q' => __( '¿Montáis también otros muebles?', 'osteza' ), 'a' => __( 'Sí: armarios, vestidores, camas, cómodas, estanterías y soportes de TV, de cualquier marca. Un solo mueble también es trabajo para nosotros. [CONFIRMAR SI HAY UN MÍNIMO]', 'osteza' ) ),
						array( 'q' => __( '¿Podéis traer los muebles y montarlos?', 'osteza' ), 'a' => __( 'Sí. Recogemos los muebles o la cocina en la tienda o en tu casa anterior, los transportamos y los montamos. También hacemos mudanzas completas y retiramos los muebles y electrodomésticos viejos, desde Benidorm a toda España y Europa.', 'osteza' ) ),
						array( 'q' => __( '¿Hacéis también reformas?', 'osteza' ), 'a' => __( 'Sí. Además del montaje hacemos reformas integrales y parciales, baños y cocinas, pintura y suelos, por ejemplo para preparar el espacio antes de montar la cocina nueva.', 'osteza' ) ),
						array( 'q' => __( '¿En qué zonas trabajáis?', 'osteza' ), 'a' => __( 'En [LOCALIDADES CONFIRMADAS] de la Costa Blanca. Si la tuya no aparece, consúltanos.', 'osteza' ) ),
					)
				),
				'linksLabel' => osteza_f( 'text', __( 'Título de los enlaces', 'osteza' ), '' ),
				'links'      => osteza_list(
					__( 'Enlaces bajo el título', 'osteza' ),
					array(
						'label' => osteza_f( 'text', __( 'Texto (vacío: título de la página)', 'osteza' ) ),
						'page'  => osteza_f( 'page', __( 'Página', 'osteza' ) ),
					)
				),
			),
		),

		'contact'       => array(
			'title'  => __( 'Formulario de contacto', 'osteza' ),
			'icon'   => 'email',
			'help'   => __( 'Los datos de contacto se editan en Apariencia → Personalizar → OSTEZA — Contacto. Las opciones del campo «Servicio» son los servicios de la portada.', 'osteza' ),
			'fields' => array(
				'kicker'   => $kicker( __( 'Contacto', 'osteza' ) ),
				'title'    => $title( __( 'Cuéntanos qué necesitas.', 'osteza' ) ),
				'lead'     => $lead( __( 'Rellena el formulario o escríbenos directamente. Te respondemos por el canal que prefieras.', 'osteza' ) ),
				'channels' => osteza_f( 'toggle', __( 'Mostrar los datos de contacto', 'osteza' ), true ),
				'service'  => osteza_f( 'text', __( 'Servicio preseleccionado (vacío: el de la página)', 'osteza' ) ),
				'town'     => osteza_f( 'text', __( 'Localidad prerrellenada', 'osteza' ) ),
			),
		),

		'cta'           => array(
			'title'  => __( 'Llamada a la acción', 'osteza' ),
			'icon'   => 'megaphone',
			'fields' => array(
				'title'  => $title( __( '¿Hablamos de tu proyecto?', 'osteza' ) ),
				'text'   => osteza_f( 'textarea', __( 'Texto', 'osteza' ), __( 'Trabajamos en [LOCALIDADES CONFIRMADAS] de la Costa Blanca.', 'osteza' ) ),
				'button' => osteza_f( 'text', __( 'Botón', 'osteza' ), __( 'Solicitar presupuesto', 'osteza' ) ),
			),
		),

		'ask'           => array(
			'title'  => __( 'Recuadro «¿No encuentras…?»', 'osteza' ),
			'icon'   => 'format-chat',
			'fields' => array(
				'title'   => $title( __( '¿No encuentras lo que buscas?', 'osteza' ) ),
				'text'    => osteza_f( 'textarea', __( 'Texto', 'osteza' ), __( 'Cuéntanos el trabajo. Si no es algo que hagamos bien, te lo diremos con claridad.', 'osteza' ) ),
				'button'  => osteza_f( 'text', __( 'Botón principal', 'osteza' ), __( 'Contar mi caso', 'osteza' ) ),
				'button2' => osteza_f( 'text', __( 'Botón secundario', 'osteza' ), __( 'Hablar por WhatsApp', 'osteza' ) ),
			),
		),

		'page-head'     => array(
			'title'  => __( 'Cabecera de página', 'osteza' ),
			'icon'   => 'heading',
			'fields' => array(
				'kicker'  => $kicker( '' ),
				'title'   => $title( '', true ),
				'lead'    => $lead( '' ),
				'buttons' => osteza_f( 'toggle', __( 'Mostrar botones de contacto', 'osteza' ), false ),
			),
		),

		'prose'         => array(
			'title'  => __( 'Texto libre', 'osteza' ),
			'icon'   => 'text',
			'help'   => __( 'Sección con párrafos, títulos y listas normales de WordPress dentro.', 'osteza' ),
			'inner'  => true,
			'fields' => array(
				'kicker' => $kicker( '' ),
				'title'  => $title( '' ),
			),
		),

		/* Services catalogue. */

		'catalog-head'  => array(
			'title'  => __( 'Catálogo: cabecera y buscador', 'osteza' ),
			'icon'   => 'search',
			'fields' => array(
				'kicker'      => $kicker( __( 'Servicios', 'osteza' ) ),
				'title'       => $title( __( 'Montamos tu cocina <span>y todo lo demás.</span>', 'osteza' ), true ),
				'lead'        => $lead( __( 'Montaje de cocinas y muebles en la Costa Blanca, mudanzas y transporte, y también reformas y acabados cuando hacen falta. Con el mismo cuidado y una persona de contacto.', 'osteza' ) ),
				'placeholder' => osteza_f( 'text', __( 'Texto del buscador', 'osteza' ), __( 'Busca un servicio: cocina, armario, mudanza…', 'osteza' ) ),
				'finderTitle' => osteza_f( 'text', __( 'Título del selector', 'osteza' ), __( '¿Qué quieres hacer?', 'osteza' ) ),
				'finder'      => osteza_list(
					__( 'Opciones del selector', 'osteza' ),
					array(
						'group' => osteza_f( 'group', __( 'Grupo', 'osteza' ) ),
						'icon'  => osteza_f( 'art', __( 'Icono', 'osteza' ) ),
						'label' => osteza_f( 'text', __( 'Texto', 'osteza' ) ),
						'sub'   => osteza_f( 'text', __( 'Subtexto', 'osteza' ) ),
					)
				),
				'noneText'    => osteza_f( 'text', __( 'Sin resultados: texto', 'osteza' ), __( 'Cuéntanos qué necesitas y te diremos con claridad si podemos hacerlo.', 'osteza' ) ),
				'noneButton'  => osteza_f( 'text', __( 'Sin resultados: botón', 'osteza' ), __( 'Preguntar por mi trabajo', 'osteza' ) ),
			),
		),

		'catalog-group' => array(
			'title'  => __( 'Catálogo: grupo de servicios', 'osteza' ),
			'icon'   => 'grid-view',
			'help'   => __( 'Muestra los servicios del grupo elegido. El título y la introducción son los del grupo (Páginas → Grupos de servicios).', 'osteza' ),
			'fields' => array(
				'group'  => osteza_f( 'group', __( 'Grupo', 'osteza' ) ),
				'layout' => osteza_f(
					'select',
					__( 'Diseño', 'osteza' ),
					'rows',
					array(
						'options' => array(
							'rows'  => __( 'Filas', 'osteza' ),
							'cards' => __( 'Tarjetas oscuras', 'osteza' ),
							'list'  => __( 'Lista con botón', 'osteza' ),
						),
					)
				),
				'number' => osteza_f( 'text', __( 'Número', 'osteza' ), '01' ),
				'button' => osteza_f( 'text', __( 'Texto del enlace o botón', 'osteza' ), '' ),
				'page'   => osteza_f( 'link', __( 'Destino (vacío: formulario)', 'osteza' ) ),
			),
		),

		/* Service page. */

		'service-hero'  => array(
			'title'  => __( 'Servicio: cabecera', 'osteza' ),
			'icon'   => 'cover-image',
			'help'   => __( 'El antetítulo y la miga de pan muestran el grupo del servicio (caja «Tarjeta del servicio»).', 'osteza' ),
			'fields' => array(
				'title'        => $title( '', true ),
				'lead'         => $lead( '' ),
				'button'       => osteza_f( 'text', __( 'Botón principal', 'osteza' ), __( 'Solicitar presupuesto', 'osteza' ) ),
				'button2'      => osteza_f( 'text', __( 'Botón de WhatsApp', 'osteza' ), __( 'Hablar por WhatsApp', 'osteza' ) ),
				'summaryTitle' => osteza_f( 'text', __( 'Título del resumen', 'osteza' ), __( 'En resumen', 'osteza' ) ),
				'summary'      => osteza_list(
					__( 'Resumen', 'osteza' ),
					array(
						'term'  => osteza_f( 'text', __( 'Concepto', 'osteza' ) ),
						'value' => osteza_f( 'text', __( 'Valor', 'osteza' ) ),
					)
				),
				'summaryLink'  => osteza_f( 'text', __( 'Enlace del resumen', 'osteza' ), __( 'Consultar mi caso', 'osteza' ) ),
				'anchors'      => osteza_list(
					__( 'Índice de la página', 'osteza' ),
					array(
						'label' => osteza_f( 'text', __( 'Texto', 'osteza' ) ),
						'id'    => osteza_f( 'text', __( 'Ancla de la sección', 'osteza' ) ),
					),
					array(
						array( 'label' => __( 'Qué incluye', 'osteza' ), 'id' => 'incluye' ),
						array( 'label' => __( 'Incluido y no incluido', 'osteza' ), 'id' => 'alcance' ),
						array( 'label' => __( 'Proceso', 'osteza' ), 'id' => 'proceso' ),
						array( 'label' => __( 'Precio y plazo', 'osteza' ), 'id' => 'precio' ),
						array( 'label' => __( 'Preguntas', 'osteza' ), 'id' => 'preguntas' ),
						array( 'label' => __( 'Contacto', 'osteza' ), 'id' => 'contacto' ),
					)
				),
			),
		),

		'includes'      => array(
			'title'  => __( 'Qué incluye', 'osteza' ),
			'icon'   => 'grid-view',
			'fields' => array(
				'anchor' => $anchor( 'incluye' ),
				'kicker' => $kicker( __( 'Qué incluye', 'osteza' ) ),
				'title'  => $title( '' ),
				'note'   => $lead( '', __( 'Nota', 'osteza' ) ),
				'items'  => osteza_list(
					__( 'Elementos', 'osteza' ),
					array( 'icon' => osteza_f( 'art', __( 'Icono', 'osteza' ) ) ) + $pair( __( 'Título', 'osteza' ), __( 'Texto', 'osteza' ) )
				),
			),
		),

		'scope'         => array(
			'title'  => __( 'Incluido / no incluido', 'osteza' ),
			'icon'   => 'yes-alt',
			'fields' => array(
				'anchor'     => $anchor( 'alcance' ),
				'yesTitle'   => osteza_f( 'text', __( 'Título de lo incluido', 'osteza' ), __( 'Normalmente incluido', 'osteza' ) ),
				'yes'        => osteza_f( 'lines', __( 'Incluido (uno por línea)', 'osteza' ) ),
				'noTitle'    => osteza_f( 'text', __( 'Título de lo opcional', 'osteza' ), __( 'Solo si lo acordamos', 'osteza' ) ),
				'no'         => osteza_f( 'lines', __( 'Opcional (uno por línea)', 'osteza' ) ),
				'note'       => osteza_f( 'text', __( 'Nota', 'osteza' ), __( 'Todo queda reflejado por escrito en el presupuesto antes de empezar.', 'osteza' ) ),
				'typesLabel' => osteza_f( 'text', __( 'Título de los tipos', 'osteza' ), __( 'Trabajamos en', 'osteza' ) ),
				'types'      => osteza_f( 'lines', __( 'Tipos (uno por línea)', 'osteza' ) ),
			),
		),

		'price'         => array(
			'title'  => __( 'Precio y plazo', 'osteza' ),
			'icon'   => 'money-alt',
			'fields' => array(
				'anchor'  => $anchor( 'precio' ),
				'kicker'  => $kicker( __( 'Precio y plazo', 'osteza' ) ),
				'title'   => $title( __( '¿Cuánto cuesta y cuánto dura?', 'osteza' ) ),
				'lead'    => $lead( '' ),
				'button'  => osteza_f( 'text', __( 'Botón', 'osteza' ), __( 'Pedir mi presupuesto', 'osteza' ) ),
				'factors' => osteza_list( __( 'Factores', 'osteza' ), $pair( __( 'Título', 'osteza' ), __( 'Texto', 'osteza' ) ) ),
			),
		),

		'related'       => array(
			'title'  => __( 'Relacionados', 'osteza' ),
			'icon'   => 'networking',
			'fields' => array(
				'kicker'    => $kicker( __( 'También te puede interesar', 'osteza' ) ),
				'title'     => $title( __( 'Servicios relacionados.', 'osteza' ) ),
				'cards'     => osteza_list(
					__( 'Tarjetas', 'osteza' ),
					array(
						'page'  => osteza_f( 'link', __( 'Página o artículo', 'osteza' ) ),
						'style' => osteza_f( 'select', __( 'Estilo', 'osteza' ), 'rc-w', array( 'options' => array( 'rc-w' => __( 'Blanca', 'osteza' ), 'rc-s' => __( 'Piedra', 'osteza' ), 'rc-g' => __( 'Verde petróleo', 'osteza' ) ) ) ),
						'icon'  => osteza_f( 'art', __( 'Icono', 'osteza' ) ),
						'cat'   => osteza_f( 'text', __( 'Etiqueta superior', 'osteza' ) ),
						'name'  => osteza_f( 'text', __( 'Título (vacío: el de la página)', 'osteza' ) ),
						'desc'  => osteza_f( 'text', __( 'Texto', 'osteza' ) ),
					)
				),
				'note'      => osteza_f( 'html', __( 'Nota de proyectos', 'osteza' ), '' ),
				'noteLink'  => osteza_f( 'text', __( 'Enlace de la nota', 'osteza' ), __( 'Ver proyectos', 'osteza' ) ),
			),
		),

		/* Zones. */

		'zones-head'    => array(
			'title'  => __( 'Zonas: cabecera con mapa', 'osteza' ),
			'icon'   => 'location-alt',
			'fields' => array(
				'kicker'   => $kicker( __( 'Zonas', 'osteza' ) ),
				'title'    => $title( __( 'Dónde trabajamos en la <span>Costa Blanca.</span>', 'osteza' ), true ),
				'lead'     => $lead( __( 'Trabajamos de forma habitual en estas localidades de la provincia de Alicante. Elige la tuya para ver qué hacemos allí, o consúltanos si no aparece.', 'osteza' ) ),
				'selected' => osteza_f( 'zone', __( 'Zona seleccionada al cargar', 'osteza' ) ),
				'label'    => osteza_f( 'text', __( 'Etiqueta de la selección', 'osteza' ), __( 'Seleccionada', 'osteza' ) ),
				'button'   => osteza_f( 'text', __( 'Botón', 'osteza' ), __( 'Ver la zona', 'osteza' ) ),
				'caption'  => osteza_f( 'text', __( 'Pie del mapa', 'osteza' ), __( 'Esquema, no a escala.', 'osteza' ) ),
			),
		),

		'zones-grid'    => array(
			'title'  => __( 'Zonas: tarjetas', 'osteza' ),
			'icon'   => 'screenoptions',
			'fields' => array(
				'title' => $title( __( 'Localidades', 'osteza' ) ),
				'note'  => osteza_f( 'text', __( 'Nota', 'osteza' ), __( 'Publicamos solo las zonas donde trabajamos de forma habitual.', 'osteza' ) ),
			),
		),

		'zone-hero'     => array(
			'title'  => __( 'Zona: cabecera', 'osteza' ),
			'icon'   => 'location',
			'fields' => array(
				'kicker'       => $kicker( '' ),
				'title'        => $title( '', true ),
				'lead'         => $lead( '' ),
				'button'       => osteza_f( 'text', __( 'Botón principal', 'osteza' ), __( 'Solicitar presupuesto', 'osteza' ) ),
				'button2'      => osteza_f( 'text', __( 'Botón de WhatsApp', 'osteza' ), __( 'Hablar por WhatsApp', 'osteza' ) ),
				'summaryTitle' => osteza_f( 'text', __( 'Título del resumen', 'osteza' ), '' ),
				'summary'      => osteza_list(
					__( 'Resumen', 'osteza' ),
					array(
						'term'  => osteza_f( 'text', __( 'Concepto', 'osteza' ) ),
						'value' => osteza_f( 'text', __( 'Valor', 'osteza' ) ),
					)
				),
				'nearLabel'    => osteza_f( 'text', __( 'Título de zonas cercanas', 'osteza' ), __( 'Cerca', 'osteza' ) ),
				'near'         => osteza_list( __( 'Zonas cercanas', 'osteza' ), array( 'zone' => osteza_f( 'zone', __( 'Zona', 'osteza' ) ) ) ),
			),
		),

		'zone-services' => array(
			'title'  => __( 'Zona: servicios', 'osteza' ),
			'icon'   => 'screenoptions',
			'help'   => __( 'Muestra los servicios de la portada.', 'osteza' ),
			'fields' => array(
				'kicker' => $kicker( '' ),
				'title'  => $title( '' ),
			),
		),

		'points'        => array(
			'title'  => __( 'Puntos a tener en cuenta', 'osteza' ),
			'icon'   => 'info-outline',
			'fields' => array(
				'kicker' => $kicker( '' ),
				'title'  => $title( __( 'Lo que tenemos en cuenta aquí.', 'osteza' ) ),
				'lead'   => $lead( __( 'Cada zona tiene sus particularidades. Estas son algunas que revisamos antes de dar un presupuesto.', 'osteza' ) ),
				'note'   => osteza_f( 'text', __( 'Nota', 'osteza' ), '' ),
				'points' => osteza_list( __( 'Puntos', 'osteza' ), $pair( __( 'Título', 'osteza' ), __( 'Texto', 'osteza' ) ) ),
			),
		),

		'projects-note' => array(
			'title'  => __( 'Aviso de proyectos', 'osteza' ),
			'icon'   => 'portfolio',
			'fields' => array(
				'kicker'    => $kicker( __( 'Proyectos', 'osteza' ) ),
				'title'     => $title( __( 'Trabajos en la zona.', 'osteza' ) ),
				'text'      => osteza_f( 'textarea', __( 'Texto', 'osteza' ), '' ),
				'linkLabel' => osteza_f( 'text', __( 'Enlace', 'osteza' ), __( 'Ver todos los proyectos', 'osteza' ) ),
			),
		),

		/* Contact page. */

		'contact-head'  => array(
			'title'  => __( 'Contacto: cabecera con canales', 'osteza' ),
			'icon'   => 'phone',
			'help'   => __( 'Los datos se editan en Apariencia → Personalizar → OSTEZA — Contacto.', 'osteza' ),
			'fields' => array(
				'kicker' => $kicker( __( 'Contacto', 'osteza' ) ),
				'title'  => $title( __( 'Hablemos de <span>tu proyecto.</span>', 'osteza' ), true ),
				'lead'   => $lead( __( 'Elige el canal que te resulte más cómodo. Para un presupuesto, cuantos más detalles nos des (fotos, medidas, plazo), mejor podremos orientarte.', 'osteza' ) ),
				'waText' => osteza_f( 'text', __( 'Texto de WhatsApp', 'osteza' ), __( 'Escríbenos y envía fotos', 'osteza' ) ),
			),
		),

		'area'          => array(
			'title'  => __( 'Zona de trabajo con mapa', 'osteza' ),
			'icon'   => 'location-alt',
			'fields' => array(
				'kicker'     => $kicker( __( 'Zona de trabajo', 'osteza' ) ),
				'title'      => $title( __( 'Dónde trabajamos.', 'osteza' ) ),
				'lead'       => $lead( __( 'Trabajamos de forma habitual en estas localidades de la Costa Blanca. Si la tuya no aparece, escríbenos y lo consultamos.', 'osteza' ) ),
				'note'       => osteza_f( 'text', __( 'Nota', 'osteza' ), __( '[Lista provisional: publicar solo las localidades confirmadas]', 'osteza' ) ),
				'hoursLabel' => osteza_f( 'text', __( 'Título del horario', 'osteza' ), __( 'Horario de atención', 'osteza' ) ),
				'caption'    => osteza_f( 'text', __( 'Pie del mapa', 'osteza' ), __( 'Esquema, no a escala.', 'osteza' ) ),
			),
		),

		/* About page. */

		'about-head'    => array(
			'title'  => __( 'Sobre nosotros: cabecera', 'osteza' ),
			'icon'   => 'cover-image',
			'fields' => array(
				'kicker'  => $kicker( __( 'Sobre nosotros', 'osteza' ) ),
				'title'   => $title( __( 'Oficio, orden <span>y palabra.</span>', 'osteza' ), true ),
				'lead'    => $lead( __( 'Somos un equipo de montaje de cocinas y muebles en la Costa Blanca, que también hace mudanzas, reformas y acabados. Creemos que un trabajo en casa debería ser predecible: saber qué se va a hacer, quién lo hace y cuándo.', 'osteza' ) ),
				'button'  => osteza_f( 'text', __( 'Botón principal', 'osteza' ), __( 'Solicitar presupuesto', 'osteza' ) ),
				'button2' => osteza_f( 'text', __( 'Botón secundario (al equipo)', 'osteza' ), __( 'Conocer al equipo', 'osteza' ) ),
				'image'   => osteza_f( 'image', __( 'Foto', 'osteza' ) ),
				'caption' => osteza_f( 'text', __( 'Texto sin foto', 'osteza' ), __( 'Espacio para una fotografía real del equipo, en obra y con luz natural.', 'osteza' ) ),
			),
		),

		'story'         => array(
			'title'  => __( 'Historia', 'osteza' ),
			'icon'   => 'book',
			'fields' => array(
				'kicker' => $kicker( __( 'Por qué existimos', 'osteza' ) ),
				'title'  => $title( __( 'Una forma más clara de trabajar en casa.', 'osteza' ) ),
				'big'    => osteza_f( 'textarea', __( 'Frase destacada', 'osteza' ), __( 'Muchas personas en la Costa Blanca han vivido una obra o un montaje con sorpresas: presupuestos poco claros, plazos que se alargan y nadie a quien llamar. OSTEZA nace para trabajar de otra manera.', 'osteza' ) ),
				'body'   => osteza_f( 'textarea', __( 'Párrafos (separados por una línea en blanco)', 'osteza' ), __( '[HISTORIA REAL: quién fundó OSTEZA, cuándo y por qué. Dos o tres párrafos en primera persona, con detalles concretos y verificables.]', 'osteza' ) . "\n\n" . __( 'Empezamos por lo que mejor sabemos hacer: montar cocinas y muebles. A partir de ahí, mudanzas, reformas y acabados interiores. Ampliaremos servicios solo cuando podamos prestarlos con el mismo estándar.', 'osteza' ) ),
				'quote'  => osteza_f( 'text', __( 'Cita', 'osteza' ), __( '«[Frase real del fundador o del equipo sobre cómo entendéis el trabajo.]»', 'osteza' ) ),
				'author' => osteza_f( 'text', __( 'Autor de la cita', 'osteza' ), __( '— [Nombre], [rol]', 'osteza' ) ),
			),
		),

		'team'          => array(
			'title'  => __( 'Equipo', 'osteza' ),
			'icon'   => 'groups',
			'fields' => array(
				'anchor' => $anchor( 'equipo-l' ),
				'kicker' => $kicker( __( 'El equipo', 'osteza' ) ),
				'title'  => $title( __( 'Oficio en obra y coordinación cercana.', 'osteza' ) ),
				'note'   => $lead( __( 'Presentamos solo a personas reales del equipo, con su consentimiento. Esta es la plantilla para cada perfil.', 'osteza' ), __( 'Nota', 'osteza' ) ),
				'people' => osteza_list(
					__( 'Personas', 'osteza' ),
					array(
						'name'  => osteza_f( 'text', __( 'Nombre', 'osteza' ) ),
						'role'  => osteza_f( 'text', __( 'Rol', 'osteza' ) ),
						'bio'   => osteza_f( 'textarea', __( 'Texto', 'osteza' ) ),
						'image' => osteza_f( 'image', __( 'Foto', 'osteza' ) ),
						'color' => osteza_f( 'select', __( 'Fondo sin foto', 'osteza' ), 'c1', array( 'options' => array( 'c1' => __( 'Arena', 'osteza' ), 'c2' => __( 'Verde petróleo', 'osteza' ), 'c3' => __( 'Piedra', 'osteza' ) ) ) ),
					),
					array(
						array( 'name' => __( '[Nombre]', 'osteza' ), 'role' => __( '[Rol · p. ej. coordinación]', 'osteza' ), 'bio' => __( '[Dos líneas sobre su experiencia real y qué hace en OSTEZA.]', 'osteza' ), 'image' => 0, 'color' => 'c1' ),
						array( 'name' => __( '[Nombre]', 'osteza' ), 'role' => __( '[Rol · p. ej. montaje de cocinas]', 'osteza' ), 'bio' => __( '[Dos líneas sobre su experiencia real y qué hace en OSTEZA.]', 'osteza' ), 'image' => 0, 'color' => 'c2' ),
						array( 'name' => __( '[Nombre]', 'osteza' ), 'role' => __( '[Rol · p. ej. mudanzas y transporte]', 'osteza' ), 'bio' => __( '[Dos líneas sobre su experiencia real y qué hace en OSTEZA.]', 'osteza' ), 'image' => 0, 'color' => 'c3' ),
					)
				),
			),
		),

		'promise'       => array(
			'title'  => __( 'Siempre / nunca', 'osteza' ),
			'icon'   => 'shield',
			'fields' => array(
				'yesKicker' => osteza_f( 'text', __( 'Antetítulo izquierdo', 'osteza' ), __( 'Lo que puedes esperar', 'osteza' ) ),
				'yesTitle'  => osteza_f( 'text', __( 'Título izquierdo', 'osteza' ), __( 'Siempre', 'osteza' ) ),
				'yes'       => osteza_f( 'lines', __( 'Lista izquierda (uno por línea)', 'osteza' ), implode( "\n", array( __( 'Presupuesto detallado y por escrito', 'osteza' ), __( 'Una persona de contacto durante todo el trabajo', 'osteza' ), __( 'Protección de tu vivienda y limpieza al terminar', 'osteza' ), __( 'Revisión final contigo antes de cerrar', 'osteza' ) ) ) ),
				'noKicker'  => osteza_f( 'text', __( 'Antetítulo derecho', 'osteza' ), __( 'Lo que no hacemos', 'osteza' ) ),
				'noTitle'   => osteza_f( 'text', __( 'Título derecho', 'osteza' ), __( 'Nunca', 'osteza' ) ),
				'no'        => osteza_f( 'lines', __( 'Lista derecha (uno por línea)', 'osteza' ), implode( "\n", array( __( 'Empezar sin un acuerdo por escrito', 'osteza' ), __( 'Prometer plazos que no podemos cumplir', 'osteza' ), __( 'Aceptar trabajos que no sabemos hacer bien', 'osteza' ), __( 'Cambiar el alcance sin hablarlo contigo', 'osteza' ) ) ) ),
			),
		),
	);

	return $blocks;
}

/**
 * Attribute schema of a block from its fields.
 */
function osteza_block_attributes( $fields ) {
	$types = array(
		'toggle' => 'boolean',
		'number' => 'number',
		'image'  => 'number',
		'page'   => 'number',
		'link'   => 'number',
		'zone'   => 'number',
		'group'  => 'number',
		'list'   => 'array',
	);
	$attrs = array();
	foreach ( $fields as $key => $field ) {
		$attrs[ $key ] = array(
			'type'    => $types[ $field['type'] ] ?? 'string',
			'default' => $field['default'],
		);
	}
	return $attrs;
}

add_action(
	'init',
	function () {
		foreach ( osteza_blocks() as $name => $block ) {
			register_block_type(
				'osteza/' . $name,
				array(
					'api_version'     => 3,
					'title'           => $block['title'],
					'category'        => 'osteza',
					'icon'            => $block['icon'],
					'attributes'      => osteza_block_attributes( $block['fields'] ),
					'supports'        => array( 'html' => false ),
					'render_callback' => function ( $attributes, $content ) use ( $name ) {
						return osteza_render_block( $name, $attributes, $content );
					},
				)
			);
		}
	},
	20
);

/**
 * Markup of a section: blocks/<name>.php with the attributes in $a.
 */
function osteza_render_block( $name, $attributes, $content = '' ) {
	$block = osteza_blocks()[ $name ] ?? null;
	if ( ! $block ) {
		return '';
	}
	$a = array();
	foreach ( $block['fields'] as $key => $field ) {
		$a[ $key ] = $attributes[ $key ] ?? $field['default'];
	}
	ob_start();
	include get_template_directory() . '/blocks/' . $name . '.php';
	return ob_get_clean();
}

add_filter(
	'block_categories_all',
	function ( $categories ) {
		array_unshift(
			$categories,
			array(
				'slug'  => 'osteza',
				'title' => 'OSTEZA',
				'icon'  => null,
			)
		);
		return $categories;
	}
);

/*
 * Editor: the generic block UI and the site styles inside the editor canvas.
 */
add_action(
	'enqueue_block_editor_assets',
	function () {
		$dir = get_template_directory();
		$uri = get_template_directory_uri();
		wp_enqueue_script(
			'osteza-blocks',
			$uri . '/assets/js/blocks.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-data', 'wp-i18n' ),
			filemtime( $dir . '/assets/js/blocks.js' ),
			true
		);
		wp_register_style( 'osteza-blocks-ui', false, array(), OSTEZA_VERSION );
		wp_enqueue_style( 'osteza-blocks-ui' );
		wp_add_inline_style( 'osteza-blocks-ui', '.osteza-list .osteza-item{border:1px solid #ddd;margin:0 0 8px}.osteza-list .osteza-item .components-panel__body-title{margin:0}.osteza-item-tools{display:flex;gap:6px;align-items:center;margin-top:8px}.osteza-add{margin-top:4px}.osteza-help{color:#757575;font-size:12px}.osteza-list>.components-base-control__field>.components-base-control__label,.osteza-list .components-base-control__label{font-weight:600}' );

		$label = function ( $post ) {
			$lang = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $post->ID ) : '';
			return $post->post_title . ( $lang ? ' (' . $lang . ')' : '' ) . ( 'publish' !== $post->post_status ? ' — ' . __( 'borrador', 'osteza' ) : '' );
		};
		$posts = function ( $type, $template = '' ) use ( $label ) {
			$args = array(
				'post_type'      => $type,
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => 300,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'lang'           => '',
			);
			if ( $template ) {
				$args['meta_key']   = '_wp_page_template';
				$args['meta_value'] = 'page-templates/' . $template . '.php';
			}
			$out = array();
			foreach ( get_posts( $args ) as $post ) {
				$out[] = array( 'value' => $post->ID, 'label' => $label( $post ) );
			}
			return $out;
		};

		$groups = array();
		foreach ( get_terms( array( 'taxonomy' => OSTEZA_GROUP, 'hide_empty' => false, 'lang' => '' ) ) as $term ) {
			$groups[] = array( 'value' => $term->term_id, 'label' => $term->name );
		}
		$art = array();
		foreach ( osteza_art_icons() as $key => $icon ) {
			$art[] = array( 'value' => $key, 'label' => $icon[0] );
		}
		$ui = array();
		foreach ( osteza_ui_icons() as $key => $text ) {
			$ui[] = array( 'value' => $key, 'label' => $text );
		}

		$blocks = array();
		foreach ( osteza_blocks() as $name => $block ) {
			$blocks[ $name ] = array(
				'title'      => $block['title'],
				'icon'       => $block['icon'],
				'help'       => $block['help'] ?? '',
				'inner'      => ! empty( $block['inner'] ),
				'fields'     => $block['fields'],
				'attributes' => osteza_block_attributes( $block['fields'] ),
			);
		}

		wp_add_inline_script(
			'osteza-blocks',
			'window.OSTEZA_EDITOR = ' . wp_json_encode(
				array(
					'blocks'  => $blocks,
					'options' => array(
						'page'  => $posts( 'page' ),
						'link'  => array_merge( $posts( 'page' ), $posts( 'post' ) ),
						'zone'  => $posts( 'page', 'zone' ),
						'group' => $groups,
						'art'   => $art,
						'icon'  => $ui,
					),
					'i18n'    => array(
						'add'    => __( 'Añadir', 'osteza' ),
						'remove' => __( 'Eliminar', 'osteza' ),
						'up'     => __( 'Subir', 'osteza' ),
						'down'   => __( 'Bajar', 'osteza' ),
						'none'   => __( '— Ninguno —', 'osteza' ),
						'image'  => __( 'Elegir imagen', 'osteza' ),
						'clear'  => __( 'Quitar imagen', 'osteza' ),
						'item'   => __( 'Elemento', 'osteza' ),
						'fields' => __( 'Contenido', 'osteza' ),
						'inner'  => __( 'Escribe aquí el texto de la sección.', 'osteza' ),
						'htmlHelp'  => __( 'Texto con formato: <span>…</span> resalta en color, <strong> en negrita, <a href="…"> enlace; {contacto} es el formulario y {privacidad} la política de privacidad.', 'osteza' ),
						'linesHelp' => __( 'Un elemento por línea.', 'osteza' ),
					),
				)
			) . ';',
			'before'
		);
	}
);

// Site styles in the editor canvas, scoped by WordPress to the editor content.
add_action(
	'after_setup_theme',
	function () {
		add_editor_style( array( 'assets/css/main.css', 'assets/css/editor.css' ) );
	}
);

/*
 * Pages: rendering of the content, or of the template's default sections while
 * the page has no blocks yet.
 */

/**
 * Prints the sections of the current page.
 *
 * @param string $type Default layout (inc/layouts.php) used while the page is empty.
 */
function osteza_the_sections( $type ) {
	$post = get_post();
	if ( $post && has_blocks( $post->post_content ) ) {
		echo apply_filters( 'the_content', $post->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput -- block markup.
		return;
	}
	foreach ( osteza_layout( $type, $post ) as $section ) {
		echo osteza_render_block( $section[0], $section[1] ); // phpcs:ignore WordPress.Security.EscapeOutput -- block markup.
	}
}

/**
 * Block names used by the queried page (its content, or its default layout).
 */
function osteza_page_blocks() {
	static $names = null;
	if ( null === $names ) {
		$names = array();
		$post  = get_queried_object();
		if ( is_front_page() && ! ( $post instanceof WP_Post ) ) {
			$post = null;
		}
		if ( $post instanceof WP_Post && has_blocks( $post->post_content ) ) {
			$walk = function ( $blocks ) use ( &$walk, &$names ) {
				foreach ( $blocks as $block ) {
					if ( $block['blockName'] && 0 === strpos( $block['blockName'], 'osteza/' ) ) {
						$names[] = substr( $block['blockName'], 7 );
					}
					$walk( $block['innerBlocks'] );
				}
			};
			$walk( parse_blocks( $post->post_content ) );
		} elseif ( is_front_page() || $post instanceof WP_Post ) {
			$type = is_front_page() ? 'home' : osteza_page_type( $post );
			if ( $type ) {
				$names = wp_list_pluck( osteza_layout( $type, $post ), 0 );
			}
		}
	}
	return $names;
}

function osteza_page_has_block( $name ) {
	return in_array( $name, osteza_page_blocks(), true );
}

/*
 * A page that opens with the dark home header gets a transparent site header over it,
 * which turns solid again once the page scrolls.
 */
add_filter(
	'body_class',
	function ( $classes ) {
		$blocks = osteza_page_blocks();
		if ( $blocks && 'hero' === $blocks[0] ) {
			$classes[] = 'hdr-over';
		}
		return $classes;
	}
);

/**
 * Layout type of a page from its template ('' for the default template).
 */
function osteza_page_type( $post ) {
	$types = array(
		'page-templates/service.php'  => 'service',
		'page-templates/services.php' => 'services',
		'page-templates/zone.php'     => 'zone',
		'page-templates/zones.php'    => 'zones',
		'page-templates/contact.php'  => 'contact',
		'page-templates/about.php'    => 'about',
	);
	return $types[ get_page_template_slug( $post ) ] ?? 'page';
}
