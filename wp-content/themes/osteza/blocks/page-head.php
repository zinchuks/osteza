<?php
/**
 * Plain page header with breadcrumb. $a: block attributes.
 */

$contact = osteza_anchor( 'contacto' );
?>
<section class="phead" aria-labelledby="h1">
<div class="wrap">
<?php osteza_breadcrumb(); ?>
<div class="cherocopy">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h1 id="h1" class="disp"><?php echo osteza_html( $a['title'] ?: get_the_title() ); ?></h1>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<?php if ( $a['buttons'] ) : ?>
<div class="hbtns"><a class="btn btn-p" href="<?php echo $contact; ?>"><?php esc_html_e( 'Solicitar presupuesto', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a><a class="btn btn-o" href="<?php echo $contact; ?>"><?php echo osteza_icon( 'whatsapp', 22 ); ?><?php esc_html_e( 'Hablar por WhatsApp', 'osteza' ); ?></a></div>
<?php endif; ?>
</div>
</div>
</section>
