<?php
/**
 * Will be included in the shariff.php only, when an admin is logged in.
 * Everything reagarding the admin menu was moved to this file.
*/

// prevent direct calls to admin_menu.php
if ( ! class_exists('WP') ) { die(); }

// the admin page
add_action( 'admin_menu', 'shariff3UU_add_admin_menu' );
add_action( 'admin_init', 'shariff3UU_options_init' );
add_action( 'init', 'shariff3UU_init_locale' );

// add settings link on plugin page
function shariff3UU_settings_link( $links ) {
	$settings_link = '<a href="options-general.php?page=shariff3uu">' . __( 'Settings', 'shariff3UU' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'shariff3UU_settings_link' );

// scripts and styles for admin pages e.g. info notice
function shariff3UU_admin_style( $hook ) {
	// styles for admin notice - needed on _ALL_ admin pages
	wp_enqueue_style( 'shariff_admin-notice', plugins_url( '../css/shariff_admin-notice.css', __FILE__ ) );
	// styles and scripts only needed on our plugin options page - no need to load them on _ALL_ admin pages
	if ( $hook == 'settings_page_shariff3uu' ) {
		// styles for our plugin options page
		wp_enqueue_style( 'shariff_options', plugins_url( '../css/shariff_options.css', __FILE__ ) );
		// scripts for pinterest default image media uploader
		wp_enqueue_script( 'jquery' ); // just in case
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'shariff3UU_admin_style' );

// add admin menu
function shariff3UU_add_admin_menu() {
	add_options_page( 'Shariff', 'Shariff', 'manage_options', 'shariff3uu', 'shariff3UU_options_page' );
}

// plugin options page
function shariff3UU_options_init(){

	// first tab - basic

	// register first tab (basic) settings and call sanitize function
	register_setting( 'basic', 'shariff3UU_basic', 'shariff3UU_basic_sanitize' );

	// first tab - basic options
	add_settings_section( 'shariff3UU_basic_section', __( 'Basic options', 'shariff3UU' ),
		'shariff3UU_basic_section_callback', 'basic' );

	// services
	add_settings_field( 'shariff3UU_text_services', '<div class="shariff_status-col">' . __( 'Enable the following services in the provided order:', 'shariff3UU' ) . '</div>',
		'shariff3UU_text_services_render', 'basic', 'shariff3UU_basic_section' );

	// add after
	add_settings_field( 'shariff3UU_multiplecheckbox_add_after', __( 'Add the Shariff buttons <u>after</u> all:', 'shariff3UU' ),
		'shariff3UU_multiplecheckbox_add_after_render', 'basic', 'shariff3UU_basic_section' );

	// add before
	add_settings_field( 'shariff3UU_checkbox_add_before', __( 'Add the Shariff buttons <u>before</u> all:', 'shariff3UU' ),
		'shariff3UU_multiplecheckbox_add_before_render', 'basic', 'shariff3UU_basic_section' );

	// disable on protected posts
	add_settings_field( 'shariff3UU_checkbox_disable_on_protected', __( 'Disable the Shariff buttons on password protected posts.', 'shariff3UU' ),
		'shariff3UU_checkbox_disable_on_protected_render', 'basic', 'shariff3UU_basic_section' );

	// second tab - design

	// register second tab (design) settings and call sanitize function
	register_setting( 'design', 'shariff3UU_design', 'shariff3UU_design_sanitize' );

	// second tab - design options
	add_settings_section( 'shariff3UU_design_section', __( 'Design options', 'shariff3UU' ),
		'shariff3UU_design_section_callback', 'design' );

	// button language
	add_settings_field( 'shariff3UU_select_language', '<div class="shariff_status-col">' . __( 'Shariff button language:', 'shariff3UU' ) . '</div>',
		'shariff3UU_select_language_render', 'design', 'shariff3UU_design_section' );

	// theme
	add_settings_field( 'shariff3UU_radio_theme', __( 'Shariff button design:', 'shariff3UU' ),
		'shariff3UU_radio_theme_render', 'design', 'shariff3UU_design_section' );

	// button size
	add_settings_field( 'shariff3UU_checkbox_buttonsize', __( 'Reduce button size by 30%.', 'shariff3UU' ),
		'shariff3UU_checkbox_buttonsize_render', 'design', 'shariff3UU_design_section' );

	// button stretch
	add_settings_field( 'shariff3UU_checkbox_buttonsstretch', __( 'Stretch buttons horizontally to full width.', 'shariff3UU' ),
		'shariff3UU_checkbox_buttonstretch_render', 'design', 'shariff3UU_design_section' );

	// vertical
	add_settings_field( 'shariff3UU_checkbox_vertical', __( 'Shariff button orientation <b>vertical</b>.', 'shariff3UU' ),
		'shariff3UU_checkbox_vertical_render', 'design', 'shariff3UU_design_section' );

	// alignment option
	add_settings_field( 'shariff3UU_radio_align', __( 'Alignment of the Shariff buttons:', 'shariff3UU' ),
		'shariff3UU_radio_align_render', 'design', 'shariff3UU_design_section' );

	// alignment option for the widget
	add_settings_field( 'shariff3UU_radio_align_widget', __( 'Alignment of the Shariff buttons in the widget:', 'shariff3UU' ),
		'shariff3UU_radio_align_widget_render', 'design', 'shariff3UU_design_section' );

	// headline
	add_settings_field( 'shariff3UU_text_headline', __( 'Headline above all Shariff buttons:', 'shariff3UU' ),
		'shariff3UU_text_headline_render', 'design', 'shariff3UU_design_section' );

	// custom css
	add_settings_field( 'shariff3UU_text_style', __( 'CSS attributes for the container <span style="text-decoration: underline;">around</span> Shariff:', 'shariff3UU' ),
		'shariff3UU_text_style_render', 'design', 'shariff3UU_design_section' );

	// third tab - advanced

	// register third tab (advanced) settings and call sanitize function
	register_setting( 'advanced', 'shariff3UU_advanced', 'shariff3UU_advanced_sanitize' );

	// third tab - advanced options
	add_settings_section( 'shariff3UU_advanced_section', __( 'Advanced options', 'shariff3UU' ),
		'shariff3UU_advanced_section_callback', 'advanced' );

	// info url
	add_settings_field(
		'shariff3UU_text_info_url', '<div class="shariff_status-col">' . __( 'Custom link for the info button:', 'shariff3UU' ) . '</div>',
		'shariff3UU_text_info_url_render', 'advanced', 'shariff3UU_advanced_section' );

	// twitter via
	add_settings_field(
		'shariff3UU_text_twittervia', __( 'Twitter username for the via tag:', 'shariff3UU' ),
		'shariff3UU_text_twittervia_render', 'advanced', 'shariff3UU_advanced_section' );

	// flattr username
	add_settings_field(
		'shariff3UU_text_flattruser', __( 'Flattr username:', 'shariff3UU' ),
		'shariff3UU_text_flattruser_render', 'advanced', 'shariff3UU_advanced_section' );

	// patreon username
	add_settings_field(
		'shariff3UU_text_patreonid', __( 'Patreon username:', 'shariff3UU' ),
		'shariff3UU_text_patreonid_render', 'advanced', 'shariff3UU_advanced_section' );

	// paypal button id
	add_settings_field(
		'shariff3UU_text_paypalbuttonid', __( 'PayPal hosted button ID:', 'shariff3UU' ),
		'shariff3UU_text_paypalbuttonid_render', 'advanced', 'shariff3UU_advanced_section' );

	// paypalme id
	add_settings_field(
		'shariff3UU_text_paypalmeid', __( 'PayPal.Me ID:', 'shariff3UU' ),
		'shariff3UU_text_paypalmeid_render', 'advanced', 'shariff3UU_advanced_section' );

	// bitcoin address
	add_settings_field(
		'shariff3UU_text_bitcoinaddress', __( 'Bitcoin address:', 'shariff3UU' ),
		'shariff3UU_text_bitcoinaddress_render', 'advanced', 'shariff3UU_advanced_section' );

	// rss feed
	add_settings_field(
		'shariff3UU_text_rssfeed', __( 'RSS feed:', 'shariff3UU' ),
		'shariff3UU_text_rssfeed_render', 'advanced', 'shariff3UU_advanced_section' );

	// default image for pinterest
	add_settings_field( 'shariff3UU_text_default_pinterest', __( 'Default image for Pinterest:', 'shariff3UU' ),
		'shariff3UU_text_default_pinterest_render', 'advanced', 'shariff3UU_advanced_section' );

	// fourth tab - mailform

	// register fourth tab (mailform) settings and call sanitize function
	register_setting( 'mailform', 'shariff3UU_mailform', 'shariff3UU_mailform_sanitize' );

	// fourth tab - mailform options
	add_settings_section( 'shariff3UU_mailform_section', __( 'Mail form options', 'shariff3UU' ),
		'shariff3UU_mailform_section_callback', 'mailform' );

	// disable mailform
	add_settings_field(
		'shariff3UU_checkbox_disable_mailform', '<div class="shariff_status-col">' . __( 'Disable the mail form functionality.', 'shariff3UU' ) .'</div>',
		'shariff3UU_checkbox_disable_mailform_render', 'mailform', 'shariff3UU_mailform_section' );

	// require sender e-mail address
	add_settings_field(
		'shariff3UU_checkbox_require_sender', '<div class="shariff_status-col">' . __( 'Require sender e-mail address.', 'shariff3UU' ) .'</div>',
		'shariff3UU_checkbox_require_sender_render', 'mailform', 'shariff3UU_mailform_section' );

	// mailform language
	add_settings_field(
		'shariff3UU_select_mailform_language', '<div class="shariff_status-col">' . __( 'Mailform language:', 'shariff3UU' ) .'</div>',
		'shariff3UU_select_mailform_language_render', 'mailform', 'shariff3UU_mailform_section' );

	// add content of the post to e-mails
	add_settings_field(
		'shariff3UU_checkbox_mail_add_post_content', '<div class="shariff_status-col">' . __( 'Add the post content to the e-mail body.', 'shariff3UU' ) .'</div>',
		'shariff3UU_checkbox_mail_add_post_content_render', 'mailform', 'shariff3UU_mailform_section' );

	// mail sender name
	add_settings_field(
		'shariff3UU_text_mail_sender_name', '<div class="shariff_status-col">' . __( 'Default sender name:', 'shariff3UU' ) .'</div>',
		'shariff3UU_text_mail_sender_name_render', 'mailform', 'shariff3UU_mailform_section' );

	// mail sender address
	add_settings_field(
		'shariff3UU_text_mail_sender_from', '<div class="shariff_status-col">' . __( 'Default sender e-mail address:', 'shariff3UU' ) .'</div>',
		'shariff3UU_text_mail_sender_from_render', 'mailform', 'shariff3UU_mailform_section' );

	// fifth tab - statistic

	// register fifth tab (statistic) settings and call sanitize function
	register_setting( 'statistic', 'shariff3UU_statistic', 'shariff3UU_statistic_sanitize' );

	// fifth tab (statistic)
	add_settings_section( 'shariff3UU_statistic_section', __( 'Statistic', 'shariff3UU' ),
		'shariff3UU_statistic_section_callback', 'statistic' );

	// share counts
	add_settings_field( 'shariff3UU_checkbox_backend', '<div class="shariff_status-col">' . __( 'Enable share counts (statistic).', 'shariff3UU' ) .'</div>',
		'shariff3UU_checkbox_backend_render', 'statistic', 'shariff3UU_statistic_section' );

	// Facebook App ID
	add_settings_field( 'shariff3UU_text_fb_id', '<div class="shariff_status-col">' . __( 'Facebook App ID:', 'shariff3UU' ) .'</div>',
		'shariff3UU_text_fb_id_render', 'statistic', 'shariff3UU_statistic_section' );

	// Facebook App Secret
	add_settings_field( 'shariff3UU_text_fb_secret', '<div class="shariff_status-col">' . __( 'Facebook App Secret:', 'shariff3UU' ) .'</div>',
		'shariff3UU_text_fb_secret_render', 'statistic', 'shariff3UU_statistic_section' );

	// ttl
	add_settings_field( 'shariff3UU_number_ttl', '<div class="shariff_status-col">' . __( 'Cache TTL in seconds (60 - 7200):', 'shariff3UU' ) .'</div>',
		'shariff3UU_number_ttl_render', 'statistic', 'shariff3UU_statistic_section' );

	// disable services
	add_settings_field( 'shariff3UU_multiplecheckbox_disable_services', '<div class="shariff_status-col">' . __( 'Disable the following services (share counts only):', 'shariff3UU' ) .'</div>',
		'shariff3UU_multiplecheckbox_disable_services_render', 'statistic', 'shariff3UU_statistic_section' );

	// external hosts
	add_settings_field( 'shariff3UU_text_external_host', '<div class="shariff_status-col">' . __( 'External host for share counts, shariff.js and CSS:', 'shariff3UU' ) .'</div>',
		'shariff3UU_text_external_host_render', 'statistic', 'shariff3UU_statistic_section' );

	// sixth tab - help

	// register sixth tab (help)
	add_settings_section( 'shariff3UU_help_section', __( 'Shariff Help', 'shariff3UU' ),
		'shariff3UU_help_section_callback', 'help' );

	// seventh tab - status

	// register seventh tab (status)
	add_settings_section( 'shariff3UU_status_section', __( 'Status', 'shariff3UU' ),
		'shariff3UU_status_section_callback', 'status' );
}

// sanitize input from the basic settings page
function shariff3UU_basic_sanitize( $input ) {
	// create array
	$valid = array();

	if ( isset( $input["version"] ) )				$valid["version"]				= sanitize_text_field( $input["version"] );
	if ( isset( $input["services"] ) )				$valid["services"]				= str_replace( ' ', '', sanitize_text_field( $input["services"] ) );
	if ( isset( $input["add_after"] ) )				$valid["add_after"]				= sani_arrays( $input["add_after"] );
	if ( isset( $input["add_before"] ) )			$valid["add_before"]			= sani_arrays( $input["add_before"] );
	if ( isset( $input["disable_on_protected"] ) )	$valid["disable_on_protected"]	= absint( $input["disable_on_protected"] );

	// remove empty elements
	$valid = array_filter( $valid );

	return $valid;
}

// sanitize input from the design settings page
function shariff3UU_design_sanitize( $input ) {
	// create array
	$valid = array();

	if ( isset( $input["lang"] ) ) 				$valid["lang"] 				= sanitize_text_field( $input["lang"] );
	if ( isset( $input["theme"] ) ) 			$valid["theme"] 			= sanitize_text_field( $input["theme"] );
	if ( isset( $input["buttonsize"] ) )		$valid["buttonsize"]		= absint( $input["buttonsize"] );
	if ( isset( $input["buttonstretch"] ) )		$valid["buttonstretch"]		= absint( $input["buttonstretch"] );
	if ( isset( $input["vertical"] ) ) 			$valid["vertical"] 			= absint( $input["vertical"] );
	if ( isset( $input["align"] ) ) 			$valid["align"] 			= sanitize_text_field( $input["align"] );
	if ( isset( $input["align_widget"] ) ) 		$valid["align_widget"] 		= sanitize_text_field( $input["align_widget"] );
	if ( isset( $input["style"] ) ) 			$valid["style"] 			= sanitize_text_field( $input["style"] );
	if ( isset( $input["headline"] ) ) 			$valid["headline"] 			= wp_kses( $input["headline"], $GLOBALS["allowed_tags"] );

	// remove empty elements
	$valid = array_filter($valid);

	return $valid;
}

// sanitize input from the advanced settings page
function shariff3UU_advanced_sanitize( $input ) {
	// create array
	$valid = array();

	// waiting for fix https://core.trac.wordpress.org/ticket/28015 in order to use esc_url_raw instead for info_url
	if ( isset($input["info_url"] ) ) 				$valid["info_url"] 				= sanitize_text_field( $input["info_url"] );
	if ( isset($input["twitter_via"] ) ) 			$valid["twitter_via"] 			= str_replace( '@', '', sanitize_text_field( $input["twitter_via"] ) );
	if ( isset($input["flattruser"] ) )    			$valid["flattruser"]       		= str_replace( '@', '', sanitize_text_field( $input["flattruser"] ) );
	if ( isset($input["patreonid"] ) )    			$valid["patreonid"]       		= str_replace( '@', '', sanitize_text_field( $input["patreonid"] ) );
	if ( isset($input["paypalbuttonid"] ) )    		$valid["paypalbuttonid"]       	= str_replace( '@', '', sanitize_text_field( $input["paypalbuttonid"] ) );
	if ( isset($input["paypalmeid"] ) )      		$valid["paypalmeid"]       	    = str_replace( '@', '', sanitize_text_field( $input["paypalmeid"] ) );
	if ( isset($input["bitcoinaddress"] ) )    		$valid["bitcoinaddress"]       	= str_replace( '@', '', sanitize_text_field( $input["bitcoinaddress"] ) );
	if ( isset($input["rssfeed"] ) )    		    $valid["rssfeed"]       	    = str_replace( '@', '', sanitize_text_field( $input["rssfeed"] ) );
	if ( isset($input["default_pinterest"] ) ) 	    $valid["default_pinterest"]		= sanitize_text_field( $input["default_pinterest"] );

	// remove empty elements
	$valid = array_filter( $valid );

	return $valid;
}

// sanitize input from the mailform settings page
function shariff3UU_mailform_sanitize( $input ) {
	// create array
	$valid = array();

	if ( isset( $input["disable_mailform"] ) )		$valid["disable_mailform"]		= absint( $input["disable_mailform"] );
	if ( isset( $input["require_sender"] ) )		$valid["require_sender"]		= absint( $input["require_sender"] );
	if ( isset( $input["mailform_language"] ) )		$valid["mailform_language"]		= sanitize_text_field( $input["mailform_language"] );
	if ( isset( $input["mail_add_post_content"] ) )	$valid["mail_add_post_content"]	= absint( $input["mail_add_post_content"] );
	if ( isset( $input["mail_sender_name"] ) )		$valid["mail_sender_name"]		= sanitize_text_field( $input["mail_sender_name"] );
	if ( isset( $input["mail_sender_from"] ) && is_email( $input["mail_sender_from"] ) != false ) $valid["mail_sender_from"] = sanitize_email( $input["mail_sender_from"] );

	// remove empty elements
	$valid = array_filter( $valid );

	return $valid;
}

// sanitize input from the statistic settings page
function shariff3UU_statistic_sanitize( $input ) {
	// create array
	$valid = array();

	if ( isset( $input["backend"] ) ) 				$valid["backend"] 				= absint( $input["backend"] );
	if ( isset( $input["fb_id"] ) ) 	    		$valid["fb_id"]					= sanitize_text_field( $input["fb_id"] );
	if ( isset( $input["fb_secret"] ) ) 	    	$valid["fb_secret"]				= sanitize_text_field( $input["fb_secret"] );
	if ( isset( $input["ttl"] ) ) 	    			$valid["ttl"]					= absint( $input["ttl"] );
	if ( isset( $input["disable"] ) ) 	    		$valid["disable"]				= sani_arrays( $input["disable"] );
	if ( isset( $input["external_host"] ) )			$valid["external_host"]			= str_replace( ' ', '', sanitize_text_field( $input["external_host"] ) );

	// protect users from themselfs
	if ( isset( $valid["ttl"] ) && $valid["ttl"] < '60' ) $valid["ttl"] = '';
	elseif ( isset( $valid["ttl"] ) && $valid["ttl"] > '7200' ) $valid["ttl"] = '7200';

	// remove empty elements
	$valid = array_filter( $valid );

	return $valid;
}

// helper function to sanitize arrays
function sani_arrays( $data = array() ) {
	if ( ! is_array($data) || ! count( $data ) ) {
		return array();
	}
	foreach ( $data as $k => $v ) {
		if ( ! is_array( $v ) && ! is_object( $v ) ) {
			$data[ $k ] = absint( trim( $v ) );
		}
		if ( is_array( $v ) ) {
			$data[ $k ] = sani_arrays( $v );
		}
	}
	return $data;
}

// render admin options: use isset() to prevent errors while debug mode is on

// basic options

// description basic options
function shariff3UU_basic_section_callback(){
	echo __( "Select the desired services in the order you want them to be displayed and where the Shariff buttons should be included automatically.", "shariff3UU" );
}

// services
function shariff3UU_text_services_render(){
	if ( isset( $GLOBALS["shariff3UU_basic"]["services"] ) ) {
		$services = $GLOBALS["shariff3UU_basic"]["services"];
	}
	else {
		$services = '';
	}
	echo '<input type="text" name="shariff3UU_basic[services]" value="' . esc_html($services) . '" size="75" placeholder="twitter|facebook|googleplus|info">';
	echo '<p><code>facebook|twitter|googleplus|whatsapp|threema|pinterest|xing|linkedin|reddit|vk|diaspora|stumbleupon</code></p>';
	echo '<p><code>tumblr|addthis|flattr|patreon|paypal|paypalme|bitcoin|mailform|mailto|printer|info|rss</code></p>';
	echo '<p>' . __( 'Use the pipe sign | (Alt Gr + &lt; or &#8997; + 7) between two or more services.', 'shariff3UU' ) . '</p>';
}

// add after
function shariff3UU_multiplecheckbox_add_after_render() {
	// add after all posts
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][posts]" ';
	if ( isset( $GLOBALS['shariff3UU_basic']['add_after']['posts'] ) ) echo checked( $GLOBALS['shariff3UU_basic']['add_after']['posts'], 1, 0 );
	echo ' value="1">' . __('Posts', 'shariff3UU') . '</p>';

	// add after all posts (blog page)
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][posts_blogpage]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_after"]["posts_blogpage"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_after"]["posts_blogpage"], 1, 0 );
	echo ' value="1">' . __('Posts (blog page)', 'shariff3UU') . '</p>';

	// add after all pages
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][pages]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_after"]["pages"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_after"]["pages"], 1, 0 );
	echo ' value="1">' . __('Pages', 'shariff3UU') . '</p>';

	// add after all bbpress replies
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][bbp_reply]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_after"]["bbp_reply"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_after"]["bbp_reply"], 1, 0 );
	echo ' value="1">' . __('bbPress replies', 'shariff3UU') . '</p>';

	// add after all excerpts
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][excerpt]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_after"]["excerpt"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_after"]["excerpt"], 1, 0 );
	echo ' value="1">' . __('Excerpt', 'shariff3UU') . '</p>';

	// add after custom post types - choose after which to add
	$post_types = get_post_types( array( '_builtin' => FALSE ) );
	if ( isset( $post_types ) && is_array( $post_types ) && ! empty( $post_types ) ) {
		echo '<p>Custom Post Types:</p>';
	};

	foreach ( $post_types as $post_type ) {
		$object = get_post_type_object( $post_type );
		printf(
			'<p><input type="checkbox" name="shariff3UU_basic[add_after][%s]" %s value="1">%s</p>',
			$post_type,
			isset( $GLOBALS['shariff3UU_basic']['add_after'][$post_type] ) ? checked( $GLOBALS['shariff3UU_basic']['add_after'][$post_type], 1, 0 ) : '',
			$object->labels->singular_name	// this should already be localized <- not always, but there is no way to know, so we have to accept the language mixup
		);
	} 
}

