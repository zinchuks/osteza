<?php
/** Run after a database backup. Adds missing SEO copy; preserves editor overrides. */
if ( PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; }
require dirname( __DIR__ ) . '/wp-load.php';
$copy = array(
 'es' => array( 'Montaje de cocinas y muebles en Costa Blanca | OSTEZA', 'Consejos de montaje, mudanzas y reformas en Costa Blanca. Guías de OSTEZA para preparar tu vivienda, organizar el trabajo y pedir presupuesto.' ),
 'en' => array( 'Kitchen fitting and furniture assembly in Costa Blanca | OSTEZA', 'Kitchen fitting, furniture assembly, moving and renovation advice. OSTEZA guides to preparing your home, planning the work and requesting a quote.' ),
 'uk' => array( 'Монтаж кухонь і збирання меблів на Коста-Бланці | OSTEZA', 'Поради зі збирання меблів, монтажу кухонь, переїздів і ремонту. Блог OSTEZA про підготовку житла, планування робіт та замовлення кошторису.' ),
 'ru' => array( 'Монтаж кухонь и сборка мебели на Коста-Бланке | OSTEZA', 'Советы по сборке мебели, монтажу кухонь, переездам и ремонту. Блог OSTEZA о подготовке жилья, планировании работ и запросе сметы.' ),
 'pl' => array( 'Montaż kuchni i mebli na Costa Blanca | OSTEZA', 'Porady dotyczące montażu kuchni i mebli, przeprowadzek i remontów. Blog OSTEZA o przygotowaniu mieszkania, planowaniu prac i wycenie.' ),
);
$count = 0;
foreach ( $copy as $lang => $texts ) {
 $front = pll_get_post( (int) get_option( 'page_on_front' ), $lang );
 if ( $front && ! get_post_meta( $front, '_osteza_seo_title', true ) ) { update_post_meta( $front, '_osteza_seo_title', $texts[0] ); $count++; }
 foreach ( get_posts( array( 'post_type' => 'page', 'numberposts' => -1, 'lang' => $lang, 'meta_key' => '_wp_page_template', 'meta_value' => 'page-templates/blog.php' ) ) as $post ) {
  if ( ! get_post_meta( $post->ID, '_osteza_seo_description', true ) ) { update_post_meta( $post->ID, '_osteza_seo_description', $texts[1] ); $count++; }
 }
}
echo "SEO fields added: $count\n";
