<?php
/**
 * Free text section: standard paragraphs, headings and lists inside. $a: block attributes, $content: inner blocks.
 */

$hid = osteza_uid( 'h-prose' );
?>
<section class="sec sec-prose"<?php echo $a['title'] ? ' aria-labelledby="' . esc_attr( $hid ) . '"' : ''; ?>>
<div class="wrap stack40">
<?php if ( $a['title'] || $a['kicker'] ) : ?>
<div class="stitle"><?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?><?php if ( $a['title'] ) : ?><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2><?php endif; ?></div>
<?php endif; ?>
<div class="prose"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- inner block markup. ?></div>
</div>
</section>