// add before
function shariff3UU_multiplecheckbox_add_before_render() {
	// Add before all posts
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_before][posts]" ';
	if ( isset( $GLOBALS['shariff3UU_basic']['add_before']['posts'] ) ) echo checked( $GLOBALS['shariff3UU_basic']['add_before']['posts'], 1, 0 );
	echo ' value="1">' . __('Posts', 'shariff3UU') . '</p>';

	// Add before all posts (blog page)
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_before][posts_blogpage]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_before"]["posts_blogpage"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_before"]["posts_blogpage"], 1, 0 );
	echo ' value="1">' . __('Posts (blog page)', 'shariff3UU') . '</p>';

	// Add before all pages
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_before][pages]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_before"]["pages"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_before"]["pages"], 1, 0 );
	echo ' value="1">' . __('Pages', 'shariff3UU') . '</p>';

	// Add before all excerpts
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_before][excerpt]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_before"]["excerpt"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_before"]["excerpt"], 1, 0 );
	echo ' value="1">' . __('Excerpt', 'shariff3UU') . '</p>';
}

// disable on password protected posts
function shariff3UU_checkbox_disable_on_protected_render() {
	echo '<input type="checkbox" name="shariff3UU_basic[disable_on_protected]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["disable_on_protected"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["disable_on_protected"], 1, 0 );
	echo ' value="1">';
}

