<?php
/**
 * Zone page header with the summary card. $a: block attributes.
 */

$contact = osteza_anchor( 'contacto' );
$near    = array();
foreach ( (array) $a['near'] as $item ) {
	$zone = osteza_zone_by( $item['zone'] ?? 0 );
	if ( $zone ) {
		$near[] = $zone['name'];
	}
}
?>
<section class="phead" aria-labelledby="h1">
<div class="wrap">
<?php osteza_breadcrumb(); ?>
<div class="thero">
<div class="aherocopy">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h1 id="h1" class="disp"><?php echo osteza_html( $a['title'] ?: get_the_title() ); ?></h1>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<div class="hbtns"><a class="btn btn-p" href="<?php echo $contact; ?>"><?php echo esc_html( $a['button'] ); ?> <?php echo osteza_arrow(); ?></a><a class="btn btn-o" href="<?php echo esc_url( osteza_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $a['button2'] ); ?></a></div>
</div>
<aside class="scard dk" aria-label="<?php esc_attr_e( 'Resumen de la zona', 'osteza' ); ?>">
<img class="scardbg" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<?php if ( $a['summaryTitle'] ) : ?><p class="disp"><?php echo esc_html( $a['summaryTitle'] ); ?></p><?php endif; ?>
<dl>
<?php foreach ( (array) $a['summary'] as $row ) : ?>
<div class="srow"><dt><?php echo esc_html( $row['term'] ?? '' ); ?></dt><dd><?php echo esc_html( $row['value'] ?? '' ); ?></dd></div>
<?php endforeach; ?>
<?php if ( $near ) : ?>
<div class="srow"><dt><?php echo esc_html( $a['nearLabel'] ); ?></dt><dd><?php echo esc_html( implode( ' · ', $near ) ); ?></dd></div>
<?php endif; ?>
</dl>
</aside>
</div>
</div>
</section>
