<?php
/*
 * Plugin Name: Shariff Wrapper
 * Plugin URI: https://de.wordpress.org/plugins/shariff/
 * Description: The Shariff Wrapper provides share buttons that respect the privacy of your visitors and are compliant to the German data protection laws.
 * Version: 4.3.0
 * Author: Jan-Peter Lambeck & 3UU
 * Author URI: https://de.wordpress.org/plugins/shariff/
 * License: MIT
 * License URI: http://opensource.org/licenses/MIT
 * Donate link: http://folge.link/?bitcoin:1Ritz1iUaLaxuYcXhUCoFhkVRH6GWiMTP
 * Text Domain: shariff
 */

// prevent direct calls to shariff.php
if ( ! class_exists('WP') ) { die(); }

// get options (needed for front- and backend)
$shariff3UU_basic = (array) get_option( 'shariff3UU_basic' );
$shariff3UU_design = (array) get_option( 'shariff3UU_design' );
$shariff3UU_advanced = (array) get_option( 'shariff3UU_advanced' );
$shariff3UU_mailform = (array) get_option( 'shariff3UU_mailform' );
$shariff3UU_statistic = (array) get_option( 'shariff3UU_statistic' );
$shariff3UU = array_merge( $shariff3UU_basic, $shariff3UU_design, $shariff3UU_advanced, $shariff3UU_mailform, $shariff3UU_statistic );

// update function to perform tasks _once_ after an update, based on version number to work for automatic as well as manual updates
function shariff3UU_update() {
	/******************** ADJUST VERSION ********************/
	$code_version = "4.3.0"; // set code version - needs to be adjusted for every new version!
	/******************** ADJUST VERSION ********************/

	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];

	// check if the installed version is older than the code version and include updates.php if neccessary
	if ( empty( $shariff3UU["version"] ) || ( isset( $shariff3UU["version"] ) && version_compare( $shariff3UU["version"], $code_version ) == '-1' ) ) {
		// include updates.php
		include( plugin_dir_path( __FILE__ ) . 'updates.php' );
	}
}
add_action( 'admin_init', 'shariff3UU_update' );

// allowed tags for headline
$allowed_tags = array(
	// direct formatting e.g. <strong>
	'strong' => array(),
	'em'     => array(),
	'b'      => array(),
	'i'      => array(),
	'br'     => array(),
	// elements that can be formatted via CSS
	'span' => array( 'class' => array(), 'style' => array(), 'id' => array() ),
	'div'  => array( 'class' => array(), 'style' => array(), 'id' => array() ),
	'p'    => array( 'class' => array(), 'style' => array(), 'id' => array() ),
	'h1'   => array( 'class' => array(), 'style' => array(), 'id' => array() ),
	'h2'   => array( 'class' => array(), 'style' => array(), 'id' => array() ),
	'h3'   => array( 'class' => array(), 'style' => array(), 'id' => array() ),
	'h4'   => array( 'class' => array(), 'style' => array(), 'id' => array() ),
	'h5'   => array( 'class' => array(), 'style' => array(), 'id' => array() ),
	'h6'   => array( 'class' => array(), 'style' => array(), 'id' => array() ),
	'hr'   => array( 'class' => array(), 'style' => array(), 'id' => array() ),
);

// admin options
if ( is_admin() ) {
	// include admin_menu.php
	include( plugin_dir_path( __FILE__ ) . 'admin/admin_menu.php' );
	// include admin_notices.php
	include( plugin_dir_path( __FILE__ ) . 'admin/admin_notices.php' );
}

// custom meta box
function shariff3UU_include_metabox() {
	// check if user is allowed to publish posts
	if ( current_user_can( 'publish_posts' ) ) {
		// include admin_metabox.php
		include( plugin_dir_path( __FILE__ ) . 'admin/admin_metabox.php' );
	}
}
add_action('init','shariff3UU_include_metabox');

// waiting for WordPress core to handle the saving of the dismiss click themself
function shariff3UU_dismiss_update_notice() {
	update_option( 'shariff3UU_hide_update_notice', 'hide' );
}
add_action( 'wp_ajax_shariffdismiss', 'shariff3UU_dismiss_update_notice' );

// add meta links on plugin page
function shariff3UU_meta_links( $links, $file ) {
	$plugin = plugin_basename(__FILE__);
	// create link
	if ( $file == $plugin ) {
		return array_merge(
			$links,
			array( '<a href="options-general.php?page=shariff3uu">' . __( 'Settings', 'shariff' ) . '</a>', '<a href="https://wordpress.org/support/plugin/shariff" target="_blank">' . __( 'Support Forum', 'shariff' ) . '</a>' )
		);
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'shariff3UU_meta_links', 10, 2 );

// translations
function shariff_init_locale() {
	if ( function_exists( 'load_plugin_textdomain' ) ) {
		load_plugin_textdomain( 'shariff' );
	}
}

// register wp rest api route and sanitize input
function shariff3UU_sanitize_api() {
	register_rest_route( 'shariff/v1', '/share_counts', array(
		'methods' => 'GET',
		'callback' => 'shariff3UU_share_counts',
		'args' => array(
			'url' => array( 'sanitize_callback' => 'esc_url' ),
			'services' => array( 'sanitize_callback' => 'sanitize_text_field' ),
			'timestamp' => array( 'sanitize_callback' => 'absint' ),
		),
	) );
}
add_action( 'rest_api_init', 'shariff3UU_sanitize_api' );

// provide share counts via the wp rest api
function shariff3UU_share_counts( WP_REST_Request $request ) {
	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];

	// parameters
	$url = urldecode( $request['url'] );
	$services = $request['services'];
	$timestamp = $request['timestamp'];
	
	// exit if no url is provided
	if ( empty( $url ) || $url == 'undefined' ) {
		return new WP_Error( 'nourl', 'No URL provided!', array( 'status' => 400 ) );
	}

	// exit if no services are provided
	if ( empty( $services ) || $services == 'undefined' ) {
		return new WP_Error( 'noservices', 'No services provided!', array( 'status' => 400 ) );
	}

	// make sure that the provided url matches the WordPress domain
	$get_url = parse_url( $url );
	$wp_url = parse_url( get_bloginfo('url') );
	// on an external backend check allowed hosts
	if ( defined( 'SHARIFF_FRONTENDS' ) ) {
		$shariff_frontends = array_flip( explode( '|', SHARIFF_FRONTENDS ) );
		if ( ! isset( $get_url['host'] ) || ! array_key_exists( $get_url['host'], $shariff_frontends ) ) {
			return new WP_Error( 'externaldomainnotallowed', 'External domain not allowed by this server!', array( 'status' => 400 ) );
		}
	}
	// else compare that domain is equal 
	elseif ( ! isset( $get_url['host'] ) || $get_url['host'] != $wp_url['host'] ) {
		return new WP_Error( 'domainnotallowed', 'Domain not allowed by this server!', array( 'status' => 400 ) );
	}

	// encode shareurl
	$post_url  = urlencode( esc_url( $url ) );
	$post_url2 = $url;
	
	// set transient name
	// transient names can only contain 40 characters, therefore we use a hash (md5 always creeates a 32 character hash)
	// we need a prefix so we can clean up on deinstallation and updates
	$post_hash = 'shariff' . hash( "md5", $post_url );

	// check for ttl option, must be between 60 and 7200 seconds
	if ( isset( $shariff3UU['ttl'] ) ) {
		$ttl = absint( $shariff3UU['ttl'] );
		// make sure ttl is a reasonable number
		if ( $ttl < '61' ) $ttl = '60';
		elseif ( $ttl > '7200' ) $ttl = '7200';
	}
	// else set it to new default (five minutes)
	else {
		$ttl = '300';
	}

	// adjust ttl based on the post age
	if ( isset ( $timestamp ) && ( ! isset( $shariff3UU["disable_dynamic_cache"] ) || ( isset( $shariff3UU["disable_dynamic_cache"] ) && $shariff3UU["disable_dynamic_cache"] != '1' ) ) ) {
		// the timestamp represents the last time the post or page was modfied
		$post_time = intval( $timestamp );
		$current_time = current_time( 'timestamp', true );
		$post_age = round( abs( $current_time - $post_time ) );
		if ( $post_age > '0' ) {
			$post_age_days = round( $post_age / 60 / 60 / 24 );
			// make sure ttl base is not getting too high
			if ( $ttl > '300' ) $ttl = '300';
			$ttl = round( ( $ttl + $post_age_days * 3 ) * ( $post_age_days * 2 ) );
		}
		// set minimum ttl to 60 seconds and maxium ttl to one week
		if ( $ttl < '60' ) {
			$ttl = '60';
		}
		elseif ( $ttl > '604800' ) {
			$ttl = '604800';
		}
		// in case we get a timestamp older than 01.01.2000 or for example a 0, use a reasonable default value of five minutes
		if ( $post_time < '946684800' ) {
			$ttl = '300';
		}
	}

	// default
	$need_update = false;

	// remove totalnumber for array
	$real_services = str_replace( 'totalnumber|', '', $services );
	$real_services = str_replace( '|totalnumber', '', $real_services );

	// explode services
	$service_array = explode( '|', $real_services );
	
	// remove duplicated entries
	$service_array = array_unique( $service_array );

	// get old share counts
	if ( get_transient( $post_hash ) !== false ) $old_share_counts = get_transient( $post_hash );
	else $old_share_counts = array();

	// check if we need to update
	if ( get_transient( $post_hash ) !== false ) {
		// check timestamp
		$diff = current_time( 'timestamp', true ) - $old_share_counts['timestamp'];
		if ( $diff > $ttl ) $need_update = true;
		// check if we have a different set of services than stored in the cache
		$diff_array = array_diff_key( array_flip( $service_array ), $old_share_counts );
		if ( ! empty( $diff_array ) ) {
			$need_update = true;
			// we only need to update the missing service
			$service_array = array_flip( $diff_array );
		}
	}
	else $need_update = true;
	
	// prevent php notices
	$response = '';
	$share_counts = array();

	// if we do not need an update, use stored data
	if ( $need_update === false ) {
		$share_counts = $old_share_counts;
		// update info
		$share_counts['updated'] = '0';
	}
	// if only totalnumber is requested we only use cached data
	elseif ( $services == 'totalnumber' ) {
		$share_counts = $old_share_counts;
	}
	// elseif we want to use an external API
	elseif ( isset( $shariff3UU["external_host"] ) && ! empty( $shariff3UU["external_host"] ) ) {
		$response = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( $shariff3UU["external_host"] . '?url=' . urlencode( $url ) . '&services=' . $services . '&timestamp=' . $timestamp . '"' ) ) );
		$share_counts = json_decode( $response, true );
		// save transient
		set_transient( $post_hash, $share_counts, '604800' );
		// offer a hook to work with the share counts
		do_action( 'shariff_share_counts', $share_counts );
	}
	// else we fetch new counts ourselfs
	else {
		$share_counts = shariff3UU_fetch_sharecounts( $service_array, $old_share_counts, $post_hash, $post_url, $post_url2 );
	}

	// return results, if we have some or an error message if not
	if ( isset( $share_counts ) && $share_counts != null ) {
		return $share_counts;
	}
}