// design options

// description design options
function shariff3UU_design_section_callback(){
	echo __( 'This configures the default design of the Shariff buttons. Most options can be overwritten for single posts or pages with the options within the <code>[shariff]</code> shorttag. For more information have a look at the ', 'shariff3UU');
	echo '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=shariff3uu&tab=help">';
	echo __( 'Help Section</a> and the ', 'shariff3UU' );
	echo '<a href="https://wordpress.org/support/plugin/shariff/" target="_blank">';
	echo __( 'Support Forum</a>.', 'shariff3UU' );
}

// language
function shariff3UU_select_language_render() {
	$options = $GLOBALS["shariff3UU_design"];
	if ( ! isset( $options["lang"] ) ) $options["lang"] = '';
	echo '<select name="shariff3UU_design[lang]">
	<option value="" ' .   selected( $options["lang"], "", 0 ) . '>' . __( "auto", "shariff3UU") . '</option>
	<option value="en" ' . selected( $options["lang"], "en", 0 ) . '>English</option>
	<option value="de" ' . selected( $options["lang"], "de", 0 ) . '>Deutsch</option>
	<option value="fr" ' . selected( $options["lang"], "fr", 0 ) . '>Français</option>
	<option value="es" ' . selected( $options["lang"], "es", 0 ) . '>Español</option>
	<option value="zh" ' . selected( $options["lang"], "zh", 0 ) . '>Chinese</option>
	<option value="hr" ' . selected( $options["lang"], "hr", 0 ) . '>Croatian</option>
	<option value="da" ' . selected( $options["lang"], "da", 0 ) . '>Danish</option>
	<option value="nl" ' . selected( $options["lang"], "nl", 0 ) . '>Dutch</option>
	<option value="fi" ' . selected( $options["lang"], "fi", 0 ) . '>Finnish</option>
	<option value="it" ' . selected( $options["lang"], "it", 0 ) . '>Italiano</option>
	<option value="ja" ' . selected( $options["lang"], "ja", 0 ) . '>Japanese</option>
	<option value="ko" ' . selected( $options["lang"], "ko", 0 ) . '>Korean</option>
	<option value="no" ' . selected( $options["lang"], "no", 0 ) . '>Norwegian</option>
	<option value="pl" ' . selected( $options["lang"], "pl", 0 ) . '>Polish</option>
	<option value="pt" ' . selected( $options["lang"], "pt", 0 ) . '>Portuguese</option>
	<option value="ro" ' . selected( $options["lang"], "ro", 0 ) . '>Romanian</option>
	<option value="ru" ' . selected( $options["lang"], "ru", 0 ) . '>Russian</option>
	<option value="sk" ' . selected( $options["lang"], "sk", 0 ) . '>Slovak</option>
	<option value="sl" ' . selected( $options["lang"], "sl", 0 ) . '>Slovene</option>
	<option value="sr" ' . selected( $options["lang"], "sr", 0 ) . '>Serbian</option>
	<option value="sv" ' . selected( $options["lang"], "sv", 0 ) . '>Swedish</option>
	<option value="tr" ' . selected( $options["lang"], "tr", 0 ) . '>Turkish</option>
	</select>';
}

