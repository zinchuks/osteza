<?php
/**
 * Always / never lists. $a: block attributes.
 */

$hid = osteza_uid( 'h-prom' );
?>
<section class="promsec" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap">
<div class="prom rv">
<div class="pyes">
<?php if ( $a['yesKicker'] ) : ?><p class="kick"><?php echo esc_html( $a['yesKicker'] ); ?></p><?php endif; ?>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp"><?php echo esc_html( $a['yesTitle'] ); ?></h2>
<ul class="chk"><?php foreach ( osteza_lines( $a['yes'] ) as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
</div>
<div class="pno dk">
<?php if ( $a['noKicker'] ) : ?><p class="kick"><?php echo esc_html( $a['noKicker'] ); ?></p><?php endif; ?>
<h3 class="disp"><?php echo esc_html( $a['noTitle'] ); ?></h3>
<ul class="chk no"><?php foreach ( osteza_lines( $a['no'] ) as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
</div>
</div>
</div>
</section>