// fetch share counts
function shariff3UU_fetch_sharecounts( $service_array, $old_share_counts, $post_hash, $post_url, $post_url2 ) {
	// we only need the backend part from the service phps
	$backend = '1';

	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];

	// prevent php notices
	$total_count = '0';
	$share_counts = array();
			
	// loop through all desired services
	foreach ( $service_array as $service ) {
		// only include services that are not disabled
		if ( ! empty( $service ) && ( ! isset( $shariff3UU["disable"][ $service ] ) || ( isset( $shariff3UU["disable"][ $service ] ) && $shariff3UU["disable"][ $service ] == 0 ) ) ) {
			// determine path
			$path_service_file = plugin_dir_path( __FILE__ ) . 'services/shariff-' . $service . '.php';
			// include service files
			if ( file_exists( $path_service_file ) ) include( $path_service_file );
			// if we have an error (e.g. a timeout) and we have an old share count for this service, keep it!
			if ( array_key_exists( $service, $old_share_counts ) && ( ! array_key_exists( $service, $share_counts ) || empty( $share_counts[ $service ] ) ) ) {
				$share_counts[ $service ] = $old_share_counts[ $service ];
			}
		}
		// calculate total share count
		if ( isset( $share_counts[ $service ] ) ) $total_count = $total_count + $share_counts[ $service ];
	}

	// add total count
	if ( $total_count != '0' ) $share_counts[ 'total' ] = $total_count;

	// save transient, if we have counts
	if ( isset( $share_counts ) ) {
		// add current timestamp and url
		$share_counts['timestamp'] = current_time( 'timestamp', true );
		$share_counts['url'] = $post_url2;
		// combine different set of services
		if ( get_transient( $post_hash ) !== false ) {
			$other_request = get_transient( $post_hash );
			$share_counts = array_merge( $other_request, $share_counts );
		}
		// save transient
		set_transient( $post_hash, $share_counts, '604800' );
		// offer a hook to work with the share counts
		do_action( 'shariff_share_counts', $share_counts );
		// update info
		$share_counts['updated'] = '1';
	}
	elseif ( isset( $old_share_counts ) ) {
		$share_counts = $old_share_counts;
		// update info
		$share_counts['updated'] = '0';
	}

	// return share counts
	return $share_counts;
}

// fill cache automatically
function shariff3UU_fill_cache() {
	// amount of posts - set to 100 if not set
	if ( isset( $GLOBALS["shariff3UU"]["ranking"] ) && absint( $GLOBALS["shariff3UU"]["ranking"] ) > '0' ) {
		$numberposts = absint( $GLOBALS["shariff3UU"]["ranking"] );
	}
	else {
		$numberposts = '100';
	}

	// avoid errors if no services are given - instead use the default set of services
	if ( empty( $GLOBALS["shariff3UU"]["services"] ) ) $services = "twitter|facebook|googleplus";
	else $services = $GLOBALS["shariff3UU"]["services"];

	// explode services
	$service_array = explode( '|', $services );
	
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
			// get old share counts
			if ( get_transient( $post_hash ) !== false ) $old_share_counts = get_transient( $post_hash );
			else $old_share_counts = array();
			// fetch share counts and save same
			shariff3UU_fetch_sharecounts( $service_array, $old_share_counts, $post_hash, $post_url, $url );
		}
	}
}
add_action( 'shariff3UU_fill_cache', 'shariff3UU_fill_cache' );

// add schedule event in order to fill cache automatically
function shariff3UU_fill_cache_schedule() {
	// get options manually bc of start on activation
	$shariff3UU_statistic = (array) get_option( 'shariff3UU_statistic' );
	// check if option is set
	if ( isset( $shariff3UU_statistic["automaticcache"] ) && $shariff3UU_statistic["automaticcache"] == '1' ) {
		// check if job is already scheduled
		if ( ! wp_next_scheduled( 'shariff3UU_fill_cache' ) ) {
			// add cron job
			wp_schedule_event( time(), 'weekly', 'shariff3UU_fill_cache' );
		}
	}
	// else option is not set therefore remove cron job if scheduled
	else {
		if ( wp_next_scheduled( 'shariff3UU_fill_cache' ) ) {
			// remove cron job
			wp_clear_scheduled_hook( 'shariff3UU_fill_cache' );
		}
	}
}
add_action( 'shariff3UU_save_statistic_options', 'shariff3UU_fill_cache_schedule' );

