<?php
/**
 * Template Name: Zonas
 *
 * Index of work zones. The sections are the page's blocks (OSTEZA category in the editor);
 * while the page is empty it shows the defaults of osteza_layout( 'zones' ).
 */

get_header();
the_post();

osteza_the_sections( 'zones' );

get_footer();
