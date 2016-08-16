<?php
// will be included in the shariff.php to display admin notices about updates and missing settings

// prevent direct calls to admin_notices.php
if ( ! class_exists('WP') ) { die(); }

// display an dismissible update notice
function shariff3UU_update_notice() {
	global $current_user;
	$user_id = $current_user->ID;
	// check that the user hasn't already clicked to ignore the message and can access options
	if ( current_user_can( 'manage_options' ) && ! get_option( 'shariff3UU_hide_update_notice' ) ) {
		echo "<div class='notice notice-success is-dismissible shariff-update-notice'><p>";
			$updatetext = __( 'Shariff Wrapper has been successfully updated to version %version. If you encounter any problems, please report them to the <a href="https://wordpress.org/support/plugin/shariff" target="_blank"><strong>Support Forum</strong></a>, so we can fix them!', 'shariff' );
			$updatetext = str_replace( '%version', $GLOBALS["shariff3UU"]["version"], $updatetext );
			echo $updatetext;
		echo "</p></div>";
	}
}
add_action( 'admin_notices', 'shariff3UU_update_notice' );

// display an info notice, if a service has been selected that requires a username, id, etc. and none has been provided
function shariff3UU_service_notice() {
	// prevent php info notices
	$services = array();
	// check if any services are set and if user can manage options
	if ( isset( $GLOBALS["shariff3UU"]["services"] ) && current_user_can( 'manage_options' ) ) {
		// Flattr
		if ( strpos( $GLOBALS["shariff3UU"]["services"], 'flattr' ) !== false && empty( $GLOBALS["shariff3UU"]["flattruser"] ) ) {
			$services[] = "Flattr";
		}
		// Patreon
		if ( strpos( $GLOBALS["shariff3UU"]["services"], 'patreon' ) !== false && empty( $GLOBALS["shariff3UU"]["patreonid"] ) ) {
			$services[] = "Patreon";
		}
		// PayPal
		if ( strpos( $GLOBALS["shariff3UU"]["services"], 'paypal' ) !== false && strpos( $GLOBALS["shariff3UU"]["services"], 'paypalme' ) === false && empty( $GLOBALS["shariff3UU"]["paypalbuttonid"] ) ) {
			$services[] = "PayPal";
		}
		// PayPal.me
		if ( strpos( $GLOBALS["shariff3UU"]["services"], 'paypalme' ) !== false && empty( $GLOBALS["shariff3UU"]["paypalmeid"] ) ) {
			$services[] = "PayPal.Me";
		}
		// Bitcoin
		if ( strpos( $GLOBALS["shariff3UU"]["services"], 'bitcoin' ) !== false && empty( $GLOBALS["shariff3UU"]["bitcoinaddress"] ) ) {
			$services[] = "Bitcoin";
		}
		// Mailform
		if ( strpos( $GLOBALS["shariff3UU"]["services"], 'mailform' ) !== false && isset( $GLOBALS["shariff3UU"]["disable_mailform"] ) ) {
			$services[] = "Mailform";
		}
		// loop through services and display an info notice
		foreach ( $services as $service ) {
			echo "<div class='notice notice-error'><p>";
				// mail form error
				if ( $service === "Mailform" ) {
					echo __('Please check your', 'shariff');
					echo " <a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu&tab=mailform'>" . __('Shariff Settings', 'shariff') . "</a> - ";
					echo __('Mailform has been selected as a service, but mail form functionality has been disabled on the mail form tab!', 'shariff');
				}
				// other service settings errors
				else {
					echo __('Please check your', 'shariff');
					echo " <a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu&tab=advanced'>" . __('Shariff Settings', 'shariff') . "</a> - ";
					$infotext = __('%service has been selected as a service, but no username, ID or address has been provided! Please enter the required information on the advanced tab!', 'shariff');
					$infotext = str_replace( '%service', $service, $infotext );
					echo $infotext;
				}
			echo "</p></div>";
		}
	}
}
add_action( 'admin_notices', 'shariff3UU_service_notice' );
