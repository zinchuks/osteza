<?php
/**
 * Projects archive. Shows an explanatory empty state until the first project is published.
 */

$projects = $wp_query->posts;
$contact  = osteza_anchor( 'contacto' );
$sample   = false;
foreach ( $projects as $project ) {
	$sample = $sample || get_post_meta( $project->ID, 'osteza_sample', true );
}
$will = array(
	__( 'Fotos propias de antes y después', 'osteza' ),
	__( 'Localidad y tipo de vivienda', 'osteza' ),
	__( 'Punto de partida y alcance acordado', 'osteza' ),
	__( 'Materiales y forma de ejecución', 'osteza' ),
	__( 'Duración, solo si está documentada', 'osteza' ),
);

get_header();
?>
<div class="proj">
<section class="phead" aria-labelledby="h1">
<div class="wrap">
<nav aria-label="<?php esc_attr_e( 'Migas de pan', 'osteza' ); ?>" class="bc"><ol><li><a href="<?php echo esc_url( osteza_home_url() ); ?>"><?php esc_html_e( 'Inicio', 'osteza' ); ?></a></li><li aria-current="page"><?php esc_html_e( 'Proyectos', 'osteza' ); ?></li></ol></nav>
<div class="prhead">
<div class="prheadcopy">
<p class="kick"><?php esc_html_e( 'Proyectos', 'osteza' ); ?></p>
<h1 id="h1" class="disp"><?php echo wp_kses( __( 'Trabajo real, <span>contado con detalle.</span>', 'osteza' ), array( 'span' => array() ) ); ?></h1>
<p class="lead"><?php esc_html_e( 'Cada proyecto muestra fotos propias, el punto de partida, lo que acordamos y el resultado. Nada de imágenes de catálogo.', 'osteza' ); ?></p>
</div>
</div>
</div>
</section>

<?php if ( ! $projects ) : ?>
<section class="sec sec-empty" aria-labelledby="h-empty">
<div class="wrap">
<div class="empty rv">
<div class="emptycopy">
<span class="eic" aria-hidden="true"><img src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt=""></span>
<h2 id="h-empty" class="disp"><?php esc_html_e( 'Estamos documentando nuestros primeros proyectos.', 'osteza' ); ?></h2>
<p><?php esc_html_e( 'Preferimos esperar a tener fotos reales antes que enseñarte imágenes que no son nuestras. Mientras tanto, cuéntanos tu proyecto.', 'osteza' ); ?></p>
<div class="otherbtns"><a class="btn btn-p" href="<?php echo $contact; ?>"><?php esc_html_e( 'Solicitar presupuesto', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a><a class="btn btn-ol" href="<?php echo osteza_catalog_url(); ?>"><?php esc_html_e( 'Ver servicios', 'osteza' ); ?></a><?php if ( '#' !== osteza_social_url( 'instagram' ) ) : ?><a class="btn btn-ol" href="<?php echo osteza_social_url( 'instagram' ); ?>" target="_blank" rel="noopener"><?php echo osteza_icon( 'instagram', 20 ); ?><?php esc_html_e( 'Nuestro trabajo en Instagram', 'osteza' ); ?><span class="sr"> <?php esc_html_e( '(abre una pestaña nueva)', 'osteza' ); ?></span></a><?php endif; ?></div>
</div>
<div class="willbox">
<p class="kick"><?php esc_html_e( 'Cada proyecto incluirá', 'osteza' ); ?></p>
<ul class="chk no"><?php foreach ( $will as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
</div>
</div>
</div>
</section>
<?php else : ?>
<section class="prlist" aria-labelledby="h-list">
<div class="wrap stack40">
<div class="chips" role="group" aria-label="<?php esc_attr_e( 'Filtrar por servicio', 'osteza' ); ?>"><button type="button" class="chip" aria-pressed="true" data-project-filter=""><?php esc_html_e( 'Todos', 'osteza' ); ?></button><?php foreach ( osteza_project_groups() as $key => $label ) : ?><button type="button" class="chip" aria-pressed="false" data-project-filter="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></button><?php endforeach; ?></div>
<h2 id="h-list" class="sr"><?php esc_html_e( 'Listado de proyectos', 'osteza' ); ?></h2>
<?php if ( $sample ) : ?>
<p class="draftnote"><?php esc_html_e( 'Vista de plantilla: así se mostrará el archivo cuando haya proyectos publicados.', 'osteza' ); ?></p>
<?php endif; ?>
<ul class="pgrid2">
<?php foreach ( $projects as $project ) : ?>
<?php
$p     = osteza_project( $project );
$chips = array_filter( array( $p['services'] ? $p['services'][0]['name'] : '', $p['locality'] ) );
?>
<li class="pcard rv" data-project-group="<?php echo esc_attr( $p['group'] ); ?>">
<div class="pcover cv-<?php echo esc_attr( $p['color'] ); ?>" aria-hidden="true"><?php echo get_the_post_thumbnail( $project, 'large', array( 'class' => 'pcimg', 'alt' => '' ) ); ?><?php if ( $p['sample'] ) : ?><span class="ptag"><?php esc_html_e( 'Plantilla', 'osteza' ); ?></span><?php endif; ?><?php if ( ! has_post_thumbnail( $project ) ) : ?><span class="disp"><?php echo esc_html( $p['cover'] ); ?></span><?php endif; ?></div>
<?php if ( $chips ) : ?><div class="chipxs"><?php foreach ( $chips as $chip ) : ?><span class="chipx"><?php echo esc_html( $chip ); ?></span><?php endforeach; ?></div><?php endif; ?>
<h3><a href="<?php echo esc_url( get_permalink( $project ) ); ?>"><?php echo esc_html( get_the_title( $project ) ); ?></a></h3>
<p class="pmeta"><?php echo esc_html( implode( ' · ', array_filter( array( $p['housing'], $p['year'] ) ) ) ); ?></p>
</li>
<?php endforeach; ?>
</ul>
</div>
</section>
<?php endif; ?>
</div>
<?php
get_template_part(
	'template-parts/cta',
	null,
	array(
		'title' => __( '¿Quieres que tu casa sea el próximo proyecto?', 'osteza' ),
		'text'  => __( 'Cuéntanos qué necesitas y preparamos el siguiente paso contigo.', 'osteza' ),
	)
);
get_footer();
