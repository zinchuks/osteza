<?php
/**
 * Story: highlighted sentence, paragraphs and a quote. $a: block attributes.
 */

$hid   = osteza_uid( 'h-his' );
$paras = array_filter( array_map( 'trim', preg_split( '/\n\s*\n/', (string) $a['body'] ) ) );
?>
<section class="sec" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap hgrid2">
<div class="hhead rv">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2>
</div>
<div class="rv prose2">
<?php if ( $a['big'] ) : ?><p class="big"><?php echo esc_html( $a['big'] ); ?></p><?php endif; ?>
<?php foreach ( $paras as $para ) : ?><p><?php echo nl2br( esc_html( $para ) ); ?></p><?php endforeach; ?>
<?php if ( $a['quote'] ) : ?><blockquote class="quote"><p><?php echo esc_html( $a['quote'] ); ?></p><?php if ( $a['author'] ) : ?><footer><?php echo esc_html( $a['author'] ); ?></footer><?php endif; ?></blockquote><?php endif; ?>
</div>
</div>
</section>
