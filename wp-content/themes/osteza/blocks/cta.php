<?php
/**
 * Dark call-to-action band. $a: block attributes.
 */

$contact = osteza_anchor( 'contacto' );
$hid     = osteza_uid( 'h-cta' );
?>
<section class="ctasec" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap">
<div class="ctaband dk rv">
<img class="ctabg" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<div class="ctacopy"><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp"><?php echo esc_html( $a['title'] ); ?></h2><?php if ( $a['text'] ) : ?><p><?php echo esc_html( $a['text'] ); ?></p><?php endif; ?></div>
<div class="ctabtns"><a class="btn btn-p" href="<?php echo $contact; ?>"><?php echo esc_html( $a['button'] ); ?> <?php echo osteza_arrow(); ?></a><a class="btn btn-ol" href="<?php echo esc_url( osteza_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Hablar por WhatsApp', 'osteza' ); ?></a></div>
</div>
</div>
</section>
