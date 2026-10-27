<?php
/**
 * Template Name: Contacto
 *
 * Contact page. The sections are the page's blocks (OSTEZA category in the editor);
 * while the page is empty it shows the defaults of osteza_layout( 'contact' ).
 */

get_header();
the_post();

osteza_the_sections( 'contact' );

get_footer();
