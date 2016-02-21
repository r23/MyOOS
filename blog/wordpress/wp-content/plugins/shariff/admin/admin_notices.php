<?php
/**
 * Will be included in the shariff.php.
 * Update info currently active for release 3.3 due to OpenShareCount.com replacement for Twitter
*/

// prevent direct calls to admin_notices.php
if ( ! class_exists('WP') ) { die(); }

// update info currently active for release 3.3 due to OpenShareCount replacement for Twitter

// display an update notice that can be dismissed
function shariff3UU_admin_notice() {
	global $current_user;
	$user_id = $current_user->ID;
	// check that the user hasn't already clicked to ignore the message and can access options
	if ( ! get_user_meta( $user_id, 'shariff3UU_ignore_notice' ) && current_user_can( 'manage_options' ) ) {
		$link = add_query_arg( 'shariff3UU_nag_ignore', '0', esc_url_raw( $_SERVER['REQUEST_URI'] ) );
		echo "<div class='updated'><a href='" . esc_url( $link ) . "' class='shariff_admininfo_cross'><div class='shariff_cross_icon'></div></a><p>" . __( 'Shariff Wrapper: Twitter has disabled share counts. Please read <a href="https://www.jplambeck.de/twitter-saveoursharecounts/" target="_blank"><strong>this post</strong></a> prior to activating the alternative via OpenShareCount.com!', 'shariff3UU' ) . "</span></p></div>";
 	}
}
add_action( 'admin_notices', 'shariff3UU_admin_notice' );

// helper function for shariff3UU_admin_notice()
function shariff3UU_nag_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	// If user clicks to ignore the notice, add that to their user meta
	if ( isset( $_GET['shariff3UU_nag_ignore'] ) && sanitize_text_field($_GET['shariff3UU_nag_ignore'] ) == '0' ) {
		add_user_meta( $user_id, 'shariff3UU_ignore_notice', 'true', true );
	}
}
add_action('admin_init', 'shariff3UU_nag_ignore');

// display an info notice if flattr is set as a service, but no username is entered
function shariff3UU_flattr_notice() {
	if ( isset( $GLOBALS["shariff3UU"]["services"] ) &&  ( strpos( $GLOBALS["shariff3UU"]["services"], 'flattr' ) !== false ) && empty( $GLOBALS["shariff3UU"]["flattruser"] ) && current_user_can( 'manage_options' ) ) {
		echo "<div class='error'><p>" . __('Please check your ', 'shariff3UU') . "<a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu&tab=advanced'>" . __('Shariff-Settings</a> - Flattr was selected, but no username was provided! Please enter your <strong>Flattr username</strong> in the shariff options!', 'shariff3UU') . "</span></p></div>";
	}
}
add_action( 'admin_notices', 'shariff3UU_flattr_notice' );

// display an info notice if patreon is set as a service, but no username is entered
function shariff3UU_patreon_notice() {
	if ( isset( $GLOBALS["shariff3UU"]["services"] ) &&  ( strpos( $GLOBALS["shariff3UU"]["services"], 'patreon' ) !== false ) && empty( $GLOBALS["shariff3UU"]["patreonid"] ) && current_user_can( 'manage_options' ) ) {
		echo "<div class='error'><p>" . __('Please check your ', 'shariff3UU') . "<a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu&tab=advanced'>" . __('Shariff-Settings</a> - Patreon was selected, but no username was provided! Please enter your <strong>Patreon username</strong> in the shariff options!', 'shariff3UU') . "</span></p></div>";
	}
}
add_action( 'admin_notices', 'shariff3UU_patreon_notice' );

// display an info notice if paypal is set as a service, but no button id is entered
function shariff3UU_paypal_notice() {
	if ( isset( $GLOBALS["shariff3UU"]["services"] ) &&  ( strpos( $GLOBALS["shariff3UU"]["services"], 'paypal' ) !== false ) && ( strpos( $GLOBALS["shariff3UU"]["services"], 'paypalme' ) === false ) && empty( $GLOBALS["shariff3UU"]["paypalbuttonid"] ) && current_user_can( 'manage_options' ) ) {
		echo "<div class='error'><p>" . __('Please check your ', 'shariff3UU') . "<a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu&tab=advanced'>" . __('Shariff-Settings</a> - PayPal was selected, but no button ID was provided! Please enter your <strong>Hosted Button ID</strong> in the shariff options!', 'shariff3UU') . "</span></p></div>";
	}
}
add_action( 'admin_notices', 'shariff3UU_paypal_notice' );

// display an info notice if paypalme is set as a service, but no paypal.me id is entered
function shariff3UU_paypalme_notice() {
	if ( isset( $GLOBALS["shariff3UU"]["services"] ) &&  ( strpos( $GLOBALS["shariff3UU"]["services"], 'paypalme' ) !== false ) && empty( $GLOBALS["shariff3UU"]["paypalmeid"] ) && current_user_can( 'manage_options' ) ) {
		echo "<div class='error'><p>" . __('Please check your ', 'shariff3UU') . "<a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu&tab=advanced'>" . __('Shariff-Settings</a> - PayPal.Me was selected, but no ID was provided! Please enter your <strong>PayPal.Me ID</strong> in the shariff options!', 'shariff3UU') . "</span></p></div>";
	}
}
add_action( 'admin_notices', 'shariff3UU_paypalme_notice' );

// display an info notice if bitcoin is set as a service, but no address is entered
function shariff3UU_bitcoin_notice() {
	if ( isset( $GLOBALS["shariff3UU"]["services"] ) &&  ( strpos( $GLOBALS["shariff3UU"]["services"], 'bitcoin' ) !== false ) && empty( $GLOBALS["shariff3UU"]["bitcoinaddress"] ) && current_user_can( 'manage_options' ) ) {
		echo "<div class='error'><p>" . __('Please check your ', 'shariff3UU') . "<a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu&tab=advanced'>" . __('Shariff-Settings</a> - Bitcoin was selected, but no address was provided! Please enter your <strong>Bitcoin Address</strong> in the shariff options!', 'shariff3UU') . "</span></p></div>";
	}
}
add_action( 'admin_notices', 'shariff3UU_bitcoin_notice' );

// display an info notice if mailform is set as a service, but mail form functionality has been disabled
function shariff3UU_mail_notice() {
	if ( isset( $GLOBALS["shariff3UU_mailform"]["disable_mailform"] ) && ( strpos( $GLOBALS["shariff3UU_basic"]["services"], 'mailform' ) !== false ) && isset( $GLOBALS["shariff3UU"]["disable_mailform"] ) && $GLOBALS["shariff3UU"]["disable_mailform"] == '1' && current_user_can( 'manage_options' ) ) {
		echo "<div class='error'><p>" . __('Please check your ', 'shariff3UU') . "<a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu&tab=mailform'>" . __('Shariff-Settings</a> - Mailform has been selected as a service, but mail form functionality is disabled!', 'shariff3UU') . "</span></p></div>";
	}
}
add_action( 'admin_notices', 'shariff3UU_mail_notice' );

?>
