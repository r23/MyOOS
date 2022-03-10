<?php 
/** Actions and Filters **/
//Register JavaScript and CSS for threeobjectloaderinit
add_action( 'wp_enqueue_scripts', 'threeobjectviewer_register_threeobjectloaderinit', 5 );

//Enqueue JavaScript and CSS for threeobjectloaderinit
add_action( 'wp_enqueue_scripts', 'threeobjectviewer_enqueue_threeobjectloaderinit', 10 );
