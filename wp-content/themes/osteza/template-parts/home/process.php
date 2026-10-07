<?php
$steps = array(
	array( __( 'Cuéntanos tu idea', 'osteza' ), __( 'Por el formulario, WhatsApp, Telegram o teléfono. Con fotos, si las tienes.', 'osteza' ) ),
	array( __( 'Definimos el alcance', 'osteza' ), __( 'Aclaramos contigo qué hay que hacer y, si hace falta, visitamos la vivienda.', 'osteza' ) ),
	array( __( 'Preparamos el presupuesto', 'osteza' ), __( 'Detallado y por escrito, para que sepas exactamente qué incluye.', 'osteza' ) ),
	array( __( 'Realizamos el trabajo', 'osteza' ), __( 'Con una persona de contacto y una revisión final contigo.', 'osteza' ) ),
);
?>
<section class="proc" aria-labelledby="h-how">
<div class="wrap">
<div class="shead shead-proc rv">
<div class="stitle"><p class="kick"><?php esc_html_e( 'Cómo trabajamos', 'osteza' ); ?></p><h2 id="h-how" class="disp h2"><?php esc_html_e( 'Cuatro pasos. Sin sorpresas.', 'osteza' ); ?></h2></div>
<p class="snote"><?php esc_html_e( 'Para algunos trabajos necesitaremos visitar la vivienda o pedirte fotos y medidas antes de darte un presupuesto final.', 'osteza' ); ?></p>
</div>
<ol class="steps">
<?php foreach ( $steps as $i => $step ) : ?>
<li class="step rv"><span class="stepn"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><h3><?php echo esc_html( $step[0] ); ?></h3><p><?php echo esc_html( $step[1] ); ?></p></li>
<?php endforeach; ?>
</ol>
</div>
</section>
