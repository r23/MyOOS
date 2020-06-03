<?php
/**
 * Functions and definitions
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$cpschool_includes = array(
	'theme-settings'               => '/theme-settings.php',
	'setup'                        => '/setup.php',
	'hooks-custom'                 => '/hooks-custom.php',
	'plugin-update-checker'        => '/plugins/plugin-update-checker/plugin-update-checker.php',
	'calendar-plus'                => '/plugins/calendar-plus.php',
	'advanced-custom-fields-setup' => '/plugins/advanced-custom-fields-setup.php',
	'kirki'                        => '/plugins/kirki/kirki.php',
	'wp-menu-icons'                => '/plugins/menu-icons/wp-menu-icons.php',
	'breadcrumbs'                  => '/plugins/breadcrumbs.php',
	'cp-directory-setup'           => '/plugins/cp-directory-setup.php',
	'widgets'                      => '/widgets.php',
	'enqueue'                      => '/enqueue.php',
	'template-tags'                => '/template-tags.php',
	'hooks-wp'                     => '/hooks-wp.php',
	'customizer'                   => '/customizer.php',
	'comments'                     => '/comments.php',
	'wp-bootstrap-navwalker'       => '/class-wp-bootstrap-navwalker.php',
	'editor'                       => '/editor.php',
);

$cpschool_includes = apply_filters( 'cpschool_includes', $cpschool_includes );
if ( $cpschool_includes ) {
foreach ( $cpschool_includes as $file ) {
	$filepath = locate_template( 'inc' . $file );
	if ( ! $filepath ) {
		trigger_error( sprintf( 'Error locating /inc%s for inclusion', $file ), E_USER_ERROR );
	}
	require_once $filepath;
}
}

// Handles updating theme from GitHub.
if ( class_exists( 'Puc_v4_Factory' ) ) {
	$cpschool_update_checker = Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/campuspress/campuspress-flex',
		__FILE__,
		'campuspress-flex'
	);

	$cpschool_update_checker->getVcsApi()->enableReleaseAssets();

	do_action( 'cpschool_update_checker_loaded', $cpschool_update_checker);
}
