<?php
/**
 * Projects: the "osteza_project" post type, its fields and helpers.
 */

const OSTEZA_PROJECT = 'osteza_project';

add_action(
	'init',
	function () {
		register_post_type(
			OSTEZA_PROJECT,
			array(
				'labels'       => array(
					'name'          => __( 'Proyectos', 'osteza' ),
					'singular_name' => __( 'Proyecto', 'osteza' ),
					'add_new_item'  => __( 'Añadir proyecto', 'osteza' ),
					'edit_item'     => __( 'Editar proyecto', 'osteza' ),
				),
				'public'       => true,
				'has_archive'  => 'proyectos',
				'rewrite'      => array( 'slug' => 'proyectos' ),
				'menu_icon'    => 'dashicons-hammer',
				'supports'     => array( 'title', 'excerpt', 'thumbnail' ),
				'show_in_rest' => false,
			)
		);
	}
);

// Projects are written per language, like posts and pages.
add_filter(
	'pll_get_post_types',
	function ( $types ) {
		$types[ OSTEZA_PROJECT ] = OSTEZA_PROJECT;
		return $types;
	}
);

// The archive lists every project; the chips filter them in the browser.
add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( OSTEZA_PROJECT ) ) {
			$query->set( 'posts_per_page', -1 );
		}
	}
);

function osteza_projects_url() {
	return esc_url( get_post_type_archive_link( OSTEZA_PROJECT ) );
}

/**
 * Text fields of a project: meta key suffix => ( label, rows of the textarea or 0 for a single line ).
 */
function osteza_project_fields() {
	return array(
		'locality'     => array( __( 'Localidad', 'osteza' ), 0 ),
		'housing'      => array( __( 'Vivienda (piso, villa, apartamento…)', 'osteza' ), 0 ),
		'duration'     => array( __( 'Duración (solo si consta)', 'osteza' ), 0 ),
		'year'         => array( __( 'Año', 'osteza' ), 0 ),
		'cover'        => array( __( 'Texto de la portada (si no hay foto destacada)', 'osteza' ), 0 ),
		'start'        => array( __( '01 · El punto de partida', 'osteza' ), 3 ),
		'agreed'       => array( __( '02 · Lo que acordamos (una partida por línea)', 'osteza' ), 4 ),
		'materials'    => array( __( '03 · Materiales y ejecución', 'osteza' ), 3 ),
		'result'       => array( __( '04 · El resultado', 'osteza' ), 3 ),
		'quote'        => array( __( 'Opinión del cliente (solo verificada y con permiso)', 'osteza' ), 3 ),
		'quote_author' => array( __( 'Autor de la opinión: nombre o iniciales · localidad', 'osteza' ), 0 ),
	);
}

/**
 * Groups used by the archive filter; keys match osteza_catalog().
 */
function osteza_project_groups() {
	return wp_list_pluck( osteza_catalog(), 'title' );
}

/**
 * Everything the templates need about a project.
 */
function osteza_project( $post = null ) {
	$post = get_post( $post );
	$meta = function ( $key ) use ( $post ) {
		return get_post_meta( $post->ID, 'osteza_p_' . $key, true );
	};

	$p = array(
		'id'     => $post->ID,
		'sample' => (bool) get_post_meta( $post->ID, 'osteza_sample', true ),
		'group'  => (string) $meta( 'group' ),
		'color'  => in_array( $meta( 'color' ), array_keys( osteza_cover_colors() ), true ) ? $meta( 'color' ) : 'g',
		'before' => (int) $meta( 'before' ),
	);
	foreach ( array_keys( osteza_project_fields() ) as $key ) {
		$p[ $key ] = trim( (string) $meta( $key ) );
	}
	$p['agreed']  = array_values( array_filter( array_map( 'trim', explode( "\n", $p['agreed'] ) ) ) );
	$p['gallery'] = array_values( array_filter( array_map( 'intval', explode( ',', (string) $meta( 'gallery' ) ) ) ) );

	// Services of the project: ids of default-language service pages (osteza_all_services()).
	$p['services'] = array();
	foreach ( (array) $meta( 'services' ) as $id ) {
		$service = osteza_service_by( (int) $id );
		if ( $service ) {
			$p['services'][] = $service;
		}
	}
	return $p;
}

/*
 * Editor: one box with all the fields of a project.
 */
add_action(
	'add_meta_boxes_' . OSTEZA_PROJECT,
	function () {
		add_meta_box( 'osteza_project', __( 'Datos del proyecto', 'osteza' ), 'osteza_project_meta_box', OSTEZA_PROJECT, 'normal', 'high' );
	}
);

add_action(
	'admin_enqueue_scripts',
	function () {
		$screen = get_current_screen();
		if ( $screen && OSTEZA_PROJECT === $screen->post_type && 'post' === $screen->base ) {
			wp_enqueue_media();
		}
	}
);

