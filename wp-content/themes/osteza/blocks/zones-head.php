<?php
/**
 * Zones index header with the interactive map. $a: block attributes.
 */

$zones = osteza_zones();
if ( ! $zones ) {
	return;
}
$zone     = osteza_zone_by( $a['selected'] );
$selected = $zone ? $zone['slug'] : array_key_first( $zones );
$url      = $zones[ $selected ]['url'];
?>
<section class="phead" aria-labelledby="h1">
<div class="wrap">
<?php osteza_breadcrumb(); ?>
<div class="zhero">
<div class="cherocopy">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h1 id="h1" class="disp"><?php echo osteza_html( $a['title'] ); ?></h1>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<div class="zsel" aria-live="polite" data-zone-selected><span class="zdot"></span><span><span class="zsell"><?php echo esc_html( $a['label'] ); ?></span><strong class="disp" data-zone-name><?php echo esc_html( $zones[ $selected ]['name'] ); ?></strong><span class="zsela" data-zone-area><?php echo esc_html( $zones[ $selected ]['area'] ); ?></span></span><a class="btn btn-p" href="<?php echo $url; ?>" data-zone-link<?php echo '#' === $url ? ' hidden' : ''; ?>><?php echo esc_html( $a['button'] ); ?> <?php echo osteza_arrow(); ?></a></div>
</div>
<figure class="zmap2"><?php get_template_part( 'template-parts/zone-map', null, array( 'selected' => $selected ) ); ?><?php if ( $a['caption'] ) : ?><figcaption class="zschema"><?php echo esc_html( $a['caption'] ); ?></figcaption><?php endif; ?></figure>
</div>
</div>
</section>
