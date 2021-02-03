<?php
/*
Plugin Name: Cookie Notice & Compliance for GDPR / CCPA
Description: Cookie Notice allows you to you elegantly inform users that your site uses cookies and helps you comply with GDPR, CCPA and other data privacy laws.
Version: 2.0.0
Author: Hu-manity.co
Author URI: https://hu-manity.co/
Plugin URI: https://hu-manity.co/
License: MIT License
License URI: https://opensource.org/licenses/MIT
Text Domain: cookie-notice
Domain Path: /languages

Cookie Notice
Copyright (C) 2021, Hu-manity.co - info@hu-manity.co

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Cookie Notice class.
 *
 * @class Cookie_Notice
 * @version	2.0.0
 */
class Cookie_Notice {

	private $status = '';

	/**
	 * @var $defaults
	 */
	public $defaults = array(
		'general' => array(
			'app_id'				=> '',
			'app_key'				=> '',
			'position'				=> 'bottom',
			'message_text'			=> '',
			'css_style'				=> 'bootstrap',
			'css_class'				=> '',
			'accept_text'			=> '',
			'refuse_text'			=> '',
			'refuse_opt'			=> false,
			'refuse_code'			=> '',
			'refuse_code_head'		=> '',
			'revoke_cookies'		=> false,
			'revoke_cookies_opt'	=> 'automatic',
			'revoke_message_text'	=> '',
			'revoke_text'			=> '',
			'redirection'			=> false,
			'see_more'				=> false,
			'link_target'			=> '_blank',
			'link_position'			=> 'banner',
			'time'					=> 'month',
			'time_rejected'			=> 'month',
			'hide_effect'			=> 'fade',
			'on_scroll'				=> false,
			'on_scroll_offset'		=> 100,
			'on_click'				=> false,
			'colors' => array(
				'text'			=> '#fff',
				'bar'			=> '#32323a',
				'bar_opacity'	=> 100
			),
			'see_more_opt' => array(
				'text'		=> '',
				'link_type'	=> 'page',
				'id'		=> 0,
				'link'		=> '',
				'sync'		=> false
			),
			'script_placement'			=> 'header',
			'translate'					=> true,
			'deactivation_delete'		=> false,
			'update_version'			=> 3,
			'update_notice'				=> true,
			'update_delay_date'			=> 0
		),
		'version'	=> '2.0.0'
	);
	
	private static $_instance;

	/**
	 * Disable object cloning.
	 */
	public function __clone() {}

	/**
	 * Disable unserializing of the class.
	 */
	public function __wakeup() {}

