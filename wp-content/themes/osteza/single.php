<?php
/**
 * Blog article.
 */

the_post();

$category = osteza_post_category();
$cover    = osteza_cover();
$blog     = osteza_blog_url();
$mark     = osteza_img( 'mark-light.svg' );
$author   = get_the_author();
$bio      = get_the_author_meta( 'description' );
$url      = get_permalink();
$new_tab  = '<span class="sr"> ' . esc_html__( '(abre una pestaña nueva)', 'osteza' ) . '</span>';

list( $content, $toc ) = osteza_article();

$related = get_posts(
	array(
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => true,
	)
);
$services = array();
foreach ( array_slice( osteza_services(), 0, 3 ) as $service ) {
	$services[ $service['name'] ] = $service['url'];
}

get_header();
?>
<article aria-labelledby="h1">
<header class="phead">
<div class="wrap">
<nav aria-label="<?php esc_attr_e( 'Migas de pan', 'osteza' ); ?>" class="bc"><ol><li><a href="<?php echo esc_url( osteza_home_url() ); ?>"><?php esc_html_e( 'Inicio', 'osteza' ); ?></a></li><li><a href="<?php echo $blog; ?>"><?php esc_html_e( 'Blog', 'osteza' ); ?></a></li><?php if ( $category ) : ?><li><a href="<?php echo esc_url( get_category_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a></li><?php endif; ?><li aria-current="page"><?php echo esc_html( wp_trim_words( get_the_title(), 4, '…' ) ); ?></li></ol></nav>
<div class="aheadin">
<div class="atags">
<?php if ( $category ) : ?><a class="tagp" href="<?php echo esc_url( get_category_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a><?php endif; ?>
<?php if ( osteza_is_sample() ) : ?><span class="tagp tagp-m"><?php esc_html_e( 'Borrador · contenido de ejemplo', 'osteza' ); ?></span><?php endif; ?>
</div>
<h1 id="h1" class="disp"><?php the_title(); ?></h1>
<?php if ( has_excerpt() ) : ?><p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
<div class="ameta">
<span class="aav" aria-hidden="true"><img src="<?php echo $mark; ?>" alt=""></span>
<span><strong><?php echo esc_html( $author ); ?></strong><span class="mut"><?php esc_html_e( 'Equipo OSTEZA', 'osteza' ); ?></span></span>
<span class="msep" aria-hidden="true"></span>
<span><span class="mlab"><?php esc_html_e( 'Publicado', 'osteza' ); ?></span><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
<span><span class="mlab"><?php esc_html_e( 'Actualizado', 'osteza' ); ?></span><time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time></span>
<?php /* translators: %d: minutes of reading. */ ?>
<span><span class="mlab"><?php esc_html_e( 'Lectura', 'osteza' ); ?></span><?php echo esc_html( sprintf( __( '%d min', 'osteza' ), osteza_reading_minutes() ) ); ?></span>
</div>
</div>
<div class="acover dk" aria-hidden="true">
<img class="acoverbg" src="<?php echo $mark; ?>" alt="">
<?php echo osteza_art( $cover['icon'], 72, '#EBA3AE' ); ?>
<span class="disp"><?php echo osteza_cover_text_html( $cover['text'] ); ?></span>
</div>
</div>
</header>

<div class="wrap agrid abody">
<aside class="aside">
<?php if ( $toc ) : ?>
<nav class="toc" aria-labelledby="toc-t">
<p id="toc-t" class="kick"><?php esc_html_e( 'En este artículo', 'osteza' ); ?></p>
<ol><?php foreach ( $toc as $id => $label ) : ?><li><a href="#<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></a></li><?php endforeach; ?></ol>
</nav>
<?php endif; ?>
<div class="asidecta dk"><p class="disp"><?php esc_html_e( '¿Preparando una reforma?', 'osteza' ); ?></p><p><?php esc_html_e( 'Te ayudamos a definir el alcance.', 'osteza' ); ?></p><a class="btn btn-p" href="<?php echo osteza_anchor( 'contacto' ); ?>"><?php esc_html_e( 'Solicitar presupuesto', 'osteza' ); ?></a></div>
</aside>

<div class="prose">
<?php if ( $toc ) : ?>
<details class="tocm"><summary><?php esc_html_e( 'En este artículo', 'osteza' ); ?> <span aria-hidden="true">+</span></summary><ol><?php foreach ( $toc as $id => $label ) : ?><li><a href="#<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></a></li><?php endforeach; ?></ol></details>
<?php endif; ?>

<?php echo $content; ?>

<div class="share"><span><?php esc_html_e( 'Compartir', 'osteza' ); ?></span>
<button type="button" class="cpill2" data-copy-link><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/></svg><span><?php esc_html_e( 'Copiar enlace', 'osteza' ); ?></span></button>
<a class="cpill2" href="<?php echo esc_url( 'https://wa.me/?text=' . rawurlencode( get_the_title() . ' ' . $url ) ); ?>" target="_blank" rel="noopener"><?php echo osteza_icon( 'whatsapp', 18 ); ?>WhatsApp<?php echo $new_tab; ?></a>
<a class="cpill2" href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $url ) ); ?>" target="_blank" rel="noopener"><?php echo osteza_icon( 'facebook', 18 ); ?>Facebook<?php echo $new_tab; ?></a>
</div>

<div class="author"><span class="aav" aria-hidden="true"><img src="<?php echo $mark; ?>" alt=""></span><div><strong><?php echo esc_html( $author ); ?></strong><p><?php echo esc_html( $bio ? $bio : __( '[Breve presentación real del autor y su relación con OSTEZA.]', 'osteza' ) ); ?></p></div></div>
</div>
</div>
</article>

<section class="arel" aria-labelledby="h-rel">
<div class="wrap">
<div class="arelh"><h2 id="h-rel" class="disp h2"><?php esc_html_e( 'Sigue leyendo.', 'osteza' ); ?></h2><a class="lnk" href="<?php echo $blog; ?>"><?php esc_html_e( 'Todos los artículos', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a></div>
<?php if ( $related ) : ?>
<ul class="pgrid2">
<?php
foreach ( $related as $p ) {
	get_template_part( 'template-parts/blog/card', null, array( 'post' => $p, 'icon' => 40, 'details' => false ) );
}
?>
</ul>
<?php endif; ?>
<div class="svlinks"><span><?php esc_html_e( 'Servicios relacionados:', 'osteza' ); ?></span><?php foreach ( $services as $label => $key ) : ?><a class="tagp" href="<?php echo esc_url( $key ); ?>"><?php echo esc_html( $label ); ?> →</a><?php endforeach; ?></div>
</div>
</section>

<?php
get_template_part(
	'template-parts/cta',
	null,
	array(
		'class' => 'ctasec-a',
		'title' => __( '¿Listo para empezar tu reforma?', 'osteza' ),
		'text'  => __( 'Cuéntanos qué necesitas y preparamos el siguiente paso contigo.', 'osteza' ),
	)
);
get_footer();
