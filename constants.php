<?php

/*
 * Constants required by Option Tree/Customizer
 */
define( 'LZ_BASE_DIR', get_stylesheet_directory() );
define( 'LZ_BASE_URL', get_stylesheet_directory_uri() );

/**
 * Define URL Location Constants
**/

define( 'LZ_LIB_URL',     LZ_BASE_URL  . '/lib' );
define( 'LZ_ASSETS_URL',  LZ_BASE_URL  . '/assets' );

define( 'LZ_IMG_URL',     LZ_ASSETS_URL  .  '/img' );
define( 'LZ_CSS_URL',     LZ_ASSETS_URL  .  '/css' );
define( 'LZ_JS_URL',      LZ_ASSETS_URL  .  '/js' );

define( 'LZ_ADMIN_URL',       LZ_LIB_URL  . '/admin' );
define( 'LZ_CUSTOMIZER_URL',  LZ_LIB_URL  . '/customizer' );


/**
 * Define Directory Location Constants
**/

define( 'LZ_LIB_DIR',     LZ_BASE_DIR  . '/lib' );
define( 'LZ_ASSETS_DIR',  LZ_BASE_DIR  . '/assets' );

define( 'LZ_IMG_DIR',     LZ_ASSETS_DIR  . '/img' );
define( 'LZ_CSS_DIR',     LZ_ASSETS_DIR  . '/css' );
define( 'LZ_JS_DIR',      LZ_ASSETS_DIR  . '/js' );

define( 'LZ_ADMIN_DIR',       LZ_LIB_DIR  . '/admin' );
define( 'LZ_CUSTOMIZER_DIR',  LZ_LIB_DIR  . '/customizer' );
?>