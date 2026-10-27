<?php
/**
 * Post card. Args: 'post' (WP_Post), 'tag' (wrapper element), 'class' (extra classes),
 * 'icon' (icon size), 'details' (excerpt and date line).
 */

$p       = $args['post'];
$tag     = $args['tag'] ?? 'li';
$details = $args['details'] ?? true;
$cover   = osteza_cover( $p );
?>
<<?php echo esc_html( $tag ); ?> class="pcard <?php echo esc_attr( $args['class'] ?? '' ); ?>">
<div class="pcover <?php echo esc_attr( $cover['cls'] ); ?>" aria-hidden="true"><?php echo osteza_art( $cover['icon'], $args['icon'] ?? 52 ); ?><span class="disp"><?php echo osteza_cover_text_html( $cover['text'] ); ?></span></div>
<p class="pcat"><?php echo esc_html( osteza_post_kicker( $p ) ); ?></p>
<h3><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h3>
<?php if ( $details ) : ?>
<p class="pexc"><?php echo esc_html( get_the_excerpt( $p ) ); ?></p>
<?php /* translators: %d: minutes of reading. */ ?>
<p class="pmeta"><?php echo esc_html( get_the_date( '', $p ) ); ?> · <?php echo esc_html( sprintf( __( '%d min', 'osteza' ), osteza_reading_minutes( $p ) ) ); ?></p>
<?php endif; ?>
</<?php echo esc_html( $tag ); ?>>
