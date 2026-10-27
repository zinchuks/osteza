<?php
/**
 * Page not found.
 */

$services = array_slice( osteza_services(), 0, 4 );

get_header();
?>
<section aria-labelledby="h1" class="e404">
<div class="wrap e404g">
<div class="e404copy">
<p class="kick"><?php esc_html_e( 'Error 404', 'osteza' ); ?></p>
<h1 id="h1" class="disp"><?php echo wp_kses( __( 'Esta página no existe <span>(o ya no está aquí).</span>', 'osteza' ), array( 'span' => array() ) ); ?></h1>
<p class="lead"><?php esc_html_e( 'Puede que el enlace esté mal escrito o que hayamos movido el contenido. Te ayudamos a encontrar lo que buscabas.', 'osteza' ); ?></p>
<form role="search" class="ssrch" method="get" action="<?php echo esc_url( osteza_home_url() ); ?>"><label for="sq" class="sr"><?php esc_html_e( 'Buscar en OSTEZA', 'osteza' ); ?></label><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input id="sq" type="search" name="s" placeholder="<?php esc_attr_e( 'Busca un servicio, artículo o zona', 'osteza' ); ?>"><button class="btn btn-p" type="submit"><?php esc_html_e( 'Buscar', 'osteza' ); ?></button></form>
<div class="otherbtns"><a class="btn btn-o" href="<?php echo esc_url( osteza_home_url() ); ?>"><?php esc_html_e( 'Ir al inicio', 'osteza' ); ?></a><a class="btn btn-o" href="<?php echo osteza_contact_url(); ?>"><?php esc_html_e( 'Contactar', 'osteza' ); ?></a></div>
</div>
<div class="big404" aria-hidden="true"><span class="disp">404</span><img src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt=""></div>
</div>
</section>

<section class="e404pop" aria-labelledby="h-pop">
<div class="wrap">
<h2 id="h-pop" class="disp"><?php esc_html_e( 'Quizá buscabas', 'osteza' ); ?></h2>
<ul class="qkl">
<?php foreach ( $services as $s ) : ?>
<li><a class="qk" href="<?php echo $s['url']; ?>"><?php echo osteza_art_icon( $s['icon'], 30, 'currentColor', '1.8' ); ?><span><?php echo esc_html( $s['name'] ); ?></span><?php echo osteza_arrow(); ?></a></li>
<?php endforeach; ?>
</ul>
</div>
</section>
<?php
get_footer();
