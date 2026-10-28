<?php
/**
 * One group of the services catalogue, in one of three layouts. $a: block attributes.
 */

$g = osteza_group( $a['group'] );
if ( ! $g ) {
	if ( is_admin() || defined( 'REST_REQUEST' ) ) {
		echo '<p style="padding:24px">' . esc_html__( 'Elige un grupo de servicios en la barra lateral.', 'osteza' ) . '</p>';
	}
	return;
}
$hid  = osteza_uid( 'h-g' );
$href = $a['page'] ? osteza_link( $a['page'] ) : osteza_anchor( 'contacto' );
$icon = function ( $name, $size, $width, $stroke = 'currentColor' ) {
	return osteza_art_icon( $name, $size, $stroke, $width );
};
?>
<?php if ( 'cards' === $a['layout'] ) : ?>
<section id="g-<?php echo esc_attr( $g['key'] ); ?>" class="sec gdark dk" aria-labelledby="<?php echo esc_attr( $hid ); ?>" data-group>
<div class="wrap stack">
<div class="shead rv">
<div class="gtitle"><span class="gnum"><?php echo esc_html( $a['number'] ); ?></span><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $g['title'] ); ?></h2></div>
<p class="lead"><?php echo esc_html( $g['lead'] ); ?></p>
</div>
<ul class="cgrid2">
<?php foreach ( $g['items'] as $item ) : ?>
<?php /* translators: %s: service name (screen-reader text). */ ?>
<li class="rv" data-item><a class="dcard" href="<?php echo $item['url']; ?>"><?php echo $icon( $item['icon'], 44, '1.6', '#EBA3AE' ); ?><strong class="disp"><?php echo esc_html( $item['name'] ); ?></strong><span class="dd"><?php echo esc_html( $item['desc'] ); ?></span><span class="lnk"><?php esc_html_e( 'Ver servicio', 'osteza' ); ?><span class="sr"> <?php echo esc_html( $item['name'] ); ?></span> <?php echo osteza_arrow(); ?></span></a></li>
<?php endforeach; ?>
</ul>
</div>
</section>
<?php elseif ( 'list' === $a['layout'] ) : ?>
<section id="g-<?php echo esc_attr( $g['key'] ); ?>" class="sec" aria-labelledby="<?php echo esc_attr( $hid ); ?>" data-group>
<div class="wrap mgrid">
<div class="mlead rv">
<span class="gnum"><?php echo esc_html( $a['number'] ); ?></span>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $g['title'] ); ?></h2>
<p class="lead"><?php echo esc_html( $g['lead'] ); ?></p>
<?php if ( $a['button'] ) : ?><a class="btn btn-o" href="<?php echo $href; ?>"><?php echo esc_html( $a['button'] ); ?> <?php echo osteza_arrow(); ?></a><?php endif; ?>
</div>
<ul class="mlist">
<?php foreach ( $g['items'] as $item ) : ?>
<li class="rv" data-item><a class="mcard" href="<?php echo $item['url']; ?>"><span class="mic"><?php echo $icon( $item['icon'], 32, '1.7' ); ?></span><strong><?php echo esc_html( $item['name'] ); ?></strong><span><?php echo esc_html( $item['desc'] ); ?></span><span class="sarr" aria-hidden="true">→</span></a></li>
<?php endforeach; ?>
</ul>
</div>
</section>
<?php else : ?>
<section id="g-<?php echo esc_attr( $g['key'] ); ?>" class="sec sec-g1" aria-labelledby="<?php echo esc_attr( $hid ); ?>" data-group>
<div class="wrap ggrid">
<div class="ghead rv">
<span class="gnum"><?php echo esc_html( $a['number'] ); ?></span>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $g['title'] ); ?></h2>
<p class="lead"><?php echo esc_html( $g['lead'] ); ?></p>
<?php if ( $a['button'] ) : ?><a class="lnk" href="<?php echo $href; ?>"><?php echo esc_html( $a['button'] ); ?> <?php echo osteza_arrow(); ?></a><?php endif; ?>
</div>
<ul class="rows rv">
<?php foreach ( $g['items'] as $item ) : ?>
<li data-item><a class="srow2" href="<?php echo $item['url']; ?>"><span class="sic"><?php echo $icon( $item['icon'], 30, '1.8' ); ?></span><span class="stx"><strong class="disp"><?php echo esc_html( $item['name'] ); ?></strong><span><?php echo esc_html( $item['desc'] ); ?></span></span><span class="sarr" aria-hidden="true">→</span></a></li>
<?php endforeach; ?>
</ul>
</div>
</section>
<?php endif; ?>