// theme
function shariff3UU_radio_theme_render() {
	$options = $GLOBALS["shariff3UU_design"];
	if ( ! isset( $options["theme"] ) ) $options["theme"] = "";
	$plugins_url = plugins_url();
	echo '<div class="shariff_options-table">
	<div class="shariff_options-row"><div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[theme]" value="" ' .      checked( $options["theme"], "", 0 ) .      '>default</div><div class="shariff_options-cell"><img src="' . $plugins_url . '/shariff/pictos/defaultBtns.png"></div></div>
	<div class="shariff_options-row"><div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[theme]" value="color" ' .  checked( $options["theme"], "color", 0 ) . '>color</div><div class="shariff_options-cell"><img src="' .    $plugins_url . '/shariff/pictos/colorBtns.png"></div></div>
	<div class="shariff_options-row"><div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[theme]" value="grey" ' .  checked( $options["theme"], "grey", 0 )  . '>grey</div><div class="shariff_options-cell"><img src="' .    $plugins_url . '/shariff/pictos/greyBtns.png"></div></div>
	<div class="shariff_options-row"><div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[theme]" value="white" ' . checked( $options["theme"], "white", 0 ) . '>white</div><div class="shariff_options-cell"><img src="' .    $plugins_url . '/shariff/pictos/whiteBtns.png"></div></div>
	<div class="shariff_options-row"><div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[theme]" value="round" ' . checked( $options["theme"], "round", 0 ) . '>round</div><div class="shariff_options-cell"><img src="' .   $plugins_url . '/shariff/pictos/roundBtns.png"></div></div>
	</div>';
}

// button size
function shariff3UU_checkbox_buttonsize_render() {
	$plugins_url = plugins_url();
	echo '<input type="checkbox" name="shariff3UU_design[buttonsize]" ';
	if ( isset( $GLOBALS["shariff3UU_design"]["buttonsize"] ) ) echo checked( $GLOBALS["shariff3UU_design"]["buttonsize"], 1, 0 );
	echo ' value="1"><img src="'. $plugins_url .'/shariff/pictos/smallBtns.png" align="middle">';
}

// button stretch
function shariff3UU_checkbox_buttonstretch_render() {
	$plugins_url = plugins_url();
	echo '<input type="checkbox" name="shariff3UU_design[buttonstretch]" ';
	if ( isset( $GLOBALS["shariff3UU_design"]["buttonstretch"] ) ) echo checked( $GLOBALS["shariff3UU_design"]["buttonstretch"], 1, 0 );
	echo ' value="1">';
}

// vertical
function shariff3UU_checkbox_vertical_render() {
	$plugins_url = plugins_url();
	echo '<input type="checkbox" name="shariff3UU_design[vertical]" ';
	if ( isset( $GLOBALS["shariff3UU_design"]["vertical"] ) ) echo checked( $GLOBALS["shariff3UU_design"]["vertical"], 1, 0 );
	echo ' value="1"><img src="'. $plugins_url .'/shariff/pictos/verticalBtns.png" align="top">';
}

// alignment
function shariff3UU_radio_align_render() {
	$options = $GLOBALS['shariff3UU_design'];
	if ( ! isset( $options['align'] ) ) $options['align'] = 'flex-start';
	echo '<div class="shariff_options-table"><div class="shariff_options-row">
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align]" value="flex-start" ' . checked( $options["align"], "flex-start", 0 ) . '>' . __( "left", "shariff3UU" ) . '</div>
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align]" value="center" ' .     checked( $options["align"], "center", 0 )     . '>' . __( "center", "shariff3UU" ) . '</div>
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align]" value="flex-end" ' .   checked( $options["align"], "flex-end", 0 )   . '>' . __( "right", "shariff3UU" ) . '</div>
	</div></div>';
}

// alignment widget
function shariff3UU_radio_align_widget_render() {
	$options = $GLOBALS['shariff3UU_design'];
	if ( ! isset( $options['align_widget'] ) ) $options['align_widget'] = 'flex-start';
	echo '<div class="shariff_options-table"><div class="shariff_options-row">
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align_widget]" value="flex-start" ' . checked( $options["align_widget"], "flex-start", 0 ) . '>' . __( "left", "shariff3UU" ) . '</div>
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align_widget]" value="center" ' .     checked( $options["align_widget"], "center", 0 )     . '>' . __( "center", "shariff3UU" ) . '</div>
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align_widget]" value="flex-end" ' .   checked( $options["align_widget"], "flex-end", 0 )   . '>' . __( "right", "shariff3UU" ) . '</div>
	</div></div>';
}

// headline
function shariff3UU_text_headline_render() {
	if ( isset( $GLOBALS["shariff3UU_design"]["headline"] ) ) {
		$headline = $GLOBALS["shariff3UU_design"]["headline"];
	}
	else {
		$headline = '';
	}
	echo '<input type="text" name="shariff3UU_design[headline]" value="' . esc_html( $headline ) . '" size="50" placeholder="' . __( "Share this post", "shariff3UU" ) . '">';
	echo __( '<p>Basic HTML as well as style and class attributes are allowed - e.g. <code>&lt;h3 class="shariff_headline"&gt;Share this post&lt;/h3&gt;</code></p>', "shariff3UU" );
}

// custom css
function shariff3UU_text_style_render() {
	if ( isset( $GLOBALS["shariff3UU_design"]["style"] ) ) {
		$style = $GLOBALS["shariff3UU_design"]["style"];
	}
	else {
		$style = '';
	}
	echo '<input type="text" name="shariff3UU_design[style]" value="' . esc_html($style) . '" size="50" placeholder="' . __( "More information in the FAQ.", "shariff3UU" ) . '">';
}

// advanced options

// description advanced options
function shariff3UU_advanced_section_callback(){
	echo __( 'This configures the advanced options of Shariff regarding specific services. If you are unsure about an option, take a look at the ', 'shariff3UU' );
	echo '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=shariff3uu&tab=help">';
	echo __( 'Help Section</a> and the ', 'shariff3UU' );
	echo '<a href="https://wordpress.org/support/plugin/shariff/" target="_blank">';
	echo __( 'Support Forum</a>.', 'shariff3UU' );
}

