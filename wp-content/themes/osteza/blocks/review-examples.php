<?php
/** Fictional review mockups, never presented as customer endorsements or ratings. */
$hid = osteza_uid( 'h-review-examples' );
?>
<section class="sec review-examples" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap">
<div class="review-examples-head">
<div><p class="kick"><?php esc_html_e( 'Reseñas de ejemplo', 'osteza' ); ?></p>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2></div>
<span class="review-example-label"><?php esc_html_e( 'Ejemplo ficticio', 'osteza' ); ?></span>
</div>
<p class="review-examples-disclosure"><?php esc_html_e( 'Textos ficticios para mostrar el diseño. No son opiniones de clientes reales.', 'osteza' ); ?></p>
<ul class="review-examples-track" tabindex="0" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<?php foreach ( (array) $a['items'] as $index => $item ) : ?>
<li class="review-example-card">
<span class="review-example-quote" aria-hidden="true">“</span>
<p class="review-example-service"><?php echo esc_html( $item['service'] ?? '' ); ?></p>
<blockquote><p><?php echo esc_html( $item['text'] ?? '' ); ?></p></blockquote>
<div class="review-example-footer"><span class="review-example-avatar" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><strong><?php /* translators: %02d: example number. */ echo esc_html( sprintf( __( 'Ejemplo %02d', 'osteza' ), $index + 1 ) ); ?></strong><span><?php esc_html_e( 'Ejemplo ficticio', 'osteza' ); ?></span></div>
</li>
<?php endforeach; ?>
</ul>
<?php if ( 10 === count( (array) $a['items'] ) ) : ?><p class="review-examples-hint"><?php esc_html_e( 'Desliza para ver los 10 ejemplos.', 'osteza' ); ?> <span aria-hidden="true">→</span></p><?php endif; ?>
</div>
</section>
