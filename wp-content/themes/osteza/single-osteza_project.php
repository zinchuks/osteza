<?php
/**
 * Project case study.
 */

the_post();

$p       = osteza_project();
$after   = get_post_thumbnail_id();
$compare = ( $p['before'] && $after ) || $p['sample'];
$zones   = wp_list_pluck( osteza_zones(), 'name' );
$zone    = array_search( $p['locality'], $zones, true );
$facts   = array_filter(
	array(
		__( 'Localidad', 'osteza' ) => $p['locality'],
		__( 'Vivienda', 'osteza' )  => $p['housing'],
		__( 'Servicio', 'osteza' )  => implode( ', ', wp_list_pluck( $p['services'], 'name' ) ),
		__( 'Duración', 'osteza' )  => $p['duration'],
		__( 'Año', 'osteza' )       => $p['year'],
	)
);
$story   = array_filter(
	array(
		__( 'El punto de partida', 'osteza' )     => $p['start'],
		__( 'Lo que acordamos', 'osteza' )        => $p['agreed'],
		__( 'Materiales y ejecución', 'osteza' )  => $p['materials'],
		__( 'El resultado', 'osteza' )            => $p['result'],
	)
);
$prev    = get_adjacent_post( false, '', true );
$next    = get_adjacent_post( false, '', false );

get_header();
?>
<div class="proj">
<section class="phead" aria-labelledby="h1">
<div class="wrap">
<nav aria-label="<?php esc_attr_e( 'Migas de pan', 'osteza' ); ?>" class="bc"><ol><li><a href="<?php echo esc_url( osteza_home_url() ); ?>"><?php esc_html_e( 'Inicio', 'osteza' ); ?></a></li><li><a href="<?php echo osteza_projects_url(); ?>"><?php esc_html_e( 'Proyectos', 'osteza' ); ?></a></li><li aria-current="page"><?php echo esc_html( wp_trim_words( get_the_title(), 5, '…' ) ); ?></li></ol></nav>
<div class="cshead">
<div class="atags">
<?php foreach ( $p['services'] as $service ) : ?><a class="tagp" href="<?php echo osteza_service_url( $service['key'] ?? null ); ?>"><?php echo esc_html( $service['name'] ); ?></a><?php endforeach; ?>
<?php if ( $p['locality'] ) : ?><a class="tagp" href="<?php echo $zone ? osteza_zone_url( $zone ) : '#'; ?>"><?php echo esc_html( $p['locality'] ); ?></a><?php endif; ?>
<?php if ( $p['sample'] ) : ?><span class="tagp tagp-m"><?php esc_html_e( 'Plantilla de caso', 'osteza' ); ?></span><?php endif; ?>
</div>
<h1 id="h1" class="disp"><?php the_title(); ?></h1>
<?php if ( has_excerpt() ) : ?><p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
</div>
<?php if ( $facts ) : ?>
<dl class="facts2">
<?php foreach ( $facts as $term => $value ) : ?>
<div><dt><?php echo esc_html( $term ); ?></dt><dd><?php echo esc_html( $value ); ?></dd></div>
<?php endforeach; ?>
</dl>
<?php endif; ?>
<?php if ( $compare ) : ?>
<figure class="ba">
<div class="bawrap" data-ba>
<div class="bapane after"><?php echo wp_get_attachment_image( $after, 'full' ); ?><span class="bal"><?php esc_html_e( 'Después', 'osteza' ); ?></span><?php if ( ! $after ) : ?><span class="disp bat"><?php esc_html_e( 'DESPUÉS', 'osteza' ); ?></span><?php endif; ?></div>
<div class="bapane before"><?php echo wp_get_attachment_image( $p['before'], 'full' ); ?><span class="bal"><?php esc_html_e( 'Antes', 'osteza' ); ?></span><?php if ( ! $p['before'] ) : ?><span class="disp bat"><?php esc_html_e( 'ANTES', 'osteza' ); ?></span><?php endif; ?></div>
<div class="baline" aria-hidden="true"><span class="bahandle">⟷</span></div>
</div>
<label for="ba" class="balab"><?php esc_html_e( 'Desliza para comparar antes y después', 'osteza' ); ?><input id="ba" type="range" min="0" max="100" value="50"></label>
<?php if ( $p['sample'] ) : ?><figcaption><?php esc_html_e( 'Plantilla: se sustituye por fotos propias del proyecto.', 'osteza' ); ?></figcaption><?php endif; ?>
</figure>
<?php endif; ?>
</div>
</section>

