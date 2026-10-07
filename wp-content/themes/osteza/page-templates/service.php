<?php
/**
 * Template Name: Servicio
 *
 * Service landing page. The content comes from inc/services/<slug>.php.
 */

$s = osteza_service();
if ( ! $s ) {
	require get_template_directory() . '/index.php';
	return;
}

$contact = osteza_anchor( 'contacto' );
$anchors = array(
	'incluye'   => __( 'Qué incluye', 'osteza' ),
	'alcance'   => __( 'Incluido y no incluido', 'osteza' ),
	'proceso'   => __( 'Proceso', 'osteza' ),
	'precio'    => __( 'Precio y plazo', 'osteza' ),
	'preguntas' => __( 'Preguntas', 'osteza' ),
	'contacto'  => __( 'Contacto', 'osteza' ),
);

get_header();
?>
<div class="svc">

<section class="svc-hero" aria-labelledby="h1">
<div class="wrap">
<nav aria-label="<?php esc_attr_e( 'Migas de pan', 'osteza' ); ?>" class="bc"><ol><li><a href="<?php echo esc_url( osteza_home_url() ); ?>"><?php esc_html_e( 'Inicio', 'osteza' ); ?></a></li><li><a href="<?php echo osteza_anchor( 'servicios' ); ?>"><?php esc_html_e( 'Servicios', 'osteza' ); ?></a></li><li><a href="#"><?php echo esc_html( $s['group'] ); ?></a></li><li aria-current="page"><?php echo esc_html( $s['name'] ); ?></li></ol></nav>
<div class="shero">
<div class="sherocopy">
<p class="kick"><?php echo esc_html( $s['group'] ); ?></p>
<h1 id="h1" class="disp"><?php echo wp_kses( $s['title'], array( 'span' => array() ) ); ?></h1>
<p class="lead"><?php echo esc_html( $s['lead'] ); ?></p>
<div class="hbtns">
<a class="btn btn-p" href="<?php echo $contact; ?>"><?php esc_html_e( 'Solicitar presupuesto', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
<a class="btn btn-o" href="<?php echo $contact; ?>"><?php echo osteza_icon( 'whatsapp', 22 ); ?><?php esc_html_e( 'Hablar por WhatsApp', 'osteza' ); ?></a>
</div>
</div>
<aside class="scard dk" aria-label="<?php esc_attr_e( 'Resumen del servicio', 'osteza' ); ?>">
<img class="scardbg" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<p class="disp"><?php esc_html_e( 'En resumen', 'osteza' ); ?></p>
<dl>
<?php foreach ( $s['summary'] as $term => $value ) : ?>
<div class="srow"><dt><?php echo esc_html( $term ); ?></dt><dd><?php echo esc_html( $value ); ?></dd></div>
<?php endforeach; ?>
</dl>
<a class="lnk" href="<?php echo $contact; ?>"><?php esc_html_e( 'Consultar mi caso', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
</aside>
</div>
<nav aria-label="<?php esc_attr_e( 'En esta página', 'osteza' ); ?>" class="anch">
<?php foreach ( $anchors as $id => $label ) : ?>
<a href="#<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></a>
<?php endforeach; ?>
</nav>
</div>
</section>

<section id="incluye" class="sec sec-inc" aria-labelledby="h-inc">
<div class="wrap stack">
<div class="shead rv">
<div class="stitle"><p class="kick"><?php esc_html_e( 'Qué incluye', 'osteza' ); ?></p><h2 id="h-inc" class="disp h2"><?php echo esc_html( $s['includes']['title'] ); ?></h2></div>
<p class="snote"><?php echo esc_html( $s['includes']['note'] ); ?></p>
</div>
<ul class="igrid">
<?php foreach ( $s['includes']['items'] as $item ) : ?>
<li class="icell rv"><?php echo osteza_art( $item[0], 44 ); ?><h3><?php echo esc_html( $item[1] ); ?></h3><p><?php echo esc_html( $item[2] ); ?></p></li>
<?php endforeach; ?>
</ul>
</div>
</section>

<section id="alcance" class="scope" aria-labelledby="h-scope">
<div class="wrap">
<div class="yn rv">
<div class="yn-a">
<h2 id="h-scope" class="disp"><?php esc_html_e( 'Normalmente incluido', 'osteza' ); ?></h2>
<ul class="chk"><?php foreach ( $s['scope']['yes'] as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
</div>
<div class="yn-b dk">
<h3 class="disp"><?php esc_html_e( 'Solo si lo acordamos', 'osteza' ); ?></h3>
<ul class="chk no"><?php foreach ( $s['scope']['maybe'] as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
<p><?php esc_html_e( 'Todo queda reflejado por escrito en el presupuesto antes de empezar.', 'osteza' ); ?></p>
</div>
</div>
<div class="types rv">
<span class="typesl"><?php esc_html_e( 'Trabajamos en', 'osteza' ); ?></span>
<?php foreach ( $s['scope']['types'] as $type ) : ?><span class="tagp"><?php echo esc_html( $type ); ?></span><?php endforeach; ?>
</div>
</div>
</section>

<section id="proceso" class="sec sproc dk" aria-labelledby="h-proc">
<div class="wrap">
<div class="shead rv">
<div class="stitle"><p class="kick"><?php esc_html_e( 'Proceso', 'osteza' ); ?></p><h2 id="h-proc" class="disp h2"><?php echo esc_html( $s['process']['title'] ); ?></h2></div>
<p class="lead"><?php echo esc_html( $s['process']['lead'] ); ?></p>
</div>
<ol class="tl">
<?php foreach ( $s['process']['steps'] as $i => $step ) : ?>
<li class="rv"><span class="tln"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><h3><?php echo esc_html( $step[0] ); ?></h3><p><?php echo esc_html( $step[1] ); ?></p></li>
<?php endforeach; ?>
</ol>
</div>
</section>

<section id="precio" class="sec" aria-labelledby="h-price">
<div class="wrap pgrid">
<div class="zcopy rv">
<p class="kick"><?php esc_html_e( 'Precio y plazo', 'osteza' ); ?></p>
<h2 id="h-price" class="disp h2"><?php esc_html_e( '¿Cuánto cuesta y cuánto dura?', 'osteza' ); ?></h2>
<p class="lead"><?php echo esc_html( $s['price']['lead'] ); ?></p>
<a class="btn btn-p" href="<?php echo $contact; ?>"><?php esc_html_e( 'Pedir mi presupuesto', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
</div>
<ul class="fgrid rv">
<?php foreach ( $s['price']['factors'] as $i => $factor ) : ?>
<li><span class="fn"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><strong><?php echo esc_html( $factor[0] ); ?></strong><span><?php echo esc_html( $factor[1] ); ?></span></li>
<?php endforeach; ?>
</ul>
</div>
</section>

<section id="preguntas" class="sec sfaq" aria-labelledby="h-sfaq">
<div class="wrap faqgrid">
<div class="faqcopy rv"><p class="kick"><?php esc_html_e( 'Preguntas', 'osteza' ); ?></p><h2 id="h-sfaq" class="disp h2"><?php echo esc_html( $s['faq']['title'] ); ?></h2></div>
<div class="faq">
<?php foreach ( $s['faq']['items'] as $i => $item ) : ?>
<details<?php echo 0 === $i ? ' open' : ''; ?>><summary><?php echo esc_html( $item[0] ); ?><span class="pm" aria-hidden="true">+</span></summary><p><?php echo esc_html( $item[1] ); ?></p></details>
<?php endforeach; ?>
</div>
</div>
</section>

<section class="sec srel" aria-labelledby="h-rel">
<div class="wrap">
<div class="stitle rv"><p class="kick"><?php esc_html_e( 'También te puede interesar', 'osteza' ); ?></p><h2 id="h-rel" class="disp h2"><?php esc_html_e( 'Servicios relacionados.', 'osteza' ); ?></h2></div>
<div class="rgrid">
<?php foreach ( $s['related'] as $card ) : ?>
<?php $is_service = isset( $card['key'] ); ?>
<a class="rcard rv <?php echo esc_attr( $card['cls'] ); ?>" href="<?php echo $is_service ? osteza_service_url( $card['key'] ) : '#'; ?>">
<?php echo osteza_art( $card['icon'], 44, 'rc-g' === $card['cls'] ? '#8FB8C4' : 'currentColor' ); ?>
<?php if ( isset( $card['cat'] ) ) : ?><span class="rcat"><?php echo esc_html( $card['cat'] ); ?></span><?php endif; ?>
<span class="disp"><?php echo esc_html( $card['name'] ); ?></span>
<?php if ( isset( $card['desc'] ) ) : ?><span class="rdesc"><?php echo esc_html( $card['desc'] ); ?></span><?php endif; ?>
<span class="lnk"><?php echo esc_html( $is_service ? __( 'Ver servicio', 'osteza' ) : __( 'Leer artículo', 'osteza' ) ); ?> <?php echo osteza_arrow(); ?></span>
</a>
<?php endforeach; ?>
</div>
<div class="pnote rv">
<p><?php echo wp_kses( $s['projects'], array( 'strong' => array() ) ); ?></p>
<a class="lnk" href="#"><?php esc_html_e( 'Ver proyectos', 'osteza' ); ?> <?php echo osteza_arrow(); ?></a>
</div>
</div>
</section>

</div>
<?php
get_template_part(
	'template-parts/contact',
	null,
	array(
		'title'   => $s['contact'],
		'service' => $s['form_service'],
	)
);

get_footer();
