<?php
// Will be included in the shariff.php only, when an admin is logged in.

// prevent direct calls to admin_menu.php
if ( ! class_exists('WP') ) { die(); }

// the admin page
add_action( 'admin_menu', 'shariff3UU_add_admin_menu' );
add_action( 'admin_init', 'shariff3UU_options_init' );
add_action( 'init', 'shariff_init_locale' );

// scripts and styles for admin pages e.g. info notice
function shariff3UU_admin_style( $hook ) {
	// js for admin notice - needed on _ALL_ admin pages (as long as WordPress does not handle dismiss clicks)
	wp_enqueue_script( 'shariff_notice', plugins_url( '../js/shariff-notice.js', __FILE__ ), array( 'jquery' ), '1.0', true  );
	// scripts only needed on our plugin options page - no need to load them on _ALL_ admin pages
	if ( $hook == 'settings_page_shariff3uu' ) {
		// scripts for pinterest default image media uploader
		wp_enqueue_media();
		wp_register_script( 'shariff_mediaupload', plugins_url( '../js/shariff-media.js', __FILE__ ), array( 'jquery' ), '1.0', true  );
		$translation_array = array( 'choose_image' => __( 'Choose image', 'shariff' ) );
		wp_localize_script( 'shariff_mediaupload', 'shariff_media', $translation_array );
		wp_enqueue_script( 'shariff_mediaupload' );
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
	add_settings_section( 'shariff3UU_basic_section', __( 'Basic options', 'shariff' ),
		'shariff3UU_basic_section_callback', 'basic' );

	// services
	add_settings_field( 'shariff3UU_text_services', '<div style="width:450px">' . __( 'Enable the following services in the provided order:', 'shariff' ) . '</div>',
		'shariff3UU_text_services_render', 'basic', 'shariff3UU_basic_section' );

	// add after
	add_settings_field( 'shariff3UU_multiplecheckbox_add_after', __( 'Add the Shariff buttons <u>after</u> all:', 'shariff' ),
		'shariff3UU_multiplecheckbox_add_after_render', 'basic', 'shariff3UU_basic_section' );

	// add before
	add_settings_field( 'shariff3UU_checkbox_add_before', __( 'Add the Shariff buttons <u>before</u> all:', 'shariff' ),
		'shariff3UU_multiplecheckbox_add_before_render', 'basic', 'shariff3UU_basic_section' );

	// disable on protected posts
	add_settings_field( 'shariff3UU_checkbox_disable_on_protected', __( 'Disable the Shariff buttons on password protected posts.', 'shariff' ),
		'shariff3UU_checkbox_disable_on_protected_render', 'basic', 'shariff3UU_basic_section' );

	// disable outside of loop
	add_settings_field( 'shariff3UU_checkbox_disable_outside_loop', __( 'Disable the Shariff buttons outside of the main loop.', 'shariff' ),
		'shariff3UU_checkbox_disable_outside_loop_render', 'basic', 'shariff3UU_basic_section' );

	// second tab - design

	// register second tab (design) settings and call sanitize function
	register_setting( 'design', 'shariff3UU_design', 'shariff3UU_design_sanitize' );

	// second tab - design options
	add_settings_section( 'shariff3UU_design_section', __( 'Design options', 'shariff' ),
		'shariff3UU_design_section_callback', 'design' );

	// button language
	add_settings_field( 'shariff3UU_select_language', '<div style="width:450px">' . __( 'Shariff button language:', 'shariff' ) . '</div>',
		'shariff3UU_select_language_render', 'design', 'shariff3UU_design_section' );

	// theme
	add_settings_field( 'shariff3UU_radio_theme', __( 'Shariff button design:', 'shariff' ),
		'shariff3UU_radio_theme_render', 'design', 'shariff3UU_design_section' );

	// button size
	add_settings_field( 'shariff3UU_checkbox_buttonsize', __( 'Button size:', 'shariff' ),
		'shariff3UU_checkbox_buttonsize_render', 'design', 'shariff3UU_design_section' );

	// button stretch
	add_settings_field( 'shariff3UU_checkbox_buttonsstretch', __( 'Stretch buttons horizontally to full width.', 'shariff' ),
		'shariff3UU_checkbox_buttonstretch_render', 'design', 'shariff3UU_design_section' );

	// border radius
	add_settings_field( 'shariff3UU_number_borderradius', __( 'Border radius for the round theme (1-50):', 'shariff' ),
		'shariff3UU_number_borderradius_render', 'design', 'shariff3UU_design_section' );

	// custom main color
	add_settings_field( 'shariff3UU_text_maincolor', __( 'Custom main color for <b>all</b> buttons (hexadecimal):', 'shariff' ),
		'shariff3UU_text_maincolor_render', 'design', 'shariff3UU_design_section' );

	// custom secondary color
	add_settings_field( 'shariff3UU_text_secondarycolor', __( 'Custom secondary color for <b>all</b> buttons (hexadecimal):', 'shariff' ),
		'shariff3UU_text_secondarycolor_render', 'design', 'shariff3UU_design_section' );

	// vertical
	add_settings_field( 'shariff3UU_checkbox_vertical', __( 'Shariff button orientation <b>vertical</b>.', 'shariff' ),
		'shariff3UU_checkbox_vertical_render', 'design', 'shariff3UU_design_section' );

	// alignment option
	add_settings_field( 'shariff3UU_radio_align', __( 'Alignment of the Shariff buttons:', 'shariff' ),
		'shariff3UU_radio_align_render', 'design', 'shariff3UU_design_section' );

	// alignment option for the widget
	add_settings_field( 'shariff3UU_radio_align_widget', __( 'Alignment of the Shariff buttons in the widget:', 'shariff' ),
		'shariff3UU_radio_align_widget_render', 'design', 'shariff3UU_design_section' );

	// headline
	add_settings_field( 'shariff3UU_text_headline', __( 'Headline above all Shariff buttons:', 'shariff' ),
		'shariff3UU_text_headline_render', 'design', 'shariff3UU_design_section' );

	// custom css
	add_settings_field( 'shariff3UU_text_style', __( 'Custom CSS <u>attributes</u> for the container <u>around</u> Shariff:', 'shariff' ),
		'shariff3UU_text_style_render', 'design', 'shariff3UU_design_section' );
		
	// custom css class
	add_settings_field( 'shariff3UU_text_cssclass', __( 'Custom CSS <u>class</u> for the container <u>around</u> Shariff:', 'shariff' ),
		'shariff3UU_text_cssclass_render', 'design', 'shariff3UU_design_section' );

	// hide until css loaded
	add_settings_field( 'shariff3UU_checkbox_hideuntilcss', __( 'Hide buttons until page is fully loaded.', 'shariff' ),
		'shariff3UU_checkbox_hideuntilcss_render', 'design', 'shariff3UU_design_section' );
		
	// open in popup
	add_settings_field( 'shariff3UU_checkbox_popup', __( 'Open links in a popup (requires JavaScript).', 'shariff' ),
		'shariff3UU_checkbox_popup_render', 'design', 'shariff3UU_design_section' );

	// third tab - advanced

	// register third tab (advanced) settings and call sanitize function
	register_setting( 'advanced', 'shariff3UU_advanced', 'shariff3UU_advanced_sanitize' );

	// third tab - advanced options
	add_settings_section( 'shariff3UU_advanced_section', __( 'Advanced options', 'shariff' ),
		'shariff3UU_advanced_section_callback', 'advanced' );

	// info url
	add_settings_field(
		'shariff3UU_text_info_url', '<div style="width:450px">' . __( 'Custom link for the info button:', 'shariff' ) . '</div>',
		'shariff3UU_text_info_url_render', 'advanced', 'shariff3UU_advanced_section' );

	// twitter via
	add_settings_field(
		'shariff3UU_text_twittervia', __( 'Twitter username for the via tag:', 'shariff' ),
		'shariff3UU_text_twittervia_render', 'advanced', 'shariff3UU_advanced_section' );

	// flattr username
	add_settings_field(
		'shariff3UU_text_flattruser', __( 'Flattr username:', 'shariff' ),
		'shariff3UU_text_flattruser_render', 'advanced', 'shariff3UU_advanced_section' );

	// patreon username
	add_settings_field(
		'shariff3UU_text_patreonid', __( 'Patreon username:', 'shariff' ),
		'shariff3UU_text_patreonid_render', 'advanced', 'shariff3UU_advanced_section' );

	// paypal button id
	add_settings_field(
		'shariff3UU_text_paypalbuttonid', __( 'PayPal hosted button ID:', 'shariff' ),
		'shariff3UU_text_paypalbuttonid_render', 'advanced', 'shariff3UU_advanced_section' );

	// paypalme id
	add_settings_field(
		'shariff3UU_text_paypalmeid', __( 'PayPal.Me ID:', 'shariff' ),
		'shariff3UU_text_paypalmeid_render', 'advanced', 'shariff3UU_advanced_section' );

	// bitcoin address
	add_settings_field(
		'shariff3UU_text_bitcoinaddress', __( 'Bitcoin address:', 'shariff' ),
		'shariff3UU_text_bitcoinaddress_render', 'advanced', 'shariff3UU_advanced_section' );

	// rss feed
	add_settings_field(
		'shariff3UU_text_rssfeed', __( 'RSS feed:', 'shariff' ),
		'shariff3UU_text_rssfeed_render', 'advanced', 'shariff3UU_advanced_section' );

	// default image for pinterest
	add_settings_field( 'shariff3UU_text_default_pinterest', __( 'Default image for Pinterest:', 'shariff' ),
		'shariff3UU_text_default_pinterest_render', 'advanced', 'shariff3UU_advanced_section' );
		
	// shortcode priority
	add_settings_field( 'shariff3UU_number_shortcodeprio', __( 'Shortcode priority:', 'shariff' ),
		'shariff3UU_number_shortcodeprio_render', 'advanced', 'shariff3UU_advanced_section' );

	// fourth tab - mailform

	// register fourth tab (mailform) settings and call sanitize function
	register_setting( 'mailform', 'shariff3UU_mailform', 'shariff3UU_mailform_sanitize' );

	// fourth tab - mailform options
	add_settings_section( 'shariff3UU_mailform_section', __( 'Mail form options', 'shariff' ),
		'shariff3UU_mailform_section_callback', 'mailform' );

	// disable mailform
	add_settings_field(
		'shariff3UU_checkbox_disable_mailform', '<div style="width:450px">' . __( 'Disable the mail form functionality.', 'shariff' ) . '</div>',
		'shariff3UU_checkbox_disable_mailform_render', 'mailform', 'shariff3UU_mailform_section' );

	// require sender e-mail address
	add_settings_field(
		'shariff3UU_checkbox_require_sender', __( 'Require sender e-mail address.', 'shariff' ),
		'shariff3UU_checkbox_require_sender_render', 'mailform', 'shariff3UU_mailform_section' );

	// mailform language
	add_settings_field(
		'shariff3UU_select_mailform_language', __( 'Mailform language:', 'shariff' ),
		'shariff3UU_select_mailform_language_render', 'mailform', 'shariff3UU_mailform_section' );

	// add content of the post to e-mails
	add_settings_field(
		'shariff3UU_checkbox_mail_add_post_content', __( 'Add the post content to the e-mail body.', 'shariff' ),
		'shariff3UU_checkbox_mail_add_post_content_render', 'mailform', 'shariff3UU_mailform_section' );

	// mail sender name
	add_settings_field(
		'shariff3UU_text_mail_sender_name', __( 'Default sender name:', 'shariff' ),
		'shariff3UU_text_mail_sender_name_render', 'mailform', 'shariff3UU_mailform_section' );

	// mail sender address
	add_settings_field(
		'shariff3UU_text_mail_sender_from', __( 'Default sender e-mail address:', 'shariff' ),
		'shariff3UU_text_mail_sender_from_render', 'mailform', 'shariff3UU_mailform_section' );

	// use anchor
	add_settings_field(
		'shariff3UU_checkbox_mailform_anchor', __( 'Use an anchor to jump to the mail form.', 'shariff' ),
		'shariff3UU_checkbox_mailform_anchor_render', 'mailform', 'shariff3UU_mailform_section' );
		
	// wait timer
	add_settings_field(
		'shariff3UU_number_mailform_wait', __( 'Time to wait until the same IP address is allowed to submit the form again (in seconds):', 'shariff' ),
		'shariff3UU_number_mailform_wait_render', 'mailform', 'shariff3UU_mailform_section' );

	// fifth tab - statistic

	// register fifth tab (statistic) settings and call sanitize function
	register_setting( 'statistic', 'shariff3UU_statistic', 'shariff3UU_statistic_sanitize' );

	// fifth tab (statistic)
	add_settings_section( 'shariff3UU_statistic_section', __( 'Statistic', 'shariff' ),
		'shariff3UU_statistic_section_callback', 'statistic' );

	// statistic
	add_settings_field( 'shariff3UU_checkbox_backend', '<div style="width:450px">' . __( 'Enable statistic.', 'shariff' ) . '</div>',
		'shariff3UU_checkbox_backend_render', 'statistic', 'shariff3UU_statistic_section' );

	// share counts
	add_settings_field( 'shariff3UU_checkbox_sharecounts', __( 'Show share counts on buttons.', 'shariff' ),
		'shariff3UU_checkbox_sharecounts_render', 'statistic', 'shariff3UU_statistic_section' );
		
	// hide when zero
	add_settings_field( 'shariff3UU_checkbox_hidezero', __( 'Hide share counts when they are zero.', 'shariff' ),
		'shariff3UU_checkbox_hidezero_render', 'statistic', 'shariff3UU_statistic_section' );

	// Facebook App ID
	add_settings_field( 'shariff3UU_text_fb_id', __( 'Facebook App ID:', 'shariff' ),
		'shariff3UU_text_fb_id_render', 'statistic', 'shariff3UU_statistic_section' );

	// Facebook App Secret
	add_settings_field( 'shariff3UU_text_fb_secret', __( 'Facebook App Secret:', 'shariff' ),
		'shariff3UU_text_fb_secret_render', 'statistic', 'shariff3UU_statistic_section' );

	// autoamtic cache
	add_settings_field( 'shariff3UU_checkbox_automaticcache', __( 'Fill cache automatically.', 'shariff' ),
		'shariff3UU_checkbox_automaticcache_render', 'statistic', 'shariff3UU_statistic_section' );

	// ranking
	add_settings_field( 'shariff3UU_number_ranking', __( 'Number of posts on ranking tab:', 'shariff' ),
		'shariff3UU_number_ranking_render', 'statistic', 'shariff3UU_statistic_section' );

	// ttl
	add_settings_field( 'shariff3UU_number_ttl', __( 'Cache TTL in seconds (60 - 7200):', 'shariff' ),
		'shariff3UU_number_ttl_render', 'statistic', 'shariff3UU_statistic_section' );
		
	// disable dynamic cache lifespan
	add_settings_field( 'shariff3UU_checkbox_disable_dynamic_cache', __( 'Disable the dynamic cache lifespan (not recommended).', 'shariff' ),
		'shariff3UU_checkbox_disable_dynamic_cache_render', 'statistic', 'shariff3UU_statistic_section' );
		
	// Twitter NewShareCount
	add_settings_field( 'shariff3UU_checkbox_newsharecount', __( 'Use NewShareCount instead of OpenShareCount for Twitter.', 'shariff' ),
		'shariff3UU_checkbox_newsharecount_render', 'statistic', 'shariff3UU_statistic_section' );
	
	// disable services
	add_settings_field( 'shariff3UU_multiplecheckbox_disable_services', __( 'Disable the following services (share counts only):', 'shariff' ),
		'shariff3UU_multiplecheckbox_disable_services_render', 'statistic', 'shariff3UU_statistic_section' );

	// external hosts
	add_settings_field( 'shariff3UU_text_external_host', __( 'External API for share counts:', 'shariff' ),
		'shariff3UU_text_external_host_render', 'statistic', 'shariff3UU_statistic_section' );
		
	// request external api directly from js
	add_settings_field( 'shariff3UU_checkbox_external_direct', __( 'Request external API directly.', 'shariff' ),
		'shariff3UU_checkbox_external_direct_render', 'statistic', 'shariff3UU_statistic_section' );

	// wp in subfolder and api only reachable there?
	add_settings_field( 'shariff3UU_checkbox_subapi', __( 'Local API not reachable in root.', 'shariff' ),
		'shariff3UU_checkbox_subapi_render', 'statistic', 'shariff3UU_statistic_section' );

	// sixth tab - help

	// register sixth tab (help)
	add_settings_section( 'shariff3UU_help_section', __( 'Shariff Help', 'shariff' ),
		'shariff3UU_help_section_callback', 'help' );

	// seventh tab - status

	// register seventh tab (status)
	add_settings_section( 'shariff3UU_status_section', __( 'Status', 'shariff' ),
		'shariff3UU_status_section_callback', 'status' );

	// eigth tab - ranking

	// register eigth tab (ranking)
	add_settings_section( 'shariff3UU_ranking_section', __( 'Ranking', 'shariff' ),
		'shariff3UU_ranking_section_callback', 'ranking' );
}

// sanitize input from the basic settings page
function shariff3UU_basic_sanitize( $input ) {
	// create array
	$valid = array();

	if ( isset( $input["version"] ) )				$valid["version"]				= sanitize_text_field( $input["version"] );
	if ( isset( $input["services"] ) )				$valid["services"]				= trim( preg_replace( "/[^A-Za-z|]/", '', sanitize_text_field( $input["services"] ) ), '|' );
	if ( isset( $input["add_after"] ) )				$valid["add_after"]				= sani_arrays( $input["add_after"] );
	if ( isset( $input["add_before"] ) )			$valid["add_before"]			= sani_arrays( $input["add_before"] );
	if ( isset( $input["disable_on_protected"] ) )	$valid["disable_on_protected"]	= absint( $input["disable_on_protected"] );
	if ( isset( $input["disable_outside_loop"] ) )	$valid["disable_outside_loop"]	= absint( $input["disable_outside_loop"] );

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
	if ( isset( $input["buttonsize"] ) )		$valid["buttonsize"]		= sanitize_text_field( $input["buttonsize"] );
	if ( isset( $input["buttonstretch"] ) )		$valid["buttonstretch"]		= absint( $input["buttonstretch"] );
	if ( isset( $input["borderradius"] ) ) 		$valid["borderradius"] 		= absint( $input["borderradius"] );
	if ( isset( $input["maincolor"] ) ) 		$valid["maincolor"] 		= sanitize_text_field( $input["maincolor"] );
	if ( isset( $input["secondarycolor"] ) ) 	$valid["secondarycolor"] 	= sanitize_text_field( $input["secondarycolor"] );
	if ( isset( $input["vertical"] ) ) 			$valid["vertical"] 			= absint( $input["vertical"] );
	if ( isset( $input["align"] ) ) 			$valid["align"] 			= sanitize_text_field( $input["align"] );
	if ( isset( $input["align_widget"] ) ) 		$valid["align_widget"] 		= sanitize_text_field( $input["align_widget"] );
	if ( isset( $input["style"] ) ) 			$valid["style"] 			= sanitize_text_field( $input["style"] );
	if ( isset( $input["cssclass"] ) ) 			$valid["cssclass"] 			= sanitize_text_field( $input["cssclass"] );
	if ( isset( $input["headline"] ) ) 			$valid["headline"] 			= wp_kses( $input["headline"], $GLOBALS["allowed_tags"] );
	if ( isset( $input["hideuntilcss"] ) ) 		$valid["hideuntilcss"] 		= absint( $input["hideuntilcss"] );
	if ( isset( $input["popup"] ) ) 		    $valid["popup"] 		    = absint( $input["popup"] );

	// remove empty elements
	$valid = array_filter($valid);

	return $valid;
}

// sanitize input from the advanced settings page
function shariff3UU_advanced_sanitize( $input ) {
	// create array
	$valid = array();

	if ( isset($input["info_url"] ) ) 				$valid["info_url"] 				= esc_url_raw( $input["info_url"] );
	if ( isset($input["twitter_via"] ) ) 			$valid["twitter_via"] 			= str_replace( '@', '', sanitize_text_field( $input["twitter_via"] ) );
	if ( isset($input["flattruser"] ) )    			$valid["flattruser"]       		= str_replace( '@', '', sanitize_text_field( $input["flattruser"] ) );
	if ( isset($input["patreonid"] ) )    			$valid["patreonid"]       		= str_replace( '@', '', sanitize_text_field( $input["patreonid"] ) );
	if ( isset($input["paypalbuttonid"] ) )    		$valid["paypalbuttonid"]       	= str_replace( '@', '', sanitize_text_field( $input["paypalbuttonid"] ) );
	if ( isset($input["paypalmeid"] ) )      		$valid["paypalmeid"]       	    = str_replace( '@', '', sanitize_text_field( $input["paypalmeid"] ) );
	if ( isset($input["bitcoinaddress"] ) )    		$valid["bitcoinaddress"]       	= str_replace( '@', '', sanitize_text_field( $input["bitcoinaddress"] ) );
	if ( isset($input["rssfeed"] ) )    		    $valid["rssfeed"]       	    = str_replace( '@', '', sanitize_text_field( $input["rssfeed"] ) );
	if ( isset($input["default_pinterest"] ) ) 	    $valid["default_pinterest"]		= sanitize_text_field( $input["default_pinterest"] );
	if ( isset($input["shortcodeprio"] ) ) 			$valid["shortcodeprio"] 		= absint( $input["shortcodeprio"] );

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
	if ( isset( $input["mailform_anchor"] ) )		$valid["mailform_anchor"]		= absint( $input["mailform_anchor"] );
	if ( isset( $input["mailform_wait"] ) )		    $valid["mailform_wait"]		    = absint( $input["mailform_wait"] );
	
	// protect users from themselfs
	if ( isset( $valid["mailform_wait"] ) && $valid["mailform_wait"] < '5' ) $valid["mailform_wait"] = '';
	elseif ( isset( $valid["mailform_wait"] ) && $valid["mailform_wait"] > '86400' ) $valid["mailform_wait"] = '86400';

	// remove empty elements
	$valid = array_filter( $valid );

	return $valid;
}

// sanitize input from the statistic settings page
function shariff3UU_statistic_sanitize( $input ) {
	// create array
	$valid = array();

	if ( isset( $input["backend"] ) )                $valid["backend"]               = absint( $input["backend"] );
	if ( isset( $input["sharecounts"] ) )            $valid["sharecounts"]           = absint( $input["sharecounts"] );
	if ( isset( $input["hidezero"] ) )               $valid["hidezero"]              = absint( $input["hidezero"] );
	if ( isset( $input["ranking"] ) )                $valid["ranking"]               = absint( $input["ranking"] );
	if ( isset( $input["automaticcache"] ) )         $valid["automaticcache"]        = absint( $input["automaticcache"] );
	if ( isset( $input["fb_id"] ) )                  $valid["fb_id"]                 = sanitize_text_field( $input["fb_id"] );
	if ( isset( $input["fb_secret"] ) )              $valid["fb_secret"]             = sanitize_text_field( $input["fb_secret"] );
	if ( isset( $input["ttl"] ) )                    $valid["ttl"]                   = absint( $input["ttl"] );
	if ( isset( $input["disable_dynamic_cache"] ) )  $valid["disable_dynamic_cache"] = absint( $input["disable_dynamic_cache"] );
	if ( isset( $input["newsharecount"] ) )          $valid["newsharecount"]         = absint( $input["newsharecount"] );
	if ( isset( $input["disable"] ) )                $valid["disable"]               = sani_arrays( $input["disable"] );
	if ( isset( $input["external_host"] ) )          $valid["external_host"]         = str_replace( ' ', '', rtrim( esc_url_raw( $input["external_host"], "/" ) ) );
	if ( isset( $input["external_direct"] ) )        $valid["external_direct"]       = absint( $input["external_direct"] );
	if ( isset( $input["subapi"] ) )                 $valid["subapi"]                = absint( $input["subapi"] );

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
function shariff3UU_basic_section_callback() {
	echo __( "Select the desired services in the order you want them to be displayed and where the Shariff buttons should be included automatically.", "shariff" );
}

// services
function shariff3UU_text_services_render() {
	if ( isset( $GLOBALS["shariff3UU_basic"]["services"] ) ) {
		$services = $GLOBALS["shariff3UU_basic"]["services"];
	}
	else {
		$services = '';
	}
	echo '<input type="text" name="shariff3UU_basic[services]" value="' . esc_html($services) . '" size="75" placeholder="twitter|facebook|googleplus|info">';
	echo '<p><code>facebook|twitter|googleplus|whatsapp|threema|pinterest|xing|linkedin|reddit|vk|odnoklassniki|diaspora|stumbleupon</code></p>';
	echo '<p><code>tumblr|addthis|pocket|flattr|patreon|paypal|paypalme|bitcoin|mailform|mailto|printer|rss|info</code></p>';
	echo '<p>' . __( 'Use the pipe sign | (Alt Gr + &lt; or &#8997; + 7) between two or more services.', 'shariff' ) . '</p>';
}

// add after
function shariff3UU_multiplecheckbox_add_after_render() {
	// add after all posts
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][posts]" ';
	if ( isset( $GLOBALS['shariff3UU_basic']['add_after']['posts'] ) ) echo checked( $GLOBALS['shariff3UU_basic']['add_after']['posts'], 1, 0 );
	echo ' value="1">' . __('Posts', 'shariff') . '</p>';

	// add after all posts (blog page)
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][posts_blogpage]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_after"]["posts_blogpage"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_after"]["posts_blogpage"], 1, 0 );
	echo ' value="1">' . __('Posts (blog page)', 'shariff') . '</p>';

	// add after all pages
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][pages]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_after"]["pages"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_after"]["pages"], 1, 0 );
	echo ' value="1">' . __('Pages', 'shariff') . '</p>';

	// add after all bbpress replies
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][bbp_reply]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_after"]["bbp_reply"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_after"]["bbp_reply"], 1, 0 );
	echo ' value="1">' . __('bbPress replies', 'shariff') . '</p>';

	// add after all excerpts
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][excerpt]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_after"]["excerpt"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_after"]["excerpt"], 1, 0 );
	echo ' value="1">' . __('Excerpts', 'shariff') . '</p>';

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
	echo ' value="1">' . __('Posts', 'shariff') . '</p>';

	// Add before all posts (blog page)
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_before][posts_blogpage]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_before"]["posts_blogpage"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_before"]["posts_blogpage"], 1, 0 );
	echo ' value="1">' . __('Posts (blog page)', 'shariff') . '</p>';

	// Add before all pages
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_before][pages]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_before"]["pages"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_before"]["pages"], 1, 0 );
	echo ' value="1">' . __('Pages', 'shariff') . '</p>';

	// Add before all excerpts
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_before][excerpt]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_before"]["excerpt"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_before"]["excerpt"], 1, 0 );
	echo ' value="1">' . __('Excerpts', 'shariff') . '</p>';
}

