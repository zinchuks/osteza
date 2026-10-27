<?php
/**
 * Template Name: Servicio
 *
 * Service landing page. The sections are the page's blocks (OSTEZA category in the editor);
 * while the page is empty it shows the defaults of osteza_layout( 'service' ).
 */

get_header();
the_post();
echo '<div class="svc">';
osteza_the_sections( 'service' );
echo '</div>';
get_footer();