// custom weekly cron recurrences
function shariff3UU_fill_cache_schedule_custom_recurrence( $schedules ) {
	$schedules['weekly'] = array(
		'display' => __( 'Once weekly', 'shariff' ),
		'interval' => '804600',
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'shariff3UU_fill_cache_schedule_custom_recurrence' );

// add shorttag to posts
function shariff3UU_posts( $content ) {

	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];

	// do not add Shariff to excerpts or outside the loop, if option is checked
	if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) || ( ! in_the_loop() && isset( $shariff3UU["disable_outside_loop"] ) && $shariff3UU["disable_outside_loop"] == '1' ) ) {
		return $content;
	}

	// disable share buttons on password protected posts if configured in the admin menu
	if ( ( post_password_required( get_the_ID() ) == '1' || ! empty( $GLOBALS["post"]->post_password ) ) && isset( $shariff3UU["disable_on_protected"] ) && $shariff3UU["disable_on_protected"] == '1') {
		$shariff3UU["add_before"]["posts"] = '0';
		$shariff3UU["add_before"]["posts_blogpage"] = '0';
		$shariff3UU["add_before"]["pages"] = '0';
		$shariff3UU["add_after"]["posts"] = '0';
		$shariff3UU["add_after"]["posts_blogpage"] = '0';
		$shariff3UU["add_after"]["pages"] = '0';
		$shariff3UU["add_after"]["custom_type"] = '0';
	}

	// if we want see it as text - replace the slash
	if ( strpos( $content,'/hideshariff' ) == true ) {
		$content = str_replace( "/hideshariff", "hideshariff", $content );
	}
	// but not, if the hidshariff sign is in the text |or| if a special formed "[shariff..."  shortcut is found
	elseif( ( strpos( $content, 'hideshariff' ) == true) ) {
		// remove the sign
		$content = str_replace( "hideshariff", "", $content);
		// and return without adding Shariff
		return $content;
	}

	// type of current post
	$current_post_type = get_post_type();
	if ( $current_post_type === 'post' ) $current_post_type = 'posts';
	
	// prevent php warnings in debug mode
	$add_before = '';
	$add_after = '';

	// check if shariff should be added automatically (plugin options)
	if ( ! is_singular() ) {
		// on blog page
		if ( isset( $shariff3UU["add_before"]["posts_blogpage"] ) && $shariff3UU["add_before"]["posts_blogpage"] == '1') $add_before = '1';
		if ( isset( $shariff3UU["add_after"]["posts_blogpage"] ) && $shariff3UU["add_after"]["posts_blogpage"] == '1' ) $add_after = '1';
	}
	elseif ( is_singular( 'post' ) ) {
		// on single post
		if ( isset( $shariff3UU["add_before"][$current_post_type] ) && $shariff3UU["add_before"][$current_post_type] == '1' ) $add_before = '1';
		if ( isset( $shariff3UU["add_after"][$current_post_type] ) && $shariff3UU["add_after"][$current_post_type] == '1' ) $add_after = '1';
	}
	elseif ( is_singular( 'page' ) ) {
		// on pages
		if ( isset( $shariff3UU["add_before"]["pages"] ) && $shariff3UU["add_before"]["pages"] == '1' ) $add_before = '1';
		if ( isset( $shariff3UU["add_after"]["pages"] ) && $shariff3UU["add_after"]["pages"] == '1' ) $add_after = '1';
	}
	else {
		// on custom_post_types
		$all_custom_post_types = get_post_types( array ( '_builtin' => FALSE ) );
		if ( is_array( $all_custom_post_types ) ) {
			$custom_types = array_keys( $all_custom_post_types );
			// add shariff, if custom type and option checked in the admin menu
			if ( isset( $shariff3UU['add_after'][$current_post_type] ) && $shariff3UU['add_after'][$current_post_type] == '1' ) $add_after = '1';
		}
	}
	
	// check if buttons are enabled on a single post or page via the meta box
	if ( get_post_meta( get_the_ID(), 'shariff_metabox_before', true ) ) $add_before = '1';
	if ( get_post_meta( get_the_ID(), 'shariff_metabox_after', true ) ) $add_after = '1';
	
	// add shariff
	if ( $add_before === '1' ) $content = '[shariff]' . $content;
	if ( $add_after === '1' ) $content .= '[shariff]';
	
	// return content
	return $content;
}
if ( ! isset( $GLOBALS["shariff3UU"]["shortcodeprio"] ) ) $GLOBALS["shariff3UU"]["shortcodeprio"] = '10';
add_filter( 'the_content', 'shariff3UU_posts', $GLOBALS["shariff3UU"]["shortcodeprio"]  );

// add shorttag to excerpt
function shariff3UU_excerpt( $content ) {
	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];
	// remove headline in post
	if ( isset( $shariff3UU["headline"] ) ) {
		$content = str_replace( strip_tags( $shariff3UU["headline"] ), " ", $content );
	}
	// add shariff before the excerpt, if option checked in the admin menu
	if ( isset( $shariff3UU["add_before"]["excerpt"] ) && $shariff3UU["add_before"]["excerpt"] == '1' ) {
		$content = do_shortcode( '[shariff]' ) . $content;
	}
	// add shariff after the excerpt, if option checked in the admin menu
	if ( isset( $shariff3UU["add_after"]["excerpt"] ) && $shariff3UU["add_after"]["excerpt"] == '1' ) {
		$content .= do_shortcode( '[shariff]' );
	}
	return $content;
}
add_filter( 'the_excerpt', 'shariff3UU_excerpt' );

// remove hideshariff from content in cases of excerpts or other plain text usages
function shariff3UU_hideshariff( $content ) {
	if ( ( strpos( $content, 'hideshariff' ) == true ) ) {
		// remove the sign
		$content = str_replace( "hideshariff", "", $content );
	}
	return $content;
}
add_filter( 'the_content', 'shariff3UU_hideshariff', 999 );

// remove shariff from rss feeds
function shariff3UU_removefromrss( $content ) {
	$content = preg_replace( '/<div class="shariff\b[^>]*>(.*?)<\/div>/i', '', $content );
	$content = preg_replace( '/<div class="ShariffSC\b[^>]*>(.*?)<\/div>/i', '', $content );
	return $content;
}
add_filter( 'the_content_feed', 'shariff3UU_removefromrss', 999 );

// add mailform to bbpress_replies
function bbp_add_mailform_to_bbpress_replies() {
	$content = '';
	// prepend the mail form
	if ( isset( $_REQUEST['view'] ) && $_REQUEST['view'] == 'mail' ) {
		// only add to single posts view
		$content = shariff3UU_addMailForm( $content, '0' );
	}
	// send the email
	if ( isset( $_REQUEST['act'] ) && $_REQUEST['act'] == 'sendMail' ) $content = sharif3UU_procSentMail( $content );
	echo $content;
}
add_action( 'bbp_theme_after_reply_content', 'bbp_add_mailform_to_bbpress_replies' );

// add shariff buttons after bbpress replies
function bbp_add_shariff_after_replies() {
	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];
	if( isset( $shariff3UU["add_after"]["bbp_reply"] ) && $shariff3UU["add_after"]["bbp_reply"] == '1') echo shariff3UU_render( '' );
}
add_action( 'bbp_theme_after_reply_content', 'bbp_add_shariff_after_replies' );

// register shortcode
add_shortcode( 'shariff', 'shariff3UU_render' );