// disable on password protected posts
function shariff3UU_checkbox_disable_on_protected_render() {
	echo '<input type="checkbox" name="shariff3UU_basic[disable_on_protected]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["disable_on_protected"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["disable_on_protected"], 1, 0 );
	echo ' value="1">';
}

// disable outside loop
function shariff3UU_checkbox_disable_outside_loop_render() {
	echo '<input type="checkbox" name="shariff3UU_basic[disable_outside_loop]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["disable_outside_loop"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["disable_outside_loop"], 1, 0 );
	echo ' value="1">';
}

// design options

// description design options
function shariff3UU_design_section_callback(){
	echo __( 'This configures the default design of the Shariff buttons. Most options can be overwritten for single posts or pages with the options within the <code>[shariff]</code> shorttag. For more information have a look at the ', 'shariff' );
	echo '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=shariff3uu&tab=help">';
	echo __( 'Help Section</a> and the ', 'shariff' );
	echo '<a href="https://wordpress.org/support/plugin/shariff/" target="_blank">';
	echo __( 'Support Forum</a>.', 'shariff' );
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
	echo '<div style="display:table;border-spacing:10px;margin:-15px 0 -5px -5px;border-collapse: separate">
	<div style="display:table-row"><div style="display:table-cell;vertical-align:middle;min-width:75px"><input type="radio" name="shariff3UU_design[theme]" value="" ' .      checked( $options["theme"], "", 0 ) .      '>default</div><div class="shariff_options-cell"><img src="' . $plugins_url . '/shariff/pictos/defaultBtns.png"></div></div>
	<div style="display:table-row"><div style="display:table-cell;vertical-align:middle;min-width:75px"><input type="radio" name="shariff3UU_design[theme]" value="color" ' .  checked( $options["theme"], "color", 0 ) . '>color</div><div class="shariff_options-cell"><img src="' .    $plugins_url . '/shariff/pictos/colorBtns.png"></div></div>
	<div style="display:table-row"><div style="display:table-cell;vertical-align:middle;min-width:75px"><input type="radio" name="shariff3UU_design[theme]" value="grey" ' .  checked( $options["theme"], "grey", 0 )  . '>grey</div><div class="shariff_options-cell"><img src="' .    $plugins_url . '/shariff/pictos/greyBtns.png"></div></div>
	<div style="display:table-row"><div style="display:table-cell;vertical-align:middle;min-width:75px"><input type="radio" name="shariff3UU_design[theme]" value="white" ' . checked( $options["theme"], "white", 0 ) . '>white</div><div class="shariff_options-cell"><img src="' .    $plugins_url . '/shariff/pictos/whiteBtns.png"></div></div>
	<div style="display:table-row"><div style="display:table-cell;vertical-align:middle;min-width:75px"><input type="radio" name="shariff3UU_design[theme]" value="round" ' . checked( $options["theme"], "round", 0 ) . '>round</div><div class="shariff_options-cell"><img src="' .   $plugins_url . '/shariff/pictos/roundBtns.png"></div></div>
	</div>';
}

