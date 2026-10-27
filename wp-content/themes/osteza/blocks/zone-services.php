<?php
/**
 * Services offered in a zone: the home page services. $a: block attributes.
 */

$hid = osteza_uid( 'h-sv' );
?>
<section class="sec sec-zs zserv" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap">
<div class="stitle rv"><?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2></div>
<ul class="tsvg">
<?php foreach ( osteza_services() as $s ) : ?>
<li class="rv"><a class="tsv" href="<?php echo $s['url']; ?>"><?php echo osteza_art_icon( $s['icon'], 36, 'currentColor', '1.7' ); ?><strong><?php echo esc_html( $s['name'] ); ?></strong><?php echo osteza_arrow(); ?></a></li>
<?php endforeach; ?>
</ul>
</div>
</section>