// render the shorttag to the HTML shorttag of Shariff
function shariff3UU_render( $atts, $content = null ) {
	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];
	
	// avoid errors if no attributes are given - instead use the old set of services to make it backward compatible
	if ( empty( $shariff3UU["services"] ) ) $shariff3UU["services"] = "twitter|facebook|googleplus|info";

	// use the backend option for every option that is not set in the shorttag
	$backend_options = $shariff3UU;
	if ( isset( $shariff3UU["vertical"] ) && $shariff3UU["vertical"] == '1' ) $backend_options["orientation"] = 'vertical';
	if ( isset( $shariff3UU["backend"] ) && $shariff3UU["backend"] == '1' ) $backend_options["backend"] = 'on';
	if ( isset( $shariff3UU["buttonsize"] ) && $shariff3UU["buttonsize"] == '1' ) $backend_options["buttonsize"] = 'small';
	if ( empty( $atts ) ) $atts = $backend_options;
	else $atts = array_merge( $backend_options, $atts );
	
	// get meta box shortcode
	$shariff_metabox_ignore_widget = get_post_meta( get_the_ID(), 'shariff_metabox_ignore_widget', true );
	
	// if we are not a widget or if we are a widget and not beeing set to be ignored we add the meta box settings
	if ( ( ! isset( $atts["widget"] ) || ( isset( $atts["widget"] ) && $atts["widget"] == '1' && $shariff_metabox_ignore_widget != '1' ) ) && $atts["services"] != "total" && $atts["services"] != "totalnumber" ) {
		// get meta box disable value
		$shariff3UU_metabox_disable = get_post_meta( get_the_ID(), 'shariff_metabox_disable', true );
		
		// if the meta box setting is set to diasbled we stop all further actions
		if ( $shariff3UU_metabox_disable == '1' ) return;

		// get meta box shortcode
		$shariff3UU_metabox = get_post_meta( get_the_ID(), 'shariff_metabox', true );
		
		// replace shariff with shariffmeta
		$shariff3UU_metabox = str_replace( '[shariff ', '[shariffmeta ', $shariff3UU_metabox );
		
		// get meta box atts
		do_shortcode( $shariff3UU_metabox );
		if ( isset( $GLOBALS["shariff3UU"]["metabox"] ) ) {
			$metabox = $GLOBALS["shariff3UU"]["metabox"];
		}
		else {
			$metabox = '';
		}
		
		// get meta box media attribute
		$shariff3UU_metabox_media = get_post_meta( get_the_ID(), 'shariff_metabox_media', true );
		if ( ! empty( $shariff3UU_metabox_media ) ) {
			$metabox["media"] = $shariff3UU_metabox_media;
		}
		
		// merge with atts array (meta box shortcode overrides all others)
		if ( ! empty( $metabox ) ) $atts = array_merge( $atts, $metabox );
		
		// clear metabox global
		$GLOBALS["shariff3UU"]["metabox"] = '';
	}

	// Ov3rfly: make atts configurable from outside, e.g. for language etc.
	$atts = apply_filters( 'shariff3UU_render_atts', $atts );

	// remove empty elements
	$atts = array_filter( $atts );

	// clean up services (remove leading or trailing |, spaces, etc.)
	$atts['services'] = trim( preg_replace( "/[^A-Za-z|]/", '', $atts['services'] ), '|' );

	// clean up headline in case it was used in a shorttag
	if ( array_key_exists( 'headline', $atts ) ) {
		$atts['headline'] = wp_kses( $atts['headline'], $GLOBALS["allowed_tags"] );
	}

	// enqueue styles (loading it here makes sure that it is only loaded on pages that acutally contain shariff buttons)
	// if SCRIPT_DEBUG is true, we load the non minified version
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === TRUE ) {
		wp_enqueue_style( 'shariffcss', plugins_url( '/css/shariff.css', __FILE__ ), '', $shariff3UU["version"] );
	}
	else {
		wp_enqueue_style( 'shariffcss', plugins_url( '/css/shariff.min.css', __FILE__ ), '', $shariff3UU["version"] );
	}

	// enqueue share count script (the JS should be loaded at the footer - make sure that wp_footer() is present in your theme!)
	// if SCRIPT_DEBUG is true, we load the non minified version
	if ( array_key_exists( 'backend', $atts ) && $atts['backend'] == "on" ) {
		// if SCRIPT_DEBUG is true, we load the non minified version
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === TRUE ) {
			wp_enqueue_script( 'shariffjs', plugins_url( '/js/shariff.js', __FILE__ ), '', $shariff3UU["version"], true );
		}
		else {
			wp_enqueue_script( 'shariffjs', plugins_url( '/js/shariff.min.js', __FILE__ ), '', $shariff3UU["version"], true );
		}
	}
	
	// enqueue popup script (the JS should be loaded at the footer - make sure that wp_footer() is present in your theme!)
	// if SCRIPT_DEBUG is true, we load the non minified version
	if ( array_key_exists( 'popup', $atts ) && $atts['popup'] == "1" ) {
		// if SCRIPT_DEBUG is true, we load the non minified version
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === TRUE ) {
			wp_enqueue_script( 'shariff_popup', plugins_url( '/js/shariff-popup.js', __FILE__ ), '', $shariff3UU["version"], true );
		}
		else {
			wp_enqueue_script( 'shariff_popup', plugins_url( '/js/shariff-popup.min.js', __FILE__ ), '', $shariff3UU["version"], true );
		}
	}

	// share url
	if ( array_key_exists( 'url', $atts ) ) $share_url = urlencode( $atts['url'] );
	else $share_url = urlencode( get_permalink() );

	// share title
	if ( array_key_exists( 'title', $atts ) ) $share_title = urlencode( $atts['title'] );
	else $share_title = urlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) );

	// set transient name
	$post_hash = 'shariff' . hash( "md5", $share_url );
	
	// prevent php notices
	$share_counts = array();

	// get cached share counts
	if ( array_key_exists( 'backend', $atts ) && $atts['backend'] == "on" && get_transient( $post_hash ) !== false ) {
		$share_counts = get_transient( $post_hash );
	}

	// prevent info notices in case debug mode is on
	$output = '';

	// if we have a custom style attribute or a class add ShariffSC container including these styles
	if ( array_key_exists( 'style', $atts ) || array_key_exists( 'cssclass', $atts ) ) {
		$output .= '<div class="ShariffSC';
		if ( array_key_exists( 'cssclass', $atts ) ) $output .= ' ' . esc_html( $atts['cssclass'] ) . '"';
		else $output .= '"';
		if ( array_key_exists( 'style', $atts ) ) $output .= ' style="' . esc_html( $atts['style'] ) . '"';
		$output .= '>';
	}
	
	// if no language is set, try http_negotiate_language
	if ( ! array_key_exists( 'lang', $atts ) && function_exists('http_negotiate_language') ) {
		$available_lang = array( 'en', 'de', 'fr', 'es', 'zh', 'hr', 'da', 'nl', 'fi', 'it', 'ja', 'ko', 'no', 'pl', 'pt', 'ro', 'ru', 'sk', 'sl', 'sr', 'sv', 'tr', 'zh' );
		$lang = http_negotiate_language( $available_lang );
		$atts['lang'] = substr( $lang, 0, 2 );
	}

	// default button share text
	$default_button_text_array = array(
		'bg' => 'cподеляне',
		'da' => 'del',
		'de' => 'teilen',
		'en' => 'share',
		'es' => 'compartir',
		'fi' => 'Jaa',
		'fr' => 'partager',
		'hr' => 'podijelite',
		'hu' => 'megosztás',
		'it' => 'condividi',
		'ja' => '共有',
		'ko' => '공유하기',
		'nl' => 'delen',
		'no' => 'del',
		'pl' => 'udostępnij',
		'pt' => 'compartilhar',
		'ro' => 'partajează',
		'ru' => 'поделиться',
		'sk' => 'zdieľať',
		'sl' => 'deli',
		'sr' => 'podeli',
		'sv' => 'dela',
		'tr' => 'paylaş',
		'zh' => '分享',
	);

	// add timestamp for cache
	if ( array_key_exists( 'timestamp', $atts ) ) $post_timestamp = $atts['timestamp'];
	else $post_timestamp = absint( get_the_modified_date( 'U' ) );

	// start output of actual Shariff buttons
	$output .= '<div class="shariff shariff-main';
		// alignment
		if ( array_key_exists( 'align', $atts ) && $atts['align'] != 'none' ) {
			$output .= ' shariff-align-' . $atts["align"];
		}
		// alignment widget
		if ( array_key_exists( 'align_widget', $atts ) && $atts['align_widget'] != 'none' ) {
			$output .= ' shariff-widget-align-' . $atts["align_widget"];
		}
		// alignment widget
		if ( array_key_exists( 'buttonstretch', $atts ) && $atts['buttonstretch'] == '1' ) {
			$output .= ' shariff-buttonstretch';
		}
		$output .= '"';
		// hide buttons until css is loaded
		if ( array_key_exists( 'hideuntilcss', $atts ) && $atts['hideuntilcss'] == '1' ) $output .= ' style="display:none"';
		// add information for share count request
		if ( array_key_exists( 'backend', $atts ) && $atts['backend'] == "on" ) {
			// share url
			$output .= ' data-url="' . esc_html( $share_url ) . '"';
			// timestamp for cache
			$output .= ' data-timestamp="' . $post_timestamp . '"';
			// hide share counts when zero
			if ( isset( $atts['hidezero'] ) && $atts['hidezero'] == '1' ) {
				$output .= ' data-hidezero="1"';
			}
			// add external api if entered
			if ( isset( $shariff3UU["external_host"] ) && ! empty( $shariff3UU["external_host"] ) && isset( $shariff3UU["external_direct"] ) ) {
				$output .= ' data-backendurl="' . $shariff3UU["external_host"] . '"';
			}
			// elseif WP is installed in a subdirectory and the api is only reachable in there -> adjust path
			elseif ( isset( $shariff3UU["subapi"] ) &&  $shariff3UU["subapi"] == '1' ) {
				$output .= ' data-backendurl="' . get_bloginfo( 'wpurl' ) . '/wp-json/shariff/v1/share_counts?' . '"';
			}
			// elseif pretty permalinks are not activated fall back to manual rest route
			elseif ( ! get_option('permalink_structure') ) {
				$output .= ' data-backendurl="?rest_route=/shariff/v1/share_counts&"';
			}
			// else use the home url
			else {
				$output .= ' data-backendurl="' . rtrim( home_url(), "/" ) . '/wp-json/shariff/v1/share_counts?' . '"';
			}
		}
	$output .= '>';
	
	// headline
	if ( array_key_exists( 'headline', $atts ) ) {
		if ( ! array_key_exists( 'total', $share_counts ) ) $share_counts['total'] = '0';
		$atts['headline'] = str_replace( '%total', '<span class="shariff-total">' . absint( $share_counts['total'] ) . '</span>', $atts['headline'] );
		$output .= '<div class="ShariffHeadline">' . $atts['headline'] . '</div>';
	}

	// start ul list with design classes
	$output .= '<ul class="shariff-buttons ';
		// theme
		if ( array_key_exists( 'theme', $atts ) )       $output .= 'theme-' . esc_html( $atts['theme'] ) . ' ';
		else $output .= 'theme-default ';
		// orientation
		if ( array_key_exists( 'orientation', $atts ) ) $output .= 'orientation-' . esc_html( $atts['orientation'] ) . ' ';
		else $output .= 'orientation-horizontal ';
		// size
		if ( array_key_exists( 'buttonsize', $atts ) )  $output .= 'buttonsize-' . esc_html( $atts['buttonsize'] );
		else $output .= 'buttonsize-medium';
	$output .= '">';

	// prevent warnings while debug mode is on
	$flattr_error = '';
	$paypal_error = '';
	$paypalme_error = '';
	$bitcoin_error = '';
	$patreon_error = '';
	$button_text_array = '';
	$backend_available = '';
	$mobile_only = '';

	// explode services
	$service_array = explode( '|', $atts['services'] );

	// migrate 2.3.0 mail to mailform
	$service_array = preg_replace( '/\bmail\b/', 'mailform', $service_array );

	// loop through all desired services
	foreach ( $service_array as $service ) {
		// check if necessary usernames are set and display warning to admins, if needed
		if ( $service == 'flattr' && ! array_key_exists( 'flattruser', $atts ) ) $flattr_error = '1';
		elseif ( $service == 'paypal' && ! array_key_exists( 'paypalbuttonid', $atts ) ) $paypal_error = '1';
		elseif ( $service == 'paypalme' && ! array_key_exists( 'paypalmeid', $atts ) ) $paypalme_error = '1';
		elseif ( $service == 'bitcoin' && ! array_key_exists( 'bitcoinaddress', $atts ) ) $bitcoin_error = '1';
		elseif ( $service == 'patreon' && ! array_key_exists( 'patreonid', $atts ) ) $patreon_error = '1';
		// start render button
		elseif ( $service != 'total' && $service != 'totalnumber' ) {
			
			// include service parameters
			$frontend = '1';

			// determine path to service phps
			$path_service_file = plugin_dir_path( __FILE__ ) . 'services/shariff-' . $service . '.php';

			// check if service file exists
			if ( file_exists( $path_service_file ) ) {

				// include service file
				include( $path_service_file );

				// overwrite service specific colors, if custom colors are set
				if ( array_key_exists( 'maincolor', $atts ) ) {
					$main_color = $atts['maincolor'];
				}
				if ( array_key_exists( 'secondarycolor', $atts ) ) {
					$secondary_color = $atts['secondarycolor'];
				}

				// set border radius for round theme
				if ( array_key_exists( 'borderradius', $atts ) && array_key_exists( 'theme', $atts ) && $atts['theme'] == "round" ) {
					$border_radius = '; border-radius:' . $atts['borderradius'] . '%';
				}
				else {
					$border_radius = '';
				}

				// info button for default theme
				if ( ! array_key_exists( 'maincolor', $atts ) && $service == 'info' && ( ( array_key_exists( 'theme', $atts ) && $atts['theme'] == "default" || ( array_key_exists( 'theme', $atts ) && $atts['theme'] == "round" ) ) || ! array_key_exists( 'theme', $atts ) ) ) {
					$main_color = '#fff';
					$secondary_color = "#eee";
				}

				// start li
				$output .= '<li class="shariff-button ' . $service;
					// mobile only
					if ( $mobile_only == '1') $output .= ' shariff-mobile';
				$output .=  '" style="background-color:' . $secondary_color . $border_radius . '">';
					
					// use default button share text, if $button_text_array is empty
					if ( empty( $button_text_array ) ) $button_text_array = $default_button_text_array;

					// set button text in desired language, fallback is English
					if ( array_key_exists( 'lang', $atts ) && array_key_exists( $atts['lang'], $button_text_array ) ) $button_text = $button_text_array[ $atts['lang'] ];
					else $button_text = $button_text_array['en'];

					// set button title / label in desired language, fallback is English
					if ( array_key_exists( 'lang', $atts ) && array_key_exists( $atts['lang'], $button_title_array ) ) $button_title = $button_title_array[ $atts['lang'] ];
					else $button_title = $button_title_array['en'];

					// reset $button_text_array
					$button_text_array = '';

					// build the actual button
					$output .= '<a href="' . $button_url . '" title="' . $button_title . '" aria-label="' . $button_title . '" role="button" rel="';
						if ( $mobile_only != '1' ) $output .= 'noopener ';
						$output .= 'nofollow" class="shariff-link" ';
						// same window?
						if ( ! isset( $same_window ) || isset( $same_window ) && $same_window != '1' ) $output .= 'target="_blank" ';
						$output .= 'style="background-color:' . $main_color . $border_radius;
						// theme white?
						if ( isset( $atts['theme'] ) && $atts['theme'] == "white" ) $output .= '; color:' . $main_color;
						else $output .= '; color:#fff';
					$output .= '">';
						$output .= '<span class="shariff-icon"';
							// theme white?
							if ( isset( $atts['theme'] ) && $atts['theme'] == "white" ) $output .= ' style="fill:' . $main_color . '"';
						$output .= '>' . $svg_icon . '</span>';
						$output .= '<span class="shariff-text">' . $button_text . '</span>&nbsp;';
						// share counts?
						if ( array_key_exists( 'sharecounts', $atts ) && $atts['sharecounts'] == "1" && $backend_available == '1' && ! isset ( $shariff3UU["disable"][ $service ] ) ) {
							$output .= '<span class="shariff-count" data-service="' . $service . '" style="color:' . $main_color;
							if ( array_key_exists( $service, $share_counts ) === true && $share_counts[ $service ] !== null && $share_counts[ $service ] !== '-1' && ( ! isset( $atts['hidezero'] ) || ( isset( $atts['hidezero'] ) && $atts['hidezero'] != '1' ) || ( isset( $atts['hidezero'] ) && $atts['hidezero'] == '1' && $share_counts[ $service ] > 0 ) ) ) {
								$output .= '"> ' . $share_counts[ $service ];
							}
							else $output .= ';opacity:0">';
							$output .= '</span>&nbsp;';
						}
					$output .= '</a>';
				$output .= '</li>';

				// add service to backend service, if available
				if ( $backend_available == '1' && ! isset ( $shariff3UU["disable"][ $service ] ) ) $backend_service_array[] = $service;

				// reset $backend_available, $mobile_only, $same_window
				$backend_available = '';
				$mobile_only = '';
				$same_window = '';
			}
		}
	}

	// add the list of backend services
	if ( ! empty( $backend_service_array ) ) {
		$backend_services = implode( '|', $backend_service_array );
		$output = str_replace( 'data-url=', 'data-services="' . esc_html( urlencode( $backend_services ) ) . '" data-url=', $output );
	}

	// close ul and the main shariff div
	$output .= '</ul></div>';

	// if we had a style attribute close that too
	if ( array_key_exists( 'style', $atts ) ) $output .= '</div>';

	// display warning to admins if flattr is set, but no flattr username is provided
	if ( $flattr_error == '1' && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="shariff-warning">' . __('Username for Flattr is missing!', 'shariff') . '</div>';
	}
	// display warning to admins if patreon is set, but no patreon username is provided
	if ( $patreon_error == '1' && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="shariff-warning">' . __('Username for patreon is missing!', 'shariff') . '</div>';
	}
	// display warning to admins if paypal is set, but no paypal button id is provided
	if ( $paypal_error == '1' && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="shariff-warning">' . __('Button ID for PayPal is missing!', 'shariff') . '</div>';
	}
	// display warning to admins if paypalme is set, but no paypalme id is provided
	if ( $paypalme_error == '1' && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="shariff-warning">' . __('PayPal.Me ID is missing!', 'shariff') . '</div>';
	}
	// display warning to admins if bitcoin is set, but no bitcoin address is provided
	if ( $bitcoin_error == '1' && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="shariff-warning">' . __('Address for Bitcoin is missing!', 'shariff') . '</div>';
	}

	// if the service totalnumber is set, just output the total share count
	if ( array_key_exists( '0', $service_array ) && $service_array['0'] == 'totalnumber' ) {
		$output = '<span class="shariff" data-services="totalnumber" data-url="' . $share_url . '"';
			// add external api
			if ( isset( $shariff3UU["external_host"] ) && ! empty( $shariff3UU["external_host"] ) && isset( $shariff3UU["external_direct"] ) ) {
				$output .= ' data-backendurl="' . $shariff3UU["external_host"] . '"';
			}
		$output .= '><span class="shariff-totalnumber">' . absint( $share_counts['total'] ) . '</span></span>';
	}

	return $output;
}

