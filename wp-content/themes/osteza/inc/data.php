<?php
/**
 * Content model: services and zones are pages, edited in wp-admin.
 *
 * - A service is a page with the "Servicio" template. Its card (text, tags, icon,
 *   colour), its group and whether it shows on the home page are set in the
 *   "Tarjeta del servicio" box of the page editor. Groups are the terms of the
 *   taxonomy osteza_group (Páginas → Grupos de servicios), ordered by their "Orden".
 * - A zone is a page with the "Zona" template; comarca, coordinates and coverage
 *   are in the "Datos de la zona" box.
 *
 * The lists below are read from the pages in the default language and point to
 * their translation in the current language. Keys are the default-language slugs.
 */

const OSTEZA_GROUP = 'osteza_group';

add_action(
	'init',
	function () {
		register_taxonomy(
			OSTEZA_GROUP,
			'page',
			array(
				'labels'            => array(
					'name'          => __( 'Grupos de servicios', 'osteza' ),
					'singular_name' => __( 'Grupo de servicios', 'osteza' ),
					'add_new_item'  => __( 'Añadir grupo', 'osteza' ),
					'edit_item'     => __( 'Editar grupo', 'osteza' ),
					'menu_name'     => __( 'Grupos de servicios', 'osteza' ),
				),
				'public'            => false,
				'show_ui'           => true,
				'show_in_menu'      => true,
				'show_admin_column' => true,
				'show_in_rest'      => false,
				'meta_box_cb'       => false,
				'hierarchical'      => false,
				'rewrite'           => false,
			)
		);
		register_term_meta( OSTEZA_GROUP, 'osteza_order', array( 'type' => 'integer', 'single' => true ) );
	}
);

// Groups are translated with Polylang like pages.
add_filter(
	'pll_get_taxonomies',
	function ( $taxonomies, $is_settings ) {
		if ( ! $is_settings ) {
			$taxonomies[ OSTEZA_GROUP ] = OSTEZA_GROUP;
		}
		return $taxonomies;
	},
	10,
	2
);

/*
 * Group order field on the term screens.
 */
