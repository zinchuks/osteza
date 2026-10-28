<?php
/** Read-only regressions for readable cover text and document titles. */
if ( PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; }
require dirname( __DIR__ ) . '/wp-load.php';
$checks = 0;
$check = function ( $actual, $expected ) use ( &$checks ) {
	if ( $actual !== $expected ) { throw new RuntimeException( 'Responsive content regression failed' ); }
	$checks++;
};
foreach ( array( "Antes\nde la obra" => 'Antes de la obra', "Tu\nmudanza" => 'Tu mudanza', "Cocina\nde IKEA" => 'Cocina de IKEA', "Your\nmove" => 'Your move', "Перед\nремонтом" => 'Перед ремонтом', "Перед\nремонтом " => 'Перед ремонтом', "Przed\nremontem" => 'Przed remontem' ) as $input => $expected ) {
	$check( trim( wp_strip_all_tags( osteza_cover_text_html( $input ) ) ), $expected );
}
$check( osteza_seo_branded_title( 'Service | osteza.es' ), 'Service | OSTEZA' );
$check( osteza_seo_branded_title( 'Service | gold-elk-571640.hostingersite.com' ), 'Service | OSTEZA' );
$check( osteza_seo_branded_title( 'Service | OSTEZA' ), 'Service | OSTEZA' );
$check( osteza_seo_branded_title( 'Kitchen assembly in Calpe. | osteza.es', true ), 'Kitchen assembly in Calpe | OSTEZA' );
$check( osteza_seo_branded_title( 'Збирання меблів у Кальпе.', true ), 'Збирання меблів у Кальпе | OSTEZA' );
echo "PASS $checks read-only responsive content checks\n";