// register helper shortcode
add_shortcode( 'shariffmeta', 'shariff3UU_meta' );

// meta box helper function
function shariff3UU_meta( $atts, $content = null ) {
	$GLOBALS["shariff3UU"]["metabox"] = $atts;
	return;
}

// prepend mailform if view=mail or send mail if act=sendMail
function shariff3UU_viewmail( $content ) {
	// prepend the mail form and return content
	if ( isset( $_REQUEST['view'] ) && $_REQUEST['view'] == 'mail' ) {
		// only add to single view
		if ( is_singular() ) {
			return $content = shariff3UU_addMailForm( $content, '0' );
		}
	}
	// send mail and return content
	elseif ( isset( $_REQUEST['act'] ) && $_REQUEST['act'] == 'sendMail' ) {
		// only on single view, main query and in the loop
		if ( is_singular() && is_main_query() && in_the_loop() ) {
			return $content = sharif3UU_procSentMail( $content );
		}
	}
	// return content
	else {
		return $content;
	}
}

// only add filter if mailform is not disabled
if ( ! isset( $shariff3UU["disable_mailform"] ) || ( isset( $shariff3UU["disable_mailform"] ) && $shariff3UU["disable_mailform"] != '1' ) ) {
	add_filter( 'the_content', 'shariff3UU_viewmail' );
}

