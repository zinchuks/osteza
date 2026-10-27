<?php
/**
 * Contact page header with the direct channels. $a: block attributes.
 */

$new_tab = '<span class="sr"> ' . esc_html__( '(abre una pestaña nueva)', 'osteza' ) . '</span>';
?>
<section class="phead phead-k" aria-labelledby="h1">
<div class="wrap">
<?php osteza_breadcrumb(); ?>
<div class="kgrid">
<div class="cherocopy">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h1 id="h1" class="disp"><?php echo osteza_html( $a['title'] ); ?></h1>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
</div>
<div class="qch">
<a class="qcard qwa" href="<?php echo esc_url( osteza_whatsapp_url() ); ?>" target="_blank" rel="noopener"><span class="qic"><?php echo osteza_icon( 'whatsapp', 28 ); ?></span><span><strong>WhatsApp</strong><span><?php echo esc_html( $a['waText'] ); ?></span></span><span class="ar" aria-hidden="true">↗</span><?php echo $new_tab; ?></a>
<a class="qcard" href="<?php echo esc_attr( osteza_phone_url() ); ?>"><span class="qic"><?php echo osteza_icon( 'phone', 26 ); ?></span><span><strong><?php echo esc_html( osteza_contact( 'phone' ) ); ?></strong><span><?php echo esc_html( osteza_contact( 'hours' ) ); ?></span></span><?php echo osteza_arrow(); ?></a>
<a class="qcard" href="<?php echo esc_url( osteza_telegram_url() ); ?>" target="_blank" rel="noopener"><span class="qic"><?php echo osteza_icon( 'telegram', 26 ); ?></span><span><strong>Telegram</strong><span>@<?php echo esc_html( ltrim( osteza_contact( 'telegram' ), '@' ) ); ?></span></span><span class="ar" aria-hidden="true">↗</span><?php echo $new_tab; ?></a>
<a class="qcard" href="mailto:<?php echo esc_attr( get_theme_mod( 'osteza_email', '' ) ); ?>"><span class="qic"><?php echo osteza_icon( 'mail', 26 ); ?></span><span><strong><?php esc_html_e( 'Email', 'osteza' ); ?></strong><span><?php echo esc_html( osteza_contact( 'email' ) ); ?></span></span><?php echo osteza_arrow(); ?></a>
</div>
</div>
</div>
</section>
