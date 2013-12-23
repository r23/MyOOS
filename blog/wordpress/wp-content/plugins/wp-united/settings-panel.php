<?php
/** 
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
*	The plugin settings panels.
* 
*/

if ( !defined('ABSPATH') && !defined('IN_PHPBB') ) exit;

/**
 * Add menu options for WP-United Settings panel
 */
 if(!is_multisite()) {
	add_action('admin_menu', 'wpu_settings_menu');
} else {
	add_action('network_admin_menu', 'wpu_settings_menu');
}

function wpu_settings_menu() {  
	global $wpUnited, $phpbbForum;
	
	if (!current_user_can('manage_options'))  {
		return;
	}
	
	if (!function_exists('add_submenu_page')) {
		return;
	}	
	
	if(isset($_GET['page'])) {
		if($_GET['page'] == 'wpu_acp') {
			global $phpbbForum;
			wp_redirect($phpbbForum->append_sid($phpbbForum->get_board_url()  .  'adm/index.php'), 302);
			die();
		}
		if($_GET['page'] == 'wpu-user-mapper') {
			if( isset($_POST['wpumapload']) && check_ajax_referer('wp-united-map') ) {
				// Send user mapper html data
				wpu_map_show_data();
				die();
			}
			if(isset($_GET['term']) && check_ajax_referer('wp-united-usersearch')) {
				// send JSON back for autocomplete
				
				$pkg = ($_GET['pkg'] == 'phpbb') ? 'phpbb' : 'wp';
				$term = request_var('term', '');

				require($wpUnited->get_plugin_path() . 'user-mapper.php');
				require($wpUnited->get_plugin_path() . 'mapped-users.php');
				
				$userMapper = new WPU_User_Mapper("leftSide={$pkg}&numToShow=10&numStart=0&showOnlyInt=0
					&showOnlyUnInt=0&showOnlyPosts=0&showOnlyNoPosts=0", 0, $term);
				
				$userMapper->send_json();
				die();
			}
			if( isset($_POST['wpumapaction']) && check_ajax_referer('wp-united-mapaction') ) {
				// Send user mapper html data
				
				wpu_process_mapaction();
				die();
			}
			if( isset($_POST['wpusetperms']) && check_ajax_referer('wp-united-mapaction') ) {
				// Send user mapper html data
				
				wpu_process_perms();
				die();
			}
						
		}
	}	
	

	wp_register_style('wpuSettingsStyles', $wpUnited->get_plugin_url() . 'theme/settings.css');
	wp_enqueue_style('wpuSettingsStyles'); 
		
	if(isset($_GET['page'])) {
		if(in_array($_GET['page'], array('wp-united-settings', 'wp-united-setup', 'wpu-user-mapper'))) {
			
			wp_enqueue_script('filetree', $wpUnited->get_plugin_url() . 'js/filetree.js', array('jquery'), false, false);				
			wp_enqueue_script('colorbox', $wpUnited->get_plugin_url() . 'js/colorbox.js', array('jquery'), false, false);				
			wp_enqueue_script('splitter', $wpUnited->get_plugin_url() . 'js/splitter.js', array('jquery', 'jquery-effects-core'), false, false);				
			
			
			wp_enqueue_script(
				'jsplumb', 
				$wpUnited->get_plugin_url() . 'js/jsplumb.js', 
				array(
					'jquery', 
					'jquery-ui-core', 
					'jquery-ui-draggable', 
					'jquery-ui-droppable'
				), 
				false, 
				false
			);				
			
			wp_enqueue_script(
				'wpu-settings', 
				$wpUnited->get_plugin_url() . 'js/settings.js', 
				array( 
					'jsplumb', 
					'splitter', 
					'colorbox', 
					'filetree', 
					'jquery-ui-widget',
					'jquery-ui-tabs', 
					'jquery-ui-button', 
					'jquery-ui-slider',
					'jquery-ui-dialog',
					'jquery-ui-autocomplete',
					'jquery-effects-core',
					'jquery-effects-slide',
					'jquery-effects-highlight'
				), 
				$wpUnited->get_version(), 
				false
			);	
				
		}
		if(in_array($_GET['page'], array('wp-united-settings', 'wp-united-setup', 'wpu-user-mapper', 'wpu-advanced-options', 'wp-united-help', 'wp-united-support'))) {
			wp_register_style('wpuSettingsStyles', $wpUnited->get_plugin_url() . 'theme/settings.css');
			wp_enqueue_style('wpuSettingsStyles');
		}
	}	
		
	$top = add_menu_page('WP-United ', __('WP-United', 'wp-united'), 'manage_options', 'wp-united-setup', 'wpu_setup_menu', $wpUnited->get_plugin_url() . 'images/tiny.gif', '2.0000000123' );
	add_submenu_page('wp-united-setup', __('WP-United Setup', 'wp-united'), __('Setup / Status', 'wp-united'), 'manage_options','wp-united-setup');
		
		
	// only show other menu items if WP-United is set up
	if($wpUnited->is_working()) {
		add_submenu_page('wp-united-setup', __('WP-United Settings', 'wp-united'), __('Settings', 'wp-united'), 'manage_options','wp-united-settings', 'wpu_settings_page');

			if($wpUnited->get_setting('integrateLogin')) {
					add_submenu_page('wp-united-setup', __('WP-United User Mapping', 'wp-united'), __('User Mapping', 'wp-united'), 'manage_options','wpu-user-mapper', 'wpu_user_mapper');
			}
		add_submenu_page('wp-united-setup', __('WP-United Advanced Options', 'wp-united'), __('Advanced Options', 'wp-united'), 'manage_options','wpu-advanced-options', 'wpu_advanced_options');
		add_submenu_page('wp-united-setup', __('Visit phpBB ACP', 'wp-united'), __('Visit phpBB ACP', 'wp-united'), 'manage_options', 'wpu_acp', 'wpu_acp');
	}
	
	add_submenu_page('wp-united-setup', __('Get Help', 'wp-united'), __('Get help', 'wp-united'), 'manage_options','wp-united-help', 'wpu_get_help');
	add_submenu_page('wp-united-setup', __('Please Help Support WP-United!', 'wp-united'), __('Support WP-United', 'wp-united'), 'manage_options','wp-united-support', 'wpu_support');
	
}


/** 
 * Just a stub for the menu to redirect to the phpBB ACP
 * We redirect before this is invoked.
 */
function wpu_acp() {
	
}

/**
 * egg
 */
function wpu_get_settings_logo() {
	global $wpUnited;
	
	$logo = 'seclogo.jpg';
	$date = date('m/d');
	if (($date == '12/24') || ($date == '12/25') || ($date == '12/26')) {
		$logo = 'seclogoegg.png';
	}
	
	return $wpUnited->get_plugin_url() . "images/settings/$logo";	
}

/**
 * Decide whether to show the advanced options, or save them
 */
function wpu_advanced_options() {
	global $wpUnited;
	?>
	<div class="wrap" id="wp-united-setup">
		<img id="panellogo" src="<?php echo wpu_get_settings_logo(); ?>" />
		<?php screen_icon('options-general'); ?>
		<h2> <?php _e('WP-United Advanced Options', 'wp-united'); ?> </h2>
		<p><?php echo sprintf(__('Some additional options can be set in the included file, %s. These do not normally need to be changed. However, to review and change these options, please open the file in a text editor.', 'wp-united'), '<strong>' . add_trailing_slash($wpUnited->get_plugin_path()) . 'options.php</strong>' ) ; ?></p>

		<?php
		if(isset($_POST['wpuadvanced-submit'])) {
			// process form
			if(check_admin_referer( 'wp-united-advanced')) {
				wpu_process_advanced_options();
			}
		} else {
				wpu_show_advanced_options();
		}
		?></div> <?php
}

function wpu_get_help() {
	global $wpUnited, $wpuDebug; 
	?>
	<div class="wrap" id="wp-united-setup">
		<img id="panellogo" src="<?php echo wpu_get_settings_logo(); ?>" />
		<?php screen_icon('options-general'); ?>
		<h2> <?php _e('Get help or support', 'wp-united'); ?> </h2>
		<h3><?php _e('Community support &amp; bug reporting', 'wp-united'); ?></h3>
		<p><?php _e('Free community support is available from the WP-United forum. Please search there for any other users who may be experiencing the same problem, and discuss any issues with fellow users.', 'wp-united'); ?></p>
		<p><?php _e('Please copy and paste the following information into a new topic post when you are seeking support. This will help us understand your setup at a glance. It is sanitized to protect your private server information and contains BBCode formatting.', 'wp-united'); ?> </p>
		<?php 
			$san = (isset($_GET['showfull'])) ? true : false; 
			$changeLink = ($san) ? '' : '&amp;showfull=1';
			$changeText = ($san) ? __('Hide sensitive information' , 'wp-united') : __('Show sensitive information', 'wp-united');	
		?>
		<p><small><a href="<?php echo get_admin_url() . 'admin.php?page=wp-united-help' . $changeLink; ?>"><?php echo $changeText; ?></a></small></p>
	
		<div style="border: 1px solid #cccccc;font-family: monospace;padding: 6px;"><?php echo $wpuDebug->get_debug_info(!$san); ?></div>
	</div>
	<?php

}

function wpu_support() {
	global $wpUnited;
	?>
	<div class="wrap" id="wp-united-setup">
		<img id="panellogo" src="<?php echo wpu_get_settings_logo(); ?>" />
		<?php screen_icon('options-general'); ?>
		<h2> <?php _e('Please Help Support WP-United', 'wp-united'); ?> </h2>
		<p><?php _e('Thank you very much for downloading and using WP-United. I hope you enjoy it.', 'wp-united');  ?></p>
		<p><?php _e('WP-United represents thousands of hours of coding, support and significant ongoing server costs.', 'wp-united');  ?></p>
		<p><?php _e('It is free software, and I hope you find it useful. If you do, please support me by making a donation here.', 'wp-united'); ?></p>
		<p><?php _e('Any amount, however small, is very much appreciated. Thank you!', 'wp-united');  ?></p>
		
		<div id="supportmethods">
			<fieldset><legend><?php _e('PayPal', 'wp-united'); ?></legend>
				<p><?php _e('The PayPal link will take you to a donation page for our PayPal business account, \'Pet Pirates\'', 'wp-united');  ?></p>
				
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="GSBRNNH7REY8Y">
					<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				</form>
			</fieldset>
			
			<fieldset><legend><?php _e('Google Checkout', 'wp-united'); ?></legend>
				<p><?php _e('This button will take you to our Google Checkout support page.', 'wp-united'); ?></p>
				<form class="gcheckout" method="POST" action="https://checkout.google.com/cws/v2/Merchant/360787695278690/checkoutForm" accept-charset="utf-8">
					<input type="hidden" name="item_name_1" value="WP-United Support"/>
					<input type="hidden" name="item_description_1" value="Thank you very much for your contribution. It is very much appreciated, and every little helps! We hope you will enjoy using WP-United."/>
					<input type="hidden" name="item_quantity_1" value="1" id="qty"/>
					<input type="hidden" name="item_currency_1" value="GBP" />
					<label for="amt"><?php echo sprintf(__('Donation Amount %s: ', 'wp-united'), '(GBP£)'); ?></label><input type="text" name="item_price_1" value="" id="amt"/>
					<input type="hidden" name="charset"/> 
					<input type="image" id="submit" name="Google Checkout" alt="Fast checkout through Google" src="https://checkout.google.com/buttons/support.gif?merchant_id=360787695278690&w=130&h=50&style=white&variant=text" />
				</form>
			</fieldset>

			<fieldset><legend><?php _e('Send WP-United some BitCoins!', 'wp-united'); ?></legend>
			<div>
				<p><?php _e('Alternatively, please donate some Satoshi to a good cause.', 'wp-united');  ?></p>
				<a href="bitcoin:1N4swuit91Goe3QcA79RF5UyVFEijWX6UL?amount=0.5&label=WP-United">
				<img src="<?php echo $wpUnited->get_plugin_url(); ?>images/settings/btc-wpu.png"></a>
				<p><em>1N4swuit91Goe3QcA79RF5UyVFEijWX6UL</em></p>
			</div>
			</fieldset>
		</div>
		
		<br style="clear: both;" />
			
		<h3><?php _e('Other ways to support the WP-United project', 'wp-united'); ?></h3>
		<p><?php _e('If you cannot donate, please consider helping support the WP-United project in another way. For example, you could help:', 'wp-united');  ?></p>
		<ul>
			<li><?php _e('Contributing to the WP-United documentation', 'wp-united');  ?></li>
			<li><a href="http://www.wp-united.com/2012/12/12/how-to-help-translate-wp-united/" target="_blank"><?php  _e('Providing a translation', 'wp-united');  ?></a></li>
			<li><?php _e('Recommending WP-United or our paid installation services', 'wp-united');  ?></li>
			<li><a href="http://wordpress.org/support/view/plugin-reviews/" target="_blank"><?php _e('Writing a review', 'wp-united');  ?></a></li>
			<li><?php _e('Linking back to www.wp-united.com, or posting about WP-United on your blog.', 'wp-united');  ?></li>
		</ul>
		
		<p><?php printf(__('For more information, please visit the %1$sWP-United forums%2$s.', 'wp-united'), '<a href="http://www.wp-united.com/index.php">', '</a>'); ?></p>

	</div>
	
	<?php
}

/**
 * If settings have been changed and Template Voodoo is active, we reload the page twice in a hidden iFrame in order to reset the styles.
 */
function wpu_reload_preview() {
	global $wpUnited, $phpbbForum;
	
	if(!$wpUnited->is_working()) {
		return;
	}
	
	$previewUrl = '';
	if ($wpUnited->get_setting('showHdrFtr') == 'FWD') {
		$previewUrl = get_site_url();
	} else if($wpUnited->get_setting('showHdrFtr') == 'REV')  {
		if(is_object($phpbbForum)) {
			$previewUrl = $phpbbForum->get_board_url();
		}
	}
	if(empty($previewUrl)) {
		return '';
	} 
	?>
	<p id="wpulastprocessing"><?php _e('Performing final processing... Please wait...', 'wp-united'); ?></p>
	<iframe id="wpupreviewreload" onload="wpuIncPrevCtr();" src="<?php echo $previewUrl . '?wpurnd=' . rand(100000,999999); ?>" style="float: left;width:1px;height:1px;border: 0;" border="0"></iframe>
	<script type="text/javascript">
	// <![CDATA[ 
		var ctr = 0;
		function wpuIncPrevCtr() {
			if(ctr < 2) {
				ctr++;
				$wpu('#wpulastprocessing').show();
				// in case the site has frame breakout code that tries to redirect this parent page.
				window.onbeforeUnload = function(e) { 
					return '<?php _e('Please stay on this page for a few more moments until processing is complete. Stay on this page?', 'wp-united'); ?>';
				};
				try {
					document.getElementById('wpupreviewreload').contentWindow.location.reload(true);
				} catch(e) {}
			} else {
				window.onbeforeunload = null;
				try {
					$wpu('#wpulastprocessing, #wpupreviewreload').hide('slow');
				} catch(e) {}
			}
		}
	// ]]>
	</script>
	<?php
}


function wpu_setup_menu() {
	global $wpUnited, $phpbbForum;
	
	?>
		<div class="wrap" id="wp-united-setup">
		<img id="panellogo" src="<?php echo wpu_get_settings_logo(); ?>" />
		<?php screen_icon('options-general'); ?>
		<h2> <?php _e('WP-United Setup / Status', 'wp-united'); ?> </h2>
		<p><?php _e('WP-United needs to connect to phpBB in order to work. On this screen you can set up or disable the connection.', 'wp-united') ?></p>

		<div id="wputransmit"><p><strong><?php _e('Communicating with phpBB...', 'wp-united'); ?></strong><br /><?php _e('Please Wait...'); ?></p><img src="<?php echo $wpUnited->get_plugin_url() ?>images/settings/wpuldg.gif" /></div>

	<?php
	
	$needPreview = false;
	$msg = '';
	if(isset($_GET['msg'])) {
		if($_GET['msg'] == 'fail') { 
			$msg = html_entity_decode(base64_decode(stripslashes_deep((string)$_POST['msgerr'])));
		} else {
			// $msg is succcess, do preview reloads to init Template Voodoo:
			$needPreview = true;
		}
	}
		
	$buttonDisplay = 'display: block;';
	
	$versionCheck = $wpUnited->check_mod_version();
	if($versionCheck['result'] != 'OK') {
		$statusText = __('Disabled', 'wp-united');
		$statusColour = "error";
		$statusDesc = $versionCheck['message'];
		$buttonDisplay = 'display: block;';	
		$wpUnited->disable();
		$needPreview = false;
	} elseif(!$wpUnited->is_enabled() && ($wpUnited->get_last_run() == 'working')) {
			$statusText = __('Disabled', 'wp-united');
			$statusColour = "error";
			$statusDesc = __('WP-United is disabled. Select your forum location below and then click &quot;Connect&quot;', 'wp-united') . '<br /><br />' . __('You can\'t change any other settings until WP-United is connected.', 'wp-united');
			$buttonDisplay = 'display: block;';	
			$needPreview = false;
	} else {
	
		switch($wpUnited->get_last_run()) {
			case 'working':
				$statusText = __('OK', 'wp-united') . '&nbsp;' . $phpbbForum->add_smilies(':-)');
				$statusColour = "updated allok";
				$statusDesc =  __('WP-United is connected and working.', 'wp-united') . '<br />' . '<br />'. __('If you ever need to uninstall WP-United, disable it here first before disabling the plugin, so that it can be removed from phpBB.', 'wp-united');
				$buttonDisplay = 'display: none;';
				break;
			case 'connected':
				$statusText = __('Connected, but not ready or disabled due to errors', 'wp-united');
				$statusColour = "updated highlight allok";
				global $wpuAutoPackage, $wpuReleasePackage;
				$wpuWpPackage = (isset($wpuReleasePackage)) ? 'wp-united-nightly-phpbb' : 'wp-united-latest-phpbb';
				$statusDesc = __('WP-United is connected but your phpBB forum is either producing errors, or is not set up properly.', 'wp-united') .  __('You need to install the WP-United phpBB MOD.', 'wp-united') . '<br /><br />' .  sprintf(__('%1$sClick here%2$s to download the modification package. '), "<a href=\"http://www.wp-united.com/releases/{$wpuWpPackage}\">", '</a>') . sprintf(__('You can apply it using %1$sAutoMod%2$s (recommended), or manually by reading the install.xml file and following %3$sthese instructions%4$s. When done, click &quot;Connect&quot; to try again.', 'wp-united'), '<a href="http://www.phpbb.com/mods/automod/">', '</a>', '<a href="http://www.phpbb.com/mods/installing/">', '</a>') .  '<br /><br />' . __('You can\'t change any other settings until the problem is fixed.', 'wp-united');
				$needPreview = false;
				break;
			default:
				$statusText = __('Not Connected', 'wp-united');
				$statusColour = "error";
				$statusDesc = __('WP-United is not connected yet. Select your forum location below and then click &quot;Connect&quot;', 'wp-united') . '<br /><br />' . __('You can\'t change any other settings until WP-United is connected.', 'wp-united');
				$buttonDisplay = (!$wpUnited->is_enabled()) ? 'display: block;' : 'display: none;';
				$needPreview = false;
		}
	}
	
	wpu_panel_warnings();
		
	echo "<div id=\"wpustatus\" class=\"$statusColour\"><p><strong>" . sprintf(__('Current Status: %s', 'wp-united'), $statusText) . '</strong>';
	if($wpUnited->get_last_run() == 'working' && $wpUnited->is_enabled()) {
		echo '<button style="float: right;margin-bottom: 6px;" class="button-secondary" onclick="return wpu_manual_disable(\'wp-united-setup\');">' . __('Disable', 'wp-united') . '</button>';
	}
	echo "<br /><br />$statusDesc";
	if(!empty($msg)) {
		echo '<br /><br /><strong>' . __('The server returned the following information:', 'wp-united') . "</strong><br />$msg";
	}
	echo '</p></div>';
	
	if($needPreview) {
		wpu_reload_preview();
	} 
	
	?>
	<h3><?php _e('phpBB Location', 'wp-united') ?></h3>
	<form name="wpu-setup" id="wpusetup" method="post" onsubmit="return wpu_transmit('wp-united-setup', this.id);">
		<?php wp_nonce_field('wp-united-setup');  ?>
		
		<p><?php _e('WP-United needs to know where phpBB is installed on your server.', 'wp-united'); ?> <span id="txtselpath"><?php _e("Find and select your phpBB's config.php below.", 'wp-united'); ?></span><span id="txtchangepath" style="display: none;"><?php _e('Click &quot;Change Location&quot; to change the stored location.', 'wp-united'); ?></span></p>
		
		<?php 
		
			$docRoot = wpu_get_doc_root(); 
			$phpbbPath = $wpUnited->get_setting('phpbb_path');
			if($phpbbPath) {
				$showBackupPath = str_replace($docRoot, '', $phpbbPath);
				$docRootParts = explode('/', $docRoot);
				while($showBackupPath == $phpbbPath) {
					array_pop($docRoot);
					$showBackupPath = str_replace(add_trailing_slash(implode('/', $docRoot)), '', $phpbbPath);
				}
			}
		?>
		<div id="phpbbpathgroup">
			<div id="phpbbpath" style="display: none;">&nbsp;</div>
			<p id="wpubackupgroup" style="display: none;"><strong><input id="phpbbdocroot" name="phpbbdocroot" value="<?php echo $docRoot; ?>"></input><input type="text" id="wpubackupentry" name="wpubackupentry" value="<?php echo $showBackupPath; ?>"></span></input>/config.php</strong></p>
			<small><a href="#" onclick="return wpuSwitchEntryType();" id="wpuentrytype"><?php _e('I want to type the path manually', 'wp-united'); ?></a></small>
		</div>
		<p><?php _e('Path selected: ', 'wp-united'); ?><strong id="phpbbpathshow" style="color: red;"><?php _e('Not selected', 'wp-united'); ?></strong> <a id="phpbbpathchooser" href="#" onclick="return wpuChangePath();" style="display: none;"><?php _e('Change Location &raquo;', 'wp-united'); ?></a><a id="wpucancelchange" style="display: none;" href="#" onclick="return wpuCancelChange();"><?php _e('Cancel Change', 'wp-united'); ?></a></p>
		<input id="wpupathfield" type="hidden" name="wpu-path" value="notset"></input>
	
		<p class="submit">
			<input type="submit" style="<?php echo $buttonDisplay; ?>"; class="button-primary" value="<?php  _e('Connect', 'wp-united') ?>" name="wpusetup-submit" id="wpusetup-submit" />
		</p>
	</form>
	</div>
	<!-- off-screen measure for dynamic text box -->
	<strong id="wpu-measure" style="display: block; font-size: 11px;position: absolute;left: -10000px;top: 0px;"></strong>

	
	<script type="text/javascript">
	// <![CDATA[
		var transmitMessage;
		var filetreeNonce = '<?php echo wp_create_nonce ('wp-united-filetree'); ?>';
		var transmitNonce = '<?php echo wp_create_nonce ('wp-united-transmit'); ?>';
		var disableNonce = '<?php echo wp_create_nonce ('wp-united-disable'); ?>';
		var blankPageMsg = '<?php wpu_js_translate(__('Blank page received: check your error log.', 'wp-united')); ?>';
		var phpbbPath = '<?php echo ($wpUnited->get_setting('phpbb_path')) ? $wpUnited->get_setting('phpbb_path') : ''; ?>';		
		var fileTreeLdgText = '<?php wpu_js_translate(__('Loading...', 'wp-united')); ?>';
		var connectingText = '<?php wpu_js_translate(__('Connecting...', 'wp-united')); ?>';
		var manualText = '<?php wpu_js_translate(__('I want to type the path manually', 'wp-united')); ?>';
		var autoText = '<?php wpu_js_translate(__('Show me the file chooser', 'wp-united')); ?>';


		function wpu_hardened_init_tail() {
			createFileTree();
			<?php if($wpUnited->get_setting('phpbb_path')) { ?> 
				setPath('setup');
			<?php } ?>
		}
	// ]]>
	</script>	
<?php
	add_action('admin_footer', 'wpu_hardened_script_init');
}

function wpu_panel_warnings() {
	global $wpUnited, $phpbbForum, $wpuAdminIsOrphaned;
	
	if(!is_writable($wpUnited->get_plugin_path() . 'cache/')) {
		echo '<div id="cacheerr" class="error highlight"><p>' . sprintf(__('ERROR: Your cache folder, (%s) is not writable by the web server. You must make this folder writable for WP-United to work properly!'), $wpUnited->get_plugin_path() . 'cache/') .  '</p></div>';
	}

	if( defined('WPU_CANNOT_OVERRIDE') ) {
		echo '<div id="pluggableerror" class="error highlight"><p>' . __('WARNING: Another plugin is overriding WordPress login. WP-United user integration is unavailable.', 'wp-united') . '</p></div>';
	}
	if( defined('DEBUG') || defined('DEBUG_EXTRA') ) {
		echo '<div id="debugerror" class="error highlight"><p>' . __('WARNING: phpBB Debug is set. To prevent notices from showing due to switching between phpBB and WordPress, delete or comment out the two DEBUG lines from your phpBB\'s config.php. If this is a live site, debug MUST be disabled.', 'wp-united') . '</p></div>';
	}
	
	if($wpUnited->is_enabled() && $wpUnited->get_setting('integrateLogins') && defined('COOKIE_DOMAIN') && ($phpbbForum->get_cookie_domain() != COOKIE_DOMAIN)) {
		echo '<div id="cookieerror" class="error highlight"><p>' . __('WARNING: phpBB and WordPress cookie domains do not match! For user integration to work properly, please edit the cookie domain in phpBB or set the WordPress COOKIE_DOMAIN so that both phpBB &amp; WordPress can set cookies for each other.', 'wp-united') . '</p></div>';
	}
	
}

function wpu_user_mapper() { 
	global $wpUnited, $phpbbForum; ?>
	<div class="wrap" id="wp-united-setup">
	
		<img id="panellogo" src="<?php echo wpu_get_settings_logo(); ?>" />
		<?php screen_icon('options-general'); ?>
		<h2> <?php _e('WP-United User Integration Mapping', 'wp-united'); ?> </h2>
		<p><?php _e('Integrated users have an account both in WordPress and phpBB. The user mapper tool allows you to manually control which accounts are mapped together.', 'wp-united'); ?></p>
		
		<?php if($wpUnited->get_setting('integcreatewp')) { ?>
			<p><?php _e('In addition, you need to tell WP-United how to allocate WordPress roles to users when WordPress accounts are automatically given to them.', 'wp-united'); ?></p>
			<p><?php _e('Select a tab below to get started.', 'wp-united'); ?></p>
		<?php } ?>
		<div id="wputabs">
			<?php if($wpUnited->get_setting('integcreatewp')) { ?>
		
				<ul>
					<li><a href="#wpumaptab-map"><?php _e('User Mapping', 'wp-united'); ?></a></li>
					<li><a href="#wpumaptab-perms"><?php _e('New User Permissions', 'wp-united'); ?></a></li> 
				</ul>
			<?php } 
			if($wpUnited->get_setting('integcreatewp')) { ?>
				<div id="wpumaptab-perms">
				
					<p><?php _e('Unintegrated phpBB users are automatically given accounts if they have WP-United permissions. These can be set in the phpBB Administration Control Panel, but this tool makes them easier to set and visualise.', 'wp-united'); ?></p>
					<p><?php _e('phpBB groups are linked to WordPress roles by dragging connections. Blue connections grant permissions. However, since phpBB users can belong to more than one group, you may want to apply &quot;Never&quot; connections. These red connections take priority over blue connections and mean that this mapping can NEVER occur.', 'wp-united'); ?></p>
					<p><?php _e('This gives you complete control over who does what in WordPress. For some example permissions recipes, visit wp-united.com ', 'wp-united'); ?></p>
					<p><?php _e('Remember that these mappings only affect users who don\'t have WordPress accounts yet. You should map existing accounts together using the User Mapping tool.', 'wp-united'); ?></p>
					
				
					<p><?php _e(' Connect a phpBB group on the left to an appropriate WordPress role by dragging the blue dots. Connect the red squares if you want to ensure a mapping <em>never</em> happens.When happy, click &quot;Apply&quot;', 'wp-united'); ?></p>
					<?php
						global $db;
						$phpbbForum->foreground();
						
						$groupTypes = array(__('Built-In', 'wp-united'), __('User-Defined', 'wp-united'));
						$numUserDefined = 0;
							
						// Get all the groups, and associated info
						$sqlArr = array(
							'SELECT'			=>	'COUNT(ug.user_id) AS count, g.group_id, g.group_type, g.group_name',
							
							'FROM'			=>	array(
								GROUPS_TABLE	=>	'g',
							),
							
							'LEFT_JOIN'		=>	array(
								array(
									'FROM'	=>	array(USER_GROUP_TABLE	=>	'ug'),
									'ON'			=>	'g.group_id = ug.group_id'
								)
							),
							
							'GROUP_BY'	=>	'g.group_id',
							
							'ORDER_BY'	=> 'g.group_type DESC, g.group_name ASC'
						);

						$sql = $db->sql_build_query('SELECT',$sqlArr);
						$result = $db->sql_query($sql);
		
						$groupData = array();
						while ($row = $db->sql_fetchrow($result)) {
							$groupData[$row['group_id']] = array(
								'type' 						=> 	($row['group_type'] == GROUP_SPECIAL) ? __('Built-In', 'wp-united') : __('User-Defined', 'wp-united'),
								'name'						=>	(!empty($phpbbForum->lang['G_' . $row['group_name']]))? $phpbbForum->lang['G_' . $row['group_name']] : $row['group_name'],
								'db_name'					=>	$row['group_name'],
								'total_members' 			=> 	$row['count'],
								'url'						=>	$phpbbForum->append_sid($phpbbForum->get_board_url() . 'adm/index.php?i=permissions&amp;mode=setting_group_global&amp;group_id[0]=' . $row['group_id'])
							);

							if($groupData[$row['group_id']]['type'] == __('User-Defined', 'wp-united')) {
								$numUserDefined++;
							}
						}
						
						$db->sql_freeresult($result);
					?>	
						
					<table class="widefat fixed">
						<?php foreach(array('thead', 'tfoot') as $tblHead) { ?>
							<<?php echo $tblHead; ?>>
							<tr class="thead">
								<th scope="col"><?php _e('phpBB Group', 'wp-united'); ?></th>
								<th scope="col" style="text-align: right;"><?php _e('WordPress Role', 'wp-united'); ?></th>
							</tr>
							</<?php echo $tblHead; ?>>
						<?php } ?>
						<tbody><tr><td colspan="2">
							<div id="wpuplumbcanvas" class="wpuplumbcanvas" id="wpuplumb">
								<?php
								$perms = wpu_permissions_list();
								$newUserGroups = $phpbbForum->get_newuser_group();
								$linkages = array();
								$neverLinkages = array();
								$elsL = array();
								$elsR = array();
								
								?><div class="wpuplumbleft"><?php
								
									foreach ($groupTypes as $type) { 
										if(($type == __('Built-In', 'wp-united')) || ($numUserDefined > 0)) {

											$effectivePerms = wpu_assess_perms('', false, false); //wpu_get_wp_role_for_group();
											$nevers = wpu_assess_perms('', false, true);
											
											foreach ($groupData as $group_id => $row) {
												if($row['type'] == $type) {
													$blockIdL = 'wpuperml-' . str_replace(array('+', '=', '/'), array('_pls', '_eq', '_sl'), base64_encode($row['db_name']));
													$elsL[] = $blockIdL;
													?><div class="wpuplumbgroupl ui-widget-header ui-corner-all" id="<?php echo $blockIdL; ?>">
														<p><strong><?php echo $row['name'];?></strong> <?php if(in_array($row['db_name'], $newUserGroups)) echo ' <span style="color: red;">*</span>'; ?>
														<?php echo '<br /><small><strong>' . __('No. of members: ', 'wp-united') . '</strong>' . $row['total_members']; ?><br />
														<?php echo '<strong>' . __('Group type: ', 'wp-united') . '</strong>' . $type; ?></small></p>
														<?php 
															if(isset($effectivePerms[$row['name']])) {
																foreach($effectivePerms[$row['name']] as $permItem) {
																	$linkages[$blockIdL] = 'wpupermr-' . str_replace(array('+', '=', '/'), array('_pls', '_eq', '_sl'), base64_encode($permItem));
																}
															} 
															if(isset($nevers[$row['name']])) {
																foreach($nevers[$row['name']] as $neverItem) {
																	$neverLinkages[$blockIdL] = 'wpupermr-' . str_replace(array('+', '=', '/'), array('_pls', '_eq', '_sl'), base64_encode($neverItem));
																}
															} 
														?> 
													</div> <?php
												}
											} 
										}
									} 
								?></div><?php
								$phpbbForum->background();
								?>
								<div class="wpuplumbright">
										
									<?php foreach($perms as $permSetting => $wpName) {
										$blockIdR = 'wpupermr-' . str_replace(array('+', '=', '/'), array('_pls', '_eq', '_sl'), base64_encode($permSetting));
										$elsR[] = $blockIdR;  ?>
										<div class="wpuplumbgroupr ui-widget-header ui-corner-all" id="<?php echo $blockIdR; ?>">
											<strong><?php echo 'WordPress ' . $wpName; ?></strong>
										</div>
									<?php } ?>
								</div>
								<br style="clear: both;" />
							</div>

						</td></tr></tbody>
					</table>
					<small><em><span style="color: red;">* </span><?php _e('Default new user group for new phpBB users', 'wp-united'); ?></em></small>
					<div id="wpupermactions">
						<button class="wpuprocess" onclick="return wpuApplyPerms();"><?php _e('Apply', 'wp-united'); ?></button>
						<button class="wpuclear" onclick="return wpuClearPerms();"><?php _e('Reset', 'wp-united'); ?></button>
					</div>
					

					<script type="text/javascript"> // <[CDATA[
						function initPlumbing() {  
							<?php 
							$var = 0;
							$varLookups = array();
							foreach($elsL as $el) { 
								$var++;
								$varLookups[$el] = $var;
								echo "var wpuPlumb{$var} = jsPlumb.addEndpoint(\$wpu('#{$el}'), {anchor: [1,0.25,1,0], maxConnections: 1, isSource: true},  wpuEndPoint);";
								echo "var wpunPlumb{$var} = jsPlumb.addEndpoint(\$wpu('#{$el}'), {anchor: [1,0.75,1,0], maxConnections: 1, isSource: true},  wpuNeverEndPoint);";
							}
						
							foreach($elsR as $el) { 
								$var++;							
								$varLookups[$el] = $var;
								echo "var wpuPlumb{$var} = jsPlumb.addEndpoint(\$wpu('#{$el}'), {anchor: [0,0.25,-1,0], maxConnections: 10, isTarget: true},  wpuEndPoint);";
								echo "var wpunPlumb{$var} = jsPlumb.addEndpoint(\$wpu('#{$el}'), {anchor: [0,0.75,-1,0], maxConnections: 10, isTarget: true},  wpuNeverEndPoint);";
							}

							foreach($linkages as $linkL => $linkR) {
								?>jsPlumb.connect({
									source: <?php echo "wpuPlumb{$varLookups[$linkL]}"; ?>,
									target: <?php echo "wpuPlumb{$varLookups[$linkR]}"; ?>
								});
							<?php }
							
							foreach($neverLinkages as $linkL => $linkR) {
								?>jsPlumb.connect({
									source: <?php echo "wpunPlumb{$varLookups[$linkL]}"; ?>,
									target: <?php echo "wpunPlumb{$varLookups[$linkR]}"; ?>
								});
							<?php } ?>							
						}
					

						
					// ]]>
					</script>				
					
					
				</div>
			<?php } ?>
			<div id="wpumaptab-map">
				<p><?php _e('All your WordPress or phpBB users are shown on the left below, together with their integration status. On the right, you can see their corresponding integrated user, or &ndash; if they are not integrated &ndash; some suggestions for users they could integrate to.', 'wp-united'); ?></p>
				<p><?php _e('Choose the actions you wish to take, and then click &quot;Process Actions&quot; in the pop-up panel to apply them..', 'wp-united'); ?></p>
				<div class="ui-widget-header ui-corner-all wpumaptoolbar">
					<form name="wpumapdisp" id="wpumapdisp" onsubmit="return false;">
						<fieldset>
							<label for="wpumapside"><?php _e('Show on left: ', 'wp-united'); ?></label>
							<select id="wpumapside" name="wpumapside">
								<option value="wp"><?php _e('WordPress users', 'wp-united'); ?></option>
								<option value="phpbb"><?php _e('phpBB users', 'wp-united'); ?></option>
							</select> 
							<label for="wpunumshow"><?php _e('Number to show: ', 'wp-united'); ?></label>
							<select id="wpunumshow" name="wpunumshow">
								<option value="1">1</option>
								<option value="5">5</option>
								<option value="10" selected="selected">10</option>
								<option value="20">20</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="250">250</option>
								<!--<option value="500">500</option>
								<option value="1000">1000</option>-->
							</select> 	
							<label for="wputypeshow"><?php _e('Show: ', 'wp-united'); ?></label>
							<select id="wputypeshow" name="wputypeshow">
								<option value="all"><?php _e('All', 'wp-united'); ?></option>
								<option value="int"><?php _e('All Integrated', 'wp-united'); ?></option>
								<option value="unint"><?php _e('All Unintegrated', 'wp-united'); ?></option>
								<option value="posts"><?php _e('All With Posts', 'wp-united'); ?></option>
								<option value="noposts"><?php _e('All Without Posts', 'wp-united'); ?></option>
							</select>
							<span id="wpumapsrcharea">
								<label for="wpumapsearchbox"><?php _e('or search for user: ', 'wp-united'); ?></label>
								<input type="text" id="wpumapsearchbox" name="wpumapsearchbox"></input>
							</span>
							<input type="hidden" name="wpufirstitem" id="wpufirstitem" value="0" />			
						</fieldset>
					</form>
					<div id="wpumappaginate1" class="wpumappaginate">
					</div>
				</div>

				<div id="wpumapcontainer">
					<div id="wpumapscreen">
						<div class="wpuloading">
							<p><?php _e('Loading...', 'wp-united'); ?></p>
							<img src="<?php echo $wpUnited->get_plugin_url() ?>images/settings/wpuldg.gif" />
						</div>
					</div>
					<div id="wpumappanel" class="ui-widget">
						<h3 class="ui-widget-header ui-corner-all"><?php _e('Actions to process', 'wp-united'); ?></h3>
						<ul id="wpupanelactionlist">
						</ul>
						<div id="wpupanelactions">
							<small>
								<button class="wpuprocess" onclick="return wpuProcess();"><?php _e('Process actions', 'wp-united'); ?></button>
								<button class="wpuclear" onclick="return wpuMapClearAll();"><?php _e('Clear all', 'wp-united'); ?></button>
							</small>
						</div>
					</div>
				</div>
				<div class="ui-widget-header ui-corner-all wpumaptoolbar">
					<div id="wpumappaginate2" class="wpumappaginate">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="wpuoffscreen">
	</div>
	<div id="wpu-reload" title="Message" style="display: none;">
		<p id="wpu-desc">&nbsp;</p><img id="wpuldgimg" src="<?php echo $wpUnited->get_plugin_url() ?>images/settings/wpuldg.gif" />
	</div>
	<script type="text/javascript">
	// <![CDATA[
		var mapNonce = '<?php echo wp_create_nonce ('wp-united-map'); ?>';
		var autofillNonce = '<?php echo wp_create_nonce ('wp-united-usersearch'); ?>';
		var firstMapActionNonce = '<?php echo wp_create_nonce ('wp-united-mapaction'); ?>';
		
		var imgLdg						= '<?php echo $wpUnited->get_plugin_url() ?>images/settings/wpuldg.gif';
		var currWpUser				= '<?php echo $GLOBALS['current_user']->ID; ?>';
		var currPhpbbUser			= '<?php echo $phpbbForum->get_userdata('user_id'); ?>';

		var wpText 					=	'<?php wpu_js_translate(__('WordPress', 'wp-united')); ?>';
		var phpbbText 				= '<?php wpu_js_translate(__('phpBB', 'wp-united')); ?>';
		var mapEditTitle 			= '<?php wpu_js_translate(__('Editing user. When you are finished, close this screen.', 'wp-united')); ?>';
		var mapProfileTitle 		= '<?php wpu_js_translate(__('Viewing user profile. When you are finished, close this screen.', 'wp-united')); ?>';
		var actionBreak 			=	'<?php wpu_js_translate(__('Break integration', 'wp-united')); ?>';
		var actionBreakDets 		=	'<?php wpu_js_translate(__('between %1$s and %2$s', 'wp-united')); ?>';
		var actionSync 			=	'<?php wpu_js_translate(__('Synchronize profiles', 'wp-united')); ?>';
		var actionSyncDets 		=	'<?php wpu_js_translate(__('between %1$s and %2$s', 'wp-united')); ?>';
		var actionDelBoth 			=	'<?php wpu_js_translate(__('Delete ', 'wp-united')); ?>';
		var actionDelBothDets 	=	'<?php wpu_js_translate(__('%1$s from %2$s and %3$s from %4$s', 'wp-united')); ?>';
		var actionDel 				=	'<?php wpu_js_translate(__('Delete ', 'wp-united')); ?>';
		var actionDelDets 			=	'<?php wpu_js_translate(__('%1$s from %2$s', 'wp-united')); ?>';
		var actionCreate			=	'<?php wpu_js_translate(__('Create ', 'wp-united')); ?>';
		var actionCreateDets 	=	'<?php wpu_js_translate(__('integrated counterpart for %1$s in %2$s', 'wp-united')); ?>';
		var actionIntegrate		=	'<?php wpu_js_translate(__('Integrate ', 'wp-united')); ?>';
		var actionIntegrateDets =	'<?php wpu_js_translate(__('%1$s user %2$s to %3$s user %4$s', 'wp-united')); ?>';
		
		var wpuProcessingText = 	'<?php wpu_js_translate(__('Processing permission mappings...', 'wp-united')); ?>';
		var wpuWaitText = 			'<?php wpu_js_translate(__('Please wait...', 'wp-united')); ?>';
		var wpuConnectingText = 	'<?php wpu_js_translate(__('Connecting...', 'wp-united')); ?>';
		var wpuClearingText = 		'<?php wpu_js_translate(__('Clearing changes', 'wp-united')); ?>';
		var wpuLoading	 = 			'<?php wpu_js_translate(__('Loading...', 'wp-united')); ?>';
		var wpuReloading	 = 			'<?php wpu_js_translate(__('Reloading settings from phpBB...', 'wp-united')); ?>';
		
		var acpPopupTitle = '<?php wpu_js_translate(__('phpBB Administration Panel. After saving your settings, close this window to return to WP-United.', 'wp-united')); ?>';
		
		
		function wpu_hardened_init_tail() {
			<?php if($wpUnited->get_setting('integcreatewp')) { 
				// re-call jsPlumb init, in case it failed on document.ready
			?>
				jsPlumb.init();
				wpuSetupPermsMapper();
			<?php } ?>
			setupUserMapperPage();
		}

	// ]]>
	</script>
		
<?php
	add_action('admin_footer', 'wpu_hardened_script_init');
}



function wpu_process_perms() {
	global $phpbbForum;
	
	$conns = stripslashes(base64_decode(str_replace(array('%2B', '%3D', '%2F'), array('+', '=', '/'), (string)$_POST['wpusetperms'])));
	$conns = explode(',', $conns);
	$nevers = stripslashes(base64_decode(str_replace(array('%2B', '%3D', '%2F'), array('+', '=', '/'), (string)$_POST['wpusetnevers'])));
	$nevers = explode(',', $nevers);	
	$permsList = array_keys(wpu_permissions_list());
	
	$phpbbForum->clear_group_permissions();

	foreach($conns as $conn) {
		list($phpbbGroup, $wpuPermName) = explode('=', $conn);
		$wpuPerm = base64_decode(str_replace(array('_pls', '_eq', '_sl'), array('+', '=', '/'), $wpuPermName));
		if(in_array($wpuPerm, $permsList)) {

			wpu_set_phpbb_group_permissions(
				base64_decode(str_replace(array('_pls', '_eq', '_sl'), array('+', '=', '/'), $phpbbGroup)), 
				$wpuPerm
			);
		}
	}
	foreach($nevers as $never) {
		list($phpbbGroup, $wpuPermName) = explode('=', $never);
		$wpuPerm = base64_decode(str_replace(array('_pls', '_eq', '_sl'), array('+', '=', '/'), $wpuPermName));
		if(in_array($wpuPerm, $permsList)) {
			wpu_set_phpbb_group_permissions(
				base64_decode(str_replace(array('_pls', '_eq', '_sl'), array('+', '=', '/'), $phpbbGroup)), 
				$wpuPerm, 
				ACL_NEVER
			);
		}
	}

	die('OK');
}	


function wpu_map_show_data() {
	global $wpUnited, $wpdb, $phpbbForum, $db, $user;
	
	$type = (isset($_POST['wpumapside']) && $_POST['wpumapside'] == 'phpbb' ) ? 'phpbb' : 'wp';
	$first = (isset($_POST['wpufirstitem'])) ? (int) $_POST['wpufirstitem'] : 0;
	$num = (isset($_POST['wpunumshow'])) ? (int) $_POST['wpunumshow'] : 50;
	
	$showOnlyInt = ((isset($_POST['wputypeshow'])) && ($_POST['wputypeshow'] == 'int')) ? 1 : 0;
	$showOnlyUnInt = ((isset($_POST['wputypeshow'])) && ($_POST['wputypeshow'] == 'unint')) ? 1 : 0;
	$showOnlyPosts = ((isset($_POST['wputypeshow'])) && ($_POST['wputypeshow'] == 'posts')) ? 1 : 0;
	$showOnlyNoPosts = ((isset($_POST['wputypeshow'])) && ($_POST['wputypeshow'] == 'noposts')) ? 1 : 0;
	
	$showLike = (isset($_POST['wpumapsearchbox'])) ? str_replace(array('"', '&'), array('|QUOT|', '|AMP|'), stripslashes(strip_tags((string) $_POST['wpumapsearchbox']))) : '';
	
	require($wpUnited->get_plugin_path() . 'user-mapper.php');
	require($wpUnited->get_plugin_path() . 'mapped-users.php');
	
	$userMapper = new WPU_User_Mapper("leftSide={$type}&numToShow={$num}&numStart={$first}&showOnlyInt={$showOnlyInt}
		&showOnlyUnInt={$showOnlyUnInt}&showOnlyPosts={$showOnlyPosts}&showOnlyNoPosts={$showOnlyNoPosts}&showLike={$showLike}");

	$alt = '';
	
	wpu_ajax_header();
	
	echo '<wpumapper>';
	
	$fStateChanged = $phpbbForum->foreground();
	$pagination = generate_pagination('#', $userMapper->num_users(), $num, $first, true);
	$pagination = str_replace('<a ', '<a onclick="return wpuMapPaginate(this);"', $pagination);
	$phpbbForum->background($fStateChanged);
	
	$total = $userMapper->num_users();
	$to = (($first + $num) > $total) ? $total : ($first + $num);
	$package = ($type == 'phpbb') ? __('phpBB', 'wp-united') : __('WordPress', 'wp-united');
	$packageUsers = ($total > 1) ? sprintf(__('%s users', 'wp-united'), $package) :  sprintf(__('%s user', 'wp-united'), $package);

	echo '<pagination><![CDATA[<p><em class="wpumapcount">' . sprintf(__('Showing %1$d to %2$d of %3$d %4$s.', 'wp-united'), ($first + 1), $to, $total, $packageUsers) . ' </em>' . $pagination . '</p>]]></pagination>';
	
	echo '<mapcontent><![CDATA[';
	ob_start();
	$haveUnintegratedUsers = false;
	$haveIntegratedUsers = false;
	
	if($total == 0) {
		echo '<em id="wpumaptable">' . __('There are no users to show that match your criteria', 'wp-united') . '</em>';
	} else {
		?><table id="wpumaptable"><?php
		foreach($userMapper->users as $userID => $user) { 
			?>
			<tr class="wpumaprow<?php echo $alt; ?>"  id="wpuuser<?php echo $userID ?>">
				<td> 
					<?php echo $user; ?>
				</td><td>
				<?php if(!$user->is_integrated()) { 
					$haveUnintegratedUsers = true; ?>
				
					<div class="wpuintegnot ui-widget-header ui-corner-all">
						<p><?php echo __('Status: ', 'wp-united') . __('Not Integrated', 'wp-united'); ?></p>
						<p class="wpubuttonset">
							<?php echo $user->create_action(); ?>
							<?php echo $user->del_action(); ?>
						</p>
					</div>
					</td><td>
					<div class="wpumapsugg">
					<p class="wpuintto"><?php _e('Integrate to a suggested match', 'wp-united'); ?>:</p>
						<div class="wpudetails">
							<?php echo $user->get_suggested_matches(); ?>
						</div>
						<p class="wpuintto"><?php _e('Or, type a name', 'wp-united'); ?>:</p>
						<div class="wpuavatartyped" id="wpuavatartyped<?php echo $userID; ?>"></div><input class="wpuusrtyped" id="wpumapsearch-<?php echo $userID; ?>" /> <small class="wpubuttonset"><a href="#" class="wpumapactionlnktyped" onclick="return false;" id="wpumapfrom-<?php echo $userID; ?>"><?php _e('Integrate', 'wp-united'); ?></a></small>
					</div>
				<?php } else { 
					$haveIntegratedUsers = true;?>
					<div class="wpuintegok ui-widget-header ui-corner-all">
						<p><?php echo __('Status: ', 'wp-united') . __('Integrated', 'wp-united'); ?></p>
						<p class="wpubuttonset">
							<?php echo $user->sync_profiles_action(); ?>
							<?php echo $user->break_action(); ?>
							<?php echo $user->delboth_action(); ?>
						</p>
					</div>
				</td><td>
					<?php echo $user->get_partner(); ?>
				<?php } ?>
				</td>
			</tr>
			<?php 
			$alt = ($alt == '') ? ' wpualt' : '';
		}
		echo '</table>';
	}
	
	$content = base64_encode(ob_get_contents());
	ob_end_clean();
	
	echo $content . ']]></mapcontent><bulk><![CDATA[';
	if($total>0) {
		echo '<div id="wpubulk"><select id="wpuquicksel" name="wpuquicksel">
			<option value="0">---- ' . __('Bulk actions', 'wp-united') . ' ----</option>';
		if($haveUnintegratedUsers) {
			echo '<option value="del">' . __('Delete all unintegrated', 'wp-united') . '</option>';
		}
		if($haveIntegratedUsers) {
			echo '<option value="break">' . __('Break all integrated', 'wp-united') . '</option>';
			echo '<option value="sync">' . __('Sync all integrated profiles', 'wp-united') . '</option>';
		}
		if($haveUnintegratedUsers) {
			echo  '<option value="create">' . __('Create users for all unintegrated', 'wp-united') . '</option>';
		}				
		echo '</select><button id="wpuquickselbtn" onclick="return wpuMapBulkActions();">' . __('Add', 'wp-united') . '</button></div>';
	}
	echo ']]></bulk></wpumapper>';
	
}




/**
 * Perform an action requested by the user mapper
 */
function wpu_process_mapaction() {
	global $phpbbForum, $db, $wpdb, $phpbb_root_path, $phpEx;
	
	wpu_ajax_header();
	echo '<wpumapaction>';
	
	$action = (isset($_POST['type'])) ? (string)$_POST['type'] : '';
	$userID = (isset($_POST['userid'])) ? (int)$_POST['userid'] : 0;
	$intUserID = (isset($_POST['intuserid'])) ? (int)$_POST['intuserid'] : 0;
	$package = (isset($_POST['package'])) ? (string)$_POST['package'] : '';
	
	if(
		empty($action) || 
		empty($userID) || 
		empty($package) || 
		(($action == 'delboth') && empty($intUserID)) ||
		(($action == 'break') && empty($intUserID)) ||
		(($action == 'sync') && empty($intUserID))
	) {
		wpu_map_action_error('Cannot perform action, required details are missing');
	}
	
	require_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
	
	
	switch($action) {
		
		case 'del':
			if($package == 'wp') {
				// First break if the user is integrated
				wpu_map_break($userID);
				wp_delete_user($userID, '0');
			} else {
				$fStateChanged = $phpbbForum->foreground();
				user_delete('retain', $userID);
				$phpbbForum->background($fStateChanged);
			}
			echo '<status>OK</status>';
		break;

		case 'delboth':
			$wUserID = ($package == 'wp') ? $userID : $intUserID;
			$pUserID = ($package == 'wp') ? $intUserID : $userID;

			wp_delete_user($wUserID, '0');
			$fStateChanged = $phpbbForum->foreground();
			user_delete('retain', $pUserID);
			$phpbbForum->background($fStateChanged);
			echo '<status>OK</status>';
		break;		
		
		case 'integrate':
			
			$wUserID = ($package == 'wp') ? $userID : $intUserID;
			$pUserID = ($package == 'wp') ? $intUserID : $userID;
		
			if ( (!empty($wUserID)) && (!empty($pUserID))  ) {

				wpu_update_int_id($pUserID, $wUserID);
				// Sync profiles
				$wpuNewDetails = $phpbbForum->get_userdata('', $pUserID);
				$phpbbForum->background($fStateChanged);
				$wpUsrData = get_userdata($wUserID);
				// Don't modify passwords
				wpu_sync_profiles($wpUsrData, $wpuNewDetails, 'sync', true);
				echo '<status>OK</status>';
			}
		break;
		
		case 'break':
			$id = ($package == 'wp') ? $userID : $intUserID;
			wpu_map_break($id);
			echo '<status>OK</status>';
		break;
		
		case 'sync':
			$wpUserID = ($package == 'wp') ? $userID : $intUserID;
			$pUserID = ($package == 'wp') ? $intUserID : $userID;
			$wpUsrData = get_userdata($wpUserID);
			$pUsrData = $phpbbForum->get_userdata('', $pUserID);
			wpu_sync_profiles($wpUsrData, $pUsrData, 'sync', true);
			echo '<status>OK</status>';
		break;
		
		case 'createin':
		
			// create user in phpBB
			if($package == 'phpbb') {
				$phpbbID = wpu_create_phpbb_user($userID);
					
				if($phpbbID == 0) {
					die('<status>FAIL</status><details>' . __('Could not add user to phpBB', 'wp-united') . '</details></wpumapaction>');
				} else if($phpbbID == -1) {
					die('<status>FAIL</status><details>' . __('A suitable username could not be found in phpBB', 'wp-united') . '</details></wpumapaction>');
				}
				wpu_sync_profiles(get_userdata($userID), $phpbbForum->get_userdata('', $phpbbID), 'wp-update');
				
			} else {

				// create user in WordPress
				$wpuNewDetails = $phpbbForum->get_userdata('', $userID);
				
				require_once( ABSPATH . WPINC . '/registration.php');
				
				if( !$userLevel = wpu_get_user_level($userID) ) {
					die('<status>FAIL</status><details>' . __('Cannot create integrated user, as they would have no integration permissions.', 'wp-united') . '</details></wpumapaction>');
				}
				
				
				$newUserID = wpu_create_wp_user($wpuNewDetails['username'], $wpuNewDetails['user_password'], $wpuNewDetails);
						
				if($newUserID) { 
					if($wpUser = get_userdata($newUserID)) { 
						wpu_update_int_id($userID, $wpUser->ID);
						
						wpu_sync_profiles($wpUser, $wpuNewDetails, 'phpbb-update');

						wpu_set_role($wpUser->ID, $userLevel);
						
					}
				} else {
					die('<status>FAIL</status><details>' . __('Could not add user to WordPress', 'wp-united') . '</details></wpumapaction>');
				}
			}
			
		echo '<status>OK</status>';

		break;
		
	}
	echo '<nonce>' . wp_create_nonce('wp-united-mapaction') . '</nonce>';
	echo '</wpumapaction>';
	die();	
	
}


function wpu_map_break($intID) {
	global $phpbbForum, $db;
	$fStateChanged = $phpbbForum->foreground();
	$sql = 'UPDATE ' . USERS_TABLE . ' 
				 SET user_wpuint_id = NULL 
				WHERE user_wpuint_id = ' . (int)$intID;

	if (!$pDel = $db->sql_query($sql)) {
		wpu_map_action_error('Error when breaking integration');
	}
	$phpbbForum->background($fStateChanged);	
	
	wpu_map_killusermeta($intID);
	
}

function wpu_map_killusermeta($intID) {
	//update usermeta on WP side
	if(function_exists('delete_user_meta')) {
		@delete_user_meta($intID, 'phpbb_userid');
		@delete_user_meta($intID, 'phpbb_userLogin');
	} else {
		@delete_usermeta( $intID, 'phpbb_userid');
		@delete_usermeta( $intID, 'phpbb_userLogin');
	}	
}

function wpu_map_action_error($errDesc) {
		echo '<status>ERROR</status>';
		echo '<details>' . $errDesc . '</details>';
		echo '</wpumapaction>';
		die();
	
}

	
/**
 * The main WP-United settings panel
 */	
function wpu_settings_page() {	
	
	global $phpbbForum, $wpUnited; 

	$needPreview = false;
	?>
	
	<div class="wrap" id="wp-united-setup">
		<img id="panellogo" src="<?php echo wpu_get_settings_logo(); ?>" />
		<?php screen_icon('options-general'); ?>
		<h2> <?php _e('WP-United Settings', 'wp-united'); ?> </h2>
	
			<div id="wputransmit"><p><strong><?php _e('Sending settings to phpBB...', 'wp-united'); ?></strong><br /><?php _e('Please wait...', 'wp-united'); ?></p><img src="<?php echo $wpUnited->get_plugin_url() ?>images/settings/wpuldg.gif" /></div>
			
			<?php
				if(isset($_GET['msg'])) {
					if($_GET['msg'] == 'success') {
						$needPreview = true;
			?>
			<div id="wpustatus" class="updated"><p><?php _e('Settings applied successfully.', 'wp-united'); ?></p></div>
			<?php
				} elseif($_GET['msg'] == 'fail') {
			?>
					<div id="wpustatus" class="error">
						<p><?php _e('An error occurred. The error details are below. Please check your settings or try disabling plugins.', 'wp-united'); ?></p>
						<div style="margin-bottom: 8px;" id="wpuerrordets">
							<?php 
								echo html_entity_decode(base64_decode(stripslashes_deep((string)$_POST['msgerr'])));
							?>
						</div>
					</div>
			<?php
				}
			}
			
			wpu_panel_warnings();
			
			if($needPreview) {
				wpu_reload_preview();
			} 
			
			
			?>
			<p><?php _e('WP-United is modular; You can enable or disable any of the four major features below: User Integration, Theme Integration, Behaviour Integration and User Blogs.', 'wp-united') ?></p>
			<p><?php _e('Visit each of the tabs to select the settings, then hit Submit when done.', 'wp-united') ?></p>
					
			<form name="wpu-settings" id="wpusettings" method="post" onsubmit="return wpu_transmit('wp-united-settings', this.id);">
				
				<div id="wputabs">
					<ul>
						<li><a href="#wputab-basic"><?php _e('Basic Settings', 'wp-united'); ?></a></li>
						<?php if(!defined('WPU_CANNOT_OVERRIDE')) { ?>
							<li><a href="#wputab-user"><?php _e('User Integration', 'wp-united'); ?></a></li>
						<?php } ?>
						<li><a href="#wputab-theme"><?php _e('Theme Integration', 'wp-united'); ?></a></li>
						<li><a href="#wputab-behav"><?php _e('Behaviour Integration', 'wp-united'); ?></a></li>
					<!--	<li><a href="#wputab-blogs">User Blogs</a></li>-->
					</ul>

					<div id="wputab-basic">
						<h3><?php _e('Path to phpBB3', 'wp-united'); ?></h3>
						<p><?php _e('WP-United needs to know where phpBB is installed on your server. You can change the location on the &quot;Setup / Status&quot; page.', 'wp-united'); ?></p>
					
						<p><?php _e('Path selected: ', 'wp-united'); ?><strong id="phpbbpathshow" style="color: red;"><?php _e('Not selected', 'wp-united'); ?></strong> <a href="admin.php?page=wp-united-setup" id="phpbbpathchange"><?php _e('Change Location &raquo;', 'wp-united'); ?></a></p>
						<input id="wpupathfield" type="hidden" name="wpu-path" value="notset"></input>
						<h3><?php _e('Forum Page', 'wp-united'); ?></h3>
						<p><?php _e("Create a WordPress forum page? If you enable this option, WP-United will create a blank page in your WordPress installation, so that 'Forum' links appear in your blog. These links will automatically direct to your forum.", 'wp-united'); ?></p>
						<input type="checkbox" id="wpuforumpage" name="wpuforumpage" <?php if($wpUnited->get_setting('useForumPage')) { ?>checked="checked"<?php } ?> /><label for="wpuforumpage"><?php _e('Enable Forum Page', 'wp-united'); ?></label>		
					</div>
					<?php if(!defined('WPU_CANNOT_OVERRIDE')) { ?>
						<div id="wputab-user">
							
							<h3><?php _e('Integrate logins?', 'wp-united'); ?></h3>
							<p><?php _e('This will enable some or all of your users to have a seamless session across both phpBB and WordPress. If they are logged in to one, they will be logged in to the other. Accounts will be created in the respective part of the site as needed. Note that you will need to set permissions in the User Mapper section that will appear once this option is enabled. Otherwise, by default, only the phpBB founder user is integrated.', 'wp-united'); ?></p>
							

							<input type="checkbox" id="wpuloginint" name="wpuloginint" <?php if($wpUnited->get_setting('integrateLogin')) { ?>checked="checked"<?php } ?> /><label for="wpuloginint"><?php _e('Enable Login Integration?', 'wp-united'); ?></label>		
							
							<div id="wpusettingsxpost" class="subsettings">
								
								<h4><?php _e('Auto-create WordPress accounts when needed?', 'wp-united'); ?></h4>
								<p><?php _e('Create WordPress accounts for unintegrated phpBB users with appropriate permissions when they visit or register?', 'wp-united'); ?></p>
								<input type="checkbox" id="wpucreatewacct" name="wpucreatewacct" <?php if($wpUnited->get_setting('integcreatewp')) { echo ' checked="checked" '; } ?>/><label for="wpucreatewacct"><?php _e('Auto-create WordPress accounts?', 'wp-united'); ?></label>	
								
								<h4><?php _e('Auto-create phpBB accounts when needed?', 'wp-united'); ?></h4>
								<p><?php _e('Create phpBB accounts for unintegrated WordPress users when they visit or register?', 'wp-united'); ?></p>
								<input type="checkbox" id="wpucreatepacct" name="wpucreatepacct" <?php if($wpUnited->get_setting('integcreatephpbb')) { echo ' checked="checked" '; } ?>/><label for="wpucreatepacct"><?php _e('Auto-create phpBB accounts?', 'wp-united'); ?></label>	
								
								<h4><?php _e('Sync avatars?', 'wp-united'); ?></h4>
								<p><?php _e('Avatars will be synced between phpBB &amp; WordPress. If a user has an avatar in phpBB, it will show in WordPress. If they have a Gravatar, it will show in phpBB.', 'wp-united'); ?></p>
								<p><?php _e('Enabling this option requires that the &quot;Allow avatars&quot; and &quot;Remote avatar linking&quot; options is enabled in phpBB, so WP-United will automatically enable those options for you if they are disabled.', 'wp-united'); ?></p>
								<input type="checkbox" id="wpuavatar" name="wpuavatar" <?php if($wpUnited->get_setting('avatarsync')) { echo ' checked="checked" '; } ?>/><label for="wpusmilies"><?php _e('Sync avatars?', 'wp-united'); ?></label>	
						
								
								
								<h4><?php _e('Enable cross-posting?', 'wp-united'); ?></h4>
								<p><?php _e('If you enable this option, users will be able to elect to have their blog entry copied to a forum when writing a blog post. To set which forums the user can cross-post to, visit the phpBB forum permissions panel, and enable the &quot;can cross-post&quot; permission for the users/groups/forums combinations you need.', 'wp-united'); ?></p>
								<input type="checkbox" id="wpuxpost" name="wpuxpost" <?php if($wpUnited->get_setting('xposting')) { ?>checked="checked"<?php } ?> /><label for="wpuxpost"><?php _e('Enable Cross-Posting?', 'wp-united'); ?></label>		
								
								
								<div id="wpusettingsxpostxtra" class="subsettings">
									<h4><?php _e('Type of cross-posting?', 'wp-united'); ?></h4>
									<p><?php _e('Choose how the post should appear in phpBB. WP-United can post an excerpt, the full post, or give you an option to select when posting each post.', 'wp-united'); ?></p>
									<input type="radio" name="rad_xpost_type" value="excerpt" id="wpuxpexc"  <?php if($wpUnited->get_setting('xposttype') == 'excerpt') { ?>checked="checked"<?php } ?>  /><label for="wpuxpexc"><?php _e('Excerpt', 'wp-united'); ?></label>
									<input type="radio" name="rad_xpost_type" value="fullpost" id="wpuxpfp" <?php if($wpUnited->get_setting('xposttype') == 'fullpost') { ?>checked="checked"<?php } ?>  /><label for="wpuxpfp"><?php _e('Full Post', 'wp-united'); ?></label>
									<input type="radio" name="rad_xpost_type" value="askme" id="wpuxpask" <?php if($wpUnited->get_setting('xposttype') == 'askme') { ?>checked="checked"<?php } ?>  /><label for="wpuxpask"><?php _e('Ask Me', 'wp-united'); ?></label>
									
									<h4><?php _e('phpBB manages comments on crossed posts?', 'wp-united'); ?></h4>
									<p><?php _e('Choose this option to have WordPress comments replaced by forum replies for cross-posted blog posts. In addition, comments posted by integrated users via the WordPress comment form will be cross-posted as replies to the forum topic.', 'wp-united'); ?><br /><br />
									<?php _e('Note that for users to be able to comment from WordPress, you need to assign them the &quot;Can reply to blog posts&quot; permission in phpBB.', 'wp-united'); ?></p>
									<input type="checkbox" name="wpuxpostcomments" id="wpuxpostcomments" <?php if($wpUnited->get_setting('xpostautolink')) { ?>checked="checked"<?php } ?> /><label for="wpuxpostcomments"><?php _e('phpBB manages comments', 'wp-united'); ?></label>		
									
									<div id="wpusettingsxpostcomments" class="subsettings">

										<h4><?php _e('Use WordPress spam filters for guest comments?', 'wp-united'); ?></h4>
										<p><?php _e('To prevent forum spam from comments posted by guests, turn this option on. Comments will then be passed through WordPress spam filters (e.g. Akismet) before being sent to the forum. This only applies to posts made by guests.', 'wp-united'); ?></p>
																				
										<input type="radio" name="rad_xpostcomappr" value="all" id="xpostcomapprall"  <?php if($wpUnited->get_setting('xpostspam') === 'all') { ?>checked="checked" <?php } ?> /><label for="xpostcomapprall"><?php _e('Yes, and override phpBB post approval requirements if the comment passes WordPress checks', 'wp-united'); ?></label><br />
										<input type="radio" name="rad_xpostcomappr" value="yes" id="xpostcomappryes" <?php if($wpUnited->get_setting('xpostspam') === 1) { ?>checked="checked" <?php } ?>  /><label for="xpostcomappryes"><?php _e('Yes, but still honour phpBB post approval requirements even if the comment passes', 'wp-united'); ?></label><br />
										<input type="radio" name="rad_xpostcomappr" value="no" id="xpostcomapprno" <?php if($wpUnited->get_setting('xpostspam') === 0) { ?>checked="checked" <?php } ?>  /><label for="xpostcomapprno"><?php _e('No, I will rely on phpBB settings.', 'wp-united'); ?></label>
									</div>
									
									
									<h4><?php _e('Force all blog posts to be cross-posted?', 'wp-united'); ?></h4>
									<p><?php _e('Setting this option will force all blog posts to be cross-posted to a specific forum. You can select the forum here. Note that users must have the &quot;can cross-post&quot; WP-United permission under phpBB Forum Permissions, or the cross-posting will not take place.', 'wp-united'); ?></p>
									<select id="wpuxpostforce" name="wpuxpostforce">
										<option value="-1" <?php if($wpUnited->get_setting('xpostforce') == -1) { echo ' selected="selected" '; } ?>>-- <?php _e('Disabled', 'wp-united'); ?> --</option>
										
										<?php
										if(defined('IN_PHPBB')) { 
											global $phpbbForum, $db;
											$fStateChanged = $phpbbForum->foreground();
											$sql = 'SELECT forum_id, forum_name FROM ' . FORUMS_TABLE . ' WHERE ' .
												'forum_type = ' . FORUM_POST;
											if ($result = $db->sql_query($sql)) {
												while ( $row = $db->sql_fetchrow($result) ) {
													echo '<option value="' . $row['forum_id'] . '"';
													if($wpUnited->get_setting('xpostforce') == (int)$row['forum_id']) {
														 echo ' selected="selected" ';
													}
													echo '>' . $row['forum_name'] . '</option>';
												}
											}
											$phpbbForum->restore_state($fStateChanged);
										}
									?>								
										
									</select>
									
								<h4><?php _e('Cross-post prefix', 'wp-united'); ?></h4>
									<p><?php _e('This will be prepended to the post title when cross-posted. Leave it blank to disable.', 'wp-united'); ?></p>
									<?php
										// The default value hasn't had translation applied as we can't do that on the phpBB side. So we translate it now.
										$xPostPrefix = $wpUnited->get_setting('xpostprefix');
										if($xPostPrefix == '[BLOG] ') {
											$xPostPrefix = __('[BLOG] ', 'wp-united');
										}
									?>
									<label for="wpuxpostprefix"><?php _e('Cross-post prefix: ', 'wp-united'); ?></label><input type="text" name="wpuxpostprefix" id="wpuxpostprefix" value="<?php echo htmlentities($xPostPrefix); ?>"></input>		
	
								</div>				
							</div>
						</div>	
					<?php } ?>	
					
					<div id="wputab-theme">
						<h3><?php _e('Integrate themes?', 'wp-united'); ?></h3>
						<p><?php _e('WP-United can integrate your phpBB &amp; WordPress templates.', 'wp-united'); ?></p>
						<input type="checkbox" id="wputplint" name="wputplint" <?php if($wpUnited->get_setting('showHdrFtr') != 'NONE') { ?>checked="checked" <?php } ?> /><label for="wputplint"><?php _e('Enable Theme Integration', 'wp-united'); ?></label>
						<div id="wpusettingstpl" class="subsettings">
							<h4><?php _e('Integration Mode', 'wp-united'); ?></h4>
							<p><?php _e('Do you want WordPress to appear inside your phpBB template, or phpBB to appear inside your WordPress template?', 'wp-united'); ?></p>
							
							<input type="radio" name="rad_tpl" value="rev" id="wputplrev"  <?php if($wpUnited->get_setting('showHdrFtr') != 'FWD') { ?>checked="checked" <?php } ?> /><label for="wputplrev"><?php _e('phpBB inside WordPress', 'wp-united'); ?></label>
							<input type="radio" name="rad_tpl" value="fwd" id="wputplfwd" <?php if($wpUnited->get_setting('showHdrFtr') == 'FWD') { ?>checked="checked" <?php } ?>  /><label for="wputplfwd"><?php _e('WordPress inside phpBB', 'wp-united'); ?></label>
							
						
							<h4><?php _e('Automatic CSS Integration', 'wp-united'); ?></h4>
							
							<p><?php _e('WP-United can automatically fix CSS conflicts between your phpBB and WordPress templates. Set the slider to "maximum compatibility" to fix most problems. If you prefer to fix CSS conflicts by hand, or if the automatic changes cause problems, try reducing the level.', 'wp-united'); ?></p>
							
							<div style="padding: 0 100px;">
								<p style="height: 11px;"><span style="float: left;"><?php _e('Off', 'wp-united'); ?></span><span style="float: right;"><?php _e('Maximum Compatibility (Recommended)', 'wp-united'); ?></span></p>
								<div id="wpucssmlvl"></div>
								<div id="cssmdesc"><p><strong><?php _e('Current Level: ', 'wp-united'); ?><span id="cssmlvltitle">xxx</span></strong><br /></p><p id="cssmlvldesc">xxx</p></div>
							</div>
							<input type="hidden" id="wpucssmlvlfield" name="wpucssmlevel" value="notset"></input>
							<p><a id="wputpladvancedstgs" href="#" onclick="return tplAdv();"><span id="wutpladvshow"><?php _e('Show Advanced Settings &raquo;', 'wp-united'); ?></span><span id="wutpladvhide" style="display: none;"><?php _e('&laquo; Hide Advanced Settings', 'wp-united'); ?></span></a></p>
							
							<div id="wpusettingstpladv" class="subsettings">
								<h4><?php _e('Advanced Settings', 'wp-united'); ?></h4>
								<div id="wputemplate-p-in-w-opts">
							
							
									<p><strong><?php _e('Use full page?', 'wp-united'); ?></strong>
										<a class="wpuwhatis" href="#" title="<?php _e("Do you want phpBB to simply appear inside your WordPress header and footer, or do you want it to show up in a fully featured WordPress page? Simple header and footer will work best for most WordPress themes – it is faster and less resource-intensive, but cannot display dynamic content on the forum page. However, if you want the WordPress sidebar to show up, or use other WordPress features on the integrated page, you could try 'full page'. This option could be a little slower.", 'wp-united'); ?>"><?php _e('What is this?', 'wp-united'); ?></a>
									</p>
									<select id="wpuhdrftrspl" name="wpuhdrftrspl">
										
										<option value="0"<?php if($wpUnited->get_setting('wpSimpleHdr') == 1) { echo ' selected="selected" '; } ?>>-- <?php _e('Statically Cached Simple Header &amp; Footer', 'wp-united'); ?> --</option>
										<?php
											$files = get_page_templates();
											$fileNames = array_values($files);
											if(!in_array('page.php', $fileNames) && locate_template(array('page.php'))) {
												$files[wpu_fix_translation(__('Fall back to page.php', 'wp-united'))] = 'page.php';
											}
											if(!in_array('index.php', $fileNames) && locate_template(array('index.php'))) {
												$files[wpu_fix_translation(__('Fall back to index.php', 'wp-united'))] = 'index.php';
											}											
											if(sizeof($files)) {
												foreach($files as $fileDesc => $file) {
													if(strpos(strtolower($file), '.php') == (strlen($file) - 4)) {
														echo '<option value="' . $file . '"';
														if( ($wpUnited->get_setting('wpPageName') == $file) && ($wpUnited->get_setting('wpSimpleHdr') == 0) ) {
															echo ' selected="selected" ';
														}
														echo '>' .  __('Full Page: ', 'wp-united') . $fileDesc . '</option>';
													}
												}
											}
										?>
									</select>
									
									<p><strong><?php _e('Padding around phpBB', 'wp-united'); ?></strong>
									<?php $padding = explode('-', $wpUnited->get_setting('phpbbPadding')); ?>
									
										<a class="wpuwhatis" href="#" title="<?php _e("phpBB is inserted on the WordPress page inside a DIV. Here you can set the padding of that DIV. This is useful because otherwise the phpBB content may not line up properly on the page. The defaults here are good for most WordPress templates. If you would prefer set this yourself, just leave these boxes blank (not '0'), and style it in your stylesheet instead.", 'wp-united'); ?>"><?php _e('What is this?', 'wp-united'); ?></a>
									</p>
										<table>
											<tr>
												<td>
													<label for="wpupadtop"><?php _e('Top:', 'wp-united'); ?></label><br />
												</td>
												<td>
													<input type="text" onkeypress="check_padding(event)" maxlength="3" style="width: 30px;" id="wpupadtop" name="wpupadtop" value="<?php echo $padding[0]; ?>" />px<br />
												</td>
											</tr>
											<tr>
												<td>
													<label for="wpupadright"><?php _e('Right:', 'wp-united'); ?></label><br />
												</td>
												<td>
													<input type="text" onkeypress="check_padding(event)" maxlength="3" style="width: 30px;" id="wpupadright" name="wpupadright" value="<?php echo $padding[1]; ?>" />px<br />
												</td>
											</tr>
											<tr>
												<td>
													<label for="wpupadbtm"><?php _e('Bottom:', 'wp-united'); ?></label><br />
												</td>
												<td>
													<input type="text" onkeypress="check_padding(event)" maxlength="3" style="width: 30px;" id="wpupadbtm" name="wpupadbtm" value="<?php echo $padding[2]; ?>" />px<br />
												</td>
											</tr>
											<tr>
												<td>
													<label for="wpupadleft"><?php _e('Left:', 'wp-united'); ?></label><br />
												</td>
												<td>
													<input type="text" onkeypress="check_padding(event)" maxlength="3" style="width: 30px;" id="wpupadleft" name="wpupadleft" value="<?php echo $padding[3]; ?>" />px<br />
												</td>
											</tr>
											</table>
										<p><a href="#" onclick="return default_padding();"><?php _e('Reset to defaults', 'wp-united'); ?></a></p>
									</div>
									<div id="wputemplate-w-in-p-opts">
										<p>
											<input type="checkbox" id="wpudtd" name="wpudtd" <?php if($wpUnited->get_setting('dtdSwitch')) { echo ' checked="checked" '; } ?>/> <label for="wpudtd"><Strong><?php _e("Use WordPress' Document Type Declaration?", 'wp-united'); ?></Strong></label>
											<a class="wpuwhatis" href="#" title="<?php _e("The Document Type Declaration, or DTD, is provided at the top of all web pages to let the browser know what type of markup language is being used. phpBB3's prosilver uses an XHTML 1.0 Strict DTD by default. Most WordPress templates, however, use an XHTML 1 transitional  or HTML 5 DTD. In most cases, this doesn't matter -- however, If you want to use WordPress' DTD on pages where WordPress is inside phpBB, then you can turn this option on. This should prevent browsers from going into quirks mode, and will ensure that even more WordPress templates display as designed.", 'wp-united'); ?>"><?php _e('What is this?', 'wp-united'); ?></a>
										</p>
									</div>
								</div>
						</div>
					</div>
					
					<div id="wputab-behav">

						<h3><?php _e('Use phpBB Word Censor?', 'wp-united'); ?></h3>
						<p><?php _e('Turn this option on if you want WordPress posts to be passed through the phpBB word censor.', 'wp-united'); ?></p>
						<input type="checkbox" id="wpucensor" name="wpucensor" <?php if($wpUnited->get_setting('phpbbCensor')) { echo ' checked="checked" '; } ?>/><label for="wpucensor"><?php _e('Enable word censoring in WordPress', 'wp-united'); ?></label>
						
						<h3><?php _e('Use phpBB smilies?', 'wp-united'); ?></h3>
						<p><?php _e('Turn this option on if you want to use phpBB smilies in WordPress comments and posts.', 'wp-united'); ?></p>
						<input type="checkbox" id="wpusmilies" name="wpusmilies" <?php if($wpUnited->get_setting('phpbbSmilies')) { echo ' checked="checked" '; } ?>/><label for="wpusmilies"><?php _e('Use phpBB smilies in WordPress', 'wp-united'); ?></label>	
						
					</div>
					
					<!--<div id="wputab-blogs">
						User Blogs - options being revamped
					</div>-->
				</div>
				
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Submit', 'wp-united') ?>" name="wpusettings-submit" />
			</p>
		</form>
		
		<div id="wpu-dialog" title="Message" style="display: none;">
			<p id="wpu-desc">&nbsp;</p>
		</div>
		
	</div>
	
		<script type="text/javascript">
		// <![CDATA[
			var transmitMessage;
			var transmitNonce = '<?php echo wp_create_nonce ('wp-united-transmit'); ?>';
			var disableNonce = '<?php echo wp_create_nonce ('wp-united-disable'); ?>';
			var blankPageMsg = '<?php wpu_js_translate(__('Blank page received: check your error log.', 'wp-united')); ?>';
			var phpbbPath = '<?php echo ($wpUnited->get_setting('phpbb_path')) ? $wpUnited->get_setting('phpbb_path') : ''; ?>';		
			var treeScript =  '<?php echo $wpUnited->get_plugin_url() . 'js/filetree.php'; ?>';
			
			var statusCSSMDisabled = '<?php wpu_js_translate(__('Off', 'wp-united')); ?>';
			var descCSSMDisabled = '<?php wpu_js_translate(__('All automatic CSS integration is disabled', 'wp-united')); ?>';
			var statusCSSMMed = '<?php wpu_js_translate(__('Medium', 'wp-united')); ?>';
			var descCSSMMed = '<?php wpu_js_translate(sprintf(__('%1$sStyles are reset to stop outer styles applying to the inner part of the page.%2$sInner CSS is made more specific so it does not affect the outer portion of the page.%2$sSome HTML IDs and class names may be duplicated.%3$s', 'wp-united'), '<ul><li>', '</li><li>', '</li></ul>')); ?>';
			var statusCSSMFull = '<?php wpu_js_translate(__('Full', 'wp-united')); ?>';
			var descCSSMFull = '<?php wpu_js_translate(sprintf(__('%1$sStyles are reset to stop outer styles applying to the inner part of the page.%2$sInner CSS is made more specific so it does not affect the outer portion of the page.%2$sHTML IDs and class names that are duplicated in the inner and outer parts of the page are fixed.%3$s', 'wp-united'), '<ul><li>', '</li><li>', '</li></ul>')); ?>';
			
			
			
			<?php 
					$cssmVal = 0;
					if($wpUnited->get_setting('cssMagic')){
						$cssmVal++;
					}
					if($wpUnited->get_setting('templateVoodoo')){
						$cssmVal++;
					}
			?>
			var cssmVal = '<?php echo $cssmVal; ?>';

			function wpu_hardened_init_tail() {
				setupSettingsPage();
				<?php if($wpUnited->get_setting('phpbb_path')) { ?> 
					setPath('settings');
				<?php } ?>	
				setupHelpButtons();
				settingsFormSetup();			
			}

		// ]]>
		</script>	

<?php 
	add_action('admin_footer', 'wpu_hardened_script_init');

}




/**
 * Process settings
 */
function wpu_process_settings() {
	global $wpUnited, $wpdb; 

	$type = 'setup';
	if(isset($_POST['type'])) {
		if($_POST['type'] == 'wp-united-settings') {
			$type = 'settings';
		}
	}
	
	$data = array();

	/**
	 * First process path to phpBB
	 */
	if(!isset($_POST['wpu-path'])) {
		die('[ERROR] ' . __("ERROR: You must specify a valid path for phpBB's config.php", 'wp-united'));
	}
	$wpuPhpbbPath = (string)$_POST['wpu-path'];
	$wpuPhpbbPath = str_replace('http:', '', $wpuPhpbbPath);
	$wpuPhpbbPath = add_trailing_slash($wpuPhpbbPath);
	if(!@file_exists($wpUnited->get_plugin_path()))  {
		die('[ERROR] ' . __("ERROR:The path you selected for phpBB's config.php is not valid", 'wp-united'));
		return;
	}
	if(!@file_exists($wpuPhpbbPath . 'config.php'))  {
		die('[ERROR] ' . __("ERROR: phpBB's config.php could not be found at the location you chose", 'wp-united'));
		return;
	}
	if($type=='setup') {
		$data['phpbb_path'] = $wpuPhpbbPath;
	}
	
	$wpUnited->update_settings($data);

	if($type == 'settings') {
		/**
		 * Process 'use forum page'
		 */
		$data['useForumPage'] = isset($_POST['wpuforumpage']) ? 1 : 0;
		
		$forum_page_ID = get_option('wpu_set_forum');
		if ( !empty($data['useForumPage']) ) {
			$content = '<!--wp-united-phpbb-forum-->';
			$title = __('Forum', 'wp-united');
			if ( !empty($forum_page_ID) ) {
				// we no longer reset title & date
				$wpdb->query( 
					"UPDATE IGNORE $wpdb->posts SET
						post_author = '0',
						post_content = '$content',
						post_content_filtered = '',
						post_excerpt = '',
						post_status = 'publish',
						post_type = 'page',
						comment_status = 'closed',
						ping_status = 'closed',
						post_password = '',
						post_name = 'forum',
						to_ping = '',
						pinged = '',
						post_modified = '".current_time('mysql')."',
						post_modified_gmt = '".current_time('mysql',1)."',
						post_parent = '0',
						menu_order = '0'
						WHERE ID = $forum_page_ID"
				);
			} else {
				$wpdb->query(
				"INSERT IGNORE INTO $wpdb->posts
						(post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, post_type, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_mime_type)
					VALUES
						('0', '".current_time('mysql')."', '".current_time('mysql',1)."', '{$content}', '', '{$title}', '', 'publish', 'page', 'closed', 'closed', '', 'forum', '', '', '".current_time('mysql')."', '".current_time('mysql',1)."', '0', '0', '')"
				);
				$forum_page_ID = $wpdb->insert_id;		
			}		
			update_option('wpu_set_forum', $forum_page_ID);			
				
		} else {
			if ( !empty($forum_page_ID) ) {
				update_option('wpu_set_forum', '');
				@wp_delete_post($forum_page_ID);
			}					
		}
		
		/** 
		 * Process login integration settings
		 */
		$data['integrateLogin'] = (isset($_POST['wpuloginint']) && (!defined('WPU_CANNOT_OVERRIDE')) ) ? 1 : 0;
		
		if($data['integrateLogin']) {
			
			$data['integcreatewp'] = (isset($_POST['wpucreatewacct'])) ? 1 : 0;
			$data['integcreatephpbb'] = (isset($_POST['wpucreatepacct'])) ? 1 : 0;
			$data['avatarsync'] = (isset($_POST['wpuavatar'])) ? 1 : 0;
			$data['xposting'] =   (isset($_POST['wpuxpost'])) ? 1 : 0;
			
			if($data['xposting'] ) { 
				
				$xpostType = (!isset($_POST['rad_xpost_type'])) ? 'excerpt' : $_POST['rad_xpost_type'];
				if($xpostType == 'askme') {
					$data['xposttype'] ='askme';
				} else if($xpostType == 'fullpost') {
					$data['xposttype'] ='fullpost';
				} else {
					$data['xposttype'] ='excerpt';
				}
				
				$data['xpostautolink'] =(isset($_POST['wpuxpostcomments'])) ? 1 : 0;
				
				if($data['xpostautolink']) {
					
					// xPostSpam could be 'yes', 'no' or 'all'
					$xPostSpam = (!isset($_POST['rad_xpostcomappr'])) ? 'all' : (string)$_POST['rad_xpostcomappr'];
					if($xPostSpam == 'no') {
						$data['xpostspam'] = 0;
					} else if($xPostSpam == 'yes') {
						$data['xpostspam'] = 1;
					} else {
						$data['xpostspam'] = 'all';
					}

				}
				
				$data['xpostforce'] =(isset($_POST['wpuxpostforce'])) ? (int) $_POST['wpuxpostforce'] : -1;
				$data['xpostprefix'] = (isset($_POST['wpuxpostprefix'])) ? (string) $_POST['wpuxpostprefix'] : __('[BLOG] ', 'wp-united');
			} else {
				//cross-posting disabled, set to default
				$data = array_merge($data, array(
					'xposttype' 		=> 'excerpt',
					'wpuxpostcomments'	=> 0,
					'xpostforce' 		=> -1,
					'xpostautolink' 	=> 0,
					'xpostspam' 		=> 'all'
					// can leave xpostprefix
				));
			}
		} else {
			// logins not integrated, set to default
			$data = array_merge($data, array(
				'integcreatewp'			=> 1,
				'integcreatephpbb'		=> 1,			
				'avatarsync'			=> 1,
				'xposting' 				=> 0,
				'xposttype' 			=> 'excerpt',
				'wpuxpostcomments'		=> 0,
				'xpostforce' 			=> -1,
				'xpostautolink' 		=> 0,
				'xpostspam' 			=> 'all',
				'xpostprefix'			=> __('[BLOG] ', 'wp-united')
			));
		}
			
			
		/**
		 * Process 'theme integration' settings
		 */
		
		 $tplInt = isset($_POST['wputplint']) ? 1 : 0;

		if($tplInt) {
			$tplDir = isset($_POST['rad_tpl']) ? (string) $_POST['rad_tpl'] : 'fwd';
			
			if($tplDir == 'rev') {
				$data['showHdrFtr'] = 'REV';
			} else {
				$data['showHdrFtr'] = 'FWD';
			}
			
			$cssmLevel = isset($_POST['wpucssmlevel']) ? (int) $_POST['wpucssmlevel'] : 2;
			switch($cssmLevel) {
				case 0:
					$data['cssMagic'] = 0;
					$data['templateVoodoo'] = 0;
					break;
				case 1:
					$data['cssMagic'] = 1;
					$data['templateVoodoo'] = 0;
					break;
				default:
					$data['cssMagic'] = 1;
					$data['templateVoodoo'] = 1;	
			}
			
			$simpleHeader = (isset($_POST['wpuhdrftrspl'])) ?  $_POST['wpuhdrftrspl'] : 0;
			
			// set defaults
			$data['wpSimpleHdr'] = 1;
			$data['wpPageName'] = 'page.php';	

			if(!empty($simpleHeader)) {
				// we would check for existence of the file, but TEMPLATEPATH isn't initialised here yet.
				$data['wpSimpleHdr'] = 0;
				$data['wpPageName'] = $simpleHeader;
			} 
			
			$padT = isset($_POST['wpupadtop']) ? $_POST['wpupadtop'] : '';
			$padR = isset($_POST['wpupadright']) ? $_POST['wpupadright'] : '';
			$padB = isset($_POST['wpupadbtm']) ? $_POST['wpupadbtm'] : '';
			$padL = isset($_POST['wpupadleft']) ? $_POST['wpupadleft'] : '';

			if ( ($padT == '') && ($padR == '') && ($padB == '') && ($padL == '') ) {
				$data['phpbbPadding'] = 'NOT_SET';
			} else {
				$data['phpbbPadding'] = (int)$padT . '-' . (int)$padR . '-' . (int)$padB . '-' . (int)$padL;
			}
			
			$data['dtdSwitch'] =(isset($_POST['wpudtd'])) ? 1 : 0;
			
		} else {
			$data = array_merge($data, array(
				'showHdrFtr' 			=> 'NONE',
				'cssMagic' 				=> 0,
				'templateVoodoo' 	=> 0,
				'wpSimpleHdr' 		=> 1,
				'wpPageName' 		=> 'page.php',
				'phpbbPadding' 		=>  '6-12-6-12',
				'dtdSwitch' 				=> 0
			));
		}
		
		/**
		 * Process 'behaviour' settings
		 */
		$data = array_merge($data, array(
			'phpbbCensor' 	=> (isset($_POST['wpucensor'])) ? 1 : 0,
			'phpbbSmilies' 	=> (isset($_POST['wpusmilies'])) ? 1 : 0
		));
		
	}

	$wpUnited->update_settings($data);
}


function wpu_panel_error($type, $text) {
	
	echo '<div id="message" class="error"><p>' . $text . '</p></div>';
	if($type=='settings') {
		wpu_settings_page();
	} else {
		wpu_show_setup_menu();
	}
	
}


function wpu_show_advanced_options() {	

	?>
		

			<!-- <form name="wpu-advoptions" id="wpuoptions" action="admin.php?page=wp-united-advanced" method="post">
			
			
			<?php wp_nonce_field('wp-united-advanced'); ?>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php  _e('Save', 'wp-united') ?>" name="wpuadvanced-submit" />
			</p>
			
			</form> -->

		
	<?php

}

function wpu_process_advanced_options() {
	echo "SAVED";
	wpu_show_advanced_options();
}

function wpu_filetree() {
	if(stristr($_POST['filetree'], '..')) {
		die();
	}
	
	
	$docRoot = wpu_get_doc_root();

	$fileLoc = str_replace( '\\', '/', urldecode($_POST['filetree']));

	if(stristr($fileLoc, $docRoot) === false) {
		$fileLoc = $docRoot . $fileLoc;
		$fileLoc = str_replace('//', '/', $fileLoc);
	}

	if( @file_exists($fileLoc) ) {
		$files = scandir($fileLoc);
		natcasesort($files);
		if( count($files) > 2 ) { /* The 2 accounts for . and .. */
			echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
			// All dirs
			foreach( $files as $file ) {
				if( @file_exists($fileLoc. $file) && $file != '.' && $file != '..' && is_dir($fileLoc . $file) ) {
					echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($fileLoc . $file) . "/\">" . htmlentities($file) . "</a></li>";
				}
			}
			// All files
			foreach( $files as $file ) {
				if( @file_exists($fileLoc . $file) && $file != '.' && $file != '..' && !is_dir($fileLoc . $file) ) {
					$ext = preg_replace('/^.*\./', '', $file);
					echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($fileLoc . $file) . "\">" . htmlentities($file) . "</a></li>";
				}
			}
			echo "</ul>";	
		}
	}
	die();
	
}

/*
	A way to initialise scripts that still works EVEN WHEN OTHER (grrrr) PLUGINS have script errors
*/
function wpu_hardened_script_init() {
	static $calledInit = false;

	if(!$calledInit) {
		$calledInit = true;
	}
	
	?>
	<script type="text/javascript">// <![CDATA[
		$wpu(document).ready(function() {  
			wpu_hardened_init();
		});
		setTimeout('wpu_hardened_init()', 1000);
	// ]]>
	</script>
	<?php
}


// end of file