// add mailform
function shariff3UU_addMailForm( $content, $error ) {
	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];

	// check if mailform is disabled
	if ( isset( $shariff3UU["disable_mailform"] ) && $shariff3UU["disable_mailform"] == '1' ) {
		echo '<div id="shariff_mailform" class="shariff_mailform"><div class="shariff_mailform_disabled">';
		echo __( 'Mail form disabled.', 'shariff' );
		echo '</div></div>';
		$mailform = '';
	}
	else {
		// set default language to English as fallback
		$lang = 'EN';

		// available languages
		$available_lang = array( 'EN', 'DE', 'FR', 'IT' );

		// check plugin options
		if ( isset( $shariff3UU["mailform_language"] ) && $shariff3UU["mailform_language"] != 'auto' ) {
			$lang = $shariff3UU["mailform_language"];
		}
		// if language is set to automatic try geoip
		// http://datenverwurstungszentrale.com/stadt-und-land-mittels-geoip-ermitteln-268.htm
		elseif ( function_exists('geoip_country_code_by_name') ) {
			switch ( @geoip_country_code_by_name( $_SERVER[REMOTE_ADDR] ) ) {
				case 'DE': $lang = 'DE';
				break;
				case 'AT': $lang = 'DE';
				break;
				case 'CH': $lang = 'DE';
				break;
				case 'FR': $lang = 'FR';
				break;
				case 'IT': $lang = 'IT';
				break;
				default: $lang = 'EN';
			}
		}
		// if no geoip try http_negotiate_language
		elseif ( function_exists('http_negotiate_language') ) {
			$lang = http_negotiate_language( $available_lang );
		}

		// include selected language
		include( plugin_dir_path( __FILE__ ) . '/locale/mailform-' . $lang . '.php' );

		// use wp_nonce_url / wp_verify_nonce to prevent automated spam by url
		$submit_link = wp_nonce_url( get_permalink(), 'shariff3UU_send_mail', 'shariff_mf_nonce' );

		// add anchor if option is set
		if ( isset( $shariff3UU["mailform_anchor"] ) && $shariff3UU["mailform_anchor"] == '1' ) {
			$submit_link .= '#shariff_mailform';
		}

		// sender address optional?
		$mf_optional_text = '';
		$mf_sender_required = '';
		if ( isset( $shariff3UU["require_sender"] ) && $shariff3UU["require_sender"] == '1' ) {
			// does not work in Safari, but nice to have in all other cases, because less requests
			$mf_sender_required = ' required';
		}
		else {
			$mf_optional_text = $mf_optional[$lang];
		}

		// field content to prefill fields in case of an error
		if ( isset( $error['mf_content_mailto'] ) ) $mf_content_mailto = $error['mf_content_mailto'];
		else $mf_content_mailto = '';
		if ( isset( $error['mf_content_from'] ) ) $mf_content_from = $error['mf_content_from'];
		else $mf_content_from = '';
		if ( isset( $error['mf_content_sender'] ) ) $mf_content_sender = $error['mf_content_sender'];
		else $mf_content_sender = '';
		if ( isset( $error['mf_content_mail_comment'] ) ) $mf_content_mail_comment = $error['mf_content_mail_comment'];
		else $mf_content_mail_comment = '';

		// create the form
		$mailform = '<div id="shariff_mailform" class="shariff_mailform">';
		// wait error
		if ( ! empty ( $error['wait'] ) ) {
			$mailform .= '<div class="shariff_mailform_error">' . sprintf($mf_wait[$lang], $error['wait']) . '</div>';
		}
		// no to address error
		$mf_to_error_html = '';
		if ( ! empty ( $error['to'] ) && $error['to'] == '1' ) {
			$mf_to_error_html = '<span class="shariff_mailform_error"> ' . $mf_to_error[$lang] . '</span>';
		}
		// no from address error
		$mf_from_error_html = '';
		if ( ! empty ( $error['from'] ) && $error['from'] == '1' ) {
			$mf_from_error_html = '<span class="shariff_mailform_error"> ' . $mf_from_error[$lang] . '</span>';
		}
		$mailform .= '<form action="' . $submit_link . '" method="POST">
						<fieldset>
							<div class="shariff_mailform_headline"><legend>' . $mf_headline[$lang] . '</legend>
							<a href="' . get_permalink() . '" class="shariff_closeX"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M10 0c-5.5 0-10 4.5-10 10s4.5 10 10 10 10-4.5 10-10-4.5-10-10-10zM10 18.1c-4.5 0-8.1-3.6-8.1-8.1s3.6-8.1 8.1-8.1 8.1 3.6 8.1 8.1-3.6 8.1-8.1 8.1z"/><path d="M13.1 5l-3.1 3.1-3.1-3.1-1.9 1.9 3.1 3.1-3.1 3.1 1.9 1.9 3.1-3.1 3.1 3.1 1.9-1.9-3.1-3.1 3.1-3.1z"/></svg></a></div>' . $mf_headinfo[$lang] . '
							<input type="hidden" name="act" value="sendMail">
							<input type="hidden" name="lang" value="' . $lang . '">
							<p><label for="mailto">' . $mf_rcpt[$lang] . '</label><br>
							<input type="text" name="mailto" id="mailto" value="' . $mf_content_mailto . '" size="27" placeholder="' . $mf_rcpt_ph[$lang] . '" required>' . $mf_to_error_html . '</p>
							<p><label for="from">' . $mf_from[$lang] . $mf_optional_text . '</label><br>
							<input type="email" name="from" id="from" value="' . $mf_content_from . '" size="27" placeholder="' . $mf_from_ph[$lang] . '" ' . $mf_sender_required .'>' . $mf_from_error_html . '</p>
							<p><label for="name">' . $mf_name[$lang] . '</label><br>
							<input type="text" name="sender" id="sender" value="' . $mf_content_sender . '" size="27" placeholder="' . $mf_name_ph[$lang] . '"></p>
							<p><label for="mail_comment">' . $mf_comment[$lang] . '</label><br>
							<textarea name="mail_comment" rows="4">' . $mf_content_mail_comment . '</textarea></p>
							<input type="url" name="url" id="shariff_mailform_url" value="" size="27" placeholder="">
						</fieldset>
						<p><input type="submit" class="shariff_mailform_submit" value="' . $mf_send[$lang] . '" /></p>
						<p>' . $mf_info[$lang] . '</p>
						</form>
					</div>';
	}
	return $mailform . $content;
}

// helper functions to make it work with PHP < 5.3
// better would be: add_filter( 'wp_mail_from_name', function( $name ) { return sanitize_text_field( $_REQUEST['sender'] ); };
function shariff3UU_set_wp_mail_from_name( $name ) { return sanitize_text_field( $_REQUEST['sender'] ); }
function shariff3UU_set2_wp_mail_from_name( $name ) { return sanitize_text_field( $_REQUEST['from'] ); }
function shariff3UU_set3_wp_mail_from_name( $name ) { return sanitize_text_field( $GLOBALS["shariff3UU"]["mail_sender_name"] ); }
function shariff3UU_set4_wp_mail_from_name( $name ) { return sanitize_text_field( get_bloginfo('name') ); }
function shariff3UU_set_wp_mail_from( $email ) { return sanitize_text_field( $GLOBALS["shariff3UU"]["mail_sender_from"] ); }

