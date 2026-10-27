<?php
/**
 * Grid of what a service includes. $a: block attributes.
 */

$hid = osteza_uid( 'h-inc' );
$id  = $a['anchor'] ? ' id="' . esc_attr( $a['anchor'] ) . '"' : '';
?>
<section<?php echo $id; ?> class="sec sec-inc" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap stack">
<div class="shead rv">
<div class="stitle"><?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2></div>
<?php if ( $a['note'] ) : ?><p class="snote"><?php echo esc_html( $a['note'] ); ?></p><?php endif; ?>
</div>
<ul class="igrid">
<?php foreach ( (array) $a['items'] as $item ) : ?>
<li class="icell rv"><?php echo osteza_art_icon( $item['icon'] ?? '', 44 ); ?><h3><?php echo esc_html( $item['title'] ?? '' ); ?></h3><p><?php echo esc_html( $item['text'] ?? '' ); ?></p></li>
<?php endforeach; ?>
</ul>
</div>
</section>
