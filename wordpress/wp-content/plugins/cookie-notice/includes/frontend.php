<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Cookie_Notice_Frontend class.
 * 
 * @class Cookie_Notice_Frontend
 */
class Cookie_Notice_Frontend {
	private $widget_url = '';
	private $is_bot = false;
	
	public function __construct() {
		// actions
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'init', array( $this, 'add_cors_http_header' ) );
		
		$this->widget_url = '//cdn.hu-manity.co/hu-banner.min.js';
	}

	/**
	 * Init frontend.
	 */
	public function init() {
		// check preview mode
		$this->preview_mode = isset( $_GET['cn_preview_mode'] );
		
		// whether to count robots
		$this->is_bot = Cookie_Notice()->bot_detect->is_crawler();

		// bail if in preview mode or it's a bot request
		if ( ! $this->preview_mode && ! $this->is_bot ) {
			// init cookie compliance
			if ( ! empty( Cookie_Notice()->get_status() ) ) {
				add_action( 'wp_head', array( $this, 'wp_head_scripts' ), 0 );
				add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_compliance_scripts' ) );
				add_action( 'wp_ajax_cn_save_config', array( $this, 'ajax_save_config' ) );
				add_action( 'wp_ajax_nopriv_cn_save_config', array( $this, 'ajax_save_config' ) );
			// init cookie notice
			} else {
				// actions
				add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_notice_scripts' ) );
				add_action( 'wp_head', array( $this, 'wp_print_header_scripts' ) );
				add_action( 'wp_print_footer_scripts', array( $this, 'wp_print_footer_scripts' ) );
				add_action( 'wp_footer', array( $this, 'add_cookie_notice' ), 1000 );
				// filters
				add_filter( 'body_class', array( $this, 'change_body_class' ) );
			}
		}
	}
	
	/**
	 * Add CORS header for API requests and purge cache.
	 */
	public function add_cors_http_header() {
		header( "Access-Control-Allow-Origin: https://app.hu-manity.co" );
		header( 'Access-Control-Allow-Methods: GET' );
		
		// purge cache
		if ( isset( $_GET['hu_purge_cache'] ) ) {
			$this->purge_cache();
		}
	}

	/**
	 * Run Cookie Compliance.
	 *
	 * @return void
	 */
	public function wp_head_scripts() {
		// get site language
		$locale = get_locale();
		$locale_code = explode( '_', $locale );
	
		$options = array(
			'appID' => Cookie_Notice()->options['general']['app_id'],
			'currentLanguage'	=> $locale_code[0]
		);
		
		$cached_config = get_transient( 'cookie_notice_compliance_cache' );
		
		if ( ! empty( $cached_config ) && is_array( $cached_config ) ) {
			$options = array_merge( $options, array(
				'cache' => true,
				'cacheType' => 'db',
				'cacheData' => $cached_config
			) );
		}
		
		// print_r( $options ); exit;

		echo '
		<!-- Hu Banner -->
		<script type="text/javascript">
			var huOptions = ' . json_encode( $options ) . ';
		</script>
		<script type="text/javascript" src="' . $this->widget_url . '"></script>';
	}
	
	/**
	 * Load compliance scripts and styles - frontend.
	 */
	public function wp_enqueue_compliance_scripts() {
		wp_enqueue_script( 
			'cookie-notice-compliance', 
			plugins_url( '../js/front-compliance.js', __FILE__ ),
			array(),
			Cookie_Notice()->defaults['version'],
			isset( Cookie_Notice()->options['general']['script_placement'] ) && Cookie_Notice()->options['general']['script_placement'] === 'footer'
		);
		
		wp_localize_script(
			'cookie-notice-compliance',
			'cnComplianceArgs',
			array(
				'ajaxUrl'				=> admin_url( 'admin-ajax.php' ),
				'nonce'					=> wp_create_nonce( 'cn_save_config' ),
				'secure'				=> (int) is_ssl()
			)
		);
	}
	
	/**
	 * Cookie notice output.
	 * 
	 * @return mixed
	 */
	public function add_cookie_notice() {
		// WPML >= 3.2
		if ( defined( 'ICL_SITEPRESS_VERSION' ) && version_compare( ICL_SITEPRESS_VERSION, '3.2', '>=' ) ) {
			Cookie_Notice()->options['general']['message_text'] = apply_filters( 'wpml_translate_single_string', Cookie_Notice()->options['general']['message_text'], 'Cookie Notice', 'Message in the notice' );
			Cookie_Notice()->options['general']['accept_text'] = apply_filters( 'wpml_translate_single_string', Cookie_Notice()->options['general']['accept_text'], 'Cookie Notice', 'Button text' );
			Cookie_Notice()->options['general']['refuse_text'] = apply_filters( 'wpml_translate_single_string', Cookie_Notice()->options['general']['refuse_text'], 'Cookie Notice', 'Refuse button text' );
			Cookie_Notice()->options['general']['revoke_message_text'] = apply_filters( 'wpml_translate_single_string', Cookie_Notice()->options['general']['revoke_message_text'], 'Cookie Notice', 'Revoke message text' );
			Cookie_Notice()->options['general']['revoke_text'] = apply_filters( 'wpml_translate_single_string', Cookie_Notice()->options['general']['revoke_text'], 'Cookie Notice', 'Revoke button text' );
			Cookie_Notice()->options['general']['see_more_opt']['text'] = apply_filters( 'wpml_translate_single_string', Cookie_Notice()->options['general']['see_more_opt']['text'], 'Cookie Notice', 'Privacy policy text' );
			Cookie_Notice()->options['general']['see_more_opt']['link'] = apply_filters( 'wpml_translate_single_string', Cookie_Notice()->options['general']['see_more_opt']['link'], 'Cookie Notice', 'Custom link' );
		// WPML and Polylang compatibility
		} elseif ( function_exists( 'icl_t' ) ) {
			Cookie_Notice()->options['general']['message_text'] = icl_t( 'Cookie Notice', 'Message in the notice', Cookie_Notice()->options['general']['message_text'] );
			Cookie_Notice()->options['general']['accept_text'] = icl_t( 'Cookie Notice', 'Button text', Cookie_Notice()->options['general']['accept_text'] );
			Cookie_Notice()->options['general']['refuse_text'] = icl_t( 'Cookie Notice', 'Refuse button text', Cookie_Notice()->options['general']['refuse_text'] );
			Cookie_Notice()->options['general']['revoke_message_text'] = icl_t( 'Cookie Notice', 'Revoke message text', Cookie_Notice()->options['general']['revoke_message_text'] );
			Cookie_Notice()->options['general']['revoke_text'] = icl_t( 'Cookie Notice', 'Revoke button text', Cookie_Notice()->options['general']['revoke_text'] );
			Cookie_Notice()->options['general']['see_more_opt']['text'] = icl_t( 'Cookie Notice', 'Privacy policy text', Cookie_Notice()->options['general']['see_more_opt']['text'] );
			Cookie_Notice()->options['general']['see_more_opt']['link'] = icl_t( 'Cookie Notice', 'Custom link', Cookie_Notice()->options['general']['see_more_opt']['link'] );
		}

		if ( function_exists( 'icl_object_id' ) )
			Cookie_Notice()->options['general']['see_more_opt']['id'] = icl_object_id( Cookie_Notice()->options['general']['see_more_opt']['id'], 'page', true );

		// get cookie container args
		$options = apply_filters( 'cn_cookie_notice_args', array(
			'position'				=> Cookie_Notice()->options['general']['position'],
			'css_style'				=> Cookie_Notice()->options['general']['css_style'],
			'css_class'				=> Cookie_Notice()->options['general']['css_class'],
			'button_class'			=> 'cn-button',
			'colors'				=> Cookie_Notice()->options['general']['colors'],
			'message_text'			=> Cookie_Notice()->options['general']['message_text'],
			'accept_text'			=> Cookie_Notice()->options['general']['accept_text'],
			'refuse_text'			=> Cookie_Notice()->options['general']['refuse_text'],
			'revoke_message_text'	=> Cookie_Notice()->options['general']['revoke_message_text'],
			'revoke_text'			=> Cookie_Notice()->options['general']['revoke_text'],
			'refuse_opt'			=> Cookie_Notice()->options['general']['refuse_opt'],
			'revoke_cookies'		=> Cookie_Notice()->options['general']['revoke_cookies'],
			'see_more'				=> Cookie_Notice()->options['general']['see_more'],
			'see_more_opt'			=> Cookie_Notice()->options['general']['see_more_opt'],
			'link_target'			=> Cookie_Notice()->options['general']['link_target'],
			'link_position'			=> Cookie_Notice()->options['general']['link_position'],
			'aria_label'			=> __( 'Cookie Notice', 'cookie-notice' )
		) );

		// check legacy parameters
		$options = Cookie_Notice()->check_legacy_params( $options, array( 'refuse_opt', 'see_more' ) );

		if ( $options['see_more'] === true )
			$options['message_text'] = do_shortcode( wp_kses_post( $options['message_text'] ) );
		else
			$options['message_text'] = wp_kses_post( $options['message_text'] );

		$options['css_class'] = esc_attr( $options['css_class'] );

		// message output
		$output = '
		<!-- Cookie Notice plugin v' . Cookie_Notice()->defaults['version'] . ' by Digital Factory https://dfactory.eu/ -->
		<div id="cookie-notice" role="banner" class="cookie-notice-hidden cookie-revoke-hidden cn-position-' . $options['position'] . '" aria-label="' . $options['aria_label'] . '" style="background-color: rgba(' . implode( ',', Cookie_Notice()->hex2rgb( $options['colors']['bar'] ) ) . ',' . $options['colors']['bar_opacity'] * 0.01 . ');">'
			. '<div class="cookie-notice-container" style="color: ' . $options['colors']['text'] . ';">'
			. '<span id="cn-notice-text" class="cn-text-container">'. $options['message_text'] . '</span>'
			. '<span id="cn-notice-buttons" class="cn-buttons-container"><a href="#" id="cn-accept-cookie" data-cookie-set="accept" class="cn-set-cookie ' . $options['button_class'] . ( $options['css_style'] !== 'none' ? ' ' . $options['css_style'] : '' ) . ( $options['css_class'] !== '' ? ' ' . $options['css_class'] : '' ) . '" aria-label="' . $options['accept_text'] . '">' . $options['accept_text'] . '</a>'
			. ( $options['refuse_opt'] === true ? '<a href="#" id="cn-refuse-cookie" data-cookie-set="refuse" class="cn-set-cookie ' . $options['button_class'] . ( $options['css_style'] !== 'none' ? ' ' . $options['css_style'] : '' ) . ( $options['css_class'] !== '' ? ' ' . $options['css_class'] : '' ) . '" aria-label="' . $options['refuse_text'] . '">' . $options['refuse_text'] . '</a>' : '' )
			. ( $options['see_more'] === true && $options['link_position'] === 'banner' ? '<a href="' . ( $options['see_more_opt']['link_type'] === 'custom' ? $options['see_more_opt']['link'] : get_permalink( $options['see_more_opt']['id'] ) ) . '" target="' . $options['link_target'] . '" id="cn-more-info" class="cn-more-info ' . $options['button_class'] . ( $options['css_style'] !== 'none' ? ' ' . $options['css_style'] : '' ) . ( $options['css_class'] !== '' ? ' ' . $options['css_class'] : '' ) . '" aria-label="' . $options['see_more_opt']['text'] . '">' . $options['see_more_opt']['text'] . '</a>' : '' ) 
			. '</span><a href="javascript:void(0);" id="cn-close-notice" data-cookie-set="accept" class="cn-close-icon" aria-label="' . $options['accept_text'] . '"></a>'
			. '</div>
			' . ( $options['refuse_opt'] === true && $options['revoke_cookies'] == true ? 
			'<div class="cookie-revoke-container" style="color: ' . $options['colors']['text'] . ';">'
			. ( ! empty( $options['revoke_message_text'] ) ? '<span id="cn-revoke-text" class="cn-text-container">'. $options['revoke_message_text'] . '</span>' : '' )
			. '<span id="cn-revoke-buttons" class="cn-buttons-container"><a href="#" class="cn-revoke-cookie ' . $options['button_class'] . ( $options['css_style'] !== 'none' ? ' ' . $options['css_style'] : '' ) . ( $options['css_class'] !== '' ? ' ' . $options['css_class'] : '' ) . '" aria-label="' . $options['revoke_text'] . '">' . esc_html( $options['revoke_text'] ) . '</a></span>
			</div>' : '' ) . '
		</div>
		<!-- / Cookie Notice plugin -->';

		echo apply_filters( 'cn_cookie_notice_output', $output, $options );
	}
	
	/**
	 * Load notice scripts and styles - frontend.
	 */
	public function wp_enqueue_notice_scripts() {
		wp_enqueue_script( 'cookie-notice-front', plugins_url( '../js/front' . ( ! ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.min' : '' ) . '.js', __FILE__ ), array(), Cookie_Notice()->defaults['version'], isset( Cookie_Notice()->options['general']['script_placement'] ) && Cookie_Notice()->options['general']['script_placement'] === 'footer' );

		wp_localize_script(
			'cookie-notice-front',
			'cnArgs',
			array(
				'ajaxUrl'				=> admin_url( 'admin-ajax.php' ),
				'nonce'					=> wp_create_nonce( 'cn_save_cases' ),
				'hideEffect'			=> Cookie_Notice()->options['general']['hide_effect'],
				'position'				=> Cookie_Notice()->options['general']['position'],
				'onScroll'				=> (int) Cookie_Notice()->options['general']['on_scroll'],
				'onScrollOffset'		=> (int) Cookie_Notice()->options['general']['on_scroll_offset'],
				'onClick'				=> (int) Cookie_Notice()->options['general']['on_click'],
				'cookieName'			=> 'cookie_notice_accepted',
				'cookieTime'			=> Cookie_Notice()->settings->times[Cookie_Notice()->options['general']['time']][1],
				'cookieTimeRejected'	=> Cookie_Notice()->settings->times[Cookie_Notice()->options['general']['time_rejected']][1],
				'cookiePath'			=> ( defined( 'COOKIEPATH' ) ? (string) COOKIEPATH : '' ),
				'cookieDomain'			=> ( defined( 'COOKIE_DOMAIN' ) ? (string) COOKIE_DOMAIN : '' ),
				'redirection'			=> (int) Cookie_Notice()->options['general']['redirection'],
				'cache'					=> (int) ( defined( 'WP_CACHE' ) && WP_CACHE ),
				'refuse'				=> (int) Cookie_Notice()->options['general']['refuse_opt'],
				'revokeCookies'			=> (int) Cookie_Notice()->options['general']['revoke_cookies'],
				'revokeCookiesOpt'		=> Cookie_Notice()->options['general']['revoke_cookies_opt'],
				'secure'				=> (int) is_ssl()
			)
		);

		wp_enqueue_style( 'cookie-notice-front', plugins_url( '../css/front' . ( ! ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.min' : '' ) . '.css', __FILE__ ) );
	}

	/**
	 * Print non functional JavaScript in body.
	 *
	 * @return mixed
	 */
	public function wp_print_footer_scripts() {
		if ( Cookie_Notice()->cookies_accepted() ) {
			$scripts = apply_filters( 'cn_refuse_code_scripts_html', html_entity_decode( trim( wp_kses( Cookie_Notice()->options['general']['refuse_code'], Cookie_Notice()->get_allowed_html() ) ) ) );

			if ( ! empty( $scripts ) )
				echo $scripts;
		}
	}

	/**
	 * Print non functional JavaScript in header.
	 *
	 * @return mixed
	 */
	public function wp_print_header_scripts() {
		if ( Cookie_Notice()->cookies_accepted() ) {
			$scripts = apply_filters( 'cn_refuse_code_scripts_html', html_entity_decode( trim( wp_kses( Cookie_Notice()->options['general']['refuse_code_head'], Cookie_Notice()->get_allowed_html() ) ) ) );

			if ( ! empty( $scripts ) )
				echo $scripts;
		}	
	}
	
	/**
	 * Add new body classes.
	 *
	 * @param array $classes Body classes
	 * @return array
	 */
	public function change_body_class( $classes ) {
		if ( is_admin() )
			return $classes;

		if ( Cookie_Notice()->cookies_set() ) {
			$classes[] = 'cookies-set';

			if ( Cookie_Notice()->cookies_accepted() )
				$classes[] = 'cookies-accepted';
			else
				$classes[] = 'cookies-refused';
		} else
			$classes[] = 'cookies-not-set';

		return $classes;
	}
	
	/**
	 * Save compliance config caching.
	 */
	public function ajax_save_config() {
		if ( ! empty( Cookie_Notice()->get_status() ) )
			return;
		
		if ( ! wp_verify_nonce( esc_attr( $_REQUEST['nonce'] ), 'cn_save_config' ) )
			return;
		
		$json_data = ! empty( $_REQUEST['data'] ) ? esc_attr( $_REQUEST['data'] ) : false;
		$config_data = array();

		if ( ! empty( $json_data ) )
			$config_data = json_decode( stripslashes( html_entity_decode( $json_data ) ), true );
		
		// save data
		if ( $config_data && is_array( $config_data ) )
			set_transient( 'cookie_notice_compliance_cache', $config_data, 24 * HOURS_IN_SECONDS );
		
		return true;
		exit;
	}
	
	/**
	 * Purge config cache.
	 */
	public function purge_cache() {
		delete_transient( 'cookie_notice_compliance_cache' );
	}
}