// info url
function shariff3UU_text_info_url_render() {
	if ( isset( $GLOBALS["shariff3UU_advanced"]["info_url"] ) ) {
		$info_url = $GLOBALS["shariff3UU_advanced"]["info_url"];
	}
	else {
		$info_url = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[info_url]" value="'. esc_html($info_url) .'" size="50" placeholder="http://ct.de/-2467514">';
}

// twitter via
function shariff3UU_text_twittervia_render() {
	if ( isset( $GLOBALS["shariff3UU_advanced"]["twitter_via"] ) ) {
		$twitter_via = $GLOBALS["shariff3UU_advanced"]["twitter_via"];
	}
	else {
		$twitter_via = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[twitter_via]" value="' . $twitter_via . '" size="50" placeholder="' . __( 'username', 'shariff3UU' ) . '">';
}

// flattr username
function shariff3UU_text_flattruser_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["flattruser"]) ) {
		$flattruser = $GLOBALS["shariff3UU_advanced"]["flattruser"];
	}
	else {
		$flattruser = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[flattruser]" value="'. $flattruser .'" size="50" placeholder="' . __( 'username', 'shariff3UU' ) . '">';
}

// patreon username
function shariff3UU_text_patreonid_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["patreonid"]) ) {
		$patreonid = $GLOBALS["shariff3UU_advanced"]["patreonid"];
	}
	else {
		$patreonid = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[patreonid]" value="'. $patreonid .'" size="50" placeholder="' . __( 'username', 'shariff3UU' ) . '">';
}

// paypal button id
function shariff3UU_text_paypalbuttonid_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["paypalbuttonid"]) ) {
		$paypalbuttonid = $GLOBALS["shariff3UU_advanced"]["paypalbuttonid"];
	}
	else {
		$paypalbuttonid = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[paypalbuttonid]" value="'. $paypalbuttonid .'" size="50" placeholder="' . __( '1ABCDEF23GH4I', 'shariff3UU' ) . '">';
}

// paypalme id
function shariff3UU_text_paypalmeid_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["paypalmeid"]) ) {
		$paypalmeid = $GLOBALS["shariff3UU_advanced"]["paypalmeid"];
	}
	else {
		$paypalmeid = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[paypalmeid]" value="'. $paypalmeid .'" size="50" placeholder="' . __( 'name', 'shariff3UU' ) . '">';
}

// bitcoin address
function shariff3UU_text_bitcoinaddress_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["bitcoinaddress"]) ) {
		$bitcoinaddress = $GLOBALS["shariff3UU_advanced"]["bitcoinaddress"];
	}
	else {
		$bitcoinaddress = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[bitcoinaddress]" value="'. $bitcoinaddress .'" size="50" placeholder="' . __( '1Ab2CdEfGhijKL34mnoPQRSTu5VwXYzaBcD', 'shariff3UU' ) . '">';
}

// rss feed
function shariff3UU_text_rssfeed_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["rssfeed"]) ) {
		$rssfeed = esc_url( $GLOBALS["shariff3UU_advanced"]["rssfeed"] );
	}
	else {
		$rssfeed = '';
	}
	$rssdefault = esc_url( get_bloginfo('rss_url') );
	echo '<input type="text" name="shariff3UU_advanced[rssfeed]" value="'. $rssfeed .'" size="50" placeholder="' . $rssdefault . '">';
}

// pinterest default image
function shariff3UU_text_default_pinterest_render() {
	$options = $GLOBALS["shariff3UU_advanced"];
	if ( ! isset( $options["default_pinterest"] ) ) $options["default_pinterest"] = '';
	echo '<div><input type="text" name="shariff3UU_advanced[default_pinterest]" value="' . $options["default_pinterest"] . '" id="image_url" class="regular-text"><input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="' . __( 'Choose image', 'shariff3UU' ) . '"></div>';
	echo '<script type="text/javascript">
	jQuery(document).ready(function($){
		$("#upload-btn").click(function(e) {
			e.preventDefault();
			var image = wp.media({
				title: "Choose image",
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on("select", function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get("selection").first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
				console.log(uploaded_image);
				var image_url = uploaded_image.toJSON().url;
				// Let"s assign the url value to the input field
				$("#image_url").val(image_url);
			});
		});
	});
	</script>';
}

// mailform options

// description mailform options
function shariff3UU_mailform_section_callback() {
	echo __( "The mail form can be completely disabled, if not needed. Otherwise, it is recommended to configure a default sender e-mail address from <u>your domain</u> that actually exists, to prevent spam filters from blocking the e-mails.", "shariff3UU" );
}

// disable mailform
function shariff3UU_checkbox_disable_mailform_render() {
	echo '<input type="checkbox" name="shariff3UU_mailform[disable_mailform]" ';
	if ( isset( $GLOBALS["shariff3UU_mailform"]["disable_mailform"] ) ) echo checked( $GLOBALS["shariff3UU_mailform"]["disable_mailform"], 1, 0 );
	echo ' value="1">';
}

// require sender e-mail address
function shariff3UU_checkbox_require_sender_render() {
	echo '<input type="checkbox" name="shariff3UU_mailform[require_sender]" ';
	if ( isset( $GLOBALS["shariff3UU_mailform"]["require_sender"] ) ) echo checked( $GLOBALS["shariff3UU_mailform"]["require_sender"], 1, 0 );
	echo ' value="1">';
}

// mailform language
function shariff3UU_select_mailform_language_render() {
	$options = $GLOBALS["shariff3UU_mailform"];
	if ( ! isset( $options["mailform_language"] ) ) $options["mailform_language"] = 'auto';
	echo '<select name="shariff3UU_mailform[mailform_language]" style="min-width:110px">
	<option value="auto" ' . selected( $options["mailform_language"], "auto", 0 ) . '>' . __( "auto", "shariff3UU") . '</option>
	<option value="EN" ' . selected( $options["mailform_language"], "EN", 0 ) . '>English</option>
	<option value="DE" ' . selected( $options["mailform_language"], "DE", 0 ) . '>Deutsch</option>
	<option value="FR" ' . selected( $options["mailform_language"], "FR", 0 ) . '>Français</option>
	<option value="IT" ' . selected( $options["mailform_language"], "IT", 0 ) . '>Italiano</option>';
}

// add post content
function shariff3UU_checkbox_mail_add_post_content_render() {
	echo '<input type="checkbox" name="shariff3UU_mailform[mail_add_post_content]" ';
	if ( isset( $GLOBALS["shariff3UU_mailform"]["mail_add_post_content"] ) ) echo checked( $GLOBALS["shariff3UU_mailform"]["mail_add_post_content"], 1, 0 );
	echo ' value="1">';
}

// sender name
function shariff3UU_text_mail_sender_name_render() {
	if ( isset( $GLOBALS["shariff3UU_mailform"]["mail_sender_name"] ) ) {
		$mail_sender_name = $GLOBALS["shariff3UU_mailform"]["mail_sender_name"];
	}
	else {
		$mail_sender_name = "";
	}
	// get blog title
	$blog_title = get_bloginfo( 'name' );
	echo '<input type="text" name="shariff3UU_mailform[mail_sender_name]" value="' . esc_html($mail_sender_name) . '" size="50" placeholder="' . $blog_title . '">';
}

// sender address
function shariff3UU_text_mail_sender_from_render() {
	if ( isset( $GLOBALS["shariff3UU_mailform"]["mail_sender_from"] ) ) {
		$mail_sender_from = $GLOBALS["shariff3UU_mailform"]["mail_sender_from"];
	}
	else {
		$mail_sender_from = "";
	}
	// get blog domain
	$blog_domain = get_bloginfo( 'url');
	// in case scheme relative URI is passed, e.g., //www.google.com/
	$input = trim($blog_domain, '/');
	// If scheme not included, prepend it
	if ( ! preg_match( '#^http(s)?://#', $input ) ) {
    	$input = 'http://' . $input;
	}
	$urlParts = parse_url($input);
	// remove www
	$domain = preg_replace('/^www\./', '', $urlParts['host']);
	echo '<input type="email" name="shariff3UU_mailform[mail_sender_from]" value="' . esc_html($mail_sender_from) . '" size="50" placeholder="wordpress@' . $domain .'">';
}

// statistic section

// description statistic options
function shariff3UU_statistic_section_callback(){
	echo __( 'This determines how share counts are handled by Shariff.', 'shariff3UU' );
	if ( isset( $GLOBALS["shariff3UU_statistic"]["external_host"] ) ) {
		echo __( ' <span style="color: red; font-weight: bold;">Warning:</span> You entered an external host! Therefore most options on this page have no effect. You need to configure them on the external server. Remember: This feature is still experimental!', 'shariff3UU' );
	}
}

// share counts
function shariff3UU_checkbox_backend_render() {
	// to check that the backend works
	// http://[your_host]/wp-content/plugins/shariff/backend/index.php?url=http%3A%2F%2F[your_host]
	// should give an array or "[ ]"

	// check PHP version
	if ( version_compare( PHP_VERSION, '5.4.0' ) < 1 ) {
		echo __( 'PHP-Version 5.4 or better is needed to enable the statistic functionality.', 'shariff3UU');
	}
	else {
		echo '<input type="checkbox" name="shariff3UU_statistic[backend]" ';
		if ( isset( $GLOBALS['shariff3UU_statistic']['backend'] ) ) {
			echo checked( $GLOBALS['shariff3UU_statistic']['backend'], 1, 0 );
		}
		echo ' value="1">';
	}
}

// Facebook App ID
function shariff3UU_text_fb_id_render() {
	if ( isset($GLOBALS["shariff3UU_statistic"]["fb_id"]) ) {
		$fb_id = $GLOBALS["shariff3UU_statistic"]["fb_id"];
	}
	else {
		$fb_id = '';
	}
	echo '<input type="text" name="shariff3UU_statistic[fb_id]" value="'. $fb_id .'" size="50" placeholder="1234567891234567">';
}

// Facebook App Secret
function shariff3UU_text_fb_secret_render() {
	if ( isset($GLOBALS["shariff3UU_statistic"]["fb_secret"]) ) {
		$fb_secret = $GLOBALS["shariff3UU_statistic"]["fb_secret"];
	}
	else {
		$fb_secret = '';
	}
	echo '<input type="text" name="shariff3UU_statistic[fb_secret]" value="'. $fb_secret .'" size="50" placeholder="123abc456def789123456789ghi12345">';
}

// ttl
function shariff3UU_number_ttl_render() {
	if ( isset($GLOBALS["shariff3UU_statistic"]["ttl"]) ) {
		$ttl = $GLOBALS["shariff3UU_statistic"]["ttl"];
	}
	else {
		$ttl = '';
	}
	echo '<input type="number" name="shariff3UU_statistic[ttl]" value="'. $ttl .'" maxlength="4" min="60" max="7200" placeholder="60" style="width: 75px">';
}

// disable services
function shariff3UU_multiplecheckbox_disable_services_render() {
	// Facebook
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][facebook]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['facebook'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['facebook'], 1, 0 );
	echo ' value="1">' . __('Facebook', 'shariff3UU') . '</p>';

	// Twitter
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][twitter]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['twitter'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['twitter'], 1, 0 );
	echo ' value="1">' . __('OpenShareCount (Twitter)', 'shariff3UU') . '</p>';

	// GooglePlus
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][googleplus]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['googleplus'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['googleplus'], 1, 0 );
	echo ' value="1">' . __('GooglePlus', 'shariff3UU') . '</p>';

	// Pinterest
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][pinterest]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['pinterest'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['pinterest'], 1, 0 );
	echo ' value="1">' . __('Pinterest', 'shariff3UU') . '</p>';

	// Xing
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][xing]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['xing'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['xing'], 1, 0 );
	echo ' value="1">' . __('Xing', 'shariff3UU') . '</p>';

	// LinkedIn
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][linkedin]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['linkedin'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['linkedin'], 1, 0 );
	echo ' value="1">' . __('LinkedIn', 'shariff3UU') . '</p>';

	// Tumblr
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][tumblr]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['tumblr'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['tumblr'], 1, 0 );
	echo ' value="1">' . __('Tumblr', 'shariff3UU') . '</p>';

	// VK
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][vk]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['vk'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['vk'], 1, 0 );
	echo ' value="1">' . __('VK', 'shariff3UU') . '</p>';

	// StumbleUpon
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][stumbleupon]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['stumbleupon'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['stumbleupon'], 1, 0 );
	echo ' value="1">' . __('StumbleUpon', 'shariff3UU') . '</p>';

	// Reddit
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][reddit]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['reddit'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['reddit'], 1, 0 );
	echo ' value="1">' . __('Reddit', 'shariff3UU') . '</p>';

	// AddThis
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][addthis]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['addthis'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['addthis'], 1, 0 );
	echo ' value="1">' . __('AddThis', 'shariff3UU') . '</p>';

	// Flattr
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][flattr]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['flattr'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['flattr'], 1, 0 );
	echo ' value="1">' . __('Flattr', 'shariff3UU') . '</p>';
}

