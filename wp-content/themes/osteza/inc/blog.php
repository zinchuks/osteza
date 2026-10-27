<?php
/**
 * Blog: post covers, featured post, archive queries, article helpers.
 */

const OSTEZA_POSTS_PER_PAGE = 9;

/**
 * Line illustrations a post cover can use: key => ( label, SVG on a 48×48 grid ).
 */
function osteza_cover_icons() {
	return array(
		'calendar' => array( __( 'Calendario', 'osteza' ), '<rect x="8" y="12" width="32" height="26" rx="2"/><path d="M8 20h32M16 8v8M32 8v8"/>' ),
		'document' => array( __( 'Documento', 'osteza' ), '<path d="M10 6h28v36H10zM16 14h16M16 20h16M16 26h10"/>' ),
		'question' => array( __( 'Pregunta', 'osteza' ), '<path d="M24 40a16 16 0 1 0 0-32 16 16 0 0 0 0 32zM19 19a5 5 0 1 1 7 4.6c-1.3.6-2 1.6-2 3V28M24 33v.5"/>' ),
		'kitchen'  => array( __( 'Cocina', 'osteza' ), '<path d="M6 10h36v10H6zM6 26h36v14H6zM18 26v14M30 26v14"/>' ),
		'bath'     => array( __( 'Baño', 'osteza' ), '<path d="M12 40V15a6 6 0 0 1 12 0v2M20 17h8M22 23v3M26 23v3M30 23v3M8 40h32"/>' ),
		'floor'    => array( __( 'Suelo', 'osteza' ), '<path d="M6 38h36M10 30h28M14 22h20M18 22l-6 16M30 22l6 16M24 22v16"/>' ),
		'paint'    => array( __( 'Pintura', 'osteza' ), '<path d="M10 8h24v10H10zM34 13h4v9H22v6M19 28h6v12h-6z"/>' ),
		'wardrobe' => array( __( 'Armario', 'osteza' ), '<path d="M10 8h22v32H10zM21 8v32M18 22v4M24 22v4M36 30l5-5"/>' ),
		'waves'    => array( __( 'Mar', 'osteza' ), '<path d="M4 30c4 0 4-4 8-4s4 4 8 4 4-4 8-4 4 4 8 4 4-4 8-4M4 38c4 0 4-4 8-4s4 4 8 4 4-4 8-4 4 4 8 4 4-4 8-4M24 6v12"/>' ),
		'truck'    => array( __( 'Furgoneta', 'osteza' ), '<path d="M4 10h25v24H4zM29 17h8l7 8v9H29zM17 34h4"/><circle cx="12" cy="36" r="4"/><circle cx="36" cy="36" r="4"/>' ),
	);
}

function osteza_cover_colors() {
	return array(
		'g' => __( 'Grafito', 'osteza' ),
		'b' => __( 'Azul petróleo', 'osteza' ),
		's' => __( 'Arena', 'osteza' ),
		'w' => __( 'Blanco', 'osteza' ),
		'l' => __( 'Azul claro', 'osteza' ),
	);
}

/**
 * Cover of a post: text (two short lines), colour class and icon markup.
 * Unset fields fall back to the start of the title, a colour picked by ID, and the calendar.
 */
function osteza_cover( $post = null ) {
	$post   = get_post( $post );
	$icons  = osteza_cover_icons();
	$colors = array_keys( osteza_cover_colors() );

	$text  = trim( (string) get_post_meta( $post->ID, 'osteza_cover_text', true ) );
	$color = get_post_meta( $post->ID, 'osteza_cover_color', true );
	$icon  = get_post_meta( $post->ID, 'osteza_cover_icon', true );

	return array(
		'text' => '' !== $text ? $text : wp_trim_words( $post->post_title, 3, '' ),
		'cls'  => 'cv-' . ( in_array( $color, $colors, true ) ? $color : $colors[ $post->ID % count( $colors ) ] ),
		'icon' => $icons[ isset( $icons[ $icon ] ) ? $icon : 'calendar' ][1],
	);
}

function osteza_cover_text_html( $text ) {
	return nl2br( esc_html( $text ) );
}

