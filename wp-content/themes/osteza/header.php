<?php
/**
 * Top bar, sticky header with mega menu, and the mobile menu panel.
 */

$lang     = osteza_current_language();
$contact  = osteza_anchor( 'contacto' );
$ig_label = __( 'Instagram de OSTEZA (abre una pestaña nueva)', 'osteza' );
$fb_label = __( 'Facebook de OSTEZA (abre una pestaña nueva)', 'osteza' );
$home     = osteza_home_url();
$current  = '';
if ( osteza_is_blog() ) {
	$current = osteza_blog_url();
} elseif ( is_page_template( 'page-templates/contact.php' ) ) {
	$current = osteza_contact_url();
} elseif ( is_post_type_archive( 'osteza_project' ) || is_singular( 'osteza_project' ) ) {
	$current = osteza_projects_url();
} elseif ( is_page_template( 'page-templates/about.php' ) ) {
	$current = osteza_about_url();
} elseif ( is_page_template( array( 'page-templates/zones.php', 'page-templates/zone.php' ) ) ) {
	$current = osteza_zones_url();
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="os">
<a href="#contenido" class="sr"><?php esc_html_e( 'Saltar al contenido', 'osteza' ); ?></a>

<div class="tbar dk">
<div class="wrap tin">
<span class="taddr"><?php echo osteza_icon( 'pin', 18, '#EBA3AE' ); ?><?php echo esc_html( osteza_contact( 'address' ) ); ?></span>
<span class="tsep" aria-hidden="true"></span>
<a href="<?php echo esc_attr( osteza_phone_url() ); ?>"><?php echo osteza_icon( 'phone', 18, '#EBA3AE' ); ?><?php echo esc_html( osteza_contact( 'phone' ) ); ?></a>
<nav class="tsoc" aria-label="<?php esc_attr_e( 'WhatsApp y redes sociales', 'osteza' ); ?>">
<a href="<?php echo esc_url( osteza_whatsapp_url() ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Escribir por WhatsApp', 'osteza' ); ?>"><?php echo osteza_icon( 'whatsapp', 19 ); ?></a>
<a href="<?php echo osteza_social_url( 'instagram' ); ?>" aria-label="<?php echo esc_attr( $ig_label ); ?>"><?php echo osteza_icon( 'instagram', 18 ); ?></a>
<a href="<?php echo osteza_social_url( 'facebook' ); ?>" aria-label="<?php echo esc_attr( $fb_label ); ?>"><?php echo osteza_icon( 'facebook', 18 ); ?></a>
</nav>
</div>
</div>

<header class="hdr" data-header>
<div class="wrap hin">
<a class="logo" href="<?php echo esc_url( $home ); ?>" aria-label="<?php esc_attr_e( 'OSTEZA, ir al inicio', 'osteza' ); ?>"><img src="<?php echo osteza_img( 'logo-dark.svg' ); ?>" alt="OSTEZA" width="148" height="32"><img class="logo-l" src="<?php echo osteza_img( 'logo-light.svg' ); ?>" alt="" width="148" height="32"></a>
<nav class="navd" aria-label="<?php esc_attr_e( 'Principal', 'osteza' ); ?>">
<button class="nl nl-b" type="button" aria-expanded="false" aria-controls="mega" data-toggle="mega"><?php esc_html_e( 'Servicios', 'osteza' ); ?><?php echo osteza_chevron(); ?></button>
<?php foreach ( osteza_nav() as $label => $href ) : ?>
<a class="nl" href="<?php echo esc_attr( $href ); ?>"<?php echo $href === $current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?></a>
<?php endforeach; ?>
</nav>
<div class="hact">
<div class="langw">
<?php /* translators: %s: current language name. */ ?>
<button class="nl langb" type="button" aria-expanded="false" aria-controls="langs" aria-label="<?php echo esc_attr( sprintf( __( 'Idioma: %s. Cambiar idioma', 'osteza' ), $lang['name'] ) ); ?>" data-toggle="langs"><?php echo esc_html( strtoupper( $lang['code'] ) ); ?><?php echo osteza_chevron( 10 ); ?></button>
<ul id="langs" class="langs" hidden>
<?php foreach ( osteza_languages() as $l ) : ?>
<li><a href="<?php echo esc_url( $l['url'] ); ?>" lang="<?php echo esc_attr( $l['code'] ); ?>" hreflang="<?php echo esc_attr( $l['code'] ); ?>"<?php echo $l['current'] ? ' aria-current="page"' : ''; ?>><span><?php echo esc_html( $l['name'] ); ?></span><span class="langm"><?php echo $l['current'] ? '✓' : esc_html( strtoupper( $l['code'] ) ); ?></span></a></li>
<?php endforeach; ?>
</ul>
</div>
<a class="btn btn-p btn-sm hide-sm" href="<?php echo $contact; ?>"><?php esc_html_e( 'Solicitar presupuesto', 'osteza' ); ?></a>
<button class="burger" type="button" aria-label="<?php esc_attr_e( 'Abrir menú', 'osteza' ); ?>" aria-expanded="false" aria-controls="mpanel" data-nav-open><?php echo osteza_icon( 'burger', 28 ); ?></button>
</div>
</div>

<div id="mega" class="mega" hidden>
<div class="wrap megain">
<div class="megacols">
<?php foreach ( osteza_service_menu() as $heading => $links ) : ?>
<div class="megacol"><p class="kick"><?php echo esc_html( $heading ); ?></p><?php foreach ( $links as $link => $url ) : ?><a class="nl" href="<?php echo $url; ?>"><?php echo esc_html( $link ); ?></a><?php endforeach; ?></div>
<?php endforeach; ?>
</div>
<div class="megacta dk">
<p class="disp"><?php echo esc_html( osteza_text( 'mega_title' ) ); ?></p>
<p><?php echo esc_html( osteza_text( 'mega_text' ) ); ?></p>
<a class="btn btn-p btn-sm" href="<?php echo $contact; ?>" data-close><?php echo esc_html( osteza_text( 'mega_button' ) ); ?></a>
</div>
</div>
</div>
</header>

<div id="mpanel" class="mpanel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menú', 'osteza' ); ?>" hidden>
<div class="mhead">
<img src="<?php echo osteza_img( 'logo-dark.svg' ); ?>" alt="OSTEZA" width="129" height="28">
<button type="button" aria-label="<?php esc_attr_e( 'Cerrar menú', 'osteza' ); ?>" data-nav-close><?php echo osteza_icon( 'close', 26 ); ?></button>
</div>
<nav class="mnav" aria-label="<?php esc_attr_e( 'Principal', 'osteza' ); ?>">
<details><summary class="disp"><?php esc_html_e( 'Servicios', 'osteza' ); ?><span aria-hidden="true">+</span></summary>
<div><?php foreach ( osteza_main_services() as $link => $url ) : ?><a class="nl" href="<?php echo $url; ?>"><?php echo esc_html( $link ); ?></a><?php endforeach; ?></div></details>
<?php foreach ( osteza_nav() as $label => $href ) : ?>
<a class="disp" href="<?php echo esc_attr( $href ); ?>" data-nav-close><?php echo esc_html( $label ); ?></a>
<?php endforeach; ?>
</nav>
<div class="mlang">
<label for="mlang" class="kick"><?php esc_html_e( 'Idioma', 'osteza' ); ?></label>
<select id="mlang" data-lang-select><?php osteza_language_options(); ?></select>
</div>
<div class="mfoot dk">
<a class="btn btn-p" href="<?php echo $contact; ?>" data-nav-close><?php esc_html_e( 'Solicitar presupuesto', 'osteza' ); ?></a>
<div class="mfollow"><span><?php esc_html_e( 'Síguenos', 'osteza' ); ?></span><a class="soc" href="<?php echo osteza_social_url( 'instagram' ); ?>" aria-label="<?php echo esc_attr( $ig_label ); ?>"><?php echo osteza_icon( 'instagram', 20 ); ?></a><a class="soc" href="<?php echo osteza_social_url( 'facebook' ); ?>" aria-label="<?php echo esc_attr( $fb_label ); ?>"><?php echo osteza_icon( 'facebook', 20 ); ?></a></div>
</div>
</div>

<main id="contenido">
