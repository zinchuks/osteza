<?php
/**
 * Zones as a list of town links. $a: block attributes.
 */

$hid = osteza_uid( 'h-zone' );
?>
<section id="zonas" class="sec" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap zgrid">
<div class="zstick zcopy rv">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2>
<?php if ( $a['lead'] ) : ?><p class="lead lead-sm"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<?php if ( $a['button'] ) : ?><a class="btn btn-o" href="<?php echo osteza_anchor( 'contacto' ); ?>"><?php echo esc_html( $a['button'] ); ?></a><?php endif; ?>
</div>
<ul class="towns rv">
<?php foreach ( osteza_zones() as $zone ) : ?>
<li><a href="<?php echo $zone['url']; ?>" class="disp"><?php echo esc_html( $zone['name'] ); ?></a></li>
<?php endforeach; ?>
<?php if ( $a['note'] ) : ?><li class="townnote"><?php echo esc_html( $a['note'] ); ?></li><?php endif; ?>
</ul>
</div>
</section>
