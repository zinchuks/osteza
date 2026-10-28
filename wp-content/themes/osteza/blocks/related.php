<?php
/**
 * Related services and articles, and the projects note. $a: block attributes.
 */

$hid = osteza_uid( 'h-rel' );
?>
<section class="sec srel" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap">
<div class="stitle rv"><?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2></div>
<?php if ( $a['cards'] ) : ?>
<div class="rgrid">
<?php foreach ( (array) $a['cards'] as $card ) : ?>
<?php
$id      = (int) ( $card['page'] ?? 0 );
$is_post = $id && 'post' === get_post_type( $id );
$cls     = $card['style'] ?? 'rc-w';
$name    = ! empty( $card['name'] ) ? $card['name'] : ( $id ? get_the_title( $id ) : '' );
?>
<a class="rcard rv <?php echo esc_attr( $cls ); ?>" href="<?php echo osteza_link( $id ); ?>">
<?php echo osteza_art_icon( $card['icon'] ?? '', 44, 'rc-g' === $cls ? '#EBA3AE' : 'currentColor' ); ?>
<?php if ( ! empty( $card['cat'] ) ) : ?><span class="rcat"><?php echo esc_html( $card['cat'] ); ?></span><?php endif; ?>
<span class="disp"><?php echo esc_html( $name ); ?></span>
<?php if ( ! empty( $card['desc'] ) ) : ?><span class="rdesc"><?php echo esc_html( $card['desc'] ); ?></span><?php endif; ?>
<span class="lnk"><?php echo esc_html( $is_post ? __( 'Leer artículo', 'osteza' ) : __( 'Ver servicio', 'osteza' ) ); ?> <?php echo osteza_arrow(); ?></span>
</a>
<?php endforeach; ?>
</div>
<?php endif; ?>
<?php if ( $a['note'] && osteza_has_projects() ) : ?>
<div class="pnote rv">
<p><?php echo osteza_html( $a['note'] ); ?></p>
<?php if ( $a['noteLink'] ) : ?><a class="lnk" href="<?php echo osteza_projects_url(); ?>"><?php echo esc_html( $a['noteLink'] ); ?> <?php echo osteza_arrow(); ?></a><?php endif; ?>
</div>
<?php endif; ?>
</div>
</section>