// button size
function shariff3UU_checkbox_buttonsize_render() {
	$options = $GLOBALS['shariff3UU_design'];
	if ( ! isset( $options['buttonsize'] ) ) $options['buttonsize'] = 'medium';
	echo '<p><input type="radio" name="shariff3UU_design[buttonsize]" value="small" ' . checked( $options["buttonsize"], "small", 0 ) . '>' . __( "small", "shariff" ) . '</p>';
	echo '<p><input type="radio" name="shariff3UU_design[buttonsize]" value="medium" ' . checked( $options["buttonsize"], "medium", 0 )     . '>' . __( "medium", "shariff" ) . '</p>';
	echo '<p><input type="radio" name="shariff3UU_design[buttonsize]" value="large" ' . checked( $options["buttonsize"], "large", 0 )   . '>' . __( "large", "shariff" ) . '</p>';
}

// button stretch
function shariff3UU_checkbox_buttonstretch_render() {
	$plugins_url = plugins_url();
	echo '<input type="checkbox" name="shariff3UU_design[buttonstretch]" ';
	if ( isset( $GLOBALS["shariff3UU_design"]["buttonstretch"] ) ) echo checked( $GLOBALS["shariff3UU_design"]["buttonstretch"], 1, 0 );
	echo ' value="1">';
}

