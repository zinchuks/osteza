<?php
/**
 * Way of working: intro and three points. $a: block attributes.
 */

$hid    = osteza_uid( 'h-app' );
$colors = array( 'ap-g', 'ap-t', 'ap-s' );
?>
<section class="appr" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap split">
<div class="split-l rv">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<?php if ( $a['linkLabel'] && '#' !== osteza_link( $a['linkPage'] ) ) : ?><a class="lnk" href="<?php echo osteza_link( $a['linkPage'] ); ?>"><?php echo esc_html( $a['linkLabel'] ); ?> <?php echo osteza_arrow(); ?></a><?php endif; ?>
</div>
<ol class="split-r rv">
<?php foreach ( (array) $a['steps'] as $i => $step ) : ?>
<li><span class="apic <?php echo esc_attr( $colors[ $i % 3 ] ); ?>"><?php echo osteza_icon( isset( osteza_ui_icons()[ $step['icon'] ?? '' ] ) ? $step['icon'] : 'doc', 26, 'currentColor', '1.6' ); ?></span><span><strong><?php echo esc_html( $step['title'] ?? '' ); ?></strong><span><?php echo esc_html( $step['text'] ?? '' ); ?></span></span></li>
<?php endforeach; ?>
</ol>
</div>
</section>
