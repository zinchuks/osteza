<?php
/**
 * Included / only if agreed, and the kinds of property. $a: block attributes.
 */

$hid   = osteza_uid( 'h-scope' );
$id    = $a['anchor'] ? ' id="' . esc_attr( $a['anchor'] ) . '"' : '';
$types = osteza_lines( $a['types'] );
?>
<section<?php echo $id; ?> class="scope" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap">
<div class="yn rv">
<div class="yn-a">
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp"><?php echo esc_html( $a['yesTitle'] ); ?></h2>
<ul class="chk"><?php foreach ( osteza_lines( $a['yes'] ) as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
</div>
<div class="yn-b dk">
<h3 class="disp"><?php echo esc_html( $a['noTitle'] ); ?></h3>
<ul class="chk no"><?php foreach ( osteza_lines( $a['no'] ) as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
<?php if ( $a['note'] ) : ?><p><?php echo esc_html( $a['note'] ); ?></p><?php endif; ?>
</div>
</div>
<?php if ( $types ) : ?>
<div class="types rv">
<span class="typesl"><?php echo esc_html( $a['typesLabel'] ); ?></span>
<?php foreach ( $types as $type ) : ?><span class="tagp"><?php echo esc_html( $type ); ?></span><?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</section>