<?php if ( $story ) : ?>
<section class="sec sec-story" aria-label="<?php esc_attr_e( 'Historia del proyecto', 'osteza' ); ?>">
<div class="wrap story">
<?php $n = 0; ?>
<?php foreach ( $story as $heading => $body ) : ?>
<div class="sblock rv"><span class="sn"><?php echo esc_html( sprintf( '%02d', ++$n ) ); ?></span><h2 class="disp"><?php echo esc_html( $heading ); ?></h2>
<?php if ( is_array( $body ) ) : ?>
<ul class="chk"><?php foreach ( $body as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
<?php else : ?>
<p><?php echo nl2br( esc_html( $body ) ); ?></p>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
</section>
<?php endif; ?>

<?php if ( $p['gallery'] || $p['sample'] ) : ?>
<section class="galsec" aria-labelledby="h-gal">
<div class="wrap">
<h2 id="h-gal" class="disp h2"><?php esc_html_e( 'Galería', 'osteza' ); ?></h2>
<ul class="gal">
<?php if ( $p['gallery'] ) : ?>
<?php foreach ( $p['gallery'] as $i => $image ) : ?>
<li class="g<?php echo (int) ( $i % 5 ) + 1; ?>"><a href="<?php echo esc_url( wp_get_attachment_url( $image ) ); ?>" target="_blank" rel="noopener"><?php echo wp_get_attachment_image( $image, 'large' ); ?></a></li>
<?php endforeach; ?>
<?php else : ?>
<?php /* translators: %d: photo number in the gallery placeholder. */ ?>
<?php for ( $i = 1; $i <= 5; $i++ ) : ?><li class="g<?php echo (int) $i; ?>"><span><?php echo esc_html( sprintf( __( 'FOTO %d', 'osteza' ), $i ) ); ?></span></li><?php endfor; ?>
<?php endif; ?>
</ul>
<?php if ( $p['sample'] ) : ?><p class="znote"><?php esc_html_e( 'Fotos propias con texto alternativo descriptivo.', 'osteza' ); ?></p><?php endif; ?>
</div>
</section>
<?php endif; ?>

<?php if ( $p['quote'] ) : ?>
<section class="testisec" aria-label="<?php esc_attr_e( 'Opinión del cliente', 'osteza' ); ?>">
<div class="wrap">
<figure class="testi rv"><blockquote><p class="disp">«<?php echo esc_html( $p['quote'] ); ?>»</p></blockquote><figcaption><?php echo esc_html( $p['quote_author'] ); ?><?php if ( $p['sample'] ) : ?> · <span class="mut"><?php esc_html_e( 'Bloque opcional: se oculta si no hay opinión verificada', 'osteza' ); ?></span><?php endif; ?></figcaption></figure>
</div>
</section>
<?php endif; ?>

<section class="prrel" aria-labelledby="h-rel">
<div class="wrap">
<?php if ( $p['services'] ) : ?>
<div class="prsv"><span id="h-rel"><?php esc_html_e( 'Servicios de este proyecto:', 'osteza' ); ?></span><?php foreach ( $p['services'] as $service ) : ?><a class="tagp" href="<?php echo osteza_service_url( $service['key'] ?? null ); ?>"><?php echo esc_html( $service['name'] ); ?> →</a><?php endforeach; ?></div>
<?php endif; ?>
<?php if ( $prev || $next ) : ?>
<nav class="pnav" aria-label="<?php esc_attr_e( 'Más proyectos', 'osteza' ); ?>">
<?php if ( $prev ) : ?><a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="pprev"><span>← <?php esc_html_e( 'Proyecto anterior', 'osteza' ); ?></span><strong class="disp"><?php echo esc_html( get_the_title( $prev ) ); ?></strong></a><?php else : ?><span></span><?php endif; ?>
<?php if ( $next ) : ?><a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="pnext"><span><?php esc_html_e( 'Proyecto siguiente', 'osteza' ); ?> →</span><strong class="disp"><?php echo esc_html( get_the_title( $next ) ); ?></strong></a><?php endif; ?>
</nav>
<?php endif; ?>
</div>
</section>
</div>
<?php
get_template_part(
	'template-parts/cta',
	null,
	array(
		'title' => __( '¿Un proyecto parecido en tu casa?', 'osteza' ),
		'text'  => __( 'Cuéntanos qué necesitas y te ayudamos a definir el alcance.', 'osteza' ),
	)
);
get_footer();
