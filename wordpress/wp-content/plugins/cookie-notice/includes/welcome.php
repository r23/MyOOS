<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Cookie_Notice_Welcome class.
 * 
 * @class Cookie_Notice_Welcome
 */
class Cookie_Notice_Welcome {
	
	private $app_login_url = '';

	public function __construct() {
		// actions
		add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ), 1 );
		add_action( 'admin_init', array( $this, 'welcome' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_cn_welcome_screen', array( $this, 'welcome_screen' ) );
		
		// filters
		add_filter( 'admin_footer_text', '__return_false', 1000 );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
		
		$this->app_login_url = 'https://app.hu-manity.co/#/en/cc2/login';
	}

	/**
	 * Add admin menus/screens.
	 *
	 * @return void
	 */
	public function admin_menus() {
		$welcome_page_title = __( 'Welcome to Cookie Notice', 'cookie-notice' );
		// about
		$about = add_dashboard_page( $welcome_page_title, $welcome_page_title, 'manage_options', 'cookie-notice-welcome', array( $this, 'welcome_page' ) );
	}

	/**
	 * Add styles just for this page, and remove dashboard page links.
	 *
	 * @return void
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', 'cookie-notice-welcome' );
		
		if ( isset( $_GET['page'] ) && $_GET['page'] === 'cookie-notice-welcome' )
			remove_all_actions( 'admin_notices' );
	}

	/**
	 * Load scripts and styles - admin.
	 */
	public function admin_enqueue_scripts( $page ) {
		if ( $page !== 'dashboard_page_cookie-notice-welcome' )
			return;

		wp_enqueue_style( 'cookie-notice-spectrum', plugins_url( '../assets/spectrum/spectrum.min.css', __FILE__ ), array(), Cookie_Notice()->defaults['version'] );

		wp_enqueue_script( 'cookie-notice-spectrum', plugins_url( '../assets/spectrum/spectrum.min.js', __FILE__ ), array(), Cookie_Notice()->defaults['version'] );
		wp_enqueue_script( 'cookie-notice-welcome', plugins_url( '../js/admin-welcome.js', __FILE__ ), array( 'jquery' ), Cookie_Notice()->defaults['version'] );
		wp_enqueue_script( 'cookie-notice-braintree-client', 'https://js.braintreegateway.com/web/3.71.0/js/client.min.js', array(), null, false );
		wp_enqueue_script( 'cookie-notice-braintree-hostedfields', 'https://js.braintreegateway.com/web/3.71.0/js/hosted-fields.min.js', array(), null, false );
		wp_enqueue_script( 'cookie-notice-braintree-paypal', 'https://js.braintreegateway.com/web/3.71.0/js/paypal-checkout.min.js', array(), null, false );

		wp_localize_script(
			'cookie-notice-welcome',
			'cnArgs',
			array(
				'ajaxURL'		=> admin_url( 'admin-ajax.php' ),
				'nonce'			=> wp_create_nonce( 'cookie-notice-welcome' ),
				'error'			=> __( 'Unexpected error occurred. Please try again later.', 'cookie-notice' ),
				'invalidFields'	=> __( 'Please fill all the required fields.', 'cookie-notice' )
			)
		);

		wp_enqueue_style( 'cookie-notice-welcome', plugins_url( '../css/admin-welcome.css', __FILE__ ) );
	}
	
	/**
	 * Add one or more classes to the body tag in the dashboard.
	 *
	 * @param string $classes
	 * @return string
	 */
	public function admin_body_class( $classes ) {
		if ( isset( $_GET['page'] ) && $_GET['page'] === 'cookie-notice-welcome' )
			$classes .= ' folded';
		
		return $classes;
	}

	/**
	 * Send user to the welcome page on first activation.
	 *
	 * @return void
	 */
	public function welcome() {
		// bail if no activation redirect transient is set
		if ( ! get_transient( 'cn_activation_redirect' ) )
			return;

		// delete the redirect transient
		delete_transient( 'cn_activation_redirect' );

		// bail if activating from network, or bulk, or within an iFrame
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) || defined( 'IFRAME_REQUEST' ) )
			return;

		if ( (isset( $_GET['action'] ) && 'upgrade-plugin' == $_GET['action']) && (isset( $_GET['plugin'] ) && strstr( $_GET['plugin'], 'cookie-notice.php' )) )
			return;

		wp_safe_redirect( admin_url( 'index.php?page=cookie-notice-welcome' ) );
		exit;
	}
	
	/**
	 * Output the welcome screen.
	 *
	 * @return void
	 */
	public function welcome_page() {
		// get plugin version
		$plugin_version = substr( Cookie_Notice()->defaults['version'], 0, 3 );
		$screen = ( isset( $_GET['screen'] ) ? (int) $_GET['screen'] : 1 );

		$this->welcome_screen( $screen );
	}
	
	/**
	 * Render welcome screen sidebar step.
	 * 
	 * @param int $step
	 * @return mixed
	 */
	public function welcome_screen( $screen, $echo = true ) {
		if ( ! current_user_can( 'install_plugins' ) )
			wp_die( _( 'You do not have permission to access this page.', 'cookie-notice' ) );

		$sidebars = array( 'about', 'login', 'register', 'configure', 'select_plan', 'success' );
		$steps = array( 1, 2, 3, 4 );
		$screens = array_merge( $sidebars, $steps );

		$is_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
		$screen = ! empty( $screen ) && in_array( $screen, $screens ) ? $screen : ( isset( $_REQUEST['screen'] ) && in_array( $_REQUEST['screen'], $screens ) ? esc_attr( $_REQUEST['screen'] ) : '' );

		if ( empty( $screen ) )
			wp_die( _( 'You do not have permission to access this page.', 'cookie-notice' ) );

		if ( $is_ajax && ! check_ajax_referer( 'cookie-notice-welcome', 'nonce' ) )
			wp_die( _( 'You do not have permission to access this page.', 'cookie-notice' ) );
		
		$logo_url = plugins_url( '../img/cookie-compliance-logo.png', __FILE__ );

		// get token data
		$token_data = get_transient( 'cookie_notice_app_token' );

		// step screens
		if ( in_array( $screen, $steps ) ) {
			$html = '
			<div class="wrap full-width-layout cn-welcome-wrap cn-welcome-step-' . $screen . ' has-loader">';

			if ( $screen == 1 ) {
				$html .= $this->welcome_screen( 'about', false );

				$html .= '
				<div class="cn-content cn-sidebar-visible">
					<div class="cn-inner">
						<div class="cn-content-full">
							<h1><b>Cookie Compliance&trade;</b></h1>
							<h2>' . __( 'The next generation of Cookie Notice', 'cookie-notice' ) . '</h2>
							<div class="cn-lead">
								<p>' . __( 'An all new web application to help you deliver better consent experiences and comply with GDPR and CCPA more effectively.', 'cookie-notice' ) . '</p>
							</div>
							<div class="cn-hero-image">
								<img src="' . plugins_url( '../img/screen-dashboard.png', __FILE__ ) . '">
							</div>
							<div class="cn-lead">
								<p>' . __( 'Digital Factory - the original developers of Cookie Notice - has joined forces with <a href="https://hu-manity.co" target="_blank" class="cn-link">Hu-manity.co</a>, the company known for introducing the 31st Human Right, to launch the Cookie Compliance&trade; web application.', 'cookie-notice' ) . '</p>
							</div>
						</div>
					</div>
				</div>';
			} elseif ( $screen == 2 ) {
				$html .= $this->welcome_screen( 'configure', false );

				$html .= '
				<div id="cn_upgrade_iframe" class="cn-content cn-sidebar-visible has-loader cn-loading"><span class="cn-spinner"></span>
					<iframe id="cn_iframe_id" src="' . home_url( '/?cn_preview_mode=1' ) . '"></iframe>
				</div>';
			} elseif ( $screen == 3 ) {
				// get options
				$app_config = get_transient( 'cookie_notice_app_config' );
				
				// echo '<pre>'; print_r( $app_config ); echo '</pre>'; 
				
				$html .= $this->welcome_screen( 'register', false );

				$html .= '
				<div class="cn-content cn-sidebar-visible">
					<div class="cn-inner">
						<div class="cn-content-full">
							<h1><b>' . __( 'Privacy Made Easy', 'cookie-notice' ) . '</b></h1>
							<h2>' . __( 'The next generation of Cookie Notice', 'cookie-notice' ) . '</h2>
							<div class="cn-lead">
								<p>' . __( 'Cookie Compliance&trade; adds GDPR & CCPA compliance features, and a new Privacy Experience to Cookie Notice.', 'cookie-notice' ) . '</p>
							</div>
							<div class="cn-hero-image">
								<div class="cn-flex-item">
									<div class="cn-logo-container">
										<img src="' . plugins_url( '../img/cookie-notice-logo-dark.png', __FILE__ ) . '">
										<span class="cn-badge">' . __( 'WP Plugin', 'cookie-notice' ) . '</span>
									</div>
									<img src="' . plugins_url( '../img/screen-notice.png', __FILE__ ) . '">
									<ul>
										<li><span>' . __( 'Customizable notice message', 'cookie-notice' ) . '</span></li>
										<li><span>' . __( 'Consent on click, scroll or close', 'cookie-notice' ) . '</span></li>
										<li><span>' . __( 'Multiple cookie expiry options', 'cookie-notice' ) . '</span></li>
										<li><span>' . __( 'Link to Privacy Policy page', 'cookie-notice' ) . '</span></li>
									</ul>
								</div>
								<div class="cn-flex-item">
									<img src="' . plugins_url( '../img/screen-plus.png', __FILE__ ) . '">
								</div>
								<div class="cn-flex-item">
									<div class="cn-logo-container">
										<img src="' . plugins_url( '../img/cookie-compliance-logo-dark.png', __FILE__ ) . '">
										<span class="cn-badge">' . __( 'Web App', 'cookie-notice' ) . '</span>
									</div>
									<img src="' . plugins_url( '../img/screen-compliance.png', __FILE__ ) . '">
									<ul>
										<li><span>' . __( 'Customizable <b>GDPR & CCPA</b> notice templates', 'cookie-notice' ) . '</span></li>
										<li><span>' . __( '<b>Consent Analytics</b> Dashboard', 'cookie-notice' ) . '</span></li>
										<li><span>' . __( 'Cookie <b>Autoblocking</b> (complies with GDPR Art.7)', 'cookie-notice' ) . '</span></li>
										<li><span>' . __( '<b>Cookie Categories</b> (complies with GDPR Art.32)', 'cookie-notice' ) . '</span></li>
										<li><span>' . __( '<b>Proof-of-Consent</b> Storage (complies with GDPR Art.30)', 'cookie-notice' ) . '</span></li>
										<li><span>' . __( "Link to <b>'Do Not Sell'</b> page (supports CCPA Sec.1798)", 'cookie-notice' ) . '</span></li>
										<li><span>' . __( 'Enhanced design controls and options', 'cookie-notice' ) . '</span></li>
										<li><span>' . __( 'Multiple new banner positions', 'cookie-notice' ) . '</span></li>
										<li><span>' . __( 'Custom language localization', 'cookie-notice' ) . '</span></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>';
			} elseif ( $screen == 4 ) {
				$html .= $this->welcome_screen( 'success', false );
				
				$html .= '
				<div class="cn-content cn-sidebar-visible">
					<div class="cn-inner">
						<div class="cn-content-full">
							<h1><b>' . __( 'Welcome', 'cookie-notice' ) . '</b></h1>
							<h2>' . __( 'You are now Promoting Privacy', 'cookie-notice' ) . '</h2>
							<div class="cn-lead">
								<p>' . __( 'Log into the Cookie Compliance&trade; web application and continue configuring your Privacy Experience.', 'cookie-notice' ) . '</p>
							</div>
							<div class="cn-buttons">
								<a href="' . $this->app_login_url . '" class="cn-btn cn-btn-lg" target="_blank">' . __( 'Go to Application', 'cookie-notice' ) . '</a>
							</div>
						</div>
					</div>
				</div>';
			}

			$html .= '
			</div>';
		// sidebar screens
		} elseif ( in_array( $screen, $sidebars ) ) {
			$html = '';

			if ( $screen === 'about' ) {
				
				$theme = wp_get_theme();
				
				$html .= '
				<div class="cn-sidebar cn-sidebar-left has-loader">
					<div class="cn-inner">
						<div class="cn-header">
							<div class="cn-top-bar">
								<div class="cn-logo"><img src="' . $logo_url . '"></div>
							</div>	
						</div>
						<div class="cn-body">
							<h2>' . __( 'GDPR & CCPA Upgrade Ready', 'cookie-notice' ) . '</h2>
							<div class="cn-lead"><p><b>' . __( 'Simulate Cookie Compliance&trade; on your site.', 'cookie-notice' ) . '</b></p><p>' . __( 'Click below to see what the next generation of Cookie Notice looks like running on your website.', 'cookie-notice' ) . '</p></div>
							<div id="cn_preview_about">
								<p>' . __( 'Site URL', 'cookie-notice' ) . ': <b>' . home_url() . '</b></p>
								<p>' . __( 'Site Name', 'cookie-notice' ) . ': <b>' . get_bloginfo( 'name' ) . '</b></p>
							</div>
							' // <div id="cn_preview_frame"><img src=" ' . esc_url( $theme->get_screenshot() ) . '" /></div>
							. '<div id="cn_preview_frame"><div id="cn_preview_frame_wrapper"><iframe id="cn_iframe_id" src="' . home_url( '/?cn_preview_mode=0' ) . '" scrolling="no" frameborder="0"></iframe></div></div>
							<div class="cn-buttons">
								<button type="button" class="cn-btn cn-btn-lg cn-screen-button" data-screen="2"><span class="cn-spinner"></span>' . __( 'Launch Live Demo', 'cookie-notice' ) . '</button>
							</div>
						</div>';
			} elseif ( $screen === 'configure' ) {
				$html .= '
				<div class="cn-sidebar cn-sidebar-left has-loader cn-theme-light">
					<div class="cn-inner">
						<div class="cn-header">
							<div class="cn-top-bar">
								<div class="cn-logo"><img src="' . $logo_url . '"></div>
							</div>	
						</div>
						<div class="cn-body">
							<h2>' . __( 'Compliance Live Demo', 'cookie-notice' ) . '</h2>
							<div class="cn-lead"><p>' . __( 'Simulate the upgraded Cookie Compliance&trade; design and compliance features through the options below. Click Save & Upgrade to create your Cookie Compliance&trade; account.', 'cookie-notice' ) . '</p></div>
							<form id="cn-form-configure" class="cn-form" action="" data-action="configure">
								<div class="cn-accordion">
									<div class="cn-accordion-item cn-form-container">
										<div class="cn-accordion-header cn-form-header"><button class="cn-accordion-button" type="button">' . __( 'Banner Compliance', 'cookie-notice' ) . '</button></div>
										<div class="cn-accordion-collapse cn-form">
											<div class="cn-form-feedback cn-hidden"></div>' .
											/*
											<div class="cn-field cn-field-select">
												<label for="cn_location">' . __( 'What is the location of your business/organization?', 'cookie-notice' ) . '​</label>
												<div class="cn-select-wrapper">
													<select id="cn_location" name="cn_location">
														<option value="0">' . __( 'Select location', 'cookie-notice' ) . '</option>';

				foreach ( Cookie_Notice()->settings->countries as $country_code => $country_name ) {
					$html .= '<option value="' . $country_code . '">' . $country_name . '</option>';
				}

				$html .= '
													</select>
												</div>
											</div>
											*/
											'
											<div id="cn_laws" class="cn-field cn-field-checkbox">
												<label>' . __( 'Select the laws that apply to your business', 'cookie-notice' ) . ':</label>
												<div class="cn-checkbox-image-wrapper">
													<label for="cn_laws_gdpr"><input id="cn_laws_gdpr" type="checkbox" name="cn_laws" value="gdpr" title="' . __( 'GDPR', 'cookie-notice' ) . '" checked><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAC/ElEQVRoge2ZzZGjMBCFmcMet4rjHjlsANQmsGRgZ7BkMGRgZ7DOYMhgnME4A08GdgZ2AujbA41HiD8JEOawXUWVXUjd73WLVqsVBB4F+OlTv3cBciB7Ng4nAV6ADHjnSz6A7bOxPQQIh94Dd43AaSFodgKkFmNOGoHEYvwySw1IgJtFFHJgC6RD4GTJnedF2jQSAUfNqzfgMFFnAnxqOi9CvNc5UwzG1CWaQede03f1Bl6MhZqxz5l0Jot97BKBRH5nc3hLCETyO52qr1LqL4wjxWm5Akd/UMaJfOzdjpUs8xvYyXp8k//RcjA7Mf01MMVdE3IjyxyfvZyMLIVEIuoarGcZJhqOgY14bJITqO8VSd/AqobZy6T2UPUbi5RSH0op9EeW5igiguVAWZ50YxKvhRoZJ4MC/maCr56iKN5GEgi139EYHVailDpqYHMgKYpir5S6a5FIvQGYIuL9B3jjXapFYnUpOgiCIAC2mpcT872+lJ4Ab1hkqfQRuHslIB9wNHa+BYHrHAToOprKJuacJSgPLH+M1HmRtLkDdkqp95aU+tqb09tthcC5No/moeLcybKpMO5KmZbPydLON3HwzagSflQD9BIid/BI4gD2OpaA2DIbBan+8qC9sD5cOxD4FADZWAJir72kkAjE8sxN4FEGF0WRT4xAVtl1/X6sCQCZlpH6wDtHYHbpIFDVUskA+HUSUEqd9eKrB/xqCVQkNmb+X4SAy8fhmEYnEbDGJanKavDCBPoPWJSnsIvk2BvlAbr3RAaEssZPYx6blN2BK2obGFGX/bBf/EsLrm7SlL3J5k73ZMGmVS9MT5Qt8T0rulGhLHViyso3sZ20uvbif1kiKl5tuFSqI/WH+Gq78HUR4dytc7CRS86fLwo078YQQ5HFXKtLEOq3NMP53lVaNpPIcs4Fy0YB9S70LNdXpgGqjW5g3AvNlvgd+DUwb6vZmHT72aY8rtY+WgN4YI5+fh3cFPUNynqz8inUt//V7OpWAnwHNuZvH/IPPeDD9c6V9FUAAAAASUVORK5CYII=" width="24" height="24"><span>' . __( 'GDPR', 'cookie-notice' ) . '</span></label>
													<label for="cn_laws_ccpa"><input id="cn_laws_ccpa" type="checkbox" name="cn_laws" value="ccpa" title="' . __( 'CCPA', 'cookie-notice' ) . '"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACcAAAAwCAYAAACScGMWAAACPElEQVRYheXYvXHbMBTAcY7AEbSA79Smskp30QiqkyLaQPQE8Qb2BtEG4QZil3Ry5ZZaAO/vAqANIwSJD1LmXXD3ToVE8sf3hEcQRVEUBXADfE+Mu2LOAVSkj/q/xj0sGVcvEgeUGTAvDlgBP4CD+Vyl4HaZuNa9WRH5JSK4oZT6CZQxuN+ZOBzYqQ9mxSkYmAuzcUqpyoE0InIUkWcng1UoLresWFlrOwCwczLa2EAispczWzvcxs5YzzXWDm4bistpwk1RfCypr2yppc3BVUvDXYAtsO7OsSRcbY5bAbfArYicrYu36Ob7Fj297wx8Ncf7JwewScGJSD3S00LjOJa9p0/E1SHlDQWm4rqmHI+LAKbgGsx/y23IMbiQVUos7g2G04yjcOYEObga2InIxQNrc3FjK2MvDtP7DOQYAIvGlcBzYub+WRKNwOJw5oRDvW8Ih4icImDxOHNiX3nHcF0GDwGwZJyvvCG4aZuwB9i31lsMbu/DAXsD9IZS6kEpVQ0FoQvPHlxfaU/jR15peGbuGf3mlhqHKYF95c0dj1MCY5ZV1wUy/uT4dOB2BtykwDmyNw0QOM6EyweS9547L/AKOID7VNwcLcUdf1Jxa3T27MjaDOoZL0m4AXRJ3uZ3Pg69p9fy/pxssVYW6GdxbrvJwjXoUnZh40oTFXrT53q4EXiNtYltkCkTaDoc71v734B9z/ex7WdSXHfxzcBvYsbfKXHlECwAd0H/JZ7MjX6ZDBcy0DPYBmyHbugVe8KbbhsHbZ0AAAAASUVORK5CYII=" width="24" height="24"><span>' . __( 'CCPA', 'cookie-notice' ) . '</span></label>
												</div>
											</div>
											<div id="cn_purposes" class="cn-field cn-field-checkbox">
												<label>' . __( 'What kind of services is your site using? Check all that apply', 'cookie-notice' ) . ':</label>
												<div class="cn-checkbox-wrapper">
													<label for="cn_purposes_functional"><input id="cn_purposes_functional" type="checkbox" name="cn_purposes" value="1" checked><span>' . __( 'I use personalization services on my site​', 'cookie-notice' ) . '</span></label>
													<label for="cn_purposes_analytics"><input id="cn_purposes_analytics" type="checkbox" name="cn_purposes" value="2"><span>' . __( 'I collect and analyse information about my website’s traffic', 'cookie-notice' ) . '</span></label>
													<label for="cn_purposes_marketing"><input id="cn_purposes_marketing" type="checkbox" name="cn_purposes" value="3"><span>' . __( 'I run targeted ads on my site using, for example, Google Adsense​', 'cookie-notice' ) . '</span></label>
												</div>
											</div>
										</div>
									</div>
									<div class="cn-accordion-item cn-form-container cn-collapsed">
										<div class="cn-accordion-header cn-form-header"><button class="cn-accordion-button" type="button">' . __( 'Banner Design', 'cookie-notice' ) . '</button></div>
										<div class="cn-accordion-collapse cn-form">
											<div class="cn-form-feedback cn-hidden"></div>
											<div class="cn-field cn-field-radio-image">
												<label>' . __( 'Select your preferred display position', 'cookie-notice' ) . '​:</label>
												<div class="cn-radio-image-wrapper">
													<label for="cn_position_bottom"><input id="cn_position_bottom" type="radio" name="cn_position" value="bottom" title="' . __( 'Bottom', 'cookie-notice' ) . '" checked><img src="' . plugins_url( '../img/layout-bottom.png', __FILE__ ) . '" width="24" height="24"></label>
													<label for="cn_position_top"><input id="cn_position_top" type="radio" name="cn_position" value="top" title="' . __( 'Top', 'cookie-notice' ) . '"><img src="' . plugins_url( '../img/layout-top.png', __FILE__ ) . '" width="24" height="24"></label>
													<label for="cn_position_left"><input id="cn_position_left" type="radio" name="cn_position" value="left" title="' . __( 'Left', 'cookie-notice' ) . '"><img src="' . plugins_url( '../img/layout-left.png', __FILE__ ) . '" width="24" height="24"></label>
													<label for="cn_position_right"><input id="cn_position_right" type="radio" name="cn_position" value="right" title="' . __( 'Right', 'cookie-notice' ) . '"><img src="' . plugins_url( '../img/layout-right.png', __FILE__ ) . '" width="24" height="24"></label>
													<label for="cn_position_center"><input id="cn_position_center" type="radio" name="cn_position" value="center" title="' . __( 'Center', 'cookie-notice' ) . '"><img src="' . plugins_url( '../img/layout-center.png', __FILE__ ) . '" width="24" height="24"></label>
												</div>
											</div>
											<div class="cn-field cn-fieldset">
												<label>' . __( 'Adjust the banner color scheme', 'cookie-notice' ) . '​:</label>
												<div class="cn-checkbox-wrapper cn-color-picker-wrapper">
													<label for="cn_color_primary"><input id="cn_color_primary" class="cn-color-picker" type="checkbox" name="cn_color_primary" value="#20c19e"><span>' . __( 'Color of the buttons and interactive elements.', 'cookie-notice' ) . '</span></label>
													<label for="cn_color_background"><input id="cn_color_background" class="cn-color-picker" type="checkbox" name="cn_color_background" value="#32323a"><span>' . __( 'Color of the banner background.', 'cookie-notice' ) . '</span></label>
													<label for="cn_color_border"><input id="cn_color_border" class="cn-color-picker" type="checkbox" name="cn_color_border" value="#86858b"><span>' . __( 'Color of the borders and inactive elements.', 'cookie-notice' ) . '</span></label>
													<label for="cn_color_text"><input id="cn_color_text" class="cn-color-picker" type="checkbox" name="cn_color_text" value="#ffffff"><span>' . __( 'Color of the body text.', 'cookie-notice' ) . '</span></label>
													<label for="cn_color_heading"><input id="cn_color_heading" class="cn-color-picker" type="checkbox" name="cn_color_heading" value="#86858b"><span>' . __( 'Color of the heading text.', 'cookie-notice' ) . '</span></label>
													<label for="cn_color_button_text"><input id="cn_color_button_text" class="cn-color-picker" type="checkbox" name="cn_color_button_text" value="#ffffff"><span>' . __( 'Color of the button text.', 'cookie-notice' ) . '</span></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="cn-field cn-field-submit cn-nav">
									<button type="button" class="cn-btn cn-screen-button" data-screen="3"><span class="cn-spinner"></span>' . __( 'Add Compliance', 'cookie-notice' ) . '</button>
								</div>';

				$html .= wp_nonce_field( 'cn_api_configure', 'cn_nonce', true, false );

				$html .= '
							</form>
						</div>';
			} elseif ( $screen === 'register' ) {
				$html .= '
				<div class="cn-sidebar cn-sidebar-left has-loader">
					<div class="cn-inner">
						<div class="cn-header">
							<div class="cn-top-bar">
								<div class="cn-logo"><img src="' . $logo_url . '"></div>
							</div>	
						</div>
						<div class="cn-body">
							<h2>' . __( 'GDPR & CCPA Upgrade Ready', 'cookie-notice' ) . '</h2>
							<div class="cn-lead">
								<p>' . __( 'Create an account to start using Cooking Compliance&trade; - The next generation of Cookie Notice.', 'cookie-notice' ) . '</p>
							</div>
							<div class="cn-accordion">
								<div id="cn-accordion-account" class="cn-accordion-item cn-form-container">
									<div class="cn-accordion-header cn-form-header"><button class="cn-accordion-button" type="button">' . __( 'Compliance Account', 'cookie-notice' ) . '</button></div>
									<div class="cn-accordion-collapse">
										<form class="cn-form" action="" data-action="register">
											<div class="cn-form-feedback cn-hidden"></div>
											<div class="cn-field cn-field-text">
												<input type="text" name="email" value="" tabindex="1" placeholder="' . __( 'Email address', 'cookie-notice' ) . '">
											</div>
											<div class="cn-field cn-field-text">
												<input type="password" name="pass" value="" tabindex="2" autocomplete="off" placeholder="' . __( 'Password', 'cookie-notice' ) . '">
											</div>
											<div class="cn-field cn-field-text">
												<input type="password" name="pass2" value="" tabindex="3" autocomplete="off" placeholder="' . __( 'Confirm Password', 'cookie-notice' ) . '">
											</div>
											<div class="cn-field cn-field-checkbox">
												<div class="cn-checkbox-wrapper">
													<label for="cn_terms"><input id="cn_terms" type="checkbox" name="terms" value="1"><span>' . __( 'I have read and agree to the', 'cookie-notice' ) . ' <a href="https://hu-manity.co/cookiecompliance-terms/" target="_blank">' . __( 'Terms of Service', 'cookie-notice' ) . '</a></span></label>
													</div>
											</div>
											<div class="cn-field cn-field-submit cn-nav">
												<button type="submit" class="cn-btn cn-screen-button" tabindex="4" ' . /* data-screen="3" */ '><span class="cn-spinner"></span>' . __( 'Sign Up', 'cookie-notice' ) . '</button>
											</div>';

				// get site language
				$locale = get_locale();
				$locale_code = explode( '_', $locale );

				$html .= '
											<input type="hidden" name="language" value="' . $locale_code[0] . '" />';

				$html .= wp_nonce_field( 'cn_api_register', 'cn_nonce', true, false );

				$html .= '
										</form>
										<p>' . __( 'Already have an account? <a href="#" class="cn-screen-button" data-screen="login">Sign in</a>', 'cookie-notice' ) . '</p>
									</div>
								</div>
								<div id="cn-accordion-billing" class="cn-accordion-item cn-form-container cn-collapsed cn-disabled">
									<div class="cn-accordion-header cn-form-header">
										<button class="cn-accordion-button" type="button">' . __( 'Compliance Plan', 'cookie-notice' ) . '</button>
									</div>
									<form class="cn-accordion-collapse cn-form" action="" data-action="payment">
										<div class="cn-form-feedback cn-hidden"></div>
										<div class="cn-field cn-field-radio">
											<div class="cn-radio-wrapper cn-plan-wrapper">
												<label for="cn_field_plan_monthly"><input id="cn_field_plan_monthly" type="radio" name="plan" value="compliance_monthly" checked><span><span class="cn-plan-description">Bill monthly</span><span class="cn-plan-price">$14.95<span class="cn-plan-period">' . __( '/mo', 'cookie-notice' ) . '</span></span><span class="cn-plan-overlay"></span></span></label>
												<label for="cn_field_plan_yearly"><input id="cn_field_plan_yearly" type="radio" name="plan" value="compliance_yearly"><span><span class="cn-plan-description">Bill yearly <span class="cn-price-off">(15% off)</span></span><span class="cn-plan-price">$149.50<span class="cn-plan-period">' . __( '/yr', 'cookie-notice' ) . '</span></span><span class="cn-plan-overlay"></span></span></label>
											</div>
										</div>
										<div class="cn-field cn-field-radio">
											<label>' . __( 'Payment Method', 'cookie-notice' ) . '</label>
											<div class="cn-radio-wrapper cn-horizontal-wrapper">
												<label for="cn_field_method_credit_card"><input id="cn_field_method_credit_card" type="radio" name="method" value="credit_card" checked><span>' . __( 'Credit Card', 'cookie-notice' ) . '</span></label>
												<label for="cn_field_method_paypal"><input id="cn_field_method_paypal" type="radio" name="method" value="paypal"><span>' . __( 'PayPal', 'cookie-notice' ) . '</span></label>
											</div>
										</div>
										<div class="cn-fieldset" id="cn_payment_method_credit_card">
											<input type="hidden" name="payment_nonce" value="" />
											<div class="cn-field cn-field-text">
												<label for="cn_card_number">' . __( 'Card Number', 'cookie-notice' ) . '</label>
												<div id="cn_card_number"></div>
											</div>
											<div class="cn-field cn-field-text cn-field-half cn-field-first">
												<label for="cn_expiration_date">' . __( 'Expiration Date', 'cookie-notice' ) . '</label>
												<div id="cn_expiration_date"></div>
											</div>
											<div class="cn-field cn-field-text cn-field-half cn-field-last">
												<label for="cn_cvv">' . __( 'CVC/CVV', 'cookie-notice' ) . '</label>
												<div id="cn_cvv"></div>
											</div>
											<div class="cn-field cn-field-submit cn-nav">
												<button type="submit" class="cn-btn cn-screen-button" tabindex="4" data-screen="4"><span class="cn-spinner"></span>' . __( 'Subscribe', 'cookie-notice' ) . '</button>
											</div>
										</div>
										<div class="cn-fieldset" id="cn_payment_method_paypal" style="display: none;">
											<div id="cn_paypal_button"></div>
										</div>';

				$html .= wp_nonce_field( 'cn_api_payment', 'cn_payment_nonce', true, false );

				$html .= '
									</form>
								</div>
							</div>
						</div>';
			} elseif ( $screen === 'login' ) {
				$html .= '
				<div class="cn-sidebar cn-sidebar-left has-loader">
					<div class="cn-inner">
						<div class="cn-header">
							<div class="cn-top-bar">
								<div class="cn-logo"><img src="' . $logo_url . '"></div>
							</div>	
						</div>
						<div class="cn-body">
							<h2>' . __( 'Compliance Sign in', 'cookie-notice' ) . '</h2>
							<div class="cn-lead">
								<p>' . __( 'Sign in to your existing Cooking Compliance&trade; account to upgrade this website.', 'cookie-notice' ) . '</p>
							</div>
							<div class="cn-accordion">
								<div id="cn-accordion-account" class="cn-accordion-item cn-form-container">
									<div class="cn-accordion-header cn-form-header"><button class="cn-accordion-button" type="button">' . __( 'Compliance Account', 'cookie-notice' ) . '</button></div>
									<div class="cn-accordion-collapse">
										<form class="cn-form" action="" data-action="login">
											<div class="cn-form-feedback cn-hidden"></div>
											<div class="cn-field cn-field-text">
												<input type="text" name="email" value="" tabindex="1" placeholder="' . __( 'Email address', 'cookie-notice' ) . '">
											</div>
											<div class="cn-field cn-field-text">
												<input type="password" name="pass" value="" tabindex="2" autocomplete="off" placeholder="' . __( 'Password', 'cookie-notice' ) . '">
											</div>
											<div class="cn-field cn-field-submit cn-nav">
												<button type="submit" class="cn-btn cn-screen-button" tabindex="4" ' . /* data-screen="4" */ '><span class="cn-spinner"></span>' . __( 'Sign in', 'cookie-notice' ) . '</button>
											</div>';
				
				// get site language
				$locale = get_locale();
				$locale_code = explode( '_', $locale );

				$html .= '
											<input type="hidden" name="language" value="' . $locale_code[0] . '" />';

				$html .= wp_nonce_field( 'cn_api_login', 'cn_nonce', true, false );

				$html .= '
										</form>
										<p>' . __( 'Don\'t have an account yet? <a href="#" class="cn-screen-button" data-screen="register">Sign up</a>', 'cookie-notice' ) . '</p>
									</div>
								</div>
								<div id="cn-accordion-billing" class="cn-accordion-item cn-form-container cn-collapsed cn-disabled">
									<div class="cn-accordion-header cn-form-header">
										<button class="cn-accordion-button" type="button">' . __( 'Compliance Plan', 'cookie-notice' ) . '</button>
									</div>
									<form class="cn-accordion-collapse cn-form" action="" data-action="payment">
										<div class="cn-form-feedback cn-hidden"></div>
										<div class="cn-field cn-field-radio">
											<div class="cn-radio-wrapper cn-plan-wrapper">
												<label for="cn_field_plan_monthly"><input id="cn_field_plan_monthly" type="radio" name="plan" value="compliance_monthly" checked><span><span class="cn-plan-description">Bill monthly</span><span class="cn-plan-price">$14.95<span class="cn-plan-period">' . __( '/mo', 'cookie-notice' ) . '</span></span><span class="cn-plan-overlay"></span></span></label>
												<label for="cn_field_plan_yearly"><input id="cn_field_plan_yearly" type="radio" name="plan" value="compliance_yearly"><span><span class="cn-plan-description">Bill yearly <span class="cn-price-off">-(15% off)</span></span><span class="cn-plan-price">$149.50<span class="cn-plan-period">' . __( '/yr', 'cookie-notice' ) . '</span></span><span class="cn-plan-overlay"></span></span></label>
											</div>
										</div>
										<div class="cn-field cn-field-radio">
											<label>' . __( 'Payment Method', 'cookie-notice' ) . '</label>
											<div class="cn-radio-wrapper cn-horizontal-wrapper">
												<label for="cn_field_method_credit_card"><input id="cn_field_method_credit_card" type="radio" name="method" value="credit_card" checked><span>' . __( 'Credit Card', 'cookie-notice' ) . '</span></label>
												<label for="cn_field_method_paypal"><input id="cn_field_method_paypal" type="radio" name="method" value="paypal"><span>' . __( 'PayPal', 'cookie-notice' ) . '</span></label>
											</div>
										</div>
										<div class="cn-fieldset" id="cn_payment_method_credit_card">
											<input type="hidden" name="payment_nonce" value="" />
											<div class="cn-field cn-field-text">
												<label for="cn_card_number">' . __( 'Card Number', 'cookie-notice' ) . '</label>
												<div id="cn_card_number"></div>
											</div>
											<div class="cn-field cn-field-text cn-field-half cn-field-first">
												<label for="cn_expiration_date">' . __( 'Expiration Date', 'cookie-notice' ) . '</label>
												<div id="cn_expiration_date"></div>
											</div>
											<div class="cn-field cn-field-text cn-field-half cn-field-last">
												<label for="cn_cvv">' . __( 'CVC/CVV', 'cookie-notice' ) . '</label>
												<div id="cn_cvv"></div>
											</div>
											<div class="cn-field cn-field-submit cn-nav">
												<button type="submit" class="cn-btn cn-screen-button" tabindex="4" data-screen="4"><span class="cn-spinner"></span>' . __( 'Subscribe', 'cookie-notice' ) . '</button>
											</div>
										</div>
										<div class="cn-fieldset" id="cn_payment_method_paypal" style="display: none;">
											<div id="cn_paypal_button"></div>
										</div>';

				$html .= wp_nonce_field( 'cn_api_payment', 'cn_payment_nonce', true, false );

				$html .= '
									</form>
								</div>
							</div>
						</div>';
			} elseif ( $screen === 'success' ) {
				$html .= '
				<div class="cn-sidebar cn-sidebar-left has-loader">
					<div class="cn-inner">
						<div class="cn-header">
							<div class="cn-top-bar">
								<div class="cn-logo"><img src="' . $logo_url . '"></div>
							</div>	
						</div>
						<div class="cn-body">
							<h2>' . __( 'Success!', 'cookie-notice' ) . '</h2>
							<div class="cn-lead"><p><b>' . __( 'You have successfully upgraded your website to Cookie Compliance&trade;', 'cookie-notice' ) . '</b></p><p>' . sprintf( __( 'Go to Cookie Compliance&trade; application now. Or access it anytime from your <a href="%s">Cookie Notice settings page</a>.', 'cookie-notice' ), esc_url( admin_url( 'options-general.php?page=cookie-notice' ) ) ) . '</p></div>
						</div>';
			}

			$html .= '
					<div class="cn-footer">';
			
			switch ( $screen ) {
				case 'about':
					$html .= '<a href="' . esc_url( admin_url( 'options-general.php?page=cookie-notice' ) ) . '" class="cn-btn cn-btn-link cn-skip-button">' . __( 'Remind me later', 'cookie-notice' ) . '</a>';
					break;
				case 'success':
					$html .= '<a href="' . esc_url( get_dashboard_url() ) . '" class="cn-btn cn-btn-link cn-skip-button">' . __( 'WordPress Dashboard', 'cookie-notice' ) . '</a>';
					break;
				default:
					$html .= '<a href="' . esc_url( admin_url( 'options-general.php?page=cookie-notice' ) ) . '" class="cn-btn cn-btn-link cn-skip-button">' . __( 'Skip for now', 'cookie-notice' ) . '</a>';
					break;
			}
			
			$html .= '
					</div>
				</div>
			</div>';
					
		}

		if ( $echo )
			echo $html;
		else
			return $html;

		if ( $is_ajax )
			exit();
	}
}