add_action(
	OSTEZA_GROUP . '_add_form_fields',
	function () {
		echo '<div class="form-field"><label for="osteza_order">' . esc_html__( 'Orden', 'osteza' ) . '</label><input type="number" name="osteza_order" id="osteza_order" value="0"><p>' . esc_html__( 'Posición del grupo en el catálogo y en el menú. La descripción es el texto de introducción del grupo en el catálogo.', 'osteza' ) . '</p></div>';
	}
);
add_action(
	OSTEZA_GROUP . '_edit_form_fields',
	function ( $term ) {
		echo '<tr class="form-field"><th><label for="osteza_order">' . esc_html__( 'Orden', 'osteza' ) . '</label></th><td><input type="number" name="osteza_order" id="osteza_order" value="' . (int) get_term_meta( $term->term_id, 'osteza_order', true ) . '"><p class="description">' . esc_html__( 'Posición del grupo en el catálogo y en el menú. La descripción es el texto de introducción del grupo en el catálogo.', 'osteza' ) . '</p></td></tr>';
	}
);
foreach ( array( 'created_', 'edited_' ) as $osteza_hook ) {
	add_action(
		$osteza_hook . OSTEZA_GROUP,
		function ( $term_id ) {
			if ( isset( $_POST['osteza_order'] ) && current_user_can( 'manage_categories' ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- checked by the term screen.
				update_term_meta( $term_id, 'osteza_order', (int) $_POST['osteza_order'] ); // phpcs:ignore WordPress.Security.NonceVerification
			}
		}
	);
}

/**
 * Card colours of the services (classes of the design).
 */
function osteza_card_colors() {
	return array(
		'c-g' => __( 'Verde petróleo', 'osteza' ),
		'c-t' => __( 'Arena', 'osteza' ),
		'c-w' => __( 'Blanco', 'osteza' ),
		'c-s' => __( 'Piedra', 'osteza' ),
	);
}

/**
 * Fields of the page boxes: meta key suffix => ( label, type ).
 */
function osteza_service_fields() {
	return array(
		'desc'   => array( __( 'Texto de la tarjeta', 'osteza' ), 'textarea' ),
		'tags'   => array( __( 'Etiquetas de la tarjeta (una por línea)', 'osteza' ), 'lines' ),
		'icon'   => array( __( 'Icono', 'osteza' ), 'icon' ),
		'color'  => array( __( 'Color de la tarjeta en la portada', 'osteza' ), 'color' ),
		'image'  => array( __( 'Imagen de la tarjeta (4:3, opcional)', 'osteza' ), 'image' ),
		'home'   => array( __( 'Mostrar en la portada, en el pie y en el formulario', 'osteza' ), 'check' ),
		'nomenu' => array( __( 'Ocultar en el menú de servicios', 'osteza' ), 'check' ),
	);
}

function osteza_zone_fields() {
	return array(
		'area'     => array( __( 'Comarca', 'osteza' ), 'text' ),
		'coverage' => array( __( 'Cobertura (texto de la tarjeta)', 'osteza' ), 'text' ),
		'lat'      => array( __( 'Latitud', 'osteza' ), 'text' ),
		'lng'      => array( __( 'Longitud', 'osteza' ), 'text' ),
		'mapx'     => array( __( 'Posición X en el esquema sin JavaScript (0–420)', 'osteza' ), 'text' ),
		'mapy'     => array( __( 'Posición Y en el esquema sin JavaScript (0–540)', 'osteza' ), 'text' ),
	);
}

add_action(
	'admin_enqueue_scripts',
	function () {
		$screen = get_current_screen();
		if ( $screen && 'page' === $screen->post_type && 'post' === $screen->base ) {
			wp_enqueue_media();
		}
	}
);

add_action(
	'add_meta_boxes_page',
	function ( $post ) {
		$template = get_page_template_slug( $post );
		$new      = 'auto-draft' === $post->post_status;
		if ( $new || 'page-templates/service.php' === $template ) {
			add_meta_box( 'osteza_service', __( 'Tarjeta del servicio', 'osteza' ), 'osteza_service_box', 'page', 'side' );
		}
		if ( $new || 'page-templates/zone.php' === $template ) {
			add_meta_box( 'osteza_zone', __( 'Datos de la zona', 'osteza' ), 'osteza_zone_box', 'page', 'side' );
		}
	}
);

function osteza_meta_field( $post, $prefix, $key, $field ) {
	$name  = $prefix . $key;
	$value = get_post_meta( $post->ID, $name, true );
	echo '<p>';
	switch ( $field[1] ) {
		case 'check':
			printf( '<label><input type="checkbox" name="%s" value="1"%s> %s</label>', esc_attr( $name ), checked( (bool) $value, true, false ), esc_html( $field[0] ) );
			break;
		case 'icon':
			printf( '<label><strong>%s</strong><br><select name="%s" style="width:100%%"><option value="">—</option>', esc_html( $field[0] ), esc_attr( $name ) );
			foreach ( osteza_art_icons() as $icon => $art ) {
				printf( '<option value="%s"%s>%s</option>', esc_attr( $icon ), selected( $value, $icon, false ), esc_html( $art[0] ) );
			}
			echo '</select></label>';
			break;
		case 'color':
			printf( '<label><strong>%s</strong><br><select name="%s" style="width:100%%">', esc_html( $field[0] ), esc_attr( $name ) );
			foreach ( osteza_card_colors() as $color => $label ) {
				printf( '<option value="%s"%s>%s</option>', esc_attr( $color ), selected( $value, $color, false ), esc_html( $label ) );
			}
			echo '</select></label>';
			break;
		case 'image':
			$id = (int) $value;
			printf( '<span data-osteza-image><strong>%s</strong><br><input type="hidden" name="%s" value="%s"><span data-preview>%s</span><br>', esc_html( $field[0] ), esc_attr( $name ), $id ? esc_attr( $id ) : '', $id ? wp_get_attachment_image( $id, 'medium', false, array( 'style' => 'max-width:100%;height:auto;margin-bottom:6px' ) ) : '' );
			printf( '<button type="button" class="button" data-choose>%s</button> <button type="button" class="button-link" data-clear>%s</button></span>', esc_html__( 'Elegir', 'osteza' ), esc_html__( 'Quitar', 'osteza' ) );
			osteza_image_field_script();
			break;
		case 'textarea':
		case 'lines':
			printf( '<label><strong>%s</strong><br><textarea name="%s" rows="3" style="width:100%%">%s</textarea></label>', esc_html( $field[0] ), esc_attr( $name ), esc_textarea( is_array( $value ) ? implode( "\n", $value ) : $value ) );
			break;
		default:
			printf( '<label><strong>%s</strong><br><input type="text" name="%s" value="%s" style="width:100%%"></label>', esc_html( $field[0] ), esc_attr( $name ), esc_attr( $value ) );
	}
	echo '</p>';
}

/**
 * Media picker for the "image" fields of the page boxes, printed once.
 */
function osteza_image_field_script() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;
	?>
	<script>
	document.addEventListener('click', function (e) {
		var box = e.target.closest('[data-osteza-image]');
		if (!box) return;
		var input = box.querySelector('input'), preview = box.querySelector('[data-preview]');
		if (e.target.closest('[data-clear]')) { input.value = ''; preview.textContent = ''; return; }
		if (!e.target.closest('[data-choose]')) return;
		var frame = wp.media({ multiple: false, library: { type: 'image' } });
		frame.on('select', function () {
			var i = frame.state().get('selection').first().toJSON(), img = document.createElement('img');
			input.value = i.id;
			img.src = (i.sizes && i.sizes.medium ? i.sizes.medium : i).url;
			img.style.cssText = 'max-width:100%;height:auto;margin-bottom:6px';
			preview.textContent = '';
			preview.appendChild(img);
		});
		frame.open();
	});
	</script>
	<?php
}

