<?php
/**
 * Team teaser with photo. $a: block attributes.
 */

$hid = osteza_uid( 'h-team' );
?>
<section id="equipo" class="sec teamsec dk" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap teamgrid">
<div class="teamcopy rv">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<?php if ( $a['points'] ) : ?>
<div class="teampts">
<?php foreach ( (array) $a['points'] as $point ) : ?>
<div><strong><?php echo esc_html( $point['title'] ?? '' ); ?></strong><span><?php echo esc_html( $point['text'] ?? '' ); ?></span></div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<?php if ( $a['linkLabel'] ) : ?><a class="lnk" href="<?php echo osteza_about_url( 'equipo-l' ); ?>"><?php echo esc_html( $a['linkLabel'] ); ?> <?php echo osteza_arrow(); ?></a><?php endif; ?>
</div>
<figure class="teamfig rv<?php echo $a['image'] ? ' has-photo' : ''; ?>">
<?php if ( $a['image'] ) : ?>
<?php echo wp_get_attachment_image( $a['image'], 'large', false, array( 'class' => 'photo' ) ); ?>
<?php else : ?>
<img src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<figcaption><?php echo esc_html( $a['caption'] ); ?></figcaption>
<?php endif; ?>
</figure>
</div>
</section>
