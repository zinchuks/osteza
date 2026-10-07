<?php
$all = __( 'Ver todos los servicios', 'osteza' );
?>
<section id="servicios" class="serv" aria-labelledby="h-serv">
<div class="wrap shead shead-serv rv">
<div class="stitle">
<p class="kick"><?php esc_html_e( 'Servicios', 'osteza' ); ?></p>
<h2 id="h-serv" class="disp h2"><?php esc_html_e( 'Soluciones para cada espacio.', 'osteza' ); ?></h2>
</div>
<div class="sctrl">
<a class="lnk hide-sm" href="#"><?php echo esc_html( $all ); ?> <?php echo osteza_arrow(); ?></a>
<div class="ctrls">
<button class="ctrl" type="button" aria-label="<?php esc_attr_e( 'Servicios anteriores', 'osteza' ); ?>" aria-controls="track" data-track-dir="-1"><?php echo osteza_icon( 'prev', 22, 'currentColor', '1.8' ); ?></button>
<button class="ctrl" type="button" aria-label="<?php esc_attr_e( 'Servicios siguientes', 'osteza' ); ?>" aria-controls="track" data-track-dir="1"><?php echo osteza_icon( 'next', 22, 'currentColor', '1.8' ); ?></button>
</div>
</div>
</div>
<div class="trackw">
<ul id="track" class="track" aria-label="<?php esc_attr_e( 'Servicios', 'osteza' ); ?>">
<?php foreach ( osteza_services() as $i => $s ) : ?>
<li class="card <?php echo esc_attr( $s['cls'] ); ?>">
<img class="cbg" src="<?php echo osteza_img( 'c-w' === $s['cls'] || 'c-s' === $s['cls'] ? 'mark-dark.svg' : 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<div class="ctop"><?php echo osteza_art( '<path d="' . esc_attr( $s['icon'] ) . '"/>', 56 ); ?><span class="cnum"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span></div>
<h3 class="disp"><?php echo esc_html( $s['name'] ); ?></h3>
<p><?php echo esc_html( $s['desc'] ); ?></p>
<ul class="ctags"><?php foreach ( $s['tags'] as $tag ) : ?><li><?php echo esc_html( $tag ); ?></li><?php endforeach; ?></ul>
<?php /* translators: %s: service name (screen-reader text). */ ?>
<a class="lnk" href="<?php echo osteza_service_url( $s['key'] ?? null ); ?>"><?php esc_html_e( 'Ver servicio', 'osteza' ); ?><span class="sr"> <?php echo esc_html( sprintf( __( 'de %s', 'osteza' ), $s['name'] ) ); ?></span> <?php echo osteza_arrow(); ?></a>
</li>
<?php endforeach; ?>
</ul>
</div>
<div class="wrap sfoot">
<div class="sprog" aria-hidden="true"><div data-track-progress></div></div>
<a class="btn btn-o btn-md" href="#"><?php echo esc_html( $all ); ?></a>
</div>
</section>
