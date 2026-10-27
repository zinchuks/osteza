<?php
/**
 * Zone cards, linked to the map of the zones header. $a: block attributes.
 */

$hid = osteza_uid( 'h-list' );
?>
<section class="sec sec-g1 zlist" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap stack40">
<div class="shead"><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2><?php if ( $a['note'] ) : ?><p class="zlistn"><?php echo esc_html( $a['note'] ); ?></p><?php endif; ?></div>
<ul class="zgrid2">
<?php foreach ( osteza_zones() as $slug => $zone ) : ?>
<li class="rv"><a class="zcard" href="<?php echo $zone['url']; ?>" data-zone="<?php echo esc_attr( $slug ); ?>" data-name="<?php echo esc_attr( $zone['name'] ); ?>" data-area="<?php echo esc_attr( $zone['area'] ); ?>"><span class="zc"><?php echo esc_html( $zone['area'] ); ?></span><strong class="disp"><?php echo esc_html( $zone['name'] ); ?></strong><?php if ( $zone['coverage'] ) : ?><span class="zs"><?php echo esc_html( $zone['coverage'] ); ?></span><?php endif; ?><?php echo osteza_arrow(); ?></a></li>
<?php endforeach; ?>
</ul>
</div>
</section>
