<?php
/**
 * Team profiles. $a: block attributes.
 */

$hid = osteza_uid( 'h-team2' );
$id  = $a['anchor'] ? ' id="' . esc_attr( $a['anchor'] ) . '"' : '';
?>
<section<?php echo $id; ?> class="sec teaml" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap stack">
<div class="shead rv">
<div class="stitle"><?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2></div>
<?php if ( $a['note'] ) : ?><p class="snote"><?php echo esc_html( $a['note'] ); ?></p><?php endif; ?>
</div>
<ul class="team">
<?php foreach ( (array) $a['people'] as $person ) : ?>
<?php $color = in_array( $person['color'] ?? '', array( 'c1', 'c2', 'c3' ), true ) ? $person['color'] : 'c1'; ?>
<li class="rv"><div class="tph <?php echo esc_attr( $color ); ?><?php echo ! empty( $person['image'] ) ? ' has-photo' : ''; ?>" aria-hidden="true">
<?php if ( ! empty( $person['image'] ) ) : ?><?php echo wp_get_attachment_image( $person['image'], 'medium_large', false, array( 'class' => 'photo', 'alt' => '' ) ); ?><?php else : ?><img src="<?php echo osteza_img( 'c2' === $color ? 'mark-light.svg' : 'mark-dark.svg' ); ?>" alt=""><?php endif; ?>
</div><strong class="disp"><?php echo esc_html( $person['name'] ?? '' ); ?></strong><span class="trole"><?php echo esc_html( $person['role'] ?? '' ); ?></span><p><?php echo esc_html( $person['bio'] ?? '' ); ?></p></li>
<?php endforeach; ?>
</ul>
</div>
</section>