// border radius
function shariff3UU_number_borderradius_render() {
	$plugins_url = plugins_url();
	if ( isset( $GLOBALS["shariff3UU_design"]["borderradius"] ) ) {
		$borderradius = $GLOBALS["shariff3UU_design"]["borderradius"];
	}
	else {
		$borderradius = '';
	}
	echo '<input type="number" name="shariff3UU_design[borderradius]" value="'. $borderradius .'" maxlength="2" min="1" max="50" placeholder="50" style="width: 75px">';
	echo '<img src="'. $plugins_url .'/shariff/pictos/borderradius.png" align="top">';
}

// custom main color
function shariff3UU_text_maincolor_render() {
	if ( isset( $GLOBALS["shariff3UU_design"]["maincolor"] ) ) {
		$maincolor = $GLOBALS["shariff3UU_design"]["maincolor"];
	}
	else {
		$maincolor = '';
	}
	echo '<input type="text" name="shariff3UU_design[maincolor]" value="' . esc_html( $maincolor ) . '" size="7" placeholder="#000000">';
}

// custom secondary color
function shariff3UU_text_secondarycolor_render() {
	if ( isset( $GLOBALS["shariff3UU_design"]["secondarycolor"] ) ) {
		$secondarycolor = $GLOBALS["shariff3UU_design"]["secondarycolor"];
	}
	else {
		$secondarycolor = '';
	}
	echo '<input type="text" name="shariff3UU_design[secondarycolor]" value="' . esc_html( $secondarycolor ) . '" size="7" placeholder="#afafaf">';
}

// vertical
function shariff3UU_checkbox_vertical_render() {
	$plugins_url = plugins_url();
	echo '<input type="checkbox" name="shariff3UU_design[vertical]" ';
	if ( isset( $GLOBALS["shariff3UU_design"]["vertical"] ) ) echo checked( $GLOBALS["shariff3UU_design"]["vertical"], 1, 0 );
	echo ' value="1">';
}

// alignment
function shariff3UU_radio_align_render() {
	$options = $GLOBALS['shariff3UU_design'];
	if ( ! isset( $options['align'] ) ) $options['align'] = 'flex-start';
	echo '<p><input type="radio" name="shariff3UU_design[align]" value="flex-start" ' . checked( $options["align"], "flex-start", 0 ) . '>' . __( "left", "shariff" ) . '</p>';
	echo '<p><input type="radio" name="shariff3UU_design[align]" value="center" ' .     checked( $options["align"], "center", 0 )     . '>' . __( "center", "shariff" ) . '</p>';
	echo '<p><input type="radio" name="shariff3UU_design[align]" value="flex-end" ' .   checked( $options["align"], "flex-end", 0 )   . '>' . __( "right", "shariff" ) . '</p>';
}

// alignment widget
function shariff3UU_radio_align_widget_render() {
	$options = $GLOBALS['shariff3UU_design'];
	if ( ! isset( $options['align_widget'] ) ) $options['align_widget'] = 'flex-start';
	echo '<p><input type="radio" name="shariff3UU_design[align_widget]" value="flex-start" ' . checked( $options["align_widget"], "flex-start", 0 ) . '>' . __( "left", "shariff" ) . '</p>';
	echo '<p><input type="radio" name="shariff3UU_design[align_widget]" value="center" ' .     checked( $options["align_widget"], "center", 0 )     . '>' . __( "center", "shariff" ) . '</p>';
	echo '<p><input type="radio" name="shariff3UU_design[align_widget]" value="flex-end" ' .   checked( $options["align_widget"], "flex-end", 0 )   . '>' . __( "right", "shariff" ) . '</p>';
}

