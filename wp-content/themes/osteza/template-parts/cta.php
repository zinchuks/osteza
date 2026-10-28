<?php
/**
 * Dark call-to-action band. Args: 'title', 'text', 'button' (main button label), 'class' (extra section classes).
 */

$contact = osteza_anchor( 'contacto' );
?>
<section class="ctasec <?php echo esc_attr( $args['class'] ?? '' ); ?>" aria-labelledby="h-cta">
<div class="wrap">
<div class="ctaband dk rv">
<img class="ctabg" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<div class="ctacopy"><h2 id="h-cta" class="disp"><?php echo esc_html( $args['title'] ); ?></h2><p><?php echo esc_html( $args['text'] ); ?></p></div>
<div class="ctabtns"><a class="btn btn-p" href="<?php echo $contact; ?>"><?php echo esc_html( $args['button'] ?? __( 'Solicitar presupuesto', 'osteza' ) ); ?> <?php echo osteza_arrow(); ?></a><a class="btn btn-ol" href="<?php echo esc_url( osteza_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Hablar por WhatsApp', 'osteza' ); ?></a></div>
</div>
</div>
</section>
