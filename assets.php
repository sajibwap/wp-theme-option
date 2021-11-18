<?php

function exc_scripts(){

	wp_enqueue_style( 'minitoggle-css', plugin_dir_url( __FILE__ )."/assets/css/minitoggle.css" );
	wp_enqueue_script( 'minitoggle-js', plugin_dir_url( __FILE__ )."/assets/js/minitoggle.js", array( 'jQuery' ), '1.10', true );
	wp_enqueue_script( 'main-js', plugin_dir_url( __FILE__ )."/assets/js/main.js", array( 'jQuery' ), time(), true );

}
add_action( 'admin_enqueue_scripts', 'exc_scripts' );

