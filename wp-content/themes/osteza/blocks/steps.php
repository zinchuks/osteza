<?php
/**
 * Numbered steps in one of four layouts. $a: block attributes.
 */

$hid   = osteza_uid( 'h-steps' );
$id    = $a['anchor'] ? ' id="' . esc_attr( $a['anchor'] ) . '"' : '';
$steps = (array) $a['steps'];
$head  = function ( $class ) use ( $a, $hid ) {
	echo '<div class="' . esc_attr( trim( 'stitle ' . $class ) ) . '">';
	if ( $a['kicker'] ) {
		echo '<p class="kick">' . esc_html( $a['kicker'] ) . '</p>';
	}
	echo '<h2 id="' . esc_attr( $hid ) . '" class="disp h2">' . esc_html( $a['title'] ) . '</h2></div>';
};
?>
<?php if ( 'timeline' === $a['layout'] ) : ?>
<section<?php echo $id; ?> class="sec sproc dk" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap">
<div class="shead rv">
<?php $head( '' ); ?>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
</div>
<ol class="tl">
<?php foreach ( $steps as $i => $step ) : ?>
<li class="rv"><span class="tln"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><h3><?php echo esc_html( $step['title'] ?? '' ); ?></h3><p><?php echo esc_html( $step['text'] ?? '' ); ?></p></li>
<?php endforeach; ?>
</ol>
</div>
</section>
<?php elseif ( 'numbers' === $a['layout'] ) : ?>
<section<?php echo $id; ?> class="sec knext" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap">
<div class="shead rv">
<?php $head( '' ); ?>
<?php if ( $a['lead'] ) : ?><p class="snote"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
</div>
<ol class="nsteps">
<?php foreach ( $steps as $i => $step ) : ?>
<li class="rv"><span class="nn"><?php echo (int) $i + 1; ?></span><h3><?php echo esc_html( $step['title'] ?? '' ); ?></h3><p><?php echo esc_html( $step['text'] ?? '' ); ?></p></li>
<?php endforeach; ?>
</ol>
</div>
</section>
<?php elseif ( 'principles' === $a['layout'] ) : ?>
<section<?php echo $id; ?> class="sec valsec dk" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<img class="valbg" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<div class="wrap">
<?php $head( 'rv' ); ?>
<ol class="vals">
<?php foreach ( $steps as $i => $step ) : ?>
<li class="rv"><span class="vn"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><h3><?php echo esc_html( $step['title'] ?? '' ); ?></h3><p><?php echo esc_html( $step['text'] ?? '' ); ?></p></li>
<?php endforeach; ?>
</ol>
</div>
</section>
<?php else : ?>
<section<?php echo $id; ?> class="proc" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap">
<div class="shead shead-proc rv">
<?php $head( '' ); ?>
<?php if ( $a['lead'] ) : ?><p class="snote"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
</div>
<ol class="steps">
<?php foreach ( $steps as $i => $step ) : ?>
<li class="step rv"><span class="stepn"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><h3><?php echo esc_html( $step['title'] ?? '' ); ?></h3><p><?php echo esc_html( $step['text'] ?? '' ); ?></p></li>
<?php endforeach; ?>
</ol>
</div>
</section>
<?php endif; ?>
