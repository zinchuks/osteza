<?php
$contact = osteza_anchor( 'contacto' );
$quick   = array(
	__( 'Reforma integral', 'osteza' )            => 'reformas-integrales',
	__( 'Baño o cocina', 'osteza' )               => null,
	__( 'Pintura y acabados', 'osteza' )          => null,
	__( 'Suelos y revestimientos', 'osteza' )     => null,
	__( 'Montaje de muebles o cocina', 'osteza' ) => null,
);
$facts   = array(
	array( 'house', __( 'De un mueble a toda la casa', 'osteza' ), __( 'Trabajos pequeños y reformas completas.', 'osteza' ) ),
	array( 'doc', __( 'Alcance por escrito', 'osteza' ), __( 'Sabes qué incluye antes de empezar.', 'osteza' ) ),
	array( 'chat', __( 'Un solo contacto', 'osteza' ), __( 'Te informamos durante todo el trabajo.', 'osteza' ) ),
);
?>
<section class="hero dk" aria-labelledby="h1">
<div class="hero-glow"></div>
<img class="hero-mark" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<div class="wrap hgrid">
<div class="hcopy">
<p class="kick"><?php esc_html_e( 'Costa Blanca · Reformas · Acabados · Montaje', 'osteza' ); ?></p>
<h1 id="h1" class="disp"><?php echo wp_kses( __( 'Dale una <span>nueva vida</span> a tu hogar.', 'osteza' ), array( 'span' => array() ) ); ?></h1>
<p class="hsub"><?php esc_html_e( 'Reformas, acabados y montaje de muebles en la Costa Blanca.', 'osteza' ); ?></p>
<div class="hbtns">
<a class="btn btn-p" href="<?php echo $contact; ?>"><?php esc_html_e( 'Solicitar presupuesto', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
<a class="btn btn-ol" href="<?php echo $contact; ?>"><?php echo osteza_icon( 'whatsapp', 22 ); ?><?php esc_html_e( 'Hablar por WhatsApp', 'osteza' ); ?></a>
</div>
</div>
<nav class="qpanel" aria-label="<?php esc_attr_e( 'Accesos rápidos a servicios', 'osteza' ); ?>">
<p class="disp"><?php esc_html_e( '¿Qué necesitas hoy?', 'osteza' ); ?></p>
<?php foreach ( $quick as $label => $key ) : ?>
<a class="qlink" href="<?php echo osteza_service_url( $key ); ?>"><?php echo esc_html( $label ); ?> <?php echo osteza_arrow(); ?></a>
<?php endforeach; ?>
<a class="qlink qlink-x" href="<?php echo $contact; ?>"><?php esc_html_e( 'Otro trabajo: cuéntanoslo', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
</nav>
</div>
<div class="wrap"><div class="facts">
<?php foreach ( $facts as $fact ) : ?>
<div class="fact"><?php echo osteza_icon( $fact[0], 28, '#8FB8C4', '1.6' ); ?><span><strong><?php echo esc_html( $fact[1] ); ?></strong><?php echo esc_html( $fact[2] ); ?></span></div>
<?php endforeach; ?>
</div></div>
</section>