// headline
function shariff3UU_text_headline_render() {
	if ( isset( $GLOBALS["shariff3UU_design"]["headline"] ) ) {
		$headline = $GLOBALS["shariff3UU_design"]["headline"];
	}
	else {
		$headline = '';
	}
	echo '<input type="text" name="shariff3UU_design[headline]" value="' . esc_html( $headline ) . '" size="50" placeholder="' . __( "Share this post", "shariff" ) . '">';
	echo '<p>';
	echo __( 'Basic HTML as well as style and class attributes are allowed. You can use %total to show the total amount of shares.', 'shariff' );
	echo '<br>';
	echo __( 'Example:', 'shariff' );
	echo '<code>&lt;h3 class="shariff_headline"&gt;';
	echo __( 'Already shared %total times!', 'shariff' );
	echo '&lt;/h3&gt;</code></p>';
}

// custom css
function shariff3UU_text_style_render() {
	if ( isset( $GLOBALS["shariff3UU_design"]["style"] ) ) {
		$style = $GLOBALS["shariff3UU_design"]["style"];
	}
	else {
		$style = '';
	}
	echo '<input type="text" name="shariff3UU_design[style]" value="' . esc_html( $style ) . '" size="50" placeholder="' . __( "More information in the FAQ.", "shariff" ) . '">';
}

// custom css class
function shariff3UU_text_cssclass_render() {
	if ( isset( $GLOBALS["shariff3UU_design"]["cssclass"] ) ) {
		$cssclass = $GLOBALS["shariff3UU_design"]["cssclass"];
	}
	else {
		$cssclass = '';
	}
	echo '<input type="text" name="shariff3UU_design[cssclass]" value="' . esc_html( $cssclass ) . '" size="50" placeholder="' . __( "More information in the FAQ.", "shariff" ) . '">';
}

// hide until page is fully loaded
function shariff3UU_checkbox_hideuntilcss_render() {
	echo '<input type="checkbox" name="shariff3UU_design[hideuntilcss]" ';
	if ( isset( $GLOBALS["shariff3UU_design"]["hideuntilcss"] ) ) echo checked( $GLOBALS["shariff3UU_design"]["hideuntilcss"], 1, 0 );
	echo ' value="1">';
}

// open links in a popup
function shariff3UU_checkbox_popup_render() {
	echo '<input type="checkbox" name="shariff3UU_design[popup]" ';
	if ( isset( $GLOBALS["shariff3UU_design"]["popup"] ) ) echo checked( $GLOBALS["shariff3UU_design"]["popup"], 1, 0 );
	echo ' value="1">';
}

// advanced options

// description advanced options
function shariff3UU_advanced_section_callback(){
	echo __( 'This configures the advanced options of Shariff regarding specific services. If you are unsure about an option, take a look at the ', 'shariff' );
	echo '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=shariff3uu&tab=help">';
	echo __( 'Help Section</a> and the ', 'shariff' );
	echo '<a href="https://wordpress.org/support/plugin/shariff/" target="_blank">';
	echo __( 'Support Forum</a>.', 'shariff' );
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
	echo '<input type="text" name="shariff3UU_advanced[twitter_via]" value="' . $twitter_via . '" size="50" placeholder="' . __( 'username', 'shariff' ) . '">';
}

// flattr username
function shariff3UU_text_flattruser_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["flattruser"]) ) {
		$flattruser = $GLOBALS["shariff3UU_advanced"]["flattruser"];
	}
	else {
		$flattruser = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[flattruser]" value="'. $flattruser .'" size="50" placeholder="' . __( 'username', 'shariff' ) . '">';
}

// patreon username
function shariff3UU_text_patreonid_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["patreonid"]) ) {
		$patreonid = $GLOBALS["shariff3UU_advanced"]["patreonid"];
	}
	else {
		$patreonid = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[patreonid]" value="'. $patreonid .'" size="50" placeholder="' . __( 'username', 'shariff' ) . '">';
}

// paypal button id
function shariff3UU_text_paypalbuttonid_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["paypalbuttonid"]) ) {
		$paypalbuttonid = $GLOBALS["shariff3UU_advanced"]["paypalbuttonid"];
	}
	else {
		$paypalbuttonid = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[paypalbuttonid]" value="'. $paypalbuttonid .'" size="50" placeholder="1ABCDEF23GH4I">';
}

// paypalme id
function shariff3UU_text_paypalmeid_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["paypalmeid"]) ) {
		$paypalmeid = $GLOBALS["shariff3UU_advanced"]["paypalmeid"];
	}
	else {
		$paypalmeid = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[paypalmeid]" value="'. $paypalmeid .'" size="50" placeholder="' . __( 'name', 'shariff' ) . '">';
}

// bitcoin address
function shariff3UU_text_bitcoinaddress_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["bitcoinaddress"]) ) {
		$bitcoinaddress = $GLOBALS["shariff3UU_advanced"]["bitcoinaddress"];
	}
	else {
		$bitcoinaddress = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[bitcoinaddress]" value="'. $bitcoinaddress .'" size="50" placeholder="1Ab2CdEfGhijKL34mnoPQRSTu5VwXYzaBcD">';
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
	echo '<div><input type="text" name="shariff3UU_advanced[default_pinterest]" value="' . $options["default_pinterest"] . '" id="shariff-image-url" class="regular-text"><input type="button" name="upload-btn" id="shariff-upload-btn" class="button-secondary" value="' . __( 'Choose image', 'shariff' ) . '"></div>';
}

// shortcodeprio
function shariff3UU_number_shortcodeprio_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["shortcodeprio"]) ) {
		$prio = $GLOBALS["shariff3UU_advanced"]["shortcodeprio"];
	}
	else {
		$prio = '';
	}
	echo '<input type="number" name="shariff3UU_advanced[shortcodeprio]" value="'. $prio .'" maxlength="2" min="0" max="20" placeholder="10" style="width: 75px">';
	echo '<p>' . __( 'Warning: <strong>DO NOT</strong> change this unless you know what you are doing or have been told so by the plugin author!', 'shariff' ) . '</p>';
}

// mailform options

// description mailform options
function shariff3UU_mailform_section_callback() {
	echo __( "The mail form can be completely disabled, if not needed. Otherwise, it is recommended to configure a default sender e-mail address from <u>your domain</u> that actually exists, to prevent spam filters from blocking the e-mails.", "shariff" );
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

// mailform anchor
function shariff3UU_checkbox_mailform_anchor_render() {
	echo '<input type="checkbox" name="shariff3UU_mailform[mailform_anchor]" ';
	if ( isset( $GLOBALS["shariff3UU_mailform"]["mailform_anchor"] ) ) echo checked( $GLOBALS["shariff3UU_mailform"]["mailform_anchor"], 1, 0 );
	echo ' value="1">';
}

// wait timer
function shariff3UU_number_mailform_wait_render() {
	if ( isset($GLOBALS["shariff3UU_mailform"]["mailform_wait"]) ) { 
		$mailform_wait = $GLOBALS["shariff3UU_mailform"]["mailform_wait"];
	} 
	else { 
		$mailform_wait = '';
	}
	echo '<input type="number" name="shariff3UU_mailform[mailform_wait]" value="'. $mailform_wait .'" maxlength="4" min="5" max="86400" placeholder="5" style="width: 75px">';
}

// statistic section

// description statistic options
function shariff3UU_statistic_section_callback(){
	echo __( 'This determines how share counts are handled by Shariff.', 'shariff' );
	if ( isset( $GLOBALS["shariff3UU_statistic"]["external_direct"] ) ) {
		echo '<br>';
		echo __( '<span style="color: red; font-weight: bold;">Warning:</span> You entered an external API and chose to call it directly! Therefore, all options and features (e.g. the ranking tab) regarding the statistic have no effect. You need to configure them on the external server. Remember: This feature is still experimental!', 'shariff' );
	}
	// hook to add or remove cron job
	do_action( 'shariff3UU_save_statistic_options' );
}

// statistic
function shariff3UU_checkbox_backend_render() {
	// check WP version
	if ( version_compare( get_bloginfo('version'), '4.4.0' ) < 1 ) {
		echo __( 'WordPress-Version 4.4 or better is required to enable the statistic / share count functionality.', 'shariff');
	}
	else {
		echo '<input type="checkbox" name="shariff3UU_statistic[backend]" ';
		if ( isset( $GLOBALS['shariff3UU_statistic']['backend'] ) ) {
			echo checked( $GLOBALS['shariff3UU_statistic']['backend'], 1, 0 );
		}
		echo ' value="1">';
	}
}

// share counts on buttons
function shariff3UU_checkbox_sharecounts_render() {
	echo '<input type="checkbox" name="shariff3UU_statistic[sharecounts]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['sharecounts'] ) ) {
		echo checked( $GLOBALS['shariff3UU_statistic']['sharecounts'], 1, 0 );
	}
	echo ' value="1">';
	if ( ! isset( $GLOBALS['shariff3UU_statistic']['backend'] ) && isset( $GLOBALS['shariff3UU_statistic']['sharecounts'] ) ) {
		echo ' ';
		echo __( 'Warning: The statistic functionality must be enabled in order for the share counts to be shown.', 'shariff' );
	}
}