/**
 * Sample posts carry example copy; the theme labels them until real articles replace them.
 */
function osteza_is_sample( $post = null ) {
	return (bool) get_post_meta( get_post( $post )->ID, 'osteza_sample', true );
}

function osteza_post_category( $post = null ) {
	$cats = get_the_category( get_post( $post )->ID );
	return $cats ? $cats[0] : null;
}

/**
 * Category line of a post card, e.g. "Cocinas y baños · Borrador".
 */
function osteza_post_kicker( $post = null ) {
	$cat = osteza_post_category( $post );
	return implode( ' · ', array_filter( array( $cat ? $cat->name : '', osteza_is_sample( $post ) ? __( 'Borrador', 'osteza' ) : '' ) ) );
}

function osteza_reading_minutes( $post = null ) {
	$words = count( preg_split( '/\s+/u', trim( wp_strip_all_tags( get_post( $post )->post_content ) ), -1, PREG_SPLIT_NO_EMPTY ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * URL of the blog archive: the page using the "Blog" template, in the current language.
 */
function osteza_blog_url() {
	return osteza_template_page_url( 'blog' ) ?: osteza_anchor( 'blog' );
}

function osteza_is_blog() {
	return is_page_template( 'page-templates/blog.php' ) || is_singular( 'post' ) || is_category() || is_search();
}

/**
 * The featured article: the most recent sticky post in the current language.
 */
function osteza_featured_post() {
	static $post = false;
	if ( false === $post ) {
		$sticky = get_option( 'sticky_posts' );
		$found  = $sticky ? get_posts(
			array(
				'post__in'            => $sticky,
				'posts_per_page'      => 1,
				'ignore_sticky_posts' => true,
			)
		) : array();
		$post   = $found ? $found[0] : null;
	}
	return $post;
}

/**
 * Whether the featured article leads the listing being shown: first page of the
 * whole blog, or of the category it belongs to.
 */
function osteza_shows_featured( $category = 0 ) {
	$featured = osteza_featured_post();
	if ( ! $featured || is_search() || max( 1, get_query_var( 'paged' ) ) > 1 ) {
		return false;
	}
	return ! $category || has_category( $category, $featured );
}

/**
 * Posts of the blog page. The featured article is listed separately, so it is left out.
 */
function osteza_blog_query() {
	$featured = osteza_featured_post();
	return new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => OSTEZA_POSTS_PER_PAGE,
			'paged'               => max( 1, get_query_var( 'paged' ) ),
			'ignore_sticky_posts' => true,
			'post__not_in'        => $featured ? array( $featured->ID ) : array(),
		)
	);
}

// Category and search listings share the blog layout: same page size, posts only, featured article apart.
add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! ( $query->is_category() || $query->is_search() ) ) {
			return;
		}
		$query->set( 'post_type', 'post' );
		$query->set( 'posts_per_page', OSTEZA_POSTS_PER_PAGE );

		$featured = osteza_featured_post();
		if ( $featured && $query->is_category() && has_category( $query->get_queried_object_id(), $featured ) ) {
			$query->set( 'post__not_in', array( $featured->ID ) );
		}
	}
);

/**
 * Article body with an id on every h2, and the table of contents built from them.
 *
 * @return array{0: string, 1: array<string, string>} HTML and ( id => heading text ).
 */
function osteza_article( $post = null ) {
	$toc  = array();
	$html = apply_filters( 'the_content', get_post( $post )->post_content );
	$html = preg_replace_callback(
		'#<h2([^>]*)>(.*?)</h2>#s',
		function ( $m ) use ( &$toc ) {
			$id = preg_match( '/\sid="([^"]+)"/', $m[1], $found ) ? $found[1] : 'p' . ( count( $toc ) + 1 );
			// A leading "1. " numbers the heading in the text but is noise in the contents list.
			$toc[ $id ] = preg_replace( '/^\d+\.\s*/', '', wp_strip_all_tags( $m[2] ) );
			return isset( $found[1] ) ? $m[0] : '<h2' . $m[1] . ' id="' . esc_attr( $id ) . '">' . $m[2] . '</h2>';
		},
		$html
	);
	return array( $html, $toc );
}

