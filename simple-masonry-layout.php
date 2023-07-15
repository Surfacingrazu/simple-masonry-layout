<?php

/*
 * Plugin Name: Simple Masonry Layout
 * Plugin URI: http://wordpress.org/plugins/simple-masonry-layout/
 * Description: With simple shortcode,Masonry Layout in action.
 * Author: Raju Tako
 * Version: 1.0
 * Author URI: https://profiles.wordpress.org/razzu
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Enque script and style
function simple_masonry_enqueue_scripts()
{   

	  wp_enqueue_style( 'sm-style', plugin_dir_url( __FILE__ ) . 'css/sm-style.css');
    wp_enqueue_style( 'sm-style');

    wp_enqueue_script('jquery'); 
    wp_enqueue_script( 'jquery-masonry' );
    wp_register_script( 'main-script', plugin_dir_url( __FILE__ ) . 'js/main.js', array('jquery'), '', true );
    wp_enqueue_script( 'main-script' );
}

add_action( 'wp_enqueue_scripts', 'simple_masonry_enqueue_scripts' );


include('inc/admin.php'); //admin option settings
include('inc/front.php'); // frontend shortcode 