	/**
	 * Main plugin instance.
	 * 
	 * @return object
	 */
	public static function instance() {
		if ( self::$_instance === null ) {
			self::$_instance = new self();

			add_action( 'plugins_loaded', array( self::$_instance, 'load_textdomain' ) );

			self::$_instance->includes();

			self::$_instance->bot_detect = new Cookie_Notice_Bot_Detect();
			self::$_instance->frontend = new Cookie_Notice_Frontend();
			self::$_instance->settings = new Cookie_Notice_Settings();
			self::$_instance->welcome = new Cookie_Notice_Welcome();
			self::$_instance->welcome_api = new Cookie_Notice_Welcome_API();
			self::$_instance->welcome_frontend = new Cookie_Notice_Welcome_Frontend();
		}

		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		
		// get options
		$options = get_option( 'cookie_notice_options', $this->defaults['general'] );

		// check legacy parameters
		$options = $this->check_legacy_params( $options, array( 'refuse_opt', 'on_scroll', 'on_click', 'deactivation_delete', 'see_more' ) );

		// merge old options with new ones
		$this->options = array(
			'general' => $this->multi_array_merge( $this->defaults['general'], $options )
		);

		if ( ! isset( $this->options['general']['see_more_opt']['sync'] ) )
			$this->options['general']['see_more_opt']['sync'] = $this->defaults['general']['see_more_opt']['sync'];
		
		// actions
		add_action( 'plugins_loaded', array( $this, 'set_status' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
		add_action( 'init', array( $this, 'wpsc_add_cookie' ) );
		add_action( 'admin_init', array( $this, 'update_notice' ) );
		add_action( 'wp_ajax_cn_dismiss_notice', array( $this, 'ajax_dismiss_admin_notice' ) );

		// filters
		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );
	}
	
	/**
	 * Set plugin status.
	 */
	public function set_status() {
		$this->status = get_option( 'cookie_notice_status', '' );
	}


	/**
	 * Include required files.
	 *
	 * @return void
	 */
	private function includes() {
		include_once( plugin_dir_path( __FILE__ ) . 'includes/bot-detect.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/frontend.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/settings.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/welcome.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/welcome-api.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/welcome-frontend.php' );
	}
	
	/**
	 * Load textdomain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'cookie-notice', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 * Activate the plugin.
	 */
	public function activation() {
		// get current database version
		$current_db_version = get_option( 'cookie_notice_version', '1.0.0' );

		// new version?
		if ( version_compare( $current_db_version, $this->defaults['version'], '<' ) ) {
			// update plugin version
			update_option( 'cookie_notice_version', $this->defaults['version'], false );
		}
		
		add_option( 'cookie_notice_options', $this->defaults['general'], '', 'no' );
	}

	/**
	 * Deactivate the plugin.
	 */
	public function deactivation() {
		if ( $this->options['general']['deactivation_delete'] === true ) {
			delete_option( 'cookie_notice_options' );
			delete_option( 'cookie_notice_version' );
			delete_option( 'cookie_notice_status' );
			
			delete_transient( 'cookie_notice_compliance_cache' );
		}
		
		// remove WP Super Cache cookie
		$this->wpsc_delete_cookie();
	}

	/**
	 * Update notice.
	 * 
	 * @return void
	 */
	public function update_notice() {
		if ( ! current_user_can( 'install_plugins' ) )
			return;
		
		$current_update = 5;
		
		if ( $this->options['general']['update_version'] < $current_update ) {
			// check version, if update version is lower than plugin version, set update notice to true
			$this->options['general'] = wp_parse_args( array( 'update_version' => $current_update, 'update_notice' => true ), $this->options['general'] );

			update_option( 'cookie_notice_options', $this->options['general'] );
			
			// show welcome
			set_transient( 'cn_activation_redirect', 1 );
		}
	}

	/**
	 * Add admin notices.
	 * 
	 * @param string $html
	 * @param string $status
	 * @param bool $paragraph
	 */
	private function add_notice( $html = '', $status = 'error', $container = '' ) {
		$this->notices[] = array(
			'html' 		=> $html,
			'status' 	=> $status,
			'container' => ( ! empty( $container ) && in_array( $container, array( 'p', 'div' ) ) ? $container : '' )
		);

		add_action( 'admin_notices', array( $this, 'display_notice'), 0 );
	}

	/**
	 * Print admin notices.
	 * 
	 * @return mixed
	 */
	public function display_notice() {
		foreach( $this->notices as $notice ) {
			echo '
			<div id="cn-admin-notice" class="cn-notice ' . $notice['status'] . '">
				' . ( ! empty( $notice['container'] ) ? '<' . $notice['container'] . ' class="cn-notice-container">' : '' ) . '
				' . $notice['html'] . '
				' . ( ! empty( $notice['container'] ) ? '</' . $notice['container'] . ' class="cn-notice-container">' : '' ) . '
			</div>';
		}
	}

	/**
	 * Dismiss admin notice.
	 */
	public function ajax_dismiss_admin_notice() {
		if ( ! current_user_can( 'install_plugins' ) )
			return;

		if ( wp_verify_nonce( esc_attr( $_REQUEST['nonce'] ), 'cn_dismiss_notice' ) ) {
			$notice_action = empty( $_REQUEST['notice_action'] ) || $_REQUEST['notice_action'] === 'dismiss' ? 'dismiss' : esc_attr( $_REQUEST['notice_action'] );

			switch ( $notice_action ) {
				// delay notice
				case 'delay':
					// set delay period to 1 week from now
					$this->options['general'] = wp_parse_args( array( 'update_delay_date' => time() + 1209600 ), $this->options['general'] );
					update_option( 'cookie_notice_options', $this->options['general'] );
					break;
				
				// delay notice
				case 'approve':
					// hide notice
					$this->options['general'] = wp_parse_args( array( 'update_notice' => false ), $this->options['general'] );
					$this->options['general'] = wp_parse_args( array( 'update_delay_date' => 0 ), $this->options['general'] );
					// update options
					update_option( 'cookie_notice_options', $this->options['general'] );
					break;

				// hide notice
				default:
					$this->options['general'] = wp_parse_args( array( 'update_notice' => false ), $this->options['general'] );
					$this->options['general'] = wp_parse_args( array( 'update_delay_date' => 0 ), $this->options['general'] );

					update_option( 'cookie_notice_options', $this->options['general'] );
			}
		}

		exit;
	}

	/**
	 * Register shortcode.
	 *
	 * @return void
	 */
	public function register_shortcodes() {
		add_shortcode( 'cookies_accepted', array( $this, 'cookies_accepted_shortcode' ) );
		add_shortcode( 'cookies_revoke', array( $this, 'cookies_revoke_shortcode' ) );
		add_shortcode( 'cookies_policy_link', array( $this, 'cookies_policy_link_shortcode' ) );
	}

	/**
	 * Register cookies accepted shortcode.
	 *
	 * @param array $args
	 * @param mixed $content
	 * @return mixed
	 */
	public function cookies_accepted_shortcode( $args, $content ) {
		if ( $this->cookies_accepted() ) {
			$scripts = html_entity_decode( trim( wp_kses( $content, $this->get_allowed_html() ) ) );

			if ( ! empty( $scripts ) ) {
				if ( preg_match_all( '/' . get_shortcode_regex() . '/', $content ) ) {
					$scripts = do_shortcode( $scripts );
				}
				return $scripts;
			}
		}

		return '';
	}

	/**
	 * Register cookies accepted shortcode.
	 *
	 * @param array $args
	 * @param mixed $content
	 * @return mixed
	 */
	public function cookies_revoke_shortcode( $args, $content ) {
		// get options
		$options = $this->options['general'];

		// defaults
		$defaults = array(
			'title'	=> $options['revoke_text'],
			'class'	=> $options['css_class']
		);

		// combine shortcode arguments
		$args = shortcode_atts( $defaults, $args );

		// escape class(es)
		$args['class'] = esc_attr( $args['class'] );
		
		if ( ! empty( $this->get_status() ) ) {
			$shortcode = '<a href="#" class="cn-revoke-cookie cn-button cn-revoke-inline' . ( $options['css_style'] !== 'none' ? ' ' . $options['css_style'] : '' ) . ( $args['class'] !== '' ? ' ' . $args['class'] : '' ) . '" title="' . esc_html( $args['title'] ) . '" data-hu-action="notice-revoke">' . esc_html( $args['title'] ) . '</a>';
		} else {
			$shortcode = '<a href="#" class="cn-revoke-cookie cn-button cn-revoke-inline' . ( $options['css_style'] !== 'none' ? ' ' . $options['css_style'] : '' ) . ( $args['class'] !== '' ? ' ' . $args['class'] : '' ) . '" title="' . esc_html( $args['title'] ) . '">' . esc_html( $args['title'] ) . '</a>';
		}

		return $shortcode;
	}

	/**
	 * Register cookies policy link shortcode.
	 *
	 * @param array $args
	 * @param string $content
	 * @return string
	 */
	public function cookies_policy_link_shortcode( $args, $content ) {
		// get options
		$options = $this->options['general'];
		
		// defaults
		$defaults = array(
			'title'	=> esc_html( $options['see_more_opt']['text'] !== '' ? $options['see_more_opt']['text'] : '&#x279c;' ),
			'link'	=> ( $options['see_more_opt']['link_type'] === 'custom' ? $options['see_more_opt']['link'] : get_permalink( $options['see_more_opt']['id'] ) ),
			'class'	=> $options['css_class']
		);
		
		// combine shortcode arguments
		$args = shortcode_atts( $defaults, $args );
		
		$shortcode = '<a href="' . $args['link'] . '" target="' . $options['link_target'] . '" id="cn-more-info" class="cn-privacy-policy-link cn-link' . ( $args['class'] !== '' ? ' ' . $args['class'] : '' ) . '">' . esc_html( $args['title'] ) . '</a>';
		
		return $shortcode;
	}

	/**
	 * Check if cookies are accepted.
	 * 
	 * @return bool
	 */
	public static function cookies_accepted() {
		if ( ! empty( Cookie_Notice()->get_status() ) ) {
			$cookies = isset( $_COOKIE['hu-consent'] ) ? json_decode( $_COOKIE['hu-consent'], true ) : array();
			
			$result = ! empty( $cookies['consent'] ) ? true : false;
		} else {
			$result = isset( $_COOKIE['cookie_notice_accepted'] ) && $_COOKIE['cookie_notice_accepted'] === 'true';
		}
		
		return apply_filters( 'cn_is_cookie_accepted', $result );
	}

	/**
	 * Check if cookies are set.
	 *
	 * @return boolean Whether cookies are set
	 */
	public function cookies_set() {
		if ( ! empty( Cookie_Notice()->get_status() ) ) {
			$result = isset( $_COOKIE['hu-consent'] );
		} else {
			$result = isset( $_COOKIE['cookie_notice_accepted'] );
		}
		
;		return apply_filters( 'cn_is_cookie_set', $result );
	}
	
	/**
	 * Add WP Super Cache cookie.
	 */
	public function wpsc_add_cookie() {
		do_action( 'wpsc_add_cookie', 'cookie_notice_accepted' );
	}
	
	/**
	 * Delete WP Super Cache cookie.
	 */
	public function wpsc_delete_cookie() {
		do_action( 'wpsc_delete_cookie', 'cookie_notice_accepted' );
	}

	/**
	 * Add links to settings page.
	 * 
	 * @param array $links
	 * @param string $file
	 * @return array
	 */
	public function plugin_action_links( $links, $file ) {
		if ( ! current_user_can( apply_filters( 'cn_manage_cookie_notice_cap', 'manage_options' ) ) )
			return $links;

		if ( $file == plugin_basename( __FILE__ ) )
			array_unshift( $links, sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=cookie-notice' ), __( 'Settings', 'cookie-notice' ) ) );

		return $links;
	}

	/**
	 * Get allowed script blocking HTML.
	 *
	 * @return array
	 */
	public function get_allowed_html() {
		return apply_filters(
			'cn_refuse_code_allowed_html',
			array_merge(
				wp_kses_allowed_html( 'post' ),
				array(
					'script' => array(
						'type' => array(),
						'src' => array(),
						'charset' => array(),
						'async' => array()
					),
					'noscript' => array(),
					'style' => array(
						'type' => array()
					),
					'iframe' => array(
						'src' => array(),
						'height' => array(),
						'width' => array(),
						'frameborder' => array(),
						'allowfullscreen' => array()
					)
				)
			)
		);
	}

	/**
	 * Helper: convert hex color to rgb color.
	 * 
	 * @param type $color
	 * @return array
	 */
	public function hex2rgb( $color ) {
		if ( $color[0] == '#' )
			$color = substr( $color, 1 );

		if ( strlen( $color ) == 6 )
			list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		elseif ( strlen( $color ) == 3 )
			list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		else
			return false;

		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );

		return array( $r, $g, $b );
	}
	
	/**
	 * Helper: Convert undersocores to CamelCase/
	 * 
	 * @param type $string
	 * @param bool $capitalize_first_char
	 * @return string
	 */
	public function underscores_to_camelcase( $string, $capitalize_first_char = false ) {
		$str = str_replace( ' ', '', ucwords( str_replace( '_', ' ', $string ) ) );

		if ( ! $capitalize_first_char ) {
			$str[0] = strtolower( $str[0] );
		}

		return $str;
	}
	
	/**
	 * Check legacy parameters that were yes/no strings.
	 *
	 * @param array $options
	 * @param array $params
	 * @return array
	 */
	public function check_legacy_params( $options, $params ) {
		foreach ( $params as $param ) {
			if ( array_key_exists( $param, $options ) && ! is_bool( $options[$param] ) )
				$options[$param] = $options[$param] === 'yes';
		}

		return $options;
	}

	/**
	 * Merge multidimensional associative arrays.
	 * Works only with strings, integers and arrays as keys. Values can be any type but they have to have same type to be kept in the final array.
	 * Every array should have the same type of elements. Only keys from $defaults array will be kept in the final array unless $siblings are not empty.
	 * $siblings examples: array( '=>', 'only_first_level', 'first_level=>second_level', 'first_key=>next_key=>sibling' ) and so on.
	 * Single '=>' means that all siblings of the highest level will be kept in the final array.
	 *
	 * @param array	$default Array with defaults values
	 * @param array	$array Array to merge
	 * @param boolean|array	$siblings Whether to allow "string" siblings to copy from $array if they do not exist in $defaults, false otherwise
	 * @return array Merged arrays
	 */
	public function multi_array_merge( $defaults, $array, $siblings = false ) {
		// make a copy for better performance and to prevent $default override in foreach
		$copy = $defaults;

		// prepare siblings for recursive deeper level
		$new_siblings = array();

		// allow siblings?
		if ( ! empty( $siblings ) && is_array( $siblings ) ) {
			foreach ( $siblings as $sibling ) {
				// highest level siblings
				if ( $sibling === '=>' ) {
					// copy all non-existent string siblings
					foreach( $array as $key => $value ) {
						if ( is_string( $key ) && ! array_key_exists( $key, $defaults ) ) {
							$defaults[$key] = null;
						}
					}
				// sublevel siblings
				} else {
					// explode siblings
					$ex = explode( '=>', $sibling );

					// copy all non-existent siblings
					foreach ( array_keys( $array[$ex[0]] ) as $key ) {
						if ( ! array_key_exists( $key, $defaults[$ex[0]] ) )
							$defaults[$ex[0]][$key] = null;
					}

					// more than one sibling child?
					if ( count( $ex ) > 1 )
						$new_siblings[$ex[0]] = array( substr_replace( $sibling, '', 0, strlen( $ex[0] . '=>' ) ) );
					// no more sibling children
					else
						$new_siblings[$ex[0]] = false;
				}
			}
		}

		// loop through first array
		foreach ( $defaults as $key => $value ) {
			// integer key?
			if ( is_int( $key ) ) {
				$copy = array_unique( array_merge( $defaults, $array ), SORT_REGULAR );

				break;
			// string key?
			} elseif ( is_string( $key ) && isset( $array[$key] ) ) {
				// string, boolean, integer or null values?
				if ( ( is_string( $value ) && is_string( $array[$key] ) ) || ( is_bool( $value ) && is_bool( $array[$key] ) ) || ( is_int( $value ) && is_int( $array[$key] ) ) || is_null( $value ) )
					$copy[$key] = $array[$key];
				// arrays
				elseif ( is_array( $value ) && isset( $array[$key] ) && is_array( $array[$key] ) ) {
					if ( empty( $value ) )
						$copy[$key] = $array[$key];
					else
						$copy[$key] = $this->multi_array_merge( $defaults[$key], $array[$key], ( isset( $new_siblings[$key] ) ? $new_siblings[$key] : false ) );
				}
			}
		}

		return $copy;
	}
	
	/**
	 * Get plugin mode
	 * 
	 * @return type
	 */
	public function get_status() {
		return $this->status; // notice, active, pending etc.
	}

	/**
	 * Indicate if current page is the Cookie Policy page
	 *
	 * @return bool
	 */
	public function is_cookie_policy_page() {
		$see_more = $this->options['general']['see_more_opt'];
		
		if ( $see_more['link_type'] !== 'page' )
			return false;

		$cp_id = $see_more['id'];
		$cp_slug = get_post_field( 'post_name', $cp_id );

		$current_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );

		return $current_page->post_name === $cp_slug;
	}

}

/**
 * Initialize Cookie Notice.
 */
function Cookie_Notice() {
	static $instance;

	// first call to instance() initializes the plugin
	if ( $instance === null || ! ( $instance instanceof Cookie_Notice ) )
		$instance = Cookie_Notice::instance();

	return $instance;
}

$cookie_notice = Cookie_Notice();