/**
 * [osteza_cta title="…" text="…"] — call-to-action card inside an article.
 */
add_shortcode(
	'osteza_cta',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'title' => __( '¿Quieres que lo revisemos contigo?', 'osteza' ),
				'text'  => __( 'Te ayudamos a definir el alcance de tu reforma y a preparar un presupuesto claro.', 'osteza' ),
			),
			$atts
		);
		return sprintf(
			'<div class="inlcta"><div><strong class="disp">%s</strong><p>%s</p></div><a class="btn btn-p" href="%s">%s %s</a></div>',
			esc_html( $atts['title'] ),
			esc_html( $atts['text'] ),
			osteza_anchor( 'contacto' ),
			esc_html__( 'Solicitar presupuesto', 'osteza' ),
			osteza_arrow()
		);
	}
);

/*
 * Editor: "Portada" box on posts for the cover text, colour, icon and the sample flag.
 */
add_action(
	'add_meta_boxes_post',
	function () {
		add_meta_box( 'osteza_cover', __( 'Portada del artículo', 'osteza' ), 'osteza_cover_meta_box', 'post', 'side' );
	}
);

function osteza_cover_meta_box( $post ) {
	wp_nonce_field( 'osteza_cover', 'osteza_cover_nonce' );
	$color = get_post_meta( $post->ID, 'osteza_cover_color', true );
	$icon  = get_post_meta( $post->ID, 'osteza_cover_icon', true );
	?>
	<p><label for="osteza_cover_text"><strong><?php esc_html_e( 'Texto (dos líneas cortas)', 'osteza' ); ?></strong></label>
	<textarea id="osteza_cover_text" name="osteza_cover_text" rows="2" style="width:100%"><?php echo esc_textarea( get_post_meta( $post->ID, 'osteza_cover_text', true ) ); ?></textarea></p>
	<p><label for="osteza_cover_color"><strong><?php esc_html_e( 'Color', 'osteza' ); ?></strong></label>
	<select id="osteza_cover_color" name="osteza_cover_color" style="width:100%"><option value=""><?php esc_html_e( 'Automático', 'osteza' ); ?></option>
	<?php foreach ( osteza_cover_colors() as $key => $label ) : ?>
		<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $color, $key ); ?>><?php echo esc_html( $label ); ?></option>
	<?php endforeach; ?>
	</select></p>
	<p><label for="osteza_cover_icon"><strong><?php esc_html_e( 'Icono', 'osteza' ); ?></strong></label>
	<select id="osteza_cover_icon" name="osteza_cover_icon" style="width:100%">
	<?php foreach ( osteza_cover_icons() as $key => $item ) : ?>
		<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $icon, $key ); ?>><?php echo esc_html( $item[0] ); ?></option>
	<?php endforeach; ?>
	</select></p>
	<p><label><input type="checkbox" name="osteza_sample" value="1"<?php checked( osteza_is_sample( $post ) ); ?>> <?php esc_html_e( 'Contenido de ejemplo (se marca como «Borrador» en la web)', 'osteza' ); ?></label></p>
	<?php
}

add_action(
	'save_post_post',
	function ( $post_id ) {
		if ( ! isset( $_POST['osteza_cover_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['osteza_cover_nonce'] ), 'osteza_cover' ) || ! current_user_can( 'edit_post', $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}
		update_post_meta( $post_id, 'osteza_cover_text', sanitize_textarea_field( wp_unslash( $_POST['osteza_cover_text'] ?? '' ) ) );
		update_post_meta( $post_id, 'osteza_cover_color', sanitize_key( $_POST['osteza_cover_color'] ?? '' ) );
		update_post_meta( $post_id, 'osteza_cover_icon', sanitize_key( $_POST['osteza_cover_icon'] ?? '' ) );
		if ( empty( $_POST['osteza_sample'] ) ) {
			delete_post_meta( $post_id, 'osteza_sample' );
		} else {
			update_post_meta( $post_id, 'osteza_sample', 1 );
		}
	}
);
