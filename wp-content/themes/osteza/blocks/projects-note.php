<?php
/**
 * Note pointing to the projects archive. $a: block attributes.
 */

if ( ! osteza_has_projects() ) {
	return;
}
$hid = osteza_uid( 'h-proj' );
?>
<section class="sec" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap stack40">
<div class="stitle rv"><?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2></div>
<div class="pnote pnote-l rv"><p><?php echo esc_html( $a['text'] ); ?></p><?php if ( $a['linkLabel'] ) : ?><a class="lnk" href="<?php echo osteza_projects_url(); ?>"><?php echo esc_html( $a['linkLabel'] ); ?> <?php echo osteza_arrow(); ?></a><?php endif; ?></div>
</div>
</section>