function osteza_service_box( $post ) {
	wp_nonce_field( 'osteza_page_meta', 'osteza_page_meta_nonce' );
	$terms   = get_terms( array( 'taxonomy' => OSTEZA_GROUP, 'hide_empty' => false ) );
	$current = wp_get_object_terms( $post->ID, OSTEZA_GROUP, array( 'fields' => 'ids' ) );
	echo '<p><label><strong>' . esc_html__( 'Grupo', 'osteza' ) . '</strong><br><select name="osteza_group" style="width:100%"><option value="">—</option>';
	foreach ( is_array( $terms ) ? $terms : array() as $term ) {
		printf( '<option value="%d"%s>%s</option>', (int) $term->term_id, selected( in_array( $term->term_id, $current, true ), true, false ), esc_html( $term->name ) );
	}
	echo '</select></label></p>';
	foreach ( osteza_service_fields() as $key => $field ) {
		osteza_meta_field( $post, 'osteza_s_', $key, $field );
	}
	echo '<p class="description">' . esc_html__( 'El nombre de la tarjeta es el título de la página; el orden es el «Orden» de Atributos de página.', 'osteza' ) . '</p>';
}

function osteza_zone_box( $post ) {
	wp_nonce_field( 'osteza_page_meta', 'osteza_page_meta_nonce' );
	foreach ( osteza_zone_fields() as $key => $field ) {
		osteza_meta_field( $post, 'osteza_z_', $key, $field );
	}
	echo '<p class="description">' . esc_html__( 'El nombre de la zona es el título de la página; el orden en las listas es el «Orden» de Atributos de página.', 'osteza' ) . '</p>';
}

