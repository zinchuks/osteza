<?php
/**
 * Price and duration with the factors that change them. $a: block attributes.
 */

$hid = osteza_uid( 'h-price' );
$id  = $a['anchor'] ? ' id="' . esc_attr( $a['anchor'] ) . '"' : '';
?>
<section<?php echo $id; ?> class="sec" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap pgrid">
<div class="zcopy rv">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<?php if ( $a['button'] ) : ?><a class="btn btn-p" href="<?php echo osteza_anchor( 'contacto' ); ?>"><?php echo esc_html( $a['button'] ); ?> <?php echo osteza_arrow(); ?></a><?php endif; ?>
</div>
<ul class="fgrid rv">
<?php foreach ( array_values( (array) $a['factors'] ) as $i => $factor ) : ?>
<li><span class="fn"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><strong><?php echo esc_html( $factor['title'] ?? '' ); ?></strong><span><?php echo esc_html( $factor['text'] ?? '' ); ?></span></li>
<?php endforeach; ?>
</ul>
</div>
</section>