// hide when zero
function shariff3UU_checkbox_hidezero_render() {
	echo '<input type="checkbox" name="shariff3UU_statistic[hidezero]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['hidezero'] ) ) {
		echo checked( $GLOBALS['shariff3UU_statistic']['hidezero'], 1, 0 );
	}
	echo ' value="1">';
}

// ranking
function shariff3UU_number_ranking_render() {
	if ( isset($GLOBALS["shariff3UU_statistic"]["ranking"]) ) {
		$numberposts = $GLOBALS["shariff3UU_statistic"]["ranking"];
	}
	else {
		$numberposts = '';
	}
	echo '<input type="number" name="shariff3UU_statistic[ranking]" value="'. $numberposts .'" maxlength="4" min="0" max="10000" placeholder="100" style="width: 75px">';
}

// automatic cache
function shariff3UU_checkbox_automaticcache_render() {
	echo '<input type="checkbox" name="shariff3UU_statistic[automaticcache]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['automaticcache'] ) ) {
		echo checked( $GLOBALS['shariff3UU_statistic']['automaticcache'], 1, 0 );
	}
	echo ' value="1">';
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

// disable dynamic cache lifespan
function shariff3UU_checkbox_disable_dynamic_cache_render() {
	echo '<input type="checkbox" name="shariff3UU_statistic[disable_dynamic_cache]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable_dynamic_cache'] ) ) {
		echo checked( $GLOBALS['shariff3UU_statistic']['disable_dynamic_cache'], 1, 0 );
	}
	echo ' value="1">';
}

// Twitter NewShareCount
function shariff3UU_checkbox_newsharecount_render() {
	echo '<input type="checkbox" name="shariff3UU_statistic[newsharecount]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['newsharecount'] ) ) {
		echo checked( $GLOBALS['shariff3UU_statistic']['newsharecount'], 1, 0 );
	}
	echo ' value="1">';
}

// disable services
function shariff3UU_multiplecheckbox_disable_services_render() {
	// Facebook
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][facebook]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['facebook'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['facebook'], 1, 0 );
	echo ' value="1">Facebook</p>';

	// Twitter
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][twitter]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['twitter'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['twitter'], 1, 0 );
	echo ' value="1">Twitter</p>';

	// GooglePlus
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][googleplus]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['googleplus'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['googleplus'], 1, 0 );
	echo ' value="1">GooglePlus</p>';

	// Pinterest
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][pinterest]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['pinterest'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['pinterest'], 1, 0 );
	echo ' value="1">Pinterest</p>';

	// Xing
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][xing]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['xing'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['xing'], 1, 0 );
	echo ' value="1">Xing</p>';

	// LinkedIn
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][linkedin]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['linkedin'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['linkedin'], 1, 0 );
	echo ' value="1">LinkedIn</p>';

	// Tumblr
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][tumblr]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['tumblr'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['tumblr'], 1, 0 );
	echo ' value="1">Tumblr</p>';

	// VK
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][vk]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['vk'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['vk'], 1, 0 );
	echo ' value="1">VK</p>';

	// StumbleUpon
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][stumbleupon]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['stumbleupon'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['stumbleupon'], 1, 0 );
	echo ' value="1">StumbleUpon</p>';

	// Reddit
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][reddit]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['reddit'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['reddit'], 1, 0 );
	echo ' value="1">Reddit</p>';

	// AddThis
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][addthis]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['addthis'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['addthis'], 1, 0 );
	echo ' value="1">AddThis</p>';

	// Flattr
	echo '<p><input type="checkbox" name="shariff3UU_statistic[disable][flattr]" ';
	if ( isset( $GLOBALS['shariff3UU_statistic']['disable']['flattr'] ) ) echo checked( $GLOBALS['shariff3UU_statistic']['disable']['flattr'], 1, 0 );
	echo ' value="1">Flattr</p>';
}

// external host
function shariff3UU_text_external_host_render(){
	if ( isset( $GLOBALS["shariff3UU_statistic"]["external_host"] ) ) {
		$external_host = $GLOBALS["shariff3UU_statistic"]["external_host"];
	}
	else {
		$external_host = '';
	}
	echo '<input type="text" name="shariff3UU_statistic[external_host]" value="' . esc_html( $external_host ) . '" size="50" placeholder="'. esc_url( get_bloginfo('url') ) .'/wp-json/shariff/v1/share_counts">';
	echo '<p>' . __( 'Warning: This is an <strong>experimental</strong> feature. Please read the <a href="https://wordpress.org/plugins/shariff/faq/" target="_blank">Frequently Asked Questions (FAQ)</a>.', 'shariff' ) . '</p>';
	echo '<p>' . __( 'Please check, if you have to add this domain to the array $SHARIFF_FRONTENDS on the external server.', 'shariff' ) . '</p>';
}

// direct external api call from JS
function shariff3UU_checkbox_external_direct_render(){
	echo '<input type="checkbox" name="shariff3UU_statistic[external_direct]" ';
		if ( isset( $GLOBALS['shariff3UU_statistic']['external_direct'] ) ) {
			echo checked( $GLOBALS['shariff3UU_statistic']['external_direct'], 1, 0 );
		}
	echo ' value="1">';
	echo '<p>' . __( 'Please check, if you have correctly set the Access-Control-Allow-Origin header!', 'shariff' ) . '</p>';
}

// local API only reachable in subfolder
function shariff3UU_checkbox_subapi_render(){
	echo '<input type="checkbox" name="shariff3UU_statistic[subapi]" ';
		if ( isset( $GLOBALS['shariff3UU_statistic']['subapi'] ) ) {
			echo checked( $GLOBALS['shariff3UU_statistic']['subapi'], 1, 0 );
		}
	echo ' value="1">';
}

// help section

