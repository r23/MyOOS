<?php
/**
 * Avoid direct calls to this file
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');

	exit();
} // END if(!function_exists('add_action'))

/**
 * The Help Class
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!class_exists('Twoclick_Social_Media_Buttons_Backend_Help')) {
	class Twoclick_Social_Media_Buttons_Backend_Help extends Twoclick_Social_Media_Buttons_Backend {

	} // END class Twoclick_Social_Media_Buttons_Backend_Help extends Twoclick_Social_Media_Buttons_Backend
} // END if(!class_exists('Twoclick_Social_Media_Buttons_Backend_Help'))