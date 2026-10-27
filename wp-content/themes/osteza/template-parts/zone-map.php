<?php
/**
 * Map of the coast with the zones of osteza_zones().
 *
 * Renders a schematic SVG, which main.js replaces with a real Leaflet map
 * (OpenStreetMap tiles) when scripts run; the SVG stays as the no-JS fallback.
 *
 * Args: 'selected' (zone slug). When given, the map is the interactive one of the
 * zones index: larger labels, and the selected town highlighted.
 */

$selected = $args['selected'] ?? null;
$pick     = null !== $selected;
$points   = array();
foreach ( osteza_zones() as $slug => $zone ) {
	$points[] = array(
		'slug' => $slug,
		'name' => $zone['name'],
		'geo'  => $zone['geo'],
	);
}
?>
<div class="zreal" data-map="<?php echo esc_attr( wp_json_encode( $points ) ); ?>"<?php echo $pick ? ' data-selected="' . esc_attr( $selected ) . '"' : ''; ?>>
<svg viewBox="-80 0 500 540" role="img" aria-labelledby="map-t">
<title id="map-t"><?php esc_html_e( 'Esquema de la Costa Blanca con las localidades donde trabajamos', 'osteza' ); ?></title>
<rect x="-80" width="500" height="540" fill="#5B3A92"/>
<path d="M-80 0H262L252 34 336 74 306 122 288 152 252 186 226 216 184 250 132 300 122 312 128 360 142 372 122 420 116 452 106 500 102 540H-80Z" fill="#ECDFE6"/>
<g font-family="Onest, system-ui, sans-serif" font-size="<?php echo $pick ? 18 : 14; ?>" fill="#1D1838" text-anchor="end">
<?php foreach ( osteza_zones() as $slug => $zone ) : ?>
<?php
list( $x, $y ) = $zone['map'];
$on            = $slug === $selected;
?>
<?php if ( $pick ) : ?>
<circle cx="<?php echo (int) $x; ?>" cy="<?php echo (int) $y; ?>" r="<?php echo $on ? 11 : 6; ?>" stroke="#F8F5F3" stroke-width="<?php echo $on ? 4 : 0; ?>"/><text x="<?php echo (int) $x - 14; ?>" y="<?php echo (int) $y + 5; ?>" font-weight="<?php echo $on ? 700 : 500; ?>"><?php echo esc_html( $zone['name'] ); ?></text>
<?php else : ?>
<circle cx="<?php echo (int) $x; ?>" cy="<?php echo (int) $y; ?>" r="6"/><text x="<?php echo (int) $x - 12; ?>" y="<?php echo (int) $y + 5; ?>" font-weight="600"><?php echo esc_html( $zone['name'] ); ?></text>
<?php endif; ?>
<?php endforeach; ?>
</g>
<text x="370" y="<?php echo $pick ? 330 : 320; ?>" font-family="Onest, system-ui, sans-serif" font-size="12" letter-spacing="3" fill="#F0C3CA" text-anchor="end"><?php esc_html_e( 'MEDITERRÁNEO', 'osteza' ); ?></text>
</svg>
</div>
