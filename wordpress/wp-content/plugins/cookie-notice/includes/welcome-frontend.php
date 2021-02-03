<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Cookie_Notice_Welcome_Frontend class.
 * 
 * @class Cookie_Notice_Welcome_Frontend
 */
class Cookie_Notice_Welcome_Frontend {
	private $widget_url = '';
	private $preview_mode = false;

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'preview_init' ), 1 );
		
		$this->widget_url = '//cdn.hu-manity.co/hu-banner.min.js';
	}

	/**
	 * Initialize preview mode.
	 */
	public function preview_init() {
		// check preview mode
		$this->preview_mode = isset( $_GET['cn_preview_mode'] ) ? absint( $_GET['cn_preview_mode'] ) : false;

		if ( $this->preview_mode !== false ) {
			// filters
			add_filter( 'show_admin_bar', '__return_false' );
			add_filter( 'cn_cookie_notice_output', '__return_false', 1000 );
			
			// actions
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_dequeue_scripts' ) );
			
			// only in live preview
			if ( $this->preview_mode === 1 ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
				add_action( 'wp_head', array( $this, 'wp_head_scripts' ), 0 );
			} 
		}
	}

	/**
	 * Load scripts and styles.
	 *
	 * @return void
	 */
	public function wp_enqueue_scripts( $page ) {
		// show only in live preview
		if ( $this->preview_mode === 1 ) {
			wp_enqueue_script( 'cookie-notice-welcome-frontend', plugins_url( '../js/front-welcome.js', __FILE__ ), array( 'jquery', 'underscore' ), Cookie_Notice()->defaults['version'] );

			wp_localize_script(
				'cookie-notice-welcome-frontend',
				'cnFrontWelcome',
				array(
					'previewMode'	=> $this->preview_mode,
					'allowedURLs'	=> $this->get_allowed_urls(),
					'cookieMessage' => Cookie_Notice()->settings->cookie_messages,
					'preferencesMessage' => Cookie_Notice()->settings->preferences_messages
				)
			);
		}
	}
	
	/**
	 * Unload scripts and styles.
	 *
	 * @return void
	 */
	public function wp_dequeue_scripts( $page ) {
		// deregister native CN
		wp_dequeue_script( 'cookie-notice-front' );
	}

	/**
	 * .
	 *
	 * @return void
	 */
	public function wp_head_scripts() {
		$options = array(
			'currentLanguage'	=> 'en',
			'previewMode'		=> true
		);

		echo '
		<!-- Hu Banner -->
		<script type="text/javascript">
			var huOptions = ' . json_encode( $options ) . ';
		</script>
		<script type="text/javascript" src="' . $this->widget_url . '"></script>
		<style>.hu-preview-mode #hu::after {content: "";position: fixed;width: 100%;height: 100%;display: block;top: 0;left: 0;}</style>';
	}

	/**
	 * Get URLs allowed to be previewed.
	 *
	 * @return array
	 */
	public function get_allowed_urls() {
		$allowed_urls = array( home_url( '/' ) );

		if ( is_ssl() && ! $this->is_cross_domain() )
			$allowed_urls[] = home_url( '/', 'https' );

		return $allowed_urls;
	}

	/**
	 * Determines whether the admin and the frontend are on different domains.
	 *
	 * @return bool
	 */
	public function is_cross_domain() {
		$admin_origin = wp_parse_url( admin_url() );
		$home_origin = wp_parse_url( home_url() );

		return ( strtolower( $admin_origin['host'] ) !== strtolower( $home_origin['host'] ) );
	}
}