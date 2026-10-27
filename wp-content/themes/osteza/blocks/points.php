<?php
/**
 * Dark section with points to keep in mind. $a: block attributes.
 */

$hid = osteza_uid( 'h-loc' );
?>
<section class="sec zloc dk" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap zlocgrid">
<div class="zloccopy rv"><?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2><?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?><?php if ( $a['note'] ) : ?><p class="zlocn"><?php echo esc_html( $a['note'] ); ?></p><?php endif; ?></div>
<ul class="lpts">
<?php foreach ( (array) $a['points'] as $point ) : ?>
<li class="rv"><strong><?php echo esc_html( $point['title'] ?? '' ); ?></strong><span><?php echo esc_html( $point['text'] ?? '' ); ?></span></li>
<?php endforeach; ?>
</ul>
</div>
</section>
