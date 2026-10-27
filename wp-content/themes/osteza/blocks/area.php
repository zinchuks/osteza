<?php
/**
 * Work area: map, towns and opening hours. $a: block attributes.
 */

$hid = osteza_uid( 'h-area' );
?>
<section class="zsec" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap">
<div class="zbox rv">
<figure class="zmap">
<?php get_template_part( 'template-parts/zone-map' ); ?>
<?php if ( $a['caption'] ) : ?><figcaption class="zschema"><?php echo esc_html( $a['caption'] ); ?></figcaption><?php endif; ?>
</figure>
<div class="zinfo">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<ul class="ztags"><?php foreach ( osteza_towns() as $town ) : ?><li class="tagp"><?php echo esc_html( $town ); ?></li><?php endforeach; ?></ul>
<?php if ( $a['note'] ) : ?><p class="znote"><?php echo esc_html( $a['note'] ); ?></p><?php endif; ?>
<div class="hrs"><?php echo osteza_icon( 'clock', 24, '#5B3A92' ); ?><span><strong><?php echo esc_html( $a['hoursLabel'] ); ?></strong><br><?php echo esc_html( osteza_contact( 'hours' ) ); ?></span></div>
</div>
</div>
</div>
</section>