// external host
function shariff3UU_text_external_host_render(){
	if ( isset( $GLOBALS["shariff3UU_statistic"]["external_host"] ) ) {
		$external_host = $GLOBALS["shariff3UU_statistic"]["external_host"];
	}
	else {
		$external_host = '';
	}
	echo '<input type="text" name="shariff3UU_statistic[external_host]" value="' . esc_html( $external_host ) . '" size="50" placeholder="'. plugins_url() .'/shariff/">';
	echo '<p>' . __( 'Warning: This is an <strong>experimental</strong> feature. Please read the <a href="https://wordpress.org/plugins/shariff/faq/" target="_blank">Frequently Asked Questions (FAQ)</a>.', 'shariff3UU' ) . '</p>';
	echo '<p>' . __( 'Please check, if you have to add this domain to the array $SHARIFF_FRONTENDS on the external server.', 'shariff3UU' ) . '</p>';
}

// help section

// description advanced options
function shariff3UU_help_section_callback() {
	echo __( '<p>The WordPress plugin "Shariff Wrapper" has been developed by <a href="http://www.datenverwurstungszentrale.com" target="_blank">3UU</a> and <a href="https://www.jplambeck.de" target=_blank">JP</a> in order to help protect the privacy of your visitors. It is based on the original Shariff buttons developed by the German computer magazin <a href="http://ct.de/shariff" target="_blank">c\'t</a> that fullfill the strict data protection laws in Germany. If you need any help with the plugin, take a look at the <a href="https://wordpress.org/plugins/shariff/faq/" target="_blank">Frequently Asked Questions (FAQ)</a> and the <a href="https://wordpress.org/support/plugin/shariff" target="_blank">Support Forum</a>. For up to date news about the plugin you can also follow <a href="https://twitter.com/jplambeck" target=_blank">@jplambeck</a> on Twitter.</p>', 'shariff3UU' );
	echo __( '<p>If you contact us about a problem with the share counts, please <u>always</u> include the information provided in the', 'shariff3UU' );
	echo ' <a href="options-general.php?page=shariff3uu&tab=basic">';
	echo __( 'status section</a>! This will help to speed up the process.</p>', 'shariff3UU' );
	echo '<p>' . __( 'If you enjoy our plugin, please consider writing a review about it on ', 'shariff3UU' );
	echo '<a href="https://wordpress.org/support/view/plugin-reviews/shariff" target="_blank">wordpress.org</a>';
	echo __( '. If you want to support us financially, you can donate via ', 'shariff3UU' );
	echo '<a href="http://folge.link/?bitcoin=1Ritz1iUaLaxuYcXhUCoFhkVRH6GWiMTP" target="_blank">Bitcoin</a> ';
	echo __( 'and', 'shariff3UU' );
	echo ' <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5BASYVM96PZ3L" target="_blanK">PayPal</a>';
	echo __( '. Thank you!', 'shariff3UU' );
	echo '</p>';

	echo __( '<p>This is a list of all available options for the <code>[shariff]</code> shortcode:</p>', 'shariff3UU' );
	// shortcode table
	echo '<div class="shariff_shortcode_table">';
		// head
		echo '<div class="shariff_shortcode_row_head">';
			echo '<div class="shariff_shortcode_cell_name-option">' . __( 'Name', 'shariff3UU' ) . '</div>';
			echo '<div class="shariff_shortcode_cell_name-option">' . __( 'Options', 'shariff3UU' ) . '</div>';
			echo '<div class="shariff_shortcode_cell_default">' . __( 'Default', 'shariff3UU' ) . '</div>';
			echo '<div class="shariff_shortcode_cell_example">' . __( 'Example', 'shariff3UU' ) . '</div>';
			echo '<div class="shariff_shortcode_cell_description">' . __( 'Description', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// services
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">services</div>';
			echo '<div class="shariff_shortcode_cell">facebook<br>twitter<br>googleplus<br>whatsapp<br>threema<br>pinterest<br>linkedin<br>xing<br>reddit<br>stumbleupon<br>tumblr<br>vk<br>diaspora<br>addthis<br>flattr<br>patreon<br>paypal<br>paypalme<br>bitcoin<br>mailform<br>mailto<br>printer<br>info<br>rss</div>';
			echo '<div class="shariff_shortcode_cell">twitter|facebook|googleplus|info</div>';
			echo '<div class="shariff_shortcode_cell">[shariff theme="facebook|twitter|mailform"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Determines which buttons to show and in which order.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// backend
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">backend</div>';
			echo '<div class="shariff_shortcode_cell">on<br>off</div>';
			echo '<div class="shariff_shortcode_cell">off</div>';
			echo '<div class="shariff_shortcode_cell">[shariff backend="on"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Enables share counts on the buttons.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// theme
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">theme</div>';
			echo '<div class="shariff_shortcode_cell">default<br>color<br>grey<br>white<br>round</div>';
			echo '<div class="shariff_shortcode_cell">default</div>';
			echo '<div class="shariff_shortcode_cell">[shariff theme="round"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Determines the main design of the buttons.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// button size
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">buttonsize</div>';
			echo '<div class="shariff_shortcode_cell">big<br>small</div>';
			echo '<div class="shariff_shortcode_cell">big</div>';
			echo '<div class="shariff_shortcode_cell">[shariff buttonsize="small"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Small reduces the size of all buttons by 30%, regardless of theme.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// orientation
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">orientation</div>';
			echo '<div class="shariff_shortcode_cell">horizontal<br>vertical</div>';
			echo '<div class="shariff_shortcode_cell">horizontal</div>';
			echo '<div class="shariff_shortcode_cell">[shariff orientation="vertical"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Changes the orientation of the buttons.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// language
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">language</div>';
			echo '<div class="shariff_shortcode_cell">da, de, en, es, fi, fr, hr, hu, it, ja, ko, nl, no, pl, pt, ro, ru, sk, sl, sr, sv, tr, zh</div>';
			echo '<div class="shariff_shortcode_cell">automatically selected by the browser</div>';
			echo '<div class="shariff_shortcode_cell">[shariff lang="de"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Changes the language of the share buttons.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// headline
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">headline</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">[shariff headline="&lt;hr style=\'margin:20px 0\'&gt;&lt;p&gt;' . __( 'Please share this post:', 'shariff3UU' ) . '&lt;/p&gt;"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Adds a headline above the Shariff buttons. Basic HTML as well as style and class attributes can be used. To remove a headline set on the plugins options page use headline="".', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// twitter_via
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">twitter_via</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">[shariff twitter_via="your_twittername"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Sets the Twitter via tag.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// flattruser
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">flattruser</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">[shariff flattruser="your_username"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Sets the Flattr username.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// patreonid
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">patreonid</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">[shariff patreonid="your_username"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Sets the Patreon username.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// paypalbuttonid
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">paypalbuttonid</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">[shariff paypalbuttonid="hosted_button_id"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Sets the PayPal hosted button ID.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// paypalmeid
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">paypalmeid</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">[shariff paypalmeid="name"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Sets the PayPal.Me ID. Default amount can be added with a / e.g. name/25.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// bitcoinaddress
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">bitcoinaddress</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">[shariff bitcoinaddress="bitcoin_address"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Sets the bitcoin address.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// media
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">media</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'The post featured image or the first image of the post.</div>', 'shariff3UU' );
			echo '<div class="shariff_shortcode_cell">[shariff media="http://www.mydomain.com/image.jpg"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Determines the default image to share for Pinterest, if no other usable image is found.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// info_url
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">info_url</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">http://ct.de/-2467514</div>';
			echo '<div class="shariff_shortcode_cell">[shariff info_url="http://www.mydomain.com/shariff-buttons"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Sets a custom link for the info button.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// url
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">url</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'The url of the current post or page.</div>', 'shariff3UU' );
			echo '<div class="shariff_shortcode_cell">[shariff url="http://www.mydomain.com/somepost"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Changes the url to share. Only for special use cases.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// title
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">title</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'The title of the current post or page.</div>', 'shariff3UU' );
			echo '<div class="shariff_shortcode_cell">' . __( '[shariff title="My Post Title"]</div>', 'shariff3UU' );
			echo '<div class="shariff_shortcode_cell">' . __( 'Changes the title to share. Only for special use cases.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// rssfeed
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">rssfeed</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'http://www.mydomain.com/feed/rss/</div>', 'shariff3UU' );
			echo '<div class="shariff_shortcode_cell">[shariff rssfeed="http://www.mydomain.com/feed/rss2/"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Changes the rss feed url to another feed.', 'shariff3UU' ) . '</div>';
		echo '</div>';

	echo '</div>';
}

// status section

// check services
function shariff3UU_status_section_callback() {
	// options
	$shariff3UU_statistic = $GLOBALS["shariff3UU_statistic"];
	// status table
	echo '<div class="shariff_status-main-table">';
	// statistic row
	echo '<div class="shariff_status-row">';
	echo '<div class="shariff_status-first-cell">' . __( 'Statistic:', 'shariff3UU' ) . '</div>';
	// check if statistic is enabled
	if( ! isset( $shariff3UU_statistic['backend'] ) ) {
		// statistic disabled message
		echo '<div class="shariff_status-table">';
		echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-disabled">' . __( 'Disabled', 'shariff3UU' ) . '</span></div></div>';
		echo '</div>';
		// end statistic row, if statistic is disabled
		echo '</div>';
	}
	else {
		// check if services produce error messages
		$post_url  = urlencode( esc_url( get_bloginfo('url') ) );
		$post_url2 = esc_url( get_bloginfo('url') );
		$backend_services_url = substr( plugin_dir_path( __FILE__ ), 0, -6) . 'backend/services/';

		// temporarily removed flattr due to ongoing problems with the flattr api
		$services = array( 'facebook', 'twitter', 'googleplus', 'pinterest', 'linkedin', 'xing', 'reddit', 'stumbleupon', 'tumblr', 'vk', 'addthis' );

		// start testing services
		foreach ( $services as $service ) {
			if ( ! isset ( $shariff3UU_statistic["disable"][ $service ] ) || ( isset ( $shariff3UU_statistic["disable"][ $service ] ) && $shariff3UU_statistic["disable"][ $service ] == 0 ) ) {
				include ( $backend_services_url . $service . '.php' );
				if ( ! isset ( $share_counts[ $service ] ) ) {
					$service_errors[ $service ] = $$service;
				}
			}
		}

		// status output
		if ( ! isset( $service_errors ) ) {
			// statistic working message
			echo '<div class="shariff_status-cell">';
				// working message table
				echo '<div class="shariff_status-table">';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-ok">' . __( 'OK', 'shariff3UU' ) . '</span></div></div>';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'No error messages.', 'shariff3UU' ) . '</div></div>';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . '</div></div>';
				echo '</div>';
			echo '</div>';
			// end statistic row, if working correctly
			echo '</div>';
		}
		else {
			// statistic error message
			echo '<div style="display: table-cell">';
				// error message table
				echo '<div class="shariff_status-table">';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-error">' . __( 'Error', 'shariff3UU' ) . '</span></div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Backend error.', 'shariff3UU' ) . '</div></div>';
					foreach( $service_errors as $service => $error ) {
						echo '<div class="shariff_status-row"><div class="shariff_status-cell">';
    					echo ucfirst( $service ) . '-Error! Message: ' . esc_html( $error );
    					echo '</div></div>';
					}
				echo '</div>';
			echo '</div>';
			// end statistic row, if not working correctly
			echo '</div>';
		}
		// Facebook row
		echo '<div class="shariff_status-row">';
		echo '<div class="shariff_status-cell">' . __( 'Facebook:', 'shariff3UU' ) . '</div>';
		// check if Facebook is responding correctly (no rate limits actice, etc.)
		$blog_url = urlencode( esc_url( get_bloginfo('url') ) );
		$facebook = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/fql?q=SELECT%20total_count%20FROM%20link_stat%20WHERE%20url="' . $blog_url . '"' ) ) );
		$facebook = json_decode( $facebook, true );
		if ( isset( $facebook['data']['0']['total_count'] ) ) {
			// Facebook working message
			echo '<div class="shariff_status-cell">';
				// working message table
				echo '<div style="display: table">';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-ok">' . __( 'OK', 'shariff3UU' ) . '</span></div></div>';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Current share count for ', 'shariff3UU' ) . urldecode( $blog_url ) . ': ' . absint( $facebook['data']['0']['total_count'] ) . '</div></div>';
				echo '</div>';
			echo '</div>';
			// end Facebook row, if working correctly
			echo '</div>';
		}
		elseif ( isset( $facebook['error']['message'] ) ) {
			// Facebook API error message
			echo '<div class="shariff_status-cell">';
				// error message table
				echo '<div class="shariff_status-table">';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-error">' . __( 'Error', 'shariff3UU' ) . '</span></div></div>';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Message:', 'shariff3UU' ) . '</div><div style="display: table-cell">' . esc_html( $facebook['error']['message'] ) . '</div></div>';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Type:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $facebook['error']['type'] ) . '</div></div>';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Code:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $facebook['error']['code'] ) . '</div></div>';
				echo '</div>';
			echo '</div>';
			// end Facebook row, if not working correctly
			echo '</div>';
		}
		// Facebook Graph API ID row
		echo '<div class="shariff_status-row">';
		echo '<div class="shariff_status-cell">' . __( 'Facebook API (ID):', 'shariff3UU' ) . '</div>';
		// credentials provided?
		if ( ! isset( $GLOBALS['shariff3UU_statistic']['fb_id'] ) || ! isset( $GLOBALS['shariff3UU_statistic']['fb_secret'] ) ) {
			// no credentials
			echo '<div class="shariff_status-cell">';
				echo '<div class="shariff_status-table">';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-disabled">' . __( 'Not configured', 'shariff3UU' ) . '</span></div></div>';
				echo '</div>';
			echo '</div>';
			// end Graph API ID row, if not configured
			echo '</div>';
		}
		else {
			// app_id and secret
			$fb_app_id = $shariff3UU_statistic['fb_id'];
			$fb_app_secret = $shariff3UU_statistic['fb_secret'];
			// check if Facebook Graph API ID is responding correctly (no rate limits actice, credentials ok, etc.)
			$blog_url = urlencode( esc_url( get_bloginfo('url') ) );
			// get fb access token
			$fb_token = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/oauth/access_token?client_id=' .  $fb_app_id . '&client_secret=' . $fb_app_secret . '&grant_type=client_credentials' ) ) );
			// use token to get share counts
			$facebookID = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/v2.2/?id=' . $blog_url . '&' . $fb_token ) ) );
			$facebookID = json_decode( $facebookID, true );
			$fb_token = json_decode( $fb_token, true );
			// is it working?
			if ( isset( $facebookID['share']['share_count'] ) ) {
				// Facebook Graph API ID working message
				echo '<div class="shariff_status-cell">';
					// working message table
					echo '<div style="display: table">';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-ok">' . __( 'OK', 'shariff3UU' ) . '</span></div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Current share count for ', 'shariff3UU' ) . urldecode( $blog_url ) . ': ' . absint( $facebookID['share']['share_count'] ) . '</div></div>';
					echo '</div>';
				echo '</div>';
				// end Facebook Graph API ID row, if working correctly
				echo '</div>';
			}
			elseif ( isset( $facebookID['error']['message'] ) ) {
				// Facebook Graph API ID error message
				echo '<div class="shariff_status-cell">';
					// error message table
					echo '<div class="shariff_status-table">';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-error">' . __( 'Error', 'shariff3UU' ) . '</span></div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Message:', 'shariff3UU' ) . '</div><div style="display: table-cell">' . esc_html( $facebookID['error']['message'] ) . '</div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Type:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $facebookID['error']['type'] ) . '</div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Code:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $facebookID['error']['code'] ) . '</div></div>';
					echo '</div>';
				echo '</div>';
				// end Facebook Graph API ID row, if not working correctly
				echo '</div>';
			}
			elseif ( isset( $fb_token['error']['message'] ) ) {
				// Facebook Graph API ID auth error message
				echo '<div class="shariff_status-cell">';
					// error message table
					echo '<div class="shariff_status-table">';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-error">' . __( 'Error', 'shariff3UU' ) . '</span></div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Message:', 'shariff3UU' ) . '</div><div style="display: table-cell">' . esc_html( $fb_token['error']['message'] ) . '</div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Type:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $fb_token['error']['type'] ) . '</div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Code:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $fb_token['error']['code'] ) . '</div></div>';
					echo '</div>';
				echo '</div>';
				// end Facebook Graph API ID row, if not working correctly bc of auth error
				echo '</div>';
			}
		}
	}

	// GD needed for QR codes of the Bitcoin links
	echo '<div class="shariff_status-row">';
	echo '<div class="shariff_status-cell">' . __( 'GD Library:', 'shariff3UU' ) . '</div>';
	// working message
	if ( function_exists( 'gd_info' ) ) {
		$tmpGDinfo = gd_info();
		echo '<div class="shariff_status-cell">';
			echo '<div style="display: table">';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-ok">' . __( 'OK', 'shariff3UU' ) . '</span></div></div>';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell">Version: ' . $tmpGDinfo["GD Version"] . '</div></div>';
			echo '</div>';
		echo '</div>';
	}
	else {
		echo '<div class="shariff_status-cell">';
			echo '<div style="display: table">';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-error">' . __( 'Error', 'shariff3UU' ) . '</span></div></div>';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'The GD Library is not installed on this server. This is only needed for the QR codes, if your are using the bitcoin button.', 'shariff3UU' ) . '</div></div>';
			echo '</div>';
		echo '</div>';
	}
	echo '</div>';

	// end status table
	echo '</div>';
}

// render the plugin option page
function shariff3UU_options_page() {
	// the <div> with the class "wrap" makes sure that admin messages are displayed below the title and not above
	echo '<div class="wrap">';

	// title
	echo '<h2>Shariff ' . $GLOBALS["shariff3UU_basic"]["version"] . '</h2>';

	// start the form
	echo '<form class="shariff" action="options.php" method="post">';

	// hidden version entry, so it will get saved upon updating the options
	echo '<input type="hidden" name="shariff3UU_basic[version]" value="' . $GLOBALS["shariff3UU_basic"]["version"] . '">';

	// determine active tab
	if ( isset( $_GET['tab'] ) ) {
		$active_tab = $_GET['tab'];
	}
	else {
		$active_tab = 'basic';
	}

	// tabs
	echo '<h2 class="nav-tab-wrapper">';
		// basic
		echo '<a href="?page=shariff3uu&tab=basic" class="nav-tab ';
		if ( $active_tab == 'basic' ) echo 'nav-tab-active';
		echo '">' . __( 'Basic', 'shariff3UU' ) . '</a>';
		// design
		echo '<a href="?page=shariff3uu&tab=design" class="nav-tab ';
		if ( $active_tab == 'design' ) echo 'nav-tab-active';
		echo '">' . __( 'Design', 'shariff3UU' ) . '</a>';
		// advanced
		echo '<a href="?page=shariff3uu&tab=advanced" class="nav-tab ';
		if ( $active_tab == 'advanced' ) echo 'nav-tab-active';
		echo '">' . __( 'Advanced', 'shariff3UU' ) . '</a>';
		// mailform
		echo '<a href="?page=shariff3uu&tab=mailform" class="nav-tab ';
		if ( $active_tab == 'mailform' ) echo 'nav-tab-active';
		echo '">' . __( 'Mail Form', 'shariff3UU' ) . '</a>';
		// statistic
		echo '<a href="?page=shariff3uu&tab=statistic" class="nav-tab ';
		if ( $active_tab == 'statistic' ) echo 'nav-tab-active';
		echo '">' . __( 'Statistic', 'shariff3UU' ) . '</a>';
		// help
		echo '<a href="?page=shariff3uu&tab=help" class="nav-tab ';
		if ( $active_tab == 'help' ) echo 'nav-tab-active';
		echo '">' . __( 'Help', 'shariff3UU' ) . '</a>';
		// status
		echo '<a href="?page=shariff3uu&tab=status" class="nav-tab ';
		if ( $active_tab == 'status' ) echo 'nav-tab-active';
		echo '">' . __( 'Status', 'shariff3UU' ) . '</a>';
	echo '</h2>';

	// content of tabs
	if ( $active_tab == 'basic' ) {
		settings_fields( 'basic' );
		do_settings_sections( 'basic' );
		submit_button();
	}
	elseif ( $active_tab == 'design' ) {
		settings_fields( 'design' );
		do_settings_sections( 'design' );
		submit_button();
	}
	elseif ( $active_tab == 'advanced' ) {
		settings_fields( 'advanced' );
		do_settings_sections( 'advanced' );
		submit_button();
	}
	elseif ( $active_tab == 'mailform' ) {
		settings_fields( 'mailform' );
		do_settings_sections( 'mailform' );
		submit_button();
	}
	elseif ( $active_tab == 'statistic' ) {
		settings_fields( 'statistic' );
		do_settings_sections( 'statistic' );
		submit_button();
	}
	elseif ( $active_tab == 'help' ) {
		settings_fields( 'help' );
		do_settings_sections( 'help' );
	}
	elseif ( $active_tab == 'status' ) {
		settings_fields( 'status' );
		do_settings_sections( 'status' );
    }

	// end of form
	echo '</form>';
} // end of plugin option page

?>
