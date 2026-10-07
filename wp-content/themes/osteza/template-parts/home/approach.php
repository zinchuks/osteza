<?php
$steps = array(
	array( 'marker', 'ap-g', __( 'Entender el trabajo', 'osteza' ), __( 'Escuchamos qué necesitas y, si hace falta, vemos el espacio.', 'osteza' ) ),
	array( 'doc-check', 'ap-t', __( 'Definir el alcance', 'osteza' ), __( 'Qué incluye, qué no y con qué materiales. Por escrito.', 'osteza' ) ),
	array( 'chat', 'ap-s', __( 'Comunicación clara', 'osteza' ), __( 'Una persona de contacto que te informa de cada avance.', 'osteza' ) ),
);
?>
<section class="appr" aria-labelledby="h-app">
<div class="wrap split">
<div class="split-l rv">
<p class="kick"><?php esc_html_e( 'Nuestra forma de trabajar', 'osteza' ); ?></p>
<h2 id="h-app" class="disp h2"><?php esc_html_e( 'Primero escuchamos. Después, todo por escrito.', 'osteza' ); ?></h2>
<p class="lead"><?php esc_html_e( 'Una obra sale bien cuando todos saben qué se va a hacer, cómo y cuándo. Por eso dedicamos tiempo al principio: entender bien el trabajo evita sorpresas al final.', 'osteza' ); ?></p>
<a class="lnk" href="#"><?php esc_html_e( 'Cómo trabajamos', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
</div>
<ol class="split-r rv">
<?php foreach ( $steps as $step ) : ?>
<li><span class="apic <?php echo esc_attr( $step[1] ); ?>"><?php echo osteza_icon( $step[0], 26, 'currentColor', '1.6' ); ?></span><span><strong><?php echo esc_html( $step[2] ); ?></strong><span><?php echo esc_html( $step[3] ); ?></span></span></li>
<?php endforeach; ?>
</ol>
</div>
</section>
