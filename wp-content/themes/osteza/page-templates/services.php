<?php
/**
 * Template Name: Servicios
 *
 * Services catalogue. The sections are the page's blocks (OSTEZA category in the editor);
 * while the page is empty it shows the defaults of osteza_layout( 'services' ).
 */

get_header();
the_post();

osteza_the_sections( 'services' );

get_footer();