// send mail
function sharif3UU_procSentMail( $content ) {
	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];
	
	// honeypot url input
	$mailform_url_field = sanitize_text_field( $_REQUEST['url'] );
	
	// check if mailform is disabled
	if ( isset( $shariff3UU["disable_mailform"] ) && $shariff3UU["disable_mailform"] == '1' ) {
		return $content;
	}
	// check if url field has been filled
	elseif ( ! empty( $mailform_url_field ) ) { 
		return $content;
	}
	else {
		// get vars from form
		$mf_nonce           = sanitize_text_field( $_REQUEST['shariff_mf_nonce'] );
		$mf_content_mailto  = sanitize_text_field( $_REQUEST['mailto'] );
		$mf_content_from    = sanitize_text_field( $_REQUEST['from'] );
		$mf_content_sender  = sanitize_text_field( $_REQUEST['sender'] );
		$mf_lang            = sanitize_text_field( $_REQUEST['lang'] );

		// clean up comments
		$mf_comment_content = $_REQUEST['mail_comment'] ;

		// falls zauberhaft alte Serverkonfiguration, erstmal die Slashes entfernen...
		if ( get_magic_quotes_gpc() == 1 ) $mf_comment_content = stripslashes( $mf_comment_content );

		// ...denn sonst kan wp_kses den content nicht entschaerfen
		$mf_comment_content = wp_kses( $mf_comment_content, '', '' );

		// check if nonce is valid
		if ( isset( $mf_nonce ) && wp_verify_nonce( $mf_nonce, 'shariff3UU_send_mail' ) ) {
			// prevent double execution
			$_REQUEST['shariff_mf_nonce'] = '';
			
			// field content to prefill forms in case of an error
			$error['mf_content_mailto']       = $mf_content_mailto;
			$error['mf_content_from']         = $mf_content_from;
			$error['mf_content_sender']       = $mf_content_sender;
			$error['mf_content_mail_comment'] = $mf_comment_content;
			
			// get min wait time
			if ( isset( $shariff3UU["mailform_wait"] ) ) {
				$minwait = $shariff3UU["mailform_wait"];
			}
			else {
				$minwait = '5';
			}
			
			// rate limiter
			$wait = shariff3UU_limitRemoteUser();
			if ( $wait > $minwait ) {
				$error['error'] = '1';
				$error['wait'] = $wait;
			}
			else {		
				// nicer sender name and address
				if ( ! empty( $mf_content_sender ) ) {
					add_filter( 'wp_mail_from_name', 'shariff3UU_set_wp_mail_from_name' );
				}
				elseif ( ! empty( $mf_content_from ) ) { 
					add_filter( 'wp_mail_from_name', 'shariff3UU_set2_wp_mail_from_name' );
				} 
				elseif ( ! empty( $GLOBALS["shariff3UU_mailform"]["mail_sender_name"] ) ) {
					add_filter( 'wp_mail_from_name', 'shariff3UU_set3_wp_mail_from_name' );
				}
				else { 
					add_filter( 'wp_mail_from_name', 'shariff3UU_set4_wp_mail_from_name' ); 
				}

				// Achtung: NICHT die Absenderadresse selber umschreiben!
				// Das fuehrt bei allen sauber aufgesetzten Absender-MTAs zu Problemen mit SPF und/oder DKIM.
				 
				// default sender address
				if ( ! empty( $shariff3UU["mail_sender_from"] ) ) {
					add_filter( 'wp_mail_from', 'shariff3UU_set_wp_mail_from' );
				}

				// build the array with recipients
				$arr = explode( ',', $mf_content_mailto );
				if ( $arr == FALSE ) $arr = array( $mf_content_mailto );
				// max 5
				for ( $i = 0; $i < count($arr); $i++ ) {
					if ( $i == '5' ) break;
					$tmp_mail = sanitize_email( $arr[$i] );
					// no need to add invalid stuff to the array
					if ( is_email( $tmp_mail ) != false ) {
						$mailto[] = $tmp_mail;
					}
				}

				// set langugage from form
				if ( ! empty( $mf_lang ) ) {
					$lang = $mf_lang;
				}
				else {
					$lang ='EN';
				}

				// fallback to EN if a language is not supported by this plugin translations
				if ( $lang != 'DE' && $lang != 'FR' && $lang != 'IT' ) { $lang = 'EN'; }

				// include selected language
				include( plugin_dir_path( __FILE__ ) . '/locale/mailform-' . $lang . '.php' );

				$subject = html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' );

				// The following post was suggested to you by
				$message[ $lang ] = $mf_mailbody1[ $lang ];

				if ( ! empty( $mf_content_sender ) ) {
					$message[ $lang ] .= $mf_content_sender;
				}
				elseif ( ! empty( $mf_content_from ) ) {
					$message[ $lang ] .= sanitize_text_field( $mf_content_from );
				}
				else {
					// somebody
					$message[ $lang ] .= $mf_mailbody2[ $lang ];
				}
				// :
				$message[ $lang ] .= $mf_mailbody3[ $lang ];

				$message[ $lang ] .= " \r\n\r\n";
				$message[ $lang ] .= get_permalink() . "\r\n\r\n";

				// add comment
				if ( ! empty( $mf_comment_content ) ) {
					$message[ $lang ] .= $mf_comment_content . "\r\n\r\n";
				}

				// post content
				if ( isset( $shariff3UU["mail_add_post_content"] ) && $shariff3UU["mail_add_post_content"] == '1') {
					// strip all html tags
					$post_content = wordwrap( strip_tags( get_the_content() ), 72, "\r\n" );
					// strip all shortcodes
					$post_content = strip_shortcodes( $post_content );
					$message[ $lang ] .= $post_content;
					$message[ $lang ] .= " \r\n";
				}

				$message[ $lang ] .= "\r\n-- \r\n";

				// mail footer / disclaimer
				$message[ $lang ] .= $mf_footer[ $lang ];

				// avoid auto-responder
				$headers = "Precedence: bulk\r\n";

				// if sender address provided, set as return-path, elseif sender required set error
				if ( ! empty( $mf_content_from ) && is_email( $mf_content_from ) != false ) {
					$headers .= "Reply-To: <" . $mf_content_from . ">\r\n";
				}
				elseif ( isset( $shariff3UU["require_sender"] ) && $shariff3UU["require_sender"] == '1' ) {
					$error['error'] = '1';
					$error['from'] = '1';
				}

				// set error, if no usuable recipient e-mail
				if ( empty( $mailto['0'] ) ) {
					$error['error'] = '1';
					$error['to'] = '1';
				}
			}
			// if we have errors provide the mailform again with error message
			if ( isset( $error['error'] ) && $error['error'] == '1' ) {
				$content = shariff3UU_addMailForm( $content, $error );
			}
			// if everything is fine, send the e-mail
			else {
				$mailnotice = '<div id="shariff_mailform" class="shariff_mailform">';
				// The e-mail was successfully send to:
				$mailnotice .= '<div class="shariff_mailform_headline">' . $mf_mail_send[ $lang ] . '<a href="' . get_permalink() . '" class="shariff_closeX"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M10 0c-5.5 0-10 4.5-10 10s4.5 10 10 10 10-4.5 10-10-4.5-10-10-10zM10 18.1c-4.5 0-8.1-3.6-8.1-8.1s3.6-8.1 8.1-8.1 8.1 3.6 8.1 8.1-3.6 8.1-8.1 8.1z"/><path d="M13.1 5l-3.1 3.1-3.1-3.1-1.9 1.9 3.1 3.1-3.1 3.1 1.9 1.9 3.1-3.1 3.1 3.1 1.9-1.9-3.1-3.1 3.1-3.1z"/></svg></a></div>';
				// send the mail ($mailto in this function is allways an array)
				foreach ( $mailto as $rcpt ) {
					wp_mail( $rcpt, $subject, $message["$lang"], $headers ); // the function is available after the hook 'plugins_loaded'
					$mailnotice .= $rcpt . '<br>';
				}
				$mailnotice .= '</div>';
				// add to content
				$content = $mailnotice . $content;
			}
		}
		return $content;
	}
}

