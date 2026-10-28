<?php
/**
 * Plugin Name: OSTEZA Polylang
 * Description: The language is always in the URL, so Polylang sets no cookie and pages stay cacheable.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! defined( 'PLL_COOKIE' ) ) { define( 'PLL_COOKIE', false ); }
