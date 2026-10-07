<?php
// Placeholder articles from the design; replace with a posts query once the blog has content.
$posts = array(
	array(
		'cls'   => 'bc-g',
		'art'   => osteza_art( '<rect x="8" y="12" width="32" height="26" rx="2"/><path d="M8 20h32M16 8v8M32 8v8"/>', 48, '#8FB8C4' ),
		'cover' => __( 'Antes<br>de la obra', 'osteza' ),
		'cat'   => __( 'Planificar una reforma · Borrador', 'osteza' ),
		'title' => __( 'Cómo preparar tu vivienda antes de una reforma', 'osteza' ),
		'desc'  => __( 'Qué retirar, qué proteger y cómo organizarte para que la obra empiece con buen pie.', 'osteza' ),
	),
	array(
		'cls'   => 'bc-t',
		'art'   => osteza_art( '<rect x="6" y="10" width="36" height="10"/><rect x="6" y="26" width="36" height="14"/><path d="M18 26v14M30 26v14"/>', 48, '#FFFFFF' ),
		'cover' => __( 'Tu nueva<br>cocina', 'osteza' ),
		'cat'   => __( 'Cocinas y baños · Borrador', 'osteza' ),
		'title' => __( 'Qué tener en cuenta al renovar una cocina', 'osteza' ),
		'desc'  => __( 'Distribución, instalaciones y materiales: las decisiones que conviene tomar primero.', 'osteza' ),
	),
	array(
		'cls'   => 'bc-s',
		'art'   => osteza_art( '<rect x="10" y="8" width="22" height="32" rx="1"/><path d="M21 8v32M18 22v4M24 22v4"/><path d="M36 28l5-5"/>', 48, '#171615' ),
		'cover' => __( 'Prepara<br>el espacio', 'osteza' ),
		'cat'   => __( 'Muebles y montaje · Borrador', 'osteza' ),
		'title' => __( 'Montaje de muebles: cómo preparar el espacio', 'osteza' ),
		'desc'  => __( 'Medidas, accesos y paredes: lo que conviene revisar antes de que llegue el montador.', 'osteza' ),
	),
);
?>
<section id="blog" class="sec blog" aria-labelledby="h-blog">
<div class="wrap stack">
<div class="shead rv">
<div class="stitle"><p class="kick"><?php esc_html_e( 'Blog', 'osteza' ); ?></p><h2 id="h-blog" class="disp h2"><?php esc_html_e( 'Ideas y consejos para tu hogar.', 'osteza' ); ?></h2></div>
<a class="lnk" href="#"><?php esc_html_e( 'Ir al blog', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
</div>
<div class="bgrid">
<?php foreach ( $posts as $p ) : ?>
<article class="bcard rv">
<div class="bcover <?php echo esc_attr( $p['cls'] ); ?>" aria-hidden="true"><?php echo $p['art']; ?><span class="disp"><?php echo wp_kses( $p['cover'], array( 'br' => array() ) ); ?></span></div>
<p class="bcat"><?php echo esc_html( $p['cat'] ); ?></p>
<h3><a href="#"><?php echo esc_html( $p['title'] ); ?></a></h3>
<p class="bdesc"><?php echo esc_html( $p['desc'] ); ?></p>
</article>
<?php endforeach; ?>
</div>
</div>
</section>