// set a timeout until new mails are possible
function shariff3UU_limitRemoteUser() {
	// options
	$shariff3UU_mailform = $GLOBALS["shariff3UU_mailform"];
	
	// rtzrtz: umgeschrieben aus dem DOS-Blocker. Nochmal gruebeln, ob wir das ohne memcache mit der Performance schaffen. Daher auch nur Grundfunktionalitaet.
	if ( ! isset( $shariff3UU_mailform['REMOTEHOSTS'] ) ) {
		$shariff3UU_mailform['REMOTEHOSTS'] = '';
	}
	$HOSTS = json_decode( $shariff3UU_mailform['REMOTEHOSTS'], true );

	// get wait time
	if ( isset( $shariff3UU_mailform["mailform_wait"] ) ) {
		$wait = $shariff3UU_mailform["mailform_wait"];
	}
	else {
		$wait = '5';
	}
	
	// calculate current wait time
	if ( $HOSTS[$_SERVER['REMOTE_ADDR']]-time()+$wait > 0 ) {
		if ( $HOSTS[$_SERVER['REMOTE_ADDR']]-time() < 86400 ) {
			$wait = ($HOSTS[$_SERVER['REMOTE_ADDR']]-time()+$wait)*2;
		}
	}
	
	$HOSTS[$_SERVER['REMOTE_ADDR']] = time()+$wait;

	// etwas Muellentsorgung
	if ( count( $HOSTS )%10 == 0 ) {
		while ( list( $key, $value ) = each( $HOSTS ) ) {
			if ( $value-time()+$wait < 0 ) {
				unset( $HOSTS[$key] );
				update_option( 'shariff3UU_mailform', $shariff3UU_mailform );
			}
		}
	}

	$REMOTEHOSTS = json_encode( $HOSTS );
	$shariff3UU_mailform['REMOTEHOSTS'] = $REMOTEHOSTS;

	// update nur, wenn wir nicht unter heftigen DOS liegen
	if ( $HOSTS[$_SERVER['REMOTE_ADDR']]-time()-$wait < '60' ) {
		update_option( 'shariff3UU_mailform', $shariff3UU_mailform );
	}

	return $HOSTS[$_SERVER['REMOTE_ADDR']]-time();
}

// widget
class ShariffWidget extends WP_Widget {
	public function __construct() {
		// translations
		if ( function_exists('load_plugin_textdomain') ) { load_plugin_textdomain( 'shariff' ); }

		$widget_options = array(
			'classname' => 'Shariff',
			'description' => __('Add Shariff as configured on the plugin options page.', 'shariff'),
			'customize_selective_refresh' => true,
			);

		$control_options = array();
		parent::__construct('Shariff', 'Shariff', $widget_options, $control_options);
	} // END __construct()

	// widget form - see WP_Widget::form()
	public function form($instance) {
		// widgets defaults
		$instance = wp_parse_args((array) $instance, array(
								 'shariff-title' => '',
								 'shariff-tag' => '[shariff]',
							 ));
		// set title
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __( 'Title', 'shariff' ) . '</strong></p>';
		// set title
		echo '<p><input id="'. $this->get_field_id('shariff-title') .'" name="'. $this->get_field_name('shariff-title')
		.'" type="text" value="'. $instance['shariff-title'] .'" />(optional)</p>';
		// set shorttag
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>Shorttag</strong></p>';
		// set shorttag
		echo '<p><input id="'. $this->get_field_id('shariff-tag') .'" name="' . $this->get_field_name('shariff-tag')
				 . '" type="text" value=\''. str_replace('\'','"',$instance['shariff-tag']) .'\' size="30" />(optional)</p>';

		echo '<p style="clear:both;"></p>';
	} // END form($instance)

	// save widget configuration
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;

		// widget conf defaults
		$new_instance = wp_parse_args( (array) $new_instance, array( 'shariff-title' => '', 'shariff-tag' => '[shariff]') );

		// check input values
		$instance['shariff-title'] = (string) strip_tags( $new_instance['shariff-title'] );
		$instance['shariff-tag'] = (string) wp_kses( $new_instance['shariff-tag'], $GLOBALS["allowed_tags"] );

		// save config
		return $instance;
	}

	// draw widget
	public function widget( $args, $instance ) {
		// extract $args
		extract( $args );

		// get options
		$shariff3UU = $GLOBALS["shariff3UU"];

		// container
		echo $before_widget;

		// print title of widget, if provided
		if ( empty( $instance['shariff-title'] ) ) {
			$title = '';
		}
		else {
			apply_filters( 'shariff-title', $instance['shariff-title'] );
			$title = $instance['shariff-title'];
		}
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		// print shorttag

		// keep original shorttag for further reference
		$original_shorttag = $instance['shariff-tag'];

		// if nothing is configured, use the global options from admin menu
		if ( $instance['shariff-tag'] == '[shariff]' ) $shorttag = '[shariff]';
		else $shorttag = $instance['shariff-tag'];

		// set url to current page to prevent sharing the first or last post on pages with multiple posts e.g. the blog page
		// ofc only when no manual url is provided in the shorttag
		$page_url = '';
		if ( strpos( $original_shorttag, ' url=' ) === false ) {
			$wpurl = get_bloginfo( 'wpurl' );
			$siteurl = get_bloginfo( 'url' );
			// for "normal" installations
			$page_url = $wpurl . esc_url_raw( $_SERVER['REQUEST_URI'] );
			// kill ?view=mail etc. if pressed a second time
			$page_url = strtok($page_url, '?');
			// if wordpress is installed in a subdirectory, but links are mapped to the main domain
			if ( $wpurl != $siteurl ) {
				$subdir = str_replace ( $siteurl , '' , $wpurl );
				$page_url = str_replace ( $subdir , '' , $page_url );
			}
			$page_url = ' url="' . $page_url;
			$page_url .= '"';
		}

		// same for title
		$page_title = '';
		$wp_title = '';
		if ( strpos( $original_shorttag, 'title=' ) === false ) {
			$wp_title = wp_get_document_title();
			// wp_title for all pages that have it
			if ( ! empty( $wp_title ) ) $page_title = $wp_title;
			// just in case
			else $page_title = get_bloginfo('name');
			// remove [ and ] with ( and )
			$page_title = str_replace( '[', '(', $page_title );
			$page_title = str_replace( ']', ')', $page_title );
			$page_title = ' title="' . $page_title . '"';

		}

		// same for media
		$media = '';
		if ( array_key_exists( 'services', $shariff3UU ) && strstr( $shariff3UU["services"], 'pinterest' ) && ( strpos( $original_shorttag,'media=' ) === false ) ) {
			if ( isset( $shariff3UU["default_pinterest"] ) ) $media = ' media="' . $shariff3UU["default_pinterest"] . '"';
			else $media = ' media="' . plugins_url( '/pictos/defaultHint.png', __FILE__ ) . '"';
		}

		// build shorttag and add url, title and media if necessary as well as the widget attribute
		$shorttag = substr($shorttag,0,-1) . $page_title . $page_url . $media . ' widget="1"]';

		// replace mailform with mailto if on blog page to avoid broken button
		if ( ! is_singular() ) {
			$shorttag = str_replace( 'mailform' , 'mailto' , $shorttag );
		}

		// process the shortcode
		// but only if it is not password protected |or| "disable on password protected posts" is not set in the options
		if ( post_password_required( get_the_ID() ) != '1' || ( isset( $shariff3UU["disable_on_protected"] ) && $shariff3UU["disable_on_protected"] != '1' ) ) {
			echo do_shortcode( $shorttag );
		}

		// close Container
		echo $after_widget;
	} // END widget( $args, $instance )
} // END class ShariffWidget
add_action( 'widgets_init', create_function( '', 'return register_widget("ShariffWidget");' ) );

// clear transients upon deactivation
function shariff3UU_deactivate() {
	global $wpdb;
	// check for multisite
	if ( is_multisite() ) {
		$current_blog_id = get_current_blog_id();
		$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
		if ( $blogs ) {
			foreach ( $blogs as $blog ) {
				// switch to each blog
				switch_to_blog( $blog['blog_id'] );
				// purge transients
				shariff3UU_purge_transients_deactivation();
				// remove cron job
				wp_clear_scheduled_hook( 'shariff3UU_fill_cache' );
				// switch back to main
				restore_current_blog();
			}
		}
	} 
	else {
		// purge transients
		shariff3UU_purge_transients_deactivation();
		// remove cron job
		wp_clear_scheduled_hook( 'shariff3UU_fill_cache' );
	}
}
register_deactivation_hook( __FILE__, 'shariff3UU_deactivate' );

// activation hook to start cron job after update
register_activation_hook( __FILE__, 'shariff3UU_fill_cache_schedule' );

// purge all the transients associated with our plugin
function shariff3UU_purge_transients_deactivation() {
	// make sure we have the $wpdb class ready
	if ( ! isset( $wpdb ) ) { global $wpdb; }
	// delete transients
	$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_timeout_shariff%"';
	$wpdb->query($sql);
	$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_shariff%"';
	$wpdb->query($sql);
	// clear object cache
	wp_cache_flush();
}

?>