function shariff3UU_help_section_callback() {
	echo '<p>';
		echo __( 'The WordPress plugin "Shariff Wrapper" has been developed by <a href="https://www.jplambeck.de" target=_blank">Jan-Peter Lambeck</a> and <a href="http://www.datenverwurstungszentrale.com" target="_blank">3UU</a> in order to help protect the privacy of your visitors.', 'shariff' );
		echo ' ' . __( 'It is based on the original Shariff buttons developed by the German computer magazin <a href="http://ct.de/shariff" target="_blank">c\'t</a> that fullfill the strict data protection laws in Germany.', 'shariff' );
		echo ' ' . __( 'If you need any help with the plugin, take a look at the <a href="https://wordpress.org/plugins/shariff/faq/" target="_blank">Frequently Asked Questions (FAQ)</a> and the <a href="https://wordpress.org/support/plugin/shariff" target="_blank">Support Forum</a>.', 'shariff' );
		echo ' ' . __( 'For up to date news about the plugin you can also follow <a href="https://twitter.com/jplambeck" target=_blank">@jplambeck</a> on Twitter.', 'shariff' );
	echo '</p>';
	echo '<p>';
		echo __( 'If you contact us about a problem with the share counts, please <u>always</u> include the information provided on the <a href="options-general.php?page=shariff3uu&tab=status">status tab</a>! ', 'shariff' );
		echo ' ' . __( 'This will help to speed up the process.', 'shariff' );
	echo '</p>';
	echo '<p>';
		echo __( 'If you enjoy our plugin, please consider writing a review about it on <a href="https://wordpress.org/support/view/plugin-reviews/shariff" target="_blank">wordpress.org</a>. ', 'shariff' );
		echo ' ' . __( 'If you want to support us financially, you can donate via <a href="http://folge.link/?bitcoin=1Ritz1iUaLaxuYcXhUCoFhkVRH6GWiMTP" target="_blank">Bitcoin</a> and <a href="https://www.paypal.me/jplambeck" target="_blanK">PayPal</a>. ', 'shariff' );
		echo ' ' . __( 'Thank you!', 'shariff' );
	echo '</p>';
	echo '<p>';
		echo __( 'This is a list of all available options for the <code>[shariff]</code> shortcode:', 'shariff' );
	echo '</p>';
	// shortcode table
	echo '<div style="display:table;background-color:#fff">';
		// head
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . __( 'Name', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . __( 'Options', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . __( 'Default', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . __( 'Example', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . __( 'Description', 'shariff' ) . '</div>';
		echo '</div>';
		// services
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">services</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">facebook<br>twitter<br>googleplus<br>whatsapp<br>threema<br>pinterest<br>linkedin<br>xing<br>reddit<br>stumbleupon<br>tumblr<br>vk<br>diaspora<br>addthis<br>flattr<br>patreon<br>paypal<br>paypalme<br>bitcoin<br>mailform<br>mailto<br>printer<br>info<br>rss</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">twitter|facebook|googleplus|info</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff services="facebook|twitter|mailform"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Determines which buttons to show and in which order.', 'shariff' ) . '</div>';
		echo '</div>';
		// backend
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">backend</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">on<br>off</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">off</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff backend="on"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Enables share counts on the buttons.', 'shariff' ) . '</div>';
		echo '</div>';
		// theme
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">theme</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">default<br>color<br>grey<br>white<br>round</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">default</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff theme="round"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Determines the main design of the buttons.', 'shariff' ) . '</div>';
		echo '</div>';
		// button size
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">buttonsize</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">small<br>medium<br>large</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">medium</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff buttonsize="small"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Determines the button size regardless of theme choice.', 'shariff' ) . '</div>';
		echo '</div>';
		// buttonstretch
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">buttonstretch</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">0<br>1</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">0</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff buttonstretch="1"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Stretch buttons horizontally to full width.', 'shariff' ) . '</div>';
		echo '</div>';
		// borderradius
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">borderradius</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">1-50</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">50</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff borderradius="1"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Sets the border radius for the round theme. 1 essentially equals a square.', 'shariff' ) . '</div>';
		echo '</div>';
		// maincolor
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">maincolor</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff maincolor="#000"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Sets a custom main color for all buttons (hexadecimal).', 'shariff' ) . '</div>';
		echo '</div>';
		// secondarycolor
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">secondarycolor</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff secondarycolor="#afafaf"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Sets a custom secondary color for all buttons (hexadecimal). The secondary color is, depending on theme, used for hover effects.', 'shariff' ) . '</div>';
		echo '</div>';
		// orientation
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">orientation</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">horizontal<br>vertical</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">horizontal</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff orientation="vertical"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Changes the orientation of the buttons.', 'shariff' ) . '</div>';
		echo '</div>';
		// alignment
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">align</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">flex-start<br>center<br>flex-end</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">flex-start</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff align="center"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Changes the horizontal alignment of the buttons. flex-start means left, center is obvious and flex-end means right.', 'shariff' ) . '</div>';
		echo '</div>';
		// language
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">language</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">da, de, en, es, fi, fr, hr, hu, it, ja, ko, nl, no, pl, pt, ro, ru, sk, sl, sr, sv, tr, zh</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Automatically selected by browser.', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff lang="de"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Changes the language of the share buttons.', 'shariff' ) . '</div>';
		echo '</div>';
		// headline
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">headline</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff headline="&lt;hr style=\'margin:20px 0\'&gt;&lt;p&gt;' . __( 'Please share this post:', 'shariff' ) . '&lt;/p&gt;"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Adds a headline above the Shariff buttons. Basic HTML as well as style and class attributes can be used. To remove a headline set on the plugins options page use headline="".', 'shariff' ) . '</div>';
		echo '</div>';
		// style
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">style</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff style="margin:20px;"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Adds custom <u>style</u> attributes to the container <u>around</u> Shariff.', 'shariff' ) . '</div>';
		echo '</div>';
		// cssclass
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">cssclass</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff class="classname"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Adds a custom <u>class</u> to the container <u>around</u> Shariff.', 'shariff' ) . '</div>';
		echo '</div>';
		// twitter_via
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">twitter_via</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff twitter_via="your_twittername"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Sets the Twitter via tag.', 'shariff' ) . '</div>';
		echo '</div>';
		// flattruser
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">flattruser</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff flattruser="your_username"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Sets the Flattr username.', 'shariff' ) . '</div>';
		echo '</div>';
		// patreonid
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">patreonid</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff patreonid="your_username"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Sets the Patreon username.', 'shariff' ) . '</div>';
		echo '</div>';
		// paypalbuttonid
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">paypalbuttonid</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff paypalbuttonid="hosted_button_id"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Sets the PayPal hosted button ID.', 'shariff' ) . '</div>';
		echo '</div>';
		// paypalmeid
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">paypalmeid</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff paypalmeid="name"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Sets the PayPal.Me ID. Default amount can be added with a / e.g. name/25.', 'shariff' ) . '</div>';
		echo '</div>';
		// bitcoinaddress
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">bitcoinaddress</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff bitcoinaddress="bitcoin_address"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Sets the bitcoin address.', 'shariff' ) . '</div>';
		echo '</div>';
		// media
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">media</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'The post featured image or the first image of the post.</div>', 'shariff' );
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff media="http://www.mydomain.com/image.jpg"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Determines the default image to share for Pinterest, if no other usable image is found.', 'shariff' ) . '</div>';
		echo '</div>';
		// info_url
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">info_url</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">http://ct.de/-2467514</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff info_url="http://www.mydomain.com/shariff-buttons"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Sets a custom link for the info button.', 'shariff' ) . '</div>';
		echo '</div>';
		// url
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">url</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'The url of the current post or page.', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff url="http://www.mydomain.com/somepost"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Changes the url to share. Only for special use cases.', 'shariff' ) . '</div>';
		echo '</div>';
		// title
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">title</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'The title of the current post or page.', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff title="' . __( 'My Post Title', 'shariff' ) . '"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Changes the title to share. Only for special use cases.', 'shariff' ) . '</div>';
		echo '</div>';
		// timestamp
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">timestamp</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'The timestamp of the last modification of the current post or page.', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff timestamp="1473240010"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Provides the time the current post or page was last modified as a timestamp. Used for determining the dynamic cache lifespan. Only for special use cases.', 'shariff' ) . '</div>';
		echo '</div>';
		// rssfeed
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">rssfeed</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">http://www.mydomain.com/feed/rss/</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff rssfeed="http://www.mydomain.com/feed/rss2/"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . __( 'Changes the rss feed url to another feed.', 'shariff' ) . '</div>';
		echo '</div>';

	echo '</div>';
}

// status section

function shariff3UU_status_section_callback() {
	// options
	$shariff3UU = $GLOBALS["shariff3UU"];

	// status table
	echo '<div style="display:table;border-spacing:10px;margin:-10px 0 0 -10px">';

	// statistic row
	echo '<div style="display:table-row">';
	echo '<div style="display:table-cell;width:125px">' . __( 'Statistic:', 'shariff' ) . '</div>';

	// check if statistic is enabled
	if ( ! isset( $shariff3UU['backend'] ) ) {
		// statistic disabled message
		echo '<div style="display:table">';
		echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold">' . __( 'Disabled', 'shariff' ) . '</div></div>';
		echo '</div>';
		// end statistic row, if statistic is disabled
		echo '</div>';
	}
	else {
		// encode shareurl
		$post_url  = urlencode( esc_url( get_bloginfo( 'url' ) ) );
		$post_url2 = esc_url( get_bloginfo( 'url' ) );
		
		// set services
		$services = array( 'facebook', 'twitter', 'googleplus', 'pinterest', 'linkedin', 'xing', 'reddit', 'stumbleupon', 'tumblr', 'vk', 'addthis', 'flattr', 'odnoklassniki' );

		// we only need the backend part
		$backend = '1';

		// but we also want error messages
		$record_errors = '1';

		// avoid debug messages
		$service_errors = array();
		
		// loop through all desired services
		foreach( $services as $service ) {
			// include service parameters
			if ( ! isset ( $shariff3UU["disable"][$service] ) || ( isset ( $shariff3UU["disable"][$service] ) && $shariff3UU["disable"][$service] == 0 ) ) {
				include( plugin_dir_path( __FILE__ ) . '../services/shariff-' . $service . '.php' );
			}
		}

		// general statistic status
		echo '<div style="display:table-cell">';
			echo '<div style="display:table">';
			if ( empty( $service_errors ) ) {
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:green">' . __( 'OK', 'shariff' ) . '</div></div>';
				echo '<div style="display:table-row"><div style="display:table-cell">' . __( 'No error messages.', 'shariff' ) . '</div></div>';
			}
			elseif ( array_filter( $service_errors ) ) {
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:red">' . __( 'Error', 'shariff' ) . '</div></div>';
				echo '<div style="display:table-row"><div style="display:table-cell">' . __( 'One or more services reported an error.', 'shariff' ) . '</div></div>';				
			}
			else {
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:orange">' . __( 'Timeout', 'shariff' ) . '</div></div>';
				echo '<div style="display:table-row"><div style="display:table-cell">' . __( 'One or more services didn\'t respond in less than five seconds.', 'shariff' ) . '</div></div>';
			}
			echo '<div style="display:table-row"><div style="display:table-cell"></div></div>';
			echo '</div>';
		echo '</div>';

		// end statistic row
		echo '</div>';

		// output all services
		foreach( $services as $service ) {
			// service row
			echo '<div style="display:table-row">';
				echo '<div class="shariff_status-first-cell">' . ucfirst( $service ) . ':</div>';
				echo '<div style="display:table-cell">';
					echo '<div class="shariff_status-table">';
					if ( isset ( $shariff3UU["disable"][$service] ) && $shariff3UU["disable"][$service] == '1' ) {
						echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold">' . __( 'Disabled', 'shariff' ) . '</div></div>';
					}
					elseif ( ! array_key_exists( $service, $service_errors ) ) {
						echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:green">' . __( 'OK', 'shariff' ) . '</div></div>';
						echo '<div style="display:table-row"><div style="display:table-cell">' . __( 'Share Count:', 'shariff' ) . ' ' . $share_counts[$service] . '</div></div>';
					}
					elseif ( empty( $service_errors[$service] ) ) {
						echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:orange">' . __( 'Timeout', 'shariff' ) . '</div></div>';
						echo '<div style="display:table-row"><div style="display:table-cell">';
							echo __( 'Service didn\'t respond in less than five seconds.', 'shariff' );
						echo '</div></div>';
					}
					else {
						echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:red">' . __( 'Error', 'shariff' ) . '</span></div></div>';
						echo '<div style="display:table-row"><div style="display:table-cell">';
							echo $service_errors[$service];
						echo '</div></div>';
					}
					echo '<div style="display:table-row"><div style="display:table-cell"></div></div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	}

	// GD needed for QR codes of the Bitcoin links
	echo '<div style="display:table-row">';
	echo '<div style="display:table-cell">' . __( 'GD Library:', 'shariff' ) . '</div>';
	// working message
	if ( function_exists( 'gd_info' ) ) {
		$tmpGDinfo = gd_info();
		echo '<div style="display:table-cell">';
			echo '<div style="display: table">';
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:green">' . __( 'OK', 'shariff' ) . '</div></div>';
				echo '<div style="display:table-row"><div style="display:table-cell">Version: ' . $tmpGDinfo["GD Version"] . '</div></div>';
			echo '</div>';
		echo '</div>';
	}
	else {
		echo '<div style="display:table-cell">';
			echo '<div style="display: table">';
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:red">' . __( 'Error', 'shariff' ) . '</div></div>';
				echo '<div style="display:table-row"><div style="display:table-cell">' . __( 'The GD Library is not installed on this server. This is only needed for the QR codes, if your are using the bitcoin button.', 'shariff' ) . '</div></div>';
			echo '</div>';
		echo '</div>';
	}
	echo '</div>';

	// end status table
	echo '</div>';
}

// ranking section

function shariff3UU_ranking_section_callback() {
	// post array
	$posts = array();
	
	// services
	$services = array();

	// amount of posts - set to 100 if not set
	if ( isset( $GLOBALS["shariff3UU"]["ranking"] ) && absint( $GLOBALS["shariff3UU"]["ranking"] ) > '0' ) {
		$numberposts = absint( $GLOBALS["shariff3UU"]["ranking"] );
	}
	else {
		$numberposts = '100';
	}
	
	// catch last 100 posts or whatever number is set for it
	$args = array( 'numberposts' => $numberposts, 'orderby' => 'post_date', 'order' => 'DESC', 'post_status' => 'publish' );
	$recent_posts = wp_get_recent_posts( $args );
	if ( $recent_posts ) {
		foreach( $recent_posts as $recent ) {
			// get url
			$url = get_permalink( $recent["ID"] );
			$post_url = urlencode( $url );
			// set transient name
			$post_hash = 'shariff' . hash( "md5", $post_url );
			// get share counts from cache
			if ( get_transient( $post_hash ) !== false ) {
				$share_counts = get_transient( $post_hash );
				$services = array_merge( $services, $share_counts );
				if ( isset( $share_counts['total'] ) ) $total = $share_counts['total'];
				else $total = '0';
			}
			else {
				$share_counts = array(); 
				$total = '';
			}
			// add to array
			$posts[ $post_hash ] = array( 'url' => $url, 'title' => $recent["post_title"], 'post_date' => $recent['post_date'], 'share_counts' => $share_counts, 'total_share_counts' => $total );
		}
	}
	
	// clean services
	unset( $services['total'] );
	unset( $services['timestamp'] );
	unset( $services['url'] );
	ksort( $services );

	// sort array: first decending using total share counts then descending using post date
	$tmp = Array();
	$tmp2 = Array();
	foreach( $posts as &$ma ) $tmp[] = &$ma["total_share_counts"]; 
	foreach( $posts as &$ma2 ) $tmp2[] = &$ma2["post_date"]; 
	array_multisort( $tmp, SORT_DESC, $tmp2, SORT_DESC, $posts );

	// intro
	echo '<p>';
		echo __( 'The following table shows the ranking of your last 100 posts in descending order by total share counts. To prevent slow loading times only cached data is being used. Therefore, you may see blank entries for posts that have not been visited by anyone since the last update or activation of Shariff Wrapper. You can simply visit the respective post yourself in order to have the share counts fetched.', 'shariff' );
	echo '</p>';

	// warning if statistic has been disabled
	if ( ! isset( $GLOBALS["shariff3UU"]["backend"] ) ) {
		echo '<p>';
			echo '<span style="color: red; font-weight: bold;">';
				echo __( 'Warning:', 'shariff' );
			echo '</span> ';
			echo __( 'The statistic option has been disabled on the statistic tab. Share counts will not get updated!', 'shariff' );
		echo '</p>';
	}
	
	// ranking table
	echo '<div style="display:table;background-color:#fff">';
		// head
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px">' . __( 'Rank', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px">' . __( 'Post', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center">' . __( 'Date', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center">' . __( 'Time', 'shariff' ) . '</div>';
			foreach( $services as $service => $nothing ) echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center;">' . ucfirst( $service ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . __( 'Total', 'shariff' ) . '</div>';
		echo '</div>';
		// posts
		$rank = '0';
		foreach( $posts as $post => $value ) {
			$rank++;
			echo '<div style="display:table-row">';
				echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">' . $rank . '</div>';
				echo '<div style="display:table-cell;border:1px solid;padding:10px"><a href="' . $value['url'] . '" target="_blank">' . $value['title'] . '</a></div>';
				echo '<div style="display:table-cell;border:1px solid;padding:10px">' . mysql2date( 'd.m.Y', $value['post_date'] ) . '</div>';
				echo '<div style="display:table-cell;border:1px solid;padding:10px">' . mysql2date( 'H:i', $value['post_date'] ) . '</div>';
				// share counts
				foreach( $services as $service => $nothing ) {
					echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">';
						if( isset( $value['share_counts'][$service] ) ) echo $value['share_counts'][$service];
					echo '</div>';
				}
				echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">';
					if ( isset( $value['share_counts']['total'] ) ) echo $value['share_counts']['total'];
				echo '</div>';
			echo '</div>';
		}
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
		echo '">' . __( 'Basic', 'shariff' ) . '</a>';
		// design
		echo '<a href="?page=shariff3uu&tab=design" class="nav-tab ';
		if ( $active_tab == 'design' ) echo 'nav-tab-active';
		echo '">' . __( 'Design', 'shariff' ) . '</a>';
		// advanced
		echo '<a href="?page=shariff3uu&tab=advanced" class="nav-tab ';
		if ( $active_tab == 'advanced' ) echo 'nav-tab-active';
		echo '">' . __( 'Advanced', 'shariff' ) . '</a>';
		// mailform
		echo '<a href="?page=shariff3uu&tab=mailform" class="nav-tab ';
		if ( $active_tab == 'mailform' ) echo 'nav-tab-active';
		echo '">' . __( 'Mail Form', 'shariff' ) . '</a>';
		// statistic
		echo '<a href="?page=shariff3uu&tab=statistic" class="nav-tab ';
		if ( $active_tab == 'statistic' ) echo 'nav-tab-active';
		echo '">' . __( 'Statistic', 'shariff' ) . '</a>';
		// help
		echo '<a href="?page=shariff3uu&tab=help" class="nav-tab ';
		if ( $active_tab == 'help' ) echo 'nav-tab-active';
		echo '">' . __( 'Help', 'shariff' ) . '</a>';
		// status
		echo '<a href="?page=shariff3uu&tab=status" class="nav-tab ';
		if ( $active_tab == 'status' ) echo 'nav-tab-active';
		echo '">' . __( 'Status', 'shariff' ) . '</a>';
		// ranking
		echo '<a href="?page=shariff3uu&tab=ranking" class="nav-tab ';
		if ( $active_tab == 'ranking' ) echo 'nav-tab-active';
		echo '">' . __( 'Ranking', 'shariff' ) . '</a>';
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
    elseif ( $active_tab == 'ranking' ) {
		settings_fields( 'ranking' );
		do_settings_sections( 'ranking' );
	}

	// end of form
	echo '</form>';
} // end of plugin option page
