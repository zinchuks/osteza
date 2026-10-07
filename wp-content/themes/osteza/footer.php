<?php
/**
 * Footer and the fixed contact shortcuts.
 */

$contact  = osteza_anchor( 'contacto' );
$ig_label = __( 'Instagram de OSTEZA (abre una pestaña nueva)', 'osteza' );
$fb_label = __( 'Facebook de OSTEZA (abre una pestaña nueva)', 'osteza' );
?>
</main>

<footer class="ftr dk">
<div class="wrap ftrin">
<div class="fclaim">
<p class="disp"><?php echo wp_kses( __( 'Cuidamos tu espacio. <span>Construimos confianza.</span>', 'osteza' ), array( 'span' => array() ) ); ?></p>
<a class="btn btn-p" href="<?php echo $contact; ?>"><?php esc_html_e( 'Solicitar presupuesto', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
</div>
<div class="fcols">
<div class="fabout">
<img src="<?php echo osteza_img( 'logo-light.svg' ); ?>" alt="OSTEZA" width="157" height="34">
<p><?php esc_html_e( 'Reformas, acabados y montaje de muebles en la Costa Blanca. Trabajos grandes y pequeños, con el mismo cuidado.', 'osteza' ); ?></p>
<div class="fsoc"><a class="soc" href="<?php echo osteza_social_url( 'instagram' ); ?>" aria-label="<?php echo esc_attr( $ig_label ); ?>"><?php echo osteza_icon( 'instagram', 20 ); ?></a><a class="soc" href="<?php echo osteza_social_url( 'facebook' ); ?>" aria-label="<?php echo esc_attr( $fb_label ); ?>"><?php echo osteza_icon( 'facebook', 20 ); ?></a></div>
</div>
<nav class="fcol" aria-labelledby="f-serv"><h2 id="f-serv"><?php esc_html_e( 'Servicios', 'osteza' ); ?></h2>
<?php foreach ( osteza_main_services() as $link => $key ) : ?>
<a href="<?php echo osteza_service_url( $key ); ?>"><?php echo esc_html( $link ); ?></a>
<?php endforeach; ?>
</nav>
<nav class="fcol" aria-labelledby="f-comp"><h2 id="f-comp">OSTEZA</h2>
<a href="<?php echo osteza_anchor( 'equipo' ); ?>"><?php esc_html_e( 'Sobre nosotros', 'osteza' ); ?></a>
<a href="#"><?php esc_html_e( 'Proyectos', 'osteza' ); ?></a>
<a href="<?php echo osteza_anchor( 'blog' ); ?>"><?php esc_html_e( 'Blog', 'osteza' ); ?></a>
<a href="<?php echo $contact; ?>"><?php esc_html_e( 'Contacto', 'osteza' ); ?></a>
</nav>
<nav class="fcol" aria-labelledby="f-zone"><h2 id="f-zone"><?php esc_html_e( 'Zonas', 'osteza' ); ?></h2>
<?php foreach ( array_slice( osteza_towns(), 0, 4 ) as $town ) : ?>
<a href="#"><?php echo esc_html( $town ); ?></a>
<?php endforeach; ?>
<a href="<?php echo osteza_anchor( 'zonas' ); ?>"><?php esc_html_e( 'Todas las zonas', 'osteza' ); ?></a>
</nav>
<div class="fcol fcon"><h2><?php esc_html_e( 'Contacto', 'osteza' ); ?></h2>
<a href="<?php echo esc_attr( osteza_phone_url() ); ?>"><?php echo esc_html( osteza_contact( 'phone' ) ); ?></a>
<a href="<?php echo esc_url( osteza_whatsapp_url() ); ?>">WhatsApp</a>
<a href="<?php echo esc_url( osteza_telegram_url() ); ?>">Telegram @<?php echo esc_html( ltrim( osteza_contact( 'telegram' ), '@' ) ); ?></a>
<a href="mailto:<?php echo esc_attr( get_theme_mod( 'osteza_email', '' ) ); ?>"><?php echo esc_html( osteza_contact( 'email' ) ); ?></a>
<span class="faddr"><?php echo esc_html( osteza_contact( 'address' ) ); ?></span>
<span class="fhours"><?php echo esc_html( osteza_contact( 'hours' ) ); ?></span>
</div>
</div>
<div class="fbot">
<div class="flang"><label for="flang"><?php esc_html_e( 'Idioma', 'osteza' ); ?></label><select id="flang" data-lang-select><?php osteza_language_options(); ?></select></div>
<nav class="flegal" aria-label="<?php esc_attr_e( 'Legal', 'osteza' ); ?>"><a href="#"><?php esc_html_e( 'Aviso legal', 'osteza' ); ?></a><a href="#"><?php esc_html_e( 'Política de privacidad', 'osteza' ); ?></a><a href="#"><?php esc_html_e( 'Política de cookies', 'osteza' ); ?></a><button type="button"><?php esc_html_e( 'Preferencias de cookies', 'osteza' ); ?></button></nav>
<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( osteza_contact( 'legal' ) ); ?></span>
</div>
</div>
<div class="mbar-sp"></div>
</footer>

<a class="fab" href="<?php echo $contact; ?>"><?php echo osteza_icon( 'whatsapp', 22 ); ?>WhatsApp</a>
<nav class="rail" aria-label="<?php esc_attr_e( 'Redes sociales', 'osteza' ); ?>">
<a href="<?php echo osteza_social_url( 'instagram' ); ?>" aria-label="<?php echo esc_attr( $ig_label ); ?>"><?php echo osteza_icon( 'instagram', 18 ); ?></a>
<a href="<?php echo osteza_social_url( 'facebook' ); ?>" aria-label="<?php echo esc_attr( $fb_label ); ?>"><?php echo osteza_icon( 'facebook', 18 ); ?></a>
</nav>
<nav class="mbar" aria-label="<?php esc_attr_e( 'Contacto rápido', 'osteza' ); ?>">
<a class="mbar-wa" href="<?php echo $contact; ?>"><?php echo osteza_icon( 'bubble', 22 ); ?>WhatsApp</a>
<a class="mbar-call" href="<?php echo esc_attr( osteza_phone_url() ); ?>"><?php echo osteza_icon( 'phone', 22 ); ?><?php esc_html_e( 'Llamar', 'osteza' ); ?></a>
</nav>
</div>
<?php wp_footer(); ?>
</body>
</html>
