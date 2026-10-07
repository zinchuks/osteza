<section class="sec" aria-labelledby="h-faq">
<div class="wrap faqgrid">
<div class="faqcopy rv"><p class="kick"><?php esc_html_e( 'Preguntas frecuentes', 'osteza' ); ?></p><h2 id="h-faq" class="disp h2"><?php esc_html_e( 'Lo que suelen preguntarnos.', 'osteza' ); ?></h2><p class="lead lead-sm"><?php esc_html_e( '¿No encuentras tu respuesta?', 'osteza' ); ?> <a href="<?php echo osteza_anchor( 'contacto' ); ?>"><?php esc_html_e( 'Escríbenos', 'osteza' ); ?></a>.</p></div>
<div class="faq">
<?php foreach ( osteza_faq() as $i => $item ) : ?>
<details<?php echo 0 === $i ? ' open' : ''; ?>><summary><?php echo esc_html( $item[0] ); ?><span class="pm" aria-hidden="true">+</span></summary><p><?php echo esc_html( $item[1] ); ?></p></details>
<?php endforeach; ?>
</div>
</div>
</section>
