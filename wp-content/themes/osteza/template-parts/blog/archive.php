<?php
/**
 * Blog listing: header with search and category chips, featured article, post grid, pager.
 *
 * Args: 'query' (WP_Query to list), 'category' (current WP_Term or null), 'base' (URL of page 1).
 */

$query    = $args['query'];
$category = $args['category'] ?? null;
$search   = is_search() ? get_search_query() : '';
$blog     = osteza_blog_url();
$featured = osteza_shows_featured( $category ? $category->term_id : 0 ) ? osteza_featured_post() : null;
$paged    = max( 1, get_query_var( 'paged' ) );
$pages    = (int) $query->max_num_pages;
$total    = (int) $query->found_posts;
$link     = function ( $n ) use ( $args ) {
	return esc_url( 1 === $n ? $args['base'] : get_pagenum_link( $n ) );
};

if ( $search ) {
	$title = __( 'Resultados', 'osteza' );
} else {
	$title = $category ? $category->name : __( 'Últimos artículos', 'osteza' );
}

$sample = $featured && osteza_is_sample( $featured );
foreach ( $query->posts as $p ) {
	$sample = $sample || osteza_is_sample( $p );
}
?>
<section class="phead" aria-labelledby="h1">
<div class="wrap">
<nav aria-label="<?php esc_attr_e( 'Migas de pan', 'osteza' ); ?>" class="bc"><ol><li><a href="<?php echo esc_url( osteza_home_url() ); ?>"><?php esc_html_e( 'Inicio', 'osteza' ); ?></a></li>
<?php if ( $category || $search ) : ?>
<li><a href="<?php echo $blog; ?>"><?php esc_html_e( 'Blog', 'osteza' ); ?></a></li><li aria-current="page"><?php echo esc_html( $title ); ?></li>
<?php else : ?>
<li aria-current="page"><?php esc_html_e( 'Blog', 'osteza' ); ?></li>
<?php endif; ?>
</ol></nav>
<div class="bhead">
<div class="bheadcopy">
<p class="kick"><?php esc_html_e( 'Blog', 'osteza' ); ?></p>
<h1 id="h1" class="disp"><?php esc_html_e( 'Ideas y consejos para tu hogar.', 'osteza' ); ?></h1>
<p class="lead"><?php esc_html_e( 'Guías prácticas para planificar una reforma, elegir materiales y preparar tu vivienda. Sin tecnicismos innecesarios.', 'osteza' ); ?></p>
</div>
<form role="search" class="bsearch" method="get" action="<?php echo esc_url( osteza_home_url() ); ?>">
<label for="bq" class="sr"><?php esc_html_e( 'Buscar en el blog', 'osteza' ); ?></label>
<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
<input id="bq" type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Buscar artículos', 'osteza' ); ?>">
</form>
</div>
<nav class="chips" aria-label="<?php esc_attr_e( 'Filtrar por categoría', 'osteza' ); ?>">
<a class="chip" href="<?php echo $blog; ?>"<?php echo ! $category && ! $search ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'Todos', 'osteza' ); ?><span class="chipn"><?php echo (int) wp_count_posts()->publish; ?></span></a>
<?php foreach ( get_categories( array( 'orderby' => 'id' ) ) as $cat ) : ?>
<a class="chip" href="<?php echo esc_url( get_category_link( $cat ) ); ?>"<?php echo $category && $category->term_id === $cat->term_id ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $cat->name ); ?><span class="chipn"><?php echo (int) $cat->count; ?></span></a>
<?php endforeach; ?>
</nav>
<?php if ( $sample ) : ?>
<p class="draftnote"><?php echo osteza_icon( 'info', 18, 'currentColor', '1.8' ); ?><?php esc_html_e( 'Contenido de ejemplo: los artículos se publicarán cuando estén escritos y revisados.', 'osteza' ); ?></p>
<?php endif; ?>
</div>
</section>

