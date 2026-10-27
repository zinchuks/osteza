<?php
/**
 * Template Name: Zona
 *
 * Landing page of one work zone. The sections are the page's blocks (OSTEZA category in the editor);
 * while the page is empty it shows the defaults of osteza_layout( 'zone' ).
 */

get_header();
the_post();
echo '<div class="zpage">';
osteza_the_sections( 'zone' );
echo '</div>';
get_footer();
