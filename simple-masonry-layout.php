<?php

/*
 * Plugin Name: Simple Masonry Layout
 * Plugin URI: http://wordpress.org/plugins/simple-masonry-layout/
 * Description: With simple shortcode, Masonry Layout in action.
 * Author: Raju Tako
 * Version: 1.3.4
 * Author URI: https://profiles.wordpress.org/razzu
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Plugin version */

define('SIMPLEMASONRYLAYOUT', '1.3.4');


if ( ! class_exists( 'SimpleMasonryAdmin' ) ) {
		require_once( dirname( __FILE__ ) . '/inc/class.simple.masonry.admin.php' );  //admin option settings
}

if ( ! class_exists( 'SimpleMasonryFront' ) ) {
		require_once( dirname( __FILE__ ) . '/inc/class.simple.masonry.front.php' );  // frontend shortcode 
}