<?php if ( $featured ) : ?>
<?php $cover = osteza_cover( $featured ); ?>
<section class="featsec" aria-label="<?php esc_attr_e( 'Artículo destacado', 'osteza' ); ?>">
<div class="wrap">
<article class="feat rv">
<div class="fcover dk" aria-hidden="true">
<img class="fcoverbg" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="">
<?php echo osteza_art( $cover['icon'], 64, '#EBA3AE' ); ?>
<span class="disp"><?php echo osteza_cover_text_html( $cover['text'] ); ?></span>
</div>
<div class="featcopy">
<p class="pcat"><?php echo esc_html( implode( ' · ', array_filter( array( __( 'Destacado', 'osteza' ), osteza_post_category( $featured )->name ?? '' ) ) ) ); ?></p>
<h2 class="disp"><a href="<?php echo esc_url( get_permalink( $featured ) ); ?>"><?php echo esc_html( get_the_title( $featured ) ); ?></a></h2>
<p class="lead"><?php echo esc_html( get_the_excerpt( $featured ) ); ?></p>
<?php /* translators: %d: minutes of reading. */ ?>
<p class="pmeta"><?php echo esc_html( get_the_date( '', $featured ) ); ?> · <?php echo esc_html( sprintf( __( '%d min de lectura', 'osteza' ), osteza_reading_minutes( $featured ) ) ); ?></p>
<a class="btn btn-p" href="<?php echo esc_url( get_permalink( $featured ) ); ?>"><?php esc_html_e( 'Leer artículo', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
</div>
</article>
</div>
</section>
<?php endif; ?>

<section class="blist" aria-labelledby="h-list">
<div class="wrap">
<?php /* translators: %s: number of articles. */ ?>
<div class="blisth"><h2 id="h-list" class="disp"><?php echo esc_html( $title ); ?></h2><span><?php echo esc_html( sprintf( _n( '%s artículo', '%s artículos', $total, 'osteza' ), number_format_i18n( $total ) ) ); ?></span></div>
<?php if ( $query->posts ) : ?>
<ul class="pgrid2">
<?php
foreach ( $query->posts as $p ) {
	get_template_part( 'template-parts/blog/card', null, array( 'post' => $p, 'class' => 'rv' ) );
}
?>
</ul>
<?php else : ?>
<div class="bempty"><strong><?php esc_html_e( 'No hay artículos que coincidan.', 'osteza' ); ?></strong><span><?php esc_html_e( 'Prueba con otra categoría o con otra búsqueda.', 'osteza' ); ?></span><a class="btn btn-o" href="<?php echo $blog; ?>"><?php esc_html_e( 'Ver todos los artículos', 'osteza' ); ?></a></div>
<?php endif; ?>
<?php if ( $pages > 1 ) : ?>
<nav aria-label="<?php esc_attr_e( 'Paginación', 'osteza' ); ?>" class="pager">
<a href="<?php echo $link( max( 1, $paged - 1 ) ); ?>" aria-label="<?php esc_attr_e( 'Página anterior', 'osteza' ); ?>" class="pg<?php echo 1 === $paged ? ' pgoff" aria-disabled="true' : ''; ?>">←</a>
<?php for ( $n = 1; $n <= $pages; $n++ ) : ?>
<a href="<?php echo $link( $n ); ?>" class="pg<?php echo $n === $paged ? ' pgon" aria-current="page' : ''; ?>"><?php echo (int) $n; ?></a>
<?php endfor; ?>
<a href="<?php echo $link( min( $pages, $paged + 1 ) ); ?>" aria-label="<?php esc_attr_e( 'Página siguiente', 'osteza' ); ?>" class="pg<?php echo $paged === $pages ? ' pgoff" aria-disabled="true' : ''; ?>">→</a>
</nav>
<?php endif; ?>
</div>
</section>

<?php
get_template_part(
	'template-parts/cta',
	null,
	array(
		'title' => __( '¿Tienes un proyecto en mente?', 'osteza' ),
		'text'  => __( 'Cuéntanos qué necesitas y te ayudamos a definir el siguiente paso.', 'osteza' ),
	)
);
