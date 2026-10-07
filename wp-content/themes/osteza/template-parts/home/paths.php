<?php
$contact = osteza_anchor( 'contacto' );
?>
<section class="sec" aria-labelledby="h-path">
<div class="wrap stack">
<div class="stitle rv"><p class="kick"><?php esc_html_e( '¿Por dónde empezamos?', 'osteza' ); ?></p><h2 id="h-path" class="disp h2"><?php esc_html_e( 'Grande o pequeño, cada trabajo cuenta.', 'osteza' ); ?></h2></div>
<div class="paths">
<article class="path path-s rv">
<?php echo osteza_art( '<path d="M30 8a8 8 0 0 0-9.6 10.4L8 30.8V40h9.2l12.4-12.4A8 8 0 0 0 40 18l-5 5-6-6 5-5"/>', 64, '#171615' ); ?>
<h3 class="disp"><?php esc_html_e( 'Necesito un trabajo puntual', 'osteza' ); ?></h3>
<p><?php esc_html_e( 'Encargos concretos, con el mismo cuidado que una obra grande.', 'osteza' ); ?></p>
<ul class="ctags"><li><?php esc_html_e( 'Montar muebles, armarios o una cocina', 'osteza' ); ?></li><li><?php esc_html_e( 'Pintar una habitación o alisar paredes', 'osteza' ); ?></li><li><?php esc_html_e( 'Cambiar un suelo o colgar estanterías y TV', 'osteza' ); ?></li></ul>
<a class="btn btn-o" href="<?php echo $contact; ?>"><?php esc_html_e( 'Pedir un trabajo puntual', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
</article>
<article class="path path-g rv dk">
<img class="pathbg" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<?php echo osteza_art( '<path d="M6 22 24 8l18 14"/><path d="M10 19v21h28V19"/><path d="M10 30h12v10M22 24h16"/>', 64, '#8FB8C4' ); ?>
<h3 class="disp"><?php esc_html_e( 'Quiero renovar mi vivienda', 'osteza' ); ?></h3>
<p><?php esc_html_e( 'Reformas parciales o integrales de pisos, apartamentos y villas.', 'osteza' ); ?></p>
<ul class="ctags"><li><?php esc_html_e( 'Cocina, baño o toda la casa', 'osteza' ); ?></li><li><?php esc_html_e( 'Redistribución, instalaciones y acabados', 'osteza' ); ?></li><li><?php esc_html_e( 'Un alcance definido desde el principio', 'osteza' ); ?></li></ul>
<a class="btn btn-p" href="<?php echo $contact; ?>"><?php esc_html_e( 'Hablar de mi reforma', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
</article>
</div>
</div>
</section>