function osteza_project_meta_box( $post ) {
	wp_nonce_field( 'osteza_project', 'osteza_project_nonce' );
	$p        = osteza_project( $post );
	$selected = (array) get_post_meta( $post->ID, 'osteza_p_services', true );
	?>
	<p><?php esc_html_e( 'El extracto es el resumen del proyecto y la imagen destacada es la foto de «después» y la portada de la tarjeta.', 'osteza' ); ?></p>
	<p><strong><?php esc_html_e( 'Servicios', 'osteza' ); ?></strong><br>
	<?php foreach ( osteza_all_services() as $service ) : ?>
		<label style="margin-right:14px;display:inline-block"><input type="checkbox" name="osteza_p_services[]" value="<?php echo (int) $service['id']; ?>"<?php checked( in_array( $service['id'], array_map( 'intval', $selected ), true ) ); ?>> <?php echo esc_html( $service['name'] ); ?></label>
	<?php endforeach; ?>
	</p>
	<p><label><strong><?php esc_html_e( 'Grupo (filtro del archivo)', 'osteza' ); ?></strong><br>
	<select name="osteza_p_group"><option value="">—</option>
	<?php foreach ( osteza_project_groups() as $key => $label ) : ?>
		<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $p['group'], $key ); ?>><?php echo esc_html( $label ); ?></option>
	<?php endforeach; ?>
	</select></label>
	<label style="margin-left:14px"><strong><?php esc_html_e( 'Color de la portada', 'osteza' ); ?></strong>
	<select name="osteza_p_color">
	<?php foreach ( osteza_cover_colors() as $key => $label ) : ?>
		<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $p['color'], $key ); ?>><?php echo esc_html( $label ); ?></option>
	<?php endforeach; ?>
	</select></label></p>
	<?php foreach ( osteza_project_fields() as $key => $field ) : ?>
		<?php $value = get_post_meta( $post->ID, 'osteza_p_' . $key, true ); ?>
		<p><label><strong><?php echo esc_html( $field[0] ); ?></strong><br>
		<?php if ( $field[1] ) : ?>
			<textarea name="osteza_p_<?php echo esc_attr( $key ); ?>" rows="<?php echo (int) $field[1]; ?>" style="width:100%"><?php echo esc_textarea( $value ); ?></textarea>
		<?php else : ?>
			<input type="text" name="osteza_p_<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>" style="width:100%">
		<?php endif; ?>
		</label></p>
	<?php endforeach; ?>
	<?php
	$pickers = array(
		'before'  => array( __( 'Foto de «antes»', 'osteza' ), false ),
		'gallery' => array( __( 'Galería', 'osteza' ), true ),
	);
	foreach ( $pickers as $key => $picker ) :
		$ids = array_filter( array_map( 'intval', explode( ',', (string) get_post_meta( $post->ID, 'osteza_p_' . $key, true ) ) ) );
		?>
		<p data-osteza-media="<?php echo $picker[1] ? 'multiple' : 'single'; ?>"><strong><?php echo esc_html( $picker[0] ); ?></strong><br>
		<input type="hidden" name="osteza_p_<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>">
		<span data-preview><?php foreach ( $ids as $id ) : ?><?php echo wp_get_attachment_image( $id, array( 80, 80 ), false, array( 'style' => 'margin:0 6px 6px 0' ) ); ?><?php endforeach; ?></span><br>
		<button type="button" class="button" data-choose><?php esc_html_e( 'Elegir', 'osteza' ); ?></button> <button type="button" class="button-link" data-clear><?php esc_html_e( 'Quitar', 'osteza' ); ?></button></p>
	<?php endforeach; ?>
	<p><label><input type="checkbox" name="osteza_sample" value="1"<?php checked( $p['sample'] ); ?>> <?php esc_html_e( 'Plantilla de ejemplo (se marca como «Plantilla» en la web)', 'osteza' ); ?></label></p>
	<script>
	document.querySelectorAll('[data-osteza-media]').forEach(function (box) {
		var input = box.querySelector('input'), preview = box.querySelector('[data-preview]');
		box.querySelector('[data-choose]').addEventListener('click', function () {
			var frame = wp.media({ multiple: box.dataset.ostezaMedia === 'multiple', library: { type: 'image' } });
			frame.on('select', function () {
				var items = frame.state().get('selection').toJSON();
				input.value = items.map(function (i) { return i.id; }).join(',');
				preview.textContent = '';
				items.forEach(function (i) {
					var img = document.createElement('img');
					img.src = (i.sizes && i.sizes.thumbnail ? i.sizes.thumbnail : i).url;
					img.width = 80; img.height = 80; img.style.margin = '0 6px 6px 0';
					preview.appendChild(img);
				});
			});
			frame.open();
		});
		box.querySelector('[data-clear]').addEventListener('click', function () { input.value = ''; preview.textContent = ''; });
	});
	</script>
	<?php
}

add_action(
	'save_post_' . OSTEZA_PROJECT,
	function ( $post_id ) {
		if ( ! isset( $_POST['osteza_project_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['osteza_project_nonce'] ), 'osteza_project' ) || ! current_user_can( 'edit_post', $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}
		foreach ( osteza_project_fields() as $key => $field ) {
			$value = wp_unslash( $_POST[ 'osteza_p_' . $key ] ?? '' );
			update_post_meta( $post_id, 'osteza_p_' . $key, $field[1] ? sanitize_textarea_field( $value ) : sanitize_text_field( $value ) );
		}
		update_post_meta( $post_id, 'osteza_p_group', sanitize_key( $_POST['osteza_p_group'] ?? '' ) );
		update_post_meta( $post_id, 'osteza_p_color', sanitize_key( $_POST['osteza_p_color'] ?? '' ) );
		update_post_meta( $post_id, 'osteza_p_services', array_map( 'intval', (array) ( $_POST['osteza_p_services'] ?? array() ) ) );
		foreach ( array( 'before', 'gallery' ) as $key ) {
			$ids = array_filter( array_map( 'intval', explode( ',', sanitize_text_field( wp_unslash( $_POST[ 'osteza_p_' . $key ] ?? '' ) ) ) ) );
			update_post_meta( $post_id, 'osteza_p_' . $key, implode( ',', $ids ) );
		}
		if ( empty( $_POST['osteza_sample'] ) ) {
			delete_post_meta( $post_id, 'osteza_sample' );
		} else {
			update_post_meta( $post_id, 'osteza_sample', 1 );
		}
	}
);