add_action(
	'save_post_page',
	function ( $post_id ) {
		if ( ! isset( $_POST['osteza_page_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['osteza_page_meta_nonce'] ), 'osteza_page_meta' ) || ! current_user_can( 'edit_post', $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}
		$boxes = array(
			'osteza_s_' => osteza_service_fields(),
			'osteza_z_' => osteza_zone_fields(),
		);
		foreach ( $boxes as $prefix => $fields ) {
			foreach ( $fields as $key => $field ) {
				$name = $prefix . $key;
				if ( 'check' === $field[1] ) {
					if ( ! isset( $_POST[ $prefix . 'desc' ] ) && ! isset( $_POST[ $prefix . 'area' ] ) ) {
						continue; // The box was not on the screen.
					}
					if ( empty( $_POST[ $name ] ) ) {
						delete_post_meta( $post_id, $name );
					} else {
						update_post_meta( $post_id, $name, 1 );
					}
				} elseif ( isset( $_POST[ $name ] ) ) {
					$value = wp_unslash( $_POST[ $name ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized below.
					if ( 'image' === $field[1] ) {
						$value = absint( $value ) ?: '';
					}
					update_post_meta( $post_id, $name, in_array( $field[1], array( 'textarea', 'lines' ), true ) ? sanitize_textarea_field( $value ) : sanitize_text_field( $value ) );
				}
			}
		}
		if ( isset( $_POST['osteza_group'] ) ) {
			$group = (int) $_POST['osteza_group'];
			wp_set_object_terms( $post_id, $group ? array( $group ) : array(), OSTEZA_GROUP );
		}
		osteza_flush_lists();
	}
);

/*
 * Lists, cached for the request.
 */

function osteza_flush_lists() {
	$GLOBALS['osteza_lists'] = array();
}

function osteza_default_lang() {
	return function_exists( 'pll_default_language' ) ? pll_default_language() : '';
}

/**
 * Published pages of the default language that use a template, by menu order.
 */
function osteza_template_pages( $template ) {
	return get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'page-templates/' . $template . '.php',
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
			'lang'           => osteza_default_lang(),
		)
	);
}

/**
 * The page in the current language for a default-language page id: ( id, title, url ).
 * The url is "#" while the page is not translated.
 */
function osteza_localize_page( $id ) {
	$local = function_exists( 'pll_get_post' ) ? pll_get_post( $id ) : $id;
	return array(
		'id'    => $local ?: $id,
		'title' => get_the_title( $local ?: $id ),
		'url'   => $local ? esc_url( get_permalink( $local ) ) : '#',
	);
}

/**
 * All services: slug => card data. Slugs are those of the default-language pages.
 */
function osteza_all_services() {
	$cache = &$GLOBALS['osteza_lists'];
	if ( ! isset( $cache['services'] ) ) {
		$cache['services'] = array();
		foreach ( osteza_template_pages( 'service' ) as $page ) {
			$local  = osteza_localize_page( $page->ID );
			$meta   = function ( $key ) use ( $local, $page ) {
				$value = get_post_meta( $local['id'], 'osteza_s_' . $key, true );
				return '' !== $value ? $value : get_post_meta( $page->ID, 'osteza_s_' . $key, true );
			};
			$groups = wp_get_object_terms( $page->ID, OSTEZA_GROUP, array( 'fields' => 'slugs' ) );

			$cache['services'][ $page->post_name ] = array(
				'id'     => $page->ID,
				'key'    => $page->post_name,
				'name'   => $local['title'],
				'url'    => $local['url'],
				'desc'   => (string) $meta( 'desc' ),
				'tags'   => array_values( array_filter( array_map( 'trim', explode( "\n", (string) $meta( 'tags' ) ) ) ) ),
				'icon'   => (string) get_post_meta( $page->ID, 'osteza_s_icon', true ),
				'cls'    => (string) get_post_meta( $page->ID, 'osteza_s_color', true ) ?: 'c-w',
				'image'  => (int) $meta( 'image' ),
				'home'   => (bool) get_post_meta( $page->ID, 'osteza_s_home', true ),
				'nomenu' => (bool) get_post_meta( $page->ID, 'osteza_s_nomenu', true ),
				'group'  => $groups && ! is_wp_error( $groups ) ? $groups[0] : '',
			);
		}
	}
	return $cache['services'];
}

/**
 * Services marked for the home page (cards, footer, mobile menu, contact form, zone pages).
 */
function osteza_services() {
	return array_values(
		array_filter(
			osteza_all_services(),
			function ( $s ) {
				return $s['home'];
			}
		)
	);
}

/**
 * Service by default-language page id or slug; null if there is none.
 */
function osteza_service_by( $id_or_slug ) {
	foreach ( osteza_all_services() as $s ) {
		if ( $s['id'] === (int) $id_or_slug || $s['key'] === $id_or_slug ) {
			return $s;
		}
	}
	return null;
}

/**
 * Groups of services in catalogue order: slug => ( id, key, title, lead, items ).
 */
function osteza_catalog() {
	$cache = &$GLOBALS['osteza_lists'];
	if ( ! isset( $cache['catalog'] ) ) {
		$cache['catalog'] = array();
		$terms            = get_terms(
			array(
				'taxonomy'   => OSTEZA_GROUP,
				'hide_empty' => false,
				'lang'       => osteza_default_lang(),
			)
		);
		if ( is_wp_error( $terms ) ) {
			$terms = array();
		}
		usort(
			$terms,
			function ( $a, $b ) {
				return (int) get_term_meta( $a->term_id, 'osteza_order', true ) <=> (int) get_term_meta( $b->term_id, 'osteza_order', true );
			}
		);
		foreach ( $terms as $term ) {
			$local = function_exists( 'pll_get_term' ) ? ( pll_get_term( $term->term_id ) ?: $term->term_id ) : $term->term_id;
			$local = get_term( $local, OSTEZA_GROUP );

			$cache['catalog'][ $term->slug ] = array(
				'id'    => $term->term_id,
				'key'   => $term->slug,
				'title' => $local->name,
				'lead'  => $local->description,
				'items' => array_values(
					array_filter(
						osteza_all_services(),
						function ( $s ) use ( $term ) {
							return $s['group'] === $term->slug;
						}
					)
				),
			);
		}
	}
	return $cache['catalog'];
}

/**
 * Group of the catalogue by term id or slug; null if there is none.
 */
function osteza_group( $id_or_slug ) {
	foreach ( osteza_catalog() as $group ) {
		if ( $group['id'] === (int) $id_or_slug || $group['key'] === $id_or_slug ) {
			return $group;
		}
	}
	return null;
}

/**
 * Mega menu columns: group title => ( label => url ).
 */
function osteza_service_menu() {
	$menu = array();
	foreach ( osteza_catalog() as $group ) {
		foreach ( $group['items'] as $s ) {
			if ( ! $s['nomenu'] ) {
				$menu[ $group['title'] ][ $s['name'] ] = $s['url'];
			}
		}
	}
	return $menu;
}

/**
 * Short service list for the mobile menu and footer: label => url.
 */
function osteza_main_services() {
	return wp_list_pluck( osteza_services(), 'url', 'name' );
}

/**
 * Primary navigation: label => href.
 */
function osteza_nav() {
	return array(
		__( 'Proyectos', 'osteza' )      => osteza_projects_url(),
		__( 'Zonas', 'osteza' )          => osteza_zones_url(),
		__( 'Sobre nosotros', 'osteza' ) => osteza_about_url(),
		__( 'Blog', 'osteza' )           => osteza_blog_url(),
		__( 'Contacto', 'osteza' )       => osteza_contact_url(),
	);
}

/**
 * Work zones in page order: slug => name, comarca, coverage, position on the
 * fallback schematic map ('map'), latitude/longitude ('geo'), url.
 */
function osteza_zones() {
	$cache = &$GLOBALS['osteza_lists'];
	if ( ! isset( $cache['zones'] ) ) {
		$cache['zones'] = array();
		foreach ( osteza_template_pages( 'zone' ) as $page ) {
			$local = osteza_localize_page( $page->ID );
			$meta  = function ( $key ) use ( $page ) {
				return get_post_meta( $page->ID, 'osteza_z_' . $key, true );
			};

			$cache['zones'][ $page->post_name ] = array(
				'id'       => $page->ID,
				'name'     => $local['title'],
				'url'      => $local['url'],
				'area'     => (string) $meta( 'area' ),
				'coverage' => (string) ( get_post_meta( $local['id'], 'osteza_z_coverage', true ) ?: $meta( 'coverage' ) ),
				'map'      => array( (int) $meta( 'mapx' ), (int) $meta( 'mapy' ) ),
				'geo'      => array( (float) $meta( 'lat' ), (float) $meta( 'lng' ) ),
			);
		}
	}
	return $cache['zones'];
}

/**
 * Zones as slug => name.
 */
function osteza_towns() {
	return wp_list_pluck( osteza_zones(), 'name' );
}

/**
 * Zone by default-language page id or slug; null if there is none.
 */
function osteza_zone_by( $id_or_slug ) {
	foreach ( osteza_zones() as $slug => $zone ) {
		if ( $zone['id'] === (int) $id_or_slug || $slug === $id_or_slug ) {
			return $zone + array( 'slug' => $slug );
		}
	}
	return null;
}
