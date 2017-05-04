<?php

/* 
Register Scripts and Style
================================================================================================*/

function theme_register_scripts() {
    wp_enqueue_style( 'prdxn-css', get_stylesheet_uri() );
    wp_enqueue_script( 'prdxn-js', esc_url( trailingslashit( get_template_directory_uri() ) . 'js/script.min.js' ), array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'theme_register_scripts', 1 );

?>