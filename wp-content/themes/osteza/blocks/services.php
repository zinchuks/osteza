<?php
/**
 * Services carousel: the service pages marked for the home page. $a: block attributes.
 */

$services = osteza_services();
$hid      = osteza_uid( 'h-serv' );
?>
<section id="servicios" class="serv" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap shead shead-serv rv">
<div class="stitle">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2>
</div>
<div class="sctrl">
<a class="lnk hide-sm" href="<?php echo osteza_catalog_url(); ?>"><?php echo esc_html( $a['linkLabel'] ); ?> <?php echo osteza_arrow(); ?></a>
<div class="ctrls">
<button class="ctrl" type="button" aria-label="<?php esc_attr_e( 'Servicios anteriores', 'osteza' ); ?>" aria-controls="track" data-track-dir="-1"><?php echo osteza_icon( 'prev', 22, 'currentColor', '1.8' ); ?></button>
<button class="ctrl" type="button" aria-label="<?php esc_attr_e( 'Servicios siguientes', 'osteza' ); ?>" aria-controls="track" data-track-dir="1"><?php echo osteza_icon( 'next', 22, 'currentColor', '1.8' ); ?></button>
</div>
</div>
</div>
<div class="trackw">
<ul id="track" class="track" aria-label="<?php esc_attr_e( 'Servicios', 'osteza' ); ?>">
<?php foreach ( $services as $i => $s ) : ?>
<li class="card <?php echo esc_attr( $s['cls'] ); ?><?php echo $s['image'] ? ' card-img' : ''; ?>">
<?php if ( $s['image'] ) : ?>
<div class="cimg"><?php echo wp_get_attachment_image( $s['image'], 'large', false, array( 'alt' => '', 'loading' => 'lazy', 'sizes' => '(max-width:760px) 84vw, 420px' ) ); ?><span class="cnum"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span></div>
<?php else : ?>
<img class="cbg" src="<?php echo osteza_img( 'c-w' === $s['cls'] || 'c-s' === $s['cls'] ? 'mark-dark.svg' : 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<div class="ctop"><?php echo osteza_art( osteza_art_inner( $s['icon'] ), 56 ); ?><span class="cnum"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span></div>
<?php endif; ?>
<h3 class="disp"><?php echo esc_html( $s['name'] ); ?></h3>
<p><?php echo esc_html( $s['desc'] ); ?></p>
<?php if ( $s['tags'] ) : ?><ul class="ctags"><?php foreach ( $s['tags'] as $tag ) : ?><li><?php echo esc_html( $tag ); ?></li><?php endforeach; ?></ul><?php endif; ?>
<?php /* translators: %s: service name (screen-reader text). */ ?>
<a class="lnk" href="<?php echo $s['url']; ?>"><?php esc_html_e( 'Ver servicio', 'osteza' ); ?><span class="sr"> <?php echo esc_html( sprintf( __( 'de %s', 'osteza' ), $s['name'] ) ); ?></span> <?php echo osteza_arrow(); ?></a>
</li>
<?php endforeach; ?>
</ul>
</div>
<div class="wrap sfoot">
<div class="sprog" aria-hidden="true"><div data-track-progress></div></div>
<a class="btn btn-o btn-md" href="<?php echo osteza_catalog_url(); ?>"><?php echo esc_html( $a['linkLabel'] ); ?></a>
</div>
</section>
