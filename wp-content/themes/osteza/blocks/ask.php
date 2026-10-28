<?php
/**
 * Light box "can't find it?" with two buttons. $a: block attributes.
 */

$contact = osteza_anchor( 'contacto' );
$hid     = osteza_uid( 'h-other' );
?>
<section class="othersec" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap">
<div class="other rv">
<div class="othercopy"><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp"><?php echo esc_html( $a['title'] ); ?></h2><?php if ( $a['text'] ) : ?><p><?php echo esc_html( $a['text'] ); ?></p><?php endif; ?></div>
<div class="otherbtns"><a class="btn btn-p" href="<?php echo $contact; ?>"><?php echo esc_html( $a['button'] ); ?> <?php echo osteza_arrow(); ?></a><?php if ( $a['button2'] ) : ?><a class="btn btn-o" href="<?php echo esc_url( osteza_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $a['button2'] ); ?></a><?php endif; ?></div>
</div>
</div>
</section>
