<?php
/**
 * Avoid direct calls to this file
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');

	exit();
} // END if(!function_exists('add_action'))

/**
 * The Settings Class
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!class_exists('Twoclick_Social_Media_Buttons_Backend')) {
	class Twoclick_Social_Media_Buttons_Backend {
		private $var_SettingsPageScreenID	= 'settings_page_twoclick_buttons';
		private $var_sSettingsPageHandle	= 'twoclick_buttons';
		private $var_sOptionsGroup			= 'twoclick_buttons_options';
		private $var_sOptionsName			= 'twoclick_buttons_settings';
		private $var_sActiveTab				= 'general-settings';
		private $var_sCapability			= 'manage_options';

		private $array_TwoclickButtonsOptions;
		private $array_SupportedNetworks;
		private $array_Tabs;

		private $obj_screen;

		/**
		 * PHP 5 Constructor
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function __construct() {
			$this->array_TwoclickButtonsOptions = get_option($this->var_sOptionsName);
			$this->array_SupportedNetworks = $this->_get_supported_networks();

			/**
			 * Sidebarwidget, wenn es angezeigt werden soll
			 *
			 * @since coming soon ...
			 * @author ppfeufer
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_sidebar_widget']) {
				if(is_readable(plugin_dir_path(__FILE__) . 'class-twoclick-sidebar-widget.php')) {
					require_once(plugin_dir_path(__FILE__) . 'class-twoclick-sidebar-widget.php');
				} // END if(is_readable(plugin_dir_path(__FILE__) . 'class-twoclick-sidebar-widget.php'))
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_sidebar_widget'])

			/**
			 * Nur ausführen wenn im Backend
			 */
			if(is_admin()) {
				// Plugin initialisieren
				add_action('admin_init', array(
					$this,
					'plugin_init'
				));

				// Optionsseite zu den Einstellungen im Dashboard hinzufügen
				add_action('admin_menu', array(
					$this,
					'embed_options_page'
				));

				// JS und CSS für die Optionsseite
				add_action('admin_enqueue_scripts', array(
					$this,
					'_enqueue_admin'
				));

				add_action('admin_head', array(
					$this,
					'_enqueue_admin_head'
				));

				add_action('admin_footer', array(
					$this,
					'_enqueue_admin_footer'
				));

				// Settingslink zur PLuginübersicht hinzufügen.
				add_filter('plugin_action_links', array(
					$this,
					'_settings_link'
				), 9, 2);

				// Updatemeldung
// 				if(ini_get('allow_url_fopen') || function_exists('curl_init')) {
// 					add_action('in_plugin_update_message-' . TWOCLICK_BASENAME, array(
// 						$this,
// 						'_update_notice'
// 					));
// 				} // END if(ini_get('allow_url_fopen') || function_exists('curl_init'))
			} // END if(is_admin())
		} // END function __construct()

		/**
		 * <[ Helper ]>
		 * Returning the plugins options
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @param string $parameter
		 * @return array
		 */
		function _get_option($parameter = '') {
			/**
			 * Prüfen ob das Formular abgesendet wurde oder das Optionsarray leer ist.
			 * Wenn ja, lade Optionen neu, ansonsten übernehme das Array.
			 */
			if((isset($_REQUEST['settings-updated']) && ($_REQUEST['settings-updated'] == true)) || (empty($this->array_TwoclickButtonsOptions))) {
				$this->array_TwoclickButtonsOptions = get_option($this->var_sOptionsName);
			} // END if((isset($_REQUEST['settings-updated']) && ($_REQUEST['settings-updated'] == true)) || (empty($this->array_TwoclickButtonsOptions)))

			if($parameter == '') {
				return $this->array_TwoclickButtonsOptions;
			} else {
				return $this->array_TwoclickButtonsOptions[$parameter];
			} // END if($parameter == '')
		} // END private function _get_option($parameter = '')

		/**
		 * <[ Helper ]>
		 * Returning the plugindata
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @return array
		 */
		private function _get_plugin_data() {
			$array_DefaultHeaders = array(
				'Name' => 'Plugin Name',
				'PluginURI' => 'Plugin URI',
				'Version' => 'Version',
				'Description' => 'Description',
				'Author' => 'Author',
				'AuthorURI' => 'Author URI',
				'TextDomain' => 'Text Domain',
				'DomainPath' => 'Domain Path',
			);

			$array_PluginData = get_file_data(TWOCLICK_PLUGIN_DIR . '2-click-socialmedia-buttons.php', $array_DefaultHeaders, 'plugin');

			$array_PluginData['Title'] = $array_PluginData['Name'];
			$array_PluginData['AuthorName'] = $array_PluginData['Author'];

			return $array_PluginData;
		} // END private function _get_plugin_data()

		/**
		 * <[ Helper ]>
		 * Returning the current screen object.
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @return Ambigous <WP_Screen, NULL, StdClass, string, multitype:>
		 */
		private function _get_screen() {
			return get_current_screen();
		} // END private function _get_screen()

		/**
		 * <[ Helper ]>
		 * Returning the current pluginversion
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @return string
		 */
		private function _get_plugin_version() {
			$array_PluginData = $this->_get_plugin_data();

			return $array_PluginData['Version'];
		} // END private function _get_plugin_version()

		/**
		 * <[ Helper ]>
		 * Check if we are on the settings page
		 *
		 * @return boolean
		 */
		function _is_twoclick_settings_page() {
			if($this->_get_screen()->id == $this->var_SettingsPageScreenID) {
				return true;
			} else {
				return false;
			} // END if($this->_get_screen()->id == $this->var_SettingsPageScreenID)
		} // END private function _is_twoclick_settings_page()

		/**
		 * Enqueues some needed scripts to the settings page
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function _enqueue_admin() {
			if($this->_is_twoclick_settings_page()) {
				/**
				 * JavaScript
				 */
				wp_enqueue_script('media-upload');
				wp_enqueue_script('thickbox');
// 				wp_register_script('twoclick-image-upload', plugins_url('/js/jquery-media-upload.js', dirname(__FILE__)), array(
				wp_register_script('twoclick-image-upload', plugins_url('/js/jquery-media-upload-min.js', dirname(__FILE__)), array(
					'jquery',
					'media-upload',
					'thickbox'
				), $this->_get_plugin_version());
				wp_localize_script('twoclick-image-upload', 'twoclick_localizing_upload_js', array(
					'use_this_image' => __('Use This Image', TWOCLICK_TEXTDOMAIN)
				));
				wp_enqueue_script('twoclick-image-upload');

				/**
				 * CSS
				 */
				$var_sCss = plugins_url('/css/twoclick-admin-min.css', dirname(__FILE__));
// 				$var_sCss = plugins_url('/css/twoclick-admin.css', dirname(__FILE__));

				wp_register_style('twoclick-admin', $var_sCss, '', $this->_get_plugin_version());
				wp_enqueue_style('twoclick-admin');
				wp_enqueue_style('thickbox');
			} // END if($this->_is_twoclick_settings_page())
		} // END function _enqueue_admin()

		/**
		 * Adds a little CSS-Fix to the admin header only for the plugins settings page
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function _enqueue_admin_head() {
			if($this->_is_twoclick_settings_page()) {
				// Do some stuff ...
			} // END if($this->_is_twoclick_settings_page())
		} // END function _enqueue_admin_head()

		/**
		 * Add some stuff to the admin_footer
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function _enqueue_admin_footer() {
			if($this->_is_twoclick_settings_page()) {
				?>
				<script type="text/javascript">
				/* <![CDATA[ */
				function getElementStyle(oElm, strCssRule) {
					var strValue = '';

					if(document.defaultView && document.defaultView.getComputedStyle) {
						strValue = document.defaultView.getComputedStyle(oElm, '').getPropertyValue(strCssRule);
					} else if(oElm.currentStyle) {
						strCssRule = strCssRule.replace(/\-(\w)/g, function (strMatch, p1) {
							return p1.toUpperCase();
						});
						strValue = oElm.currentStyle[strCssRule];
					}

					return strValue;
				}

				function toggleElementVisibility(ElementId) {
					var htmlStyle = getElementStyle(document.getElementById(ElementId), 'display');

					if(htmlStyle == 'none') {
						document.getElementById(ElementId).style.display = 'block';
					} else {
						document.getElementById(ElementId).style.display = 'none';
					}
				}
				/* ]]> */
				</script>
				<?php
			} // END if($this->_is_twoclick_settings_page())
		} // END function _enqueue_admin_footer()

		/**
		 * Initialize Options
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function plugin_init() {
			// register settings
			register_setting(
				$this->var_sOptionsGroup, // Options group, see settings_fields() call in render_options_page()
				$this->var_sOptionsName, // Database option
				array(
					$this,
					'options_validate'
				) // The sanitization callback, see options_validate()
			);

			/**
			 * Setting default options on first install or update existing options
			 *
			 * @since 1.0
			 */
			if($this->array_TwoclickButtonsOptions == false) {
				$this->array_TwoclickButtonsOptions = $this->_get_default_options();

				add_option($this->var_sOptionsName, $this->array_TwoclickButtonsOptions, '', 'yes');
			} else {
				$array_DefaultOptions = $this->_get_default_options();

				foreach((array) $array_DefaultOptions as $key => $value) {
					if(!isset($this->array_TwoclickButtonsOptions[$key])) {
						$this->array_TwoclickButtonsOptions[$key] = $value;
					}
				}

				update_option($this->var_sOptionsName, $this->array_TwoclickButtonsOptions);
			} // END if($this->array_TwoclickButtonsOptions == false)

			/**
			 * Sprachdatei wählen
			 */
			if(function_exists('load_plugin_textdomain')) {
				load_plugin_textdomain(TWOCLICK_TEXTDOMAIN, false, TWOCLICK_L10N_DIR);
			} // END if(function_exists('load_plugin_textdomain'))
		} // END function plugin_init()

		/**
		 * Validating Options
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @param array $input
		 */
		function options_validate($input) {
			if(isset($input['twoclick_buttons_settings_reset'])) {
				// Resetting options to default
				$output = $this->_get_default_options();
			} else {
				$output = $this->array_TwoclickButtonsOptions;

				if(isset($input['twoclick_buttons_settings_section'])) {
					switch($input['twoclick_buttons_settings_section']) {
						case 'general-settings':
							// Validating General Setting
							foreach((array) $this->array_SupportedNetworks as $var_sKey => $var_sValue) {
								$output['twoclick_buttons_display_' . $var_sKey] = (isset($input['twoclick_buttons_display_' . $var_sKey]) && $input['twoclick_buttons_display_' . $var_sKey] == 1) ? true : false;
								$output['twoclick_buttons_display_' . $var_sKey . '_perm'] = (isset($input['twoclick_buttons_display_' . $var_sKey . '_perm']) && $input['twoclick_buttons_display_' . $var_sKey . '_perm'] == 1) ? true : false;
							} // END foreach((array) $this->array_SupportedNetworks as $var_sKey => $var_sValue)

							$output['twoclick_buttons_display_page'] = (isset($input['twoclick_buttons_display_page']) && $input['twoclick_buttons_display_page'] == 1) ? true : false;
							$output['twoclick_buttons_display_sidebar_widget'] = (isset($input['twoclick_buttons_display_sidebar_widget']) && $input['twoclick_buttons_display_sidebar_widget']) == 1 ? true : false;
							$output['twoclick_buttons_display_private'] = (isset($input['twoclick_buttons_display_private']) && $input['twoclick_buttons_display_private'] == 1) ? true : false;
							$output['twoclick_buttons_display_password'] = (isset($input['twoclick_buttons_display_password']) && $input['twoclick_buttons_display_password'] == 1) ? true : false;
							$output['twoclick_buttons_display_index'] = (isset($input['twoclick_buttons_display_index']) && $input['twoclick_buttons_display_index'] == 1) ? true : false;
							$output['twoclick_buttons_display_year'] = (isset($input['twoclick_buttons_display_year']) && $input['twoclick_buttons_display_year'] == 1) ? true : false;
							$output['twoclick_buttons_display_month'] = (isset($input['twoclick_buttons_display_month']) && $input['twoclick_buttons_display_month'] == 1) ? true : false;
							$output['twoclick_buttons_display_day'] = (isset($input['twoclick_buttons_display_day']) && $input['twoclick_buttons_display_day'] == 1) ? true : false;
							$output['twoclick_buttons_display_search'] = (isset($input['twoclick_buttons_display_search']) && $input['twoclick_buttons_display_search'] == 1) ? true : false;
							$output['twoclick_buttons_display_category'] = (isset($input['twoclick_buttons_display_category']) && $input['twoclick_buttons_display_category'] == 1) ? true : false;
							$output['twoclick_buttons_display_tag'] = (isset($input['twoclick_buttons_display_tag']) && $input['twoclick_buttons_display_tag'] == 1) ? true : false;
							$output['twoclick_buttons_where'] = wp_filter_nohtml_kses($input['twoclick_buttons_where']);

							// Validating custom post types
							unset($output['twoclick_buttons_exclude_cpt']);
							if(isset($input['twoclick_buttons_exclude_cpt']) && is_array($input['twoclick_buttons_exclude_cpt'])) {
								foreach($input['twoclick_buttons_exclude_cpt'] as $key => $value) {
									if((post_type_exists($key)) && ($value == 1)) {
										$output['twoclick_buttons_exclude_cpt'][$key] = true;
									} else {
										unset($output['twoclick_buttons_exclude_cpt'][$key]);
									} // END if((post_type_exists($key)) && ($value == 1))
								} // END foreach($input['twoclick_buttons_exclude_page'] as $key => $value)
							} // END if(is_array($input['twoclick_buttons_exclude_page']))

							// Validating excludes pages
							unset($output['twoclick_buttons_exclude_page']);
							if(isset($input['twoclick_buttons_exclude_page']) && is_array($input['twoclick_buttons_exclude_page'])) {
								foreach($input['twoclick_buttons_exclude_page'] as $key => $value) {
									if((get_post_type($key) == 'page') && ($value == 1)) {
										$output['twoclick_buttons_exclude_page'][$key] = true;
									} else {
										unset($output['twoclick_buttons_exclude_page'][$key]);
									} // END if((get_post_type($key) == 'page') && ($value == 1))
								} // END foreach($input['twoclick_buttons_exclude_page'] as $key => $value)
							} // END if(is_array($input['twoclick_buttons_exclude_page']))
							break;

						case 'button-settings':
							// Validating Button Settings
							$output['twoclick_buttons_facebook_action'] = wp_filter_nohtml_kses($input['twoclick_buttons_facebook_action']);
							$output['twoclick_buttons_twitter_reply'] = $this->_get_sanitized_twitter_name($input['twoclick_buttons_twitter_reply']);
							$output['twoclick_buttons_twitter_tweettext'] = wp_filter_nohtml_kses($input['twoclick_buttons_twitter_tweettext']);
							$output['twoclick_buttons_twitter_tweettext_default_as'] = wp_filter_nohtml_kses($input['twoclick_buttons_twitter_tweettext_default_as']);
							$output['twoclick_buttons_twitter_tweettext_owntext'] = wp_filter_nohtml_kses($input['twoclick_buttons_twitter_tweettext_owntext']);
							$output['twoclick_buttons_twitter_hashtags'] = (isset($input['twoclick_buttons_twitter_hashtags']) && $input['twoclick_buttons_twitter_hashtags'] == 1) ? true : false;
							$output['twoclick_buttons_flattr_uid'] = wp_filter_nohtml_kses($input['twoclick_buttons_flattr_uid']);
							$output['twoclick_buttons_pinterest_description'] = wp_filter_nohtml_kses($input['twoclick_buttons_pinterest_description']);

							if(!empty($input['twoclick_buttons_language'])) {
								$output['twoclick_buttons_language'] = wp_filter_nohtml_kses($input['twoclick_buttons_language']);
							} else {
								unset($output['twoclick_buttons_language']);
							}
							break;

						case 'infotext-settings':
							// Facebook
							if(!empty($input['twoclick_buttons_infotext_facebook'])) {
								$output['twoclick_buttons_infotext_facebook'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_infotext_facebook']));
							} else {
								unset($output['twoclick_buttons_infotext_facebook']);
							} // END if(!empty($input['twoclick_buttons_infotext_facebook']))

							// Twitter
							if(!empty($input['twoclick_buttons_infotext_twitter'])) {
								$output['twoclick_buttons_infotext_twitter'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_infotext_twitter']));
							} else {
								unset($output['twoclick_buttons_infotext_twitter']);
							} // END if(!empty($input['twoclick_buttons_infotext_twitter']))

							// Google+
							if(!empty($input['twoclick_buttons_infotext_googleplus'])) {
								$output['twoclick_buttons_infotext_googleplus'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_infotext_googleplus']));
							} else {
								unset($output['twoclick_buttons_infotext_googleplus']);
							} // END if(!empty($input['twoclick_buttons_introtext']))

							// Flattr
							if(!empty($input['twoclick_buttons_infotext_flattr'])) {
								$output['twoclick_buttons_infotext_flattr'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_infotext_flattr']));
							} else {
								unset($output['twoclick_buttons_infotext_flattr']);
							} // END if(!empty($input['twoclick_buttons_infotext_flattr']))

							// Xing
							if(!empty($input['twoclick_buttons_infotext_xing'])) {
								$output['twoclick_buttons_infotext_xing'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_infotext_xing']));
							} else {
								unset($output['twoclick_buttons_infotext_xing']);
							} // END if(!empty($input['twoclick_buttons_infotext_xing']))

							// Pinterest
							if(!empty($input['twoclick_buttons_infotext_pinterest'])) {
								$output['twoclick_buttons_infotext_pinterest'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_infotext_pinterest']));
							} else {
								unset($output['twoclick_buttons_infotext_pinterest']);
							} // END if(!empty($input['twoclick_buttons_infotext_pinterest']))

							// t3n
							if(!empty($input['twoclick_buttons_infotext_t3n'])) {
								$output['twoclick_buttons_infotext_t3n'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_infotext_t3n']));
							} else {
								unset($output['twoclick_buttons_infotext_t3n']);
							} // END if(!empty($input['twoclick_buttons_infotext_t3n']))

							// LinkedIn
							if(!empty($input['twoclick_buttons_infotext_linkedin'])) {
								$output['twoclick_buttons_infotext_linkedin'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_infotext_linkedin']));
							} else {
								unset($output['twoclick_buttons_infotext_linkedin']);
							} // END if(!empty($input['twoclick_buttons_infotext_linkedin']))

							// Infobutton
							if(!empty($input['twoclick_buttons_infotext_infobutton'])) {
								$output['twoclick_buttons_infotext_infobutton'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_infotext_infobutton']));
							} else {
								unset($output['twoclick_buttons_infotext_infobutton']);
							} // END if(!empty($input['twoclick_buttons_infotext_infobutton']))

							// Permaoption
							if(!empty($input['twoclick_buttons_infotext_permaoption'])) {
								$output['twoclick_buttons_infotext_permaoption'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_infotext_permaoption']));
							} else {
								unset($output['twoclick_buttons_infotext_permaoption']);
							} // END if(!empty($input['twoclick_buttons_infotext_permaoption']))

							// Infolink
							if(!empty($input['twoclick_buttons_infolink'])) {
								$output['twoclick_buttons_infolink'] = esc_url($input['twoclick_buttons_infolink']);
							} else {
								unset($output['twoclick_buttons_infolink']);
							} // END if(!empty($input['twoclick_buttons_infolink']))

							// Introtext
							if(!empty($input['twoclick_buttons_introtext'])) {
								$output['twoclick_buttons_introtext'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_introtext']));
							} else {
								$output['twoclick_buttons_introtext'] = '';
							} // END if(!empty($input['twoclick_buttons_introtext']))
							break;

						case 'other-settings':
							// Validating Other Settings
							if(!empty($input['twoclick_buttons_postthumbnail'])) {
								$output['twoclick_buttons_postthumbnail'] = esc_url($input['twoclick_buttons_postthumbnail']);
							} else {
								unset($output['twoclick_buttons_postthumbnail']);
							} // END if(!empty($input['twoclick_buttons_postthumbnail']))

							$output['twoclick_buttons_url_tracking'] = (isset($input['twoclick_buttons_url_tracking']) && $input['twoclick_buttons_url_tracking'] == 1) ? true : false;
							$output['twoclick_buttons_opengraph_disable'] = (isset($input['twoclick_buttons_opengraph_disable']) && $input['twoclick_buttons_opengraph_disable'] == 1) ? true : false;
							$output['twoclick_buttons_permalink_with_get'] = (isset($input['twoclick_buttons_permalink_with_get']) && $input['twoclick_buttons_permalink_with_get'] == 1) ? true : false;
							$output['twoclick_buttons_display_infobox'] = (isset($input['twoclick_buttons_display_infobox']) && $input['twoclick_buttons_display_infobox'] == 1) ? true : false;

							if(!empty($input['twoclick_buttons_custom_css'])) {
								$output['twoclick_buttons_custom_css'] = stripslashes(wp_filter_post_kses($input['twoclick_buttons_custom_css']));
							} else {
								unset($output['twoclick_buttons_custom_css']);
							} // END if(!empty($input['twoclick_buttons_custom_css']))
							break;
					} // END switch($input['twoclick_buttons_settings_section'])
				} // END if(isset($input['twoclick_buttons_settings_section']))
			} // END if(isset($input['twoclick_buttons_settings_reset']))

			return $output;
		} // END function options_validate($input)

		/**
		 * <[ Helper ]>
		 * Gettings linked tabs in settings page
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function _get_tablinks_for_options_page() {
			?>
			<h2 class="nav-tab-wrapper">
				<?php
				foreach((array) $this->array_Tabs as $var_arrayKey => $var_arrayValue) {
					?>
					<a href="?page=twoclick_buttons&tab=<?php echo $var_arrayKey; ?>" class="nav-tab <?php echo $this->var_sActiveTab == $var_arrayKey ? 'nav-tab-active' : ''; ?>"><?php echo $var_arrayValue; ?></a>
					<?php
				} // END foreach((array) $this->array_Tabs as $var_arrayKey => $var_arrayValue)
				?>
			</h2>
			<?php
		} // END private function _get_tablinks_for_options_page()

		/**
		 * <[ Helper ]>
		 *
		 * Getting the default options for the first install
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @return array
		 */
		private function _get_default_options() {
			$array_TwoclickDefaultOptions = array(
				'twoclick_buttons_display_facebook' => false,
				'twoclick_buttons_display_facebook_perm' => false,
				'twoclick_buttons_display_twitter' => false,
				'twoclick_buttons_display_twitter_perm' => false,
				'twoclick_buttons_display_googleplus' => false,
				'twoclick_buttons_display_googleplus_perm' => false,
				'twoclick_buttons_display_flattr' => false,
				'twoclick_buttons_display_flattr_perm' => false,
				'twoclick_buttons_display_xing' => false,
				'twoclick_buttons_display_xing_perm' => false,
				'twoclick_buttons_display_pinterest' => false,
				'twoclick_buttons_display_pinterest_perm' => false,
				'twoclick_buttons_display_page' => false,
				'twoclick_buttons_display_index' => false,
				'twoclick_buttons_display_year' => false,
				'twoclick_buttons_display_month' => false,
				'twoclick_buttons_display_day' => false,
				'twoclick_buttons_display_search' => false,
				'twoclick_buttons_display_category' => false,
				'twoclick_buttons_display_tag' => false,
				'twoclick_buttons_where' => 'before',
				'twoclick_buttons_facebook_action' => 'recommend',
				'twoclick_buttons_twitter_reply' => '',
				'twoclick_buttons_twitter_tweettext' => 'default',
				'twoclick_buttons_twitter_tweettext_default_as' => 'posttitle-blogtitle',
				'twoclick_buttons_twitter_tweettext_owntext' => '',
				'twoclick_buttons_twitter_hashtags' => true,
				'twoclick_buttons_flattr_uid' => '',
				'twoclick_buttons_pinterest_description' => 'posttitle',
				'twoclick_buttons_infotext_facebook' => '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Facebook senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.',
				'twoclick_buttons_infotext_twitter' => '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Twitter senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.',
				'twoclick_buttons_infotext_googleplus' => '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Google+ senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.',
				'twoclick_buttons_infotext_flattr' => '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Flattr senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.',
				'twoclick_buttons_infotext_xing' => '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Xing senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.',
				'twoclick_buttons_infotext_pinterest' => '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Pinterest senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.',
				'twoclick_buttons_infotext_infobutton' => 'Wenn Sie diese Felder durch einen Klick aktivieren, werden Informationen an Facebook, Twitter, Flattr, Xing, t3n, LinkedIn, Pinterest oder Google eventuell ins Ausland übertragen und unter Umständen auch dort gespeichert. Näheres erfahren Sie durch einen Klick auf das <em>i</em>.',
				'twoclick_buttons_infotext_permaoption' => 'Dauerhaft aktivieren und Datenüber-tragung zustimmen:',
				'twoclick_buttons_infolink' => 'http://www.heise.de/ct/artikel/2-Klicks-fuer-mehr-Datenschutz-1333879.html',
				'twoclick_buttons_postthumbnail' => '',
				'twoclick_buttons_display_t3n' => false,
				'twoclick_buttons_display_t3n_perm' => false,
				'twoclick_buttons_display_linkedin' => false,
				'twoclick_buttons_display_linkedin_perm' => false,
				'twoclick_buttons_display_sidebar_widget' => false,
				'twoclick_buttons_opengraph_disable' => false,
				'twoclick_buttons_display_private' => false,
				'twoclick_buttons_display_password' => false,
				'twoclick_buttons_introtext' => '',
				'twoclick_buttons_infotext_t3n' => '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an t3n senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.',
				'twoclick_buttons_infotext_linkedin' => '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an LinkedIn senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.',
				'twoclick_buttons_url_tracking' => false,
				'twoclick_buttons_custom_css' => '',
				'twoclick_buttons_permalink_with_get' => false,
				'twoclick_buttons_display_infobox' => true
			);

			return $array_TwoclickDefaultOptions;
		} // END private function _get_default_options()

		/**
		 * <[ Helper ]>
		 * Supported Networks
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @return array
		 */
		private function _get_supported_networks() {
			$array_Networks = array(
				'facebook' => __('Facebook', TWOCLICK_TEXTDOMAIN),
				'twitter' => __('Twitter', TWOCLICK_TEXTDOMAIN),
				'googleplus' => __('Google+', TWOCLICK_TEXTDOMAIN),
				'flattr' => __('Flattr', TWOCLICK_TEXTDOMAIN),
				'xing' => __('Xing', TWOCLICK_TEXTDOMAIN),
				'pinterest' => __('Pinterest', TWOCLICK_TEXTDOMAIN),
				't3n' => __('t3n', TWOCLICK_TEXTDOMAIN),
				'linkedin' => __('LinkedIn', TWOCLICK_TEXTDOMAIN)
			);

			return $array_Networks;
		} // END private function _get_supported_networks()

		/**
		 * <[ Helper ]>
		 *
		 * Sanitize Twittername
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @param string $var_sTwitterName
		 * @return string
		 */
		private function _get_sanitized_twitter_name($var_sTwitterName) {
			return preg_replace('/[^A-Za-z0-9_]/', '', wp_filter_nohtml_kses($var_sTwitterName));
		} // END private function _get_sanitized_twitter_name($var_sTwitterName)

		/**
		 * Embedding link to options page inside the settings menu
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function embed_options_page() {
			add_options_page(
				'2-Klick-Buttons',
				'<img src="' . plugins_url('/images/twoclick.jpg', dirname(__FILE__)) . '" id="2-click-icon" alt="2 Click Social Media Buttons Icon" width="16" height="16" /> 2-Klick-Buttons',
				$this->var_sCapability,
				'twoclick_buttons',
				array(
					$this,
					'options_page'
				)
			);
		} // END function embed_options_page()

		/**
		 * Render the output of options page
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function options_page() {
			global $wp_scripts;

			if($this->_is_twoclick_settings_page()) {
				require_once(plugin_dir_path(__FILE__) . 'class-twoclick-pages-walker.php');
			} // END if($this->_is_twoclick_settings_page())

			$this->array_Tabs = array(
				'general-settings' => __('General', TWOCLICK_TEXTDOMAIN),
				'button-settings' => __('Buttons', TWOCLICK_TEXTDOMAIN),
				'infotext-settings' => __('Infotext', TWOCLICK_TEXTDOMAIN),
				'other-settings' => __('Other', TWOCLICK_TEXTDOMAIN),
// 				'help' => __('Help', TWOCLICK_TEXTDOMAIN),
				'faq' => __('F.A.Q.', TWOCLICK_TEXTDOMAIN),
				'changelog' => __('Changelog', TWOCLICK_TEXTDOMAIN),
				'donate' => __('Donate', TWOCLICK_TEXTDOMAIN),
				'debug' => __('Debug', TWOCLICK_TEXTDOMAIN),
			);
			?>
			<div class="wrap">
				<div class="icon32" id="icon-options-general">&nbsp;</div>
				<h2><?php _e('Settings for 2-Click Social Media Buttons', TWOCLICK_TEXTDOMAIN); ?></h2>
				<?php
				if(version_compare($wp_scripts->registered['jquery']->ver, TWOCLICK_JQUERY_REQUIERED, '<')) {
					?>
					<div class='error fade'>
						<p>
							<?php
							printf(__('Your WordPress is running with jQuery Version %1$s. %2$s requires at least %3$s. With your version the plugin doesn\'t work.<br />Please Update.', TWOCLICK_TEXTDOMAIN),
								$wp_scripts->registered['jquery']->ver,
								__('2-Click Social Media Buttons', TWOCLICK_TEXTDOMAIN),
								TWOCLICK_JQUERY_REQUIERED
							);
							?>
						</p>
					</div>
					<?php
				}

				/**
				 * Setting the active tab
				 */
				if(isset($_GET['tab'])) {
					$this->var_sActiveTab = wp_filter_nohtml_kses($_GET['tab']);
				} // END if(isset($_GET['tab']))

				$this->_get_tablinks_for_options_page();

				switch($this->var_sActiveTab) {
					// Tabs ohne Formular
					case 'help':
					case 'faq':
					case 'changelog':
					case 'donate':
					case 'debug':
						require_once(plugin_dir_path(__FILE__) . 'class-twoclick-backend-' . $this->var_sActiveTab . '.php');
						break;

					// Tabs mit Formular
					default:
						?>
						<form method="post" action="options.php">
							<?php
							settings_fields($this->var_sOptionsGroup);
							$options = $this->_get_option();
							?>
							<input type="hidden" value="<?php echo $this->var_sActiveTab; ?>" name="twoclick_buttons_settings[twoclick_buttons_settings_section]" id="twoclick_buttons_settings[twoclick_buttons_settings_section]" />
							<div id="twoclick-options-tabs" class="clearfix">
								<?php
								switch($this->var_sActiveTab) {
									case 'general-settings':
										?>
										<div id="general-settings clearfix">
											<?php $this->render_general_settings(); ?>
										</div>
										<?php
										break;

									case 'button-settings':
										?>
										<div id="button-settings clearfix">
											<?php $this->render_button_settings(); ?>
										</div>
										<?php
										break;

									case 'infotext-settings':
										?>
										<div id="infotext-settings clearfix">
											<?php $this->render_infotext_settings(); ?>
										</div>
										<?php
										break;

									case 'other-settings':
										?>
										<div id="other-settings clearfix">
											<?php $this->render_other_settings(); ?>
										</div>
										<?php
										break;
								} // END switch($this->var_sActiveTab)
								?>
							</div>
							<p class="submit">
								<?php
								// Speichern
								submit_button('', 'primary', 'twoclick_buttons_settings[twoclick_buttons_settings_submit]', false, array(
									'id' => 'twoclick_buttons_settings[twoclick_buttons_settings_submit]'
								));

								/**
								 * CSS des Reset-Buttons
								 *
								 * Mit WordPress 3.5 wird die secondary-Class im CSS nicht mehr richtig erkannt,
								 * also muss hier ein Workaround her.
								 *
								 * @since 1.5
								 * @author ppfeufer
								 */
								$var_sResetCssClasses = 'delete';
								if(version_compare($GLOBALS['wp_version'], '3.5-alpha', '>=')) {
									$var_sResetCssClasses = 'secondary delete twoclick-reset-options';
								}

								// Zurücksetzen
								submit_button(__('Reset Options', TWOCLICK_TEXTDOMAIN), $var_sResetCssClasses, 'twoclick_buttons_settings[twoclick_buttons_settings_reset]', false, array(
									'id' => 'twoclick_buttons_settings[twoclick_buttons_settings_reset]',
									'onclick' => 'return confirm(' . __('&quot;Do you really want to reset your configuration?&quot;', TWOCLICK_TEXTDOMAIN) . ');'
	 							));
	 							?>
							</p>
						</form>
						<?php
						break;
				} // END switch($this->var_sActiveTab)
				?>
			</div>
			<?php
		} // END function options_page()

		/**
		 * Rendering the general settings
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function render_general_settings() {
			?>
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Display', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<!-- Anzeigeeinstellungen -->
								<div>
									<div style="float:left; width:100px"><?php _e('Network', TWOCLICK_TEXTDOMAIN); ?></div>
									<div style="margin-left:100px;">

										<!-- Welche Buttons sollen angezeigt werden -->
										<?php
										foreach((array) $this->array_SupportedNetworks as $var_sKey => $var_sValue) {
											?>
											<!-- <?php echo $var_sValue; ?> -->
											<div>
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_' . $var_sKey] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_<?php echo $var_sKey; ?>]" id="twoclick_buttons_settings[twoclick_buttons_display_<?php echo $var_sKey; ?>]" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_<?php echo $var_sKey; ?>]" style="display:inline-block; width:150px;"><?php echo sprintf(__('Enable %1$s', TWOCLICK_TEXTDOMAIN), $var_sValue); ?></label>
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_' . $var_sKey . '_perm'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_<?php echo $var_sKey; ?>_perm]" id="twoclick_buttons_settings[twoclick_buttons_display_<?php echo $var_sKey; ?>_perm]" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_<?php echo $var_sKey; ?>_perm]"><?php echo sprintf(__('Option for permanent activation for %1$s', TWOCLICK_TEXTDOMAIN), $var_sValue); ?></label>
											</div>
											<?php
										} // END foreach((array) $this->array_SupportedNetworks as $var_sKey => $var_sValue)
										?>
									</div>
								</div>

								<!-- Position -->
								<div style="padding-top:25px;">
									<div style="float:left; width:100px"><?php _e('Position', TWOCLICK_TEXTDOMAIN); ?></div>
									<div style="margin-left:100px;">
										<div>
											<select name="twoclick_buttons_settings[twoclick_buttons_where]">
												<option <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_where'] == 'before') echo 'selected="selected"'; ?> value="before"><?php _e('Before the Post', TWOCLICK_TEXTDOMAIN); ?></option>
												<option <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_where'] == 'after') echo 'selected="selected"'; ?> value="after"><?php _e('After the Post', TWOCLICK_TEXTDOMAIN); ?></option>
												<option <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_where'] == 'shortcode') echo 'selected="selected"'; ?> value="shortcode"><?php _e('Manuall (Shortcode)', TWOCLICK_TEXTDOMAIN); ?></option>
												<option <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_where'] == 'template') echo 'selected="selected"'; ?> value="template"><?php _e('Manuall (Template)', TWOCLICK_TEXTDOMAIN); ?></option>
											</select>
										</div>
										<div>
											<p>
												<?php _e('If you choose "Manuall (Shortcode)", you can use the shortcode <strong>[twoclick_buttons]</strong> inside your articles.', TWOCLICK_TEXTDOMAIN); ?><br />
												<?php _e('If you choose "Manuall (Template)", you can use the code <strong>&lt;?php if(function_exists(\'get_twoclick_buttons\')) {get_twoclick_buttons(get_the_ID());}?&gt;</strong> inside your template. It\'s using all settings for "Display". <em><strong>Note:</strong> It will only work in single post or page templates. Not in any loop.</em>', TWOCLICK_TEXTDOMAIN); ?>
											</p>
										</div>

										<!-- Auf welchen Seiten sollen die Buttons angezeigt werden -->
										<div id="exclude-page" class="exclude-pages<?php if(!isset($this->array_TwoclickButtonsOptions['twoclick_buttons_display_page']) || $this->array_TwoclickButtonsOptions['twoclick_buttons_display_page'] == 0) {echo ' display-hidden';} ?>">
											<?php _e('Exclude Pages:', TWOCLICK_TEXTDOMAIN); ?>
											<?php $this->_get_pages(); ?>
										</div>

										<div>
											<p>
												<strong><?php _e('Posts and Pages Handling', TWOCLICK_TEXTDOMAIN); ?></strong>
											</p>
										</div>
										<div>
											<div style="margin-top:10px;">
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_page'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_page]" id="twoclick_buttons_settings[twoclick_buttons_display_page]" onchange="toggleElementVisibility('exclude-page')" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_page]"><?php _e('Display on CMS-Pages', TWOCLICK_TEXTDOMAIN); ?></label>
											</div>
											<div>
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_index'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_index]" id="twoclick_buttons_settings[twoclick_buttons_display_index]" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_index]"><?php _e('Display on Index', TWOCLICK_TEXTDOMAIN); ?></label>
											</div>

											<div style="margin-top:10px;">
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_private'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_private]" id="twoclick_buttons_settings[twoclick_buttons_display_private]" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_private]"><?php _e('Display on Private Posts', TWOCLICK_TEXTDOMAIN); ?></label>
											</div>
											<div>
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_password'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_password]" id="twoclick_buttons_settings[twoclick_buttons_display_password]" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_password]"><?php _e('Display on Password Protected Posts', TWOCLICK_TEXTDOMAIN); ?></label>
											</div>
											<?php
											// Prüfen ob die Sidebarwidget-Klasse vorhanden ist
											if(is_readable(plugin_dir_path(__FILE__) . 'class-twoclick-sidebar-widget.php')) {
												?>
												<div>
													<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_sidebar_widget'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_sidebar_widget]" id="twoclick_buttons_settings[twoclick_buttons_display_sidebar_widget]" />
													<label for="twoclick_buttons_settings[twoclick_buttons_display_sidebar_widget]"><?php _e('Display Sidebar-Widget', TWOCLICK_TEXTDOMAIN); ?></label> <span class="description">(<?php _e('Note: Only in single posts or pages.',  TWOCLICK_TEXTDOMAIN); ?>)</span>
												</div>
												<?php
											} // END if(is_readable(plugin_dir_path(__FILE__) . 'class-twoclick-sidebar-widget.php'))
											?>

											<div style="margin-top:10px;">
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_year'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_year]" id="twoclick_buttons_settings[twoclick_buttons_display_year]" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_year]"><?php _e('Display on Yearly Archives', TWOCLICK_TEXTDOMAIN); ?></label> <span class="description">(<?php _e('Note: Not every theme supports this option.',  TWOCLICK_TEXTDOMAIN); ?>)</span>
											</div>
											<div>
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_month'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_month]" id="twoclick_buttons_settings[twoclick_buttons_display_month]" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_month]"><?php _e('Display on Monthly Archives', TWOCLICK_TEXTDOMAIN); ?></label> <span class="description">(<?php _e('Note: Not every theme supports this option.',  TWOCLICK_TEXTDOMAIN); ?>)</span>
											</div>
											<div>
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_day'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_day]" id="twoclick_buttons_settings[twoclick_buttons_display_day]" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_day]"><?php _e('Display on Daily Archives', TWOCLICK_TEXTDOMAIN); ?></label> <span class="description">(<?php _e('Note: Not every theme supports this option.',  TWOCLICK_TEXTDOMAIN); ?>)</span>
											</div>

											<div style="margin-top:10px;">
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_search'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_search]" id="twoclick_buttons_settings[twoclick_buttons_display_search]" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_search]"><?php _e('Display on Search-Pages', TWOCLICK_TEXTDOMAIN); ?></label> <span class="description">(<?php _e('Note: Not every theme supports this option.',  TWOCLICK_TEXTDOMAIN); ?>)</span>
											</div>
											<div>
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_category'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_category]" id="twoclick_buttons_settings[twoclick_buttons_display_category]" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_category]"><?php _e('Display on Category-Archive', TWOCLICK_TEXTDOMAIN); ?></label> <span class="description">(<?php _e('Note: Not every theme supports this option.',  TWOCLICK_TEXTDOMAIN); ?>)</span>
											</div>
											<div>
												<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_tag'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_tag]" id="twoclick_buttons_settings[twoclick_buttons_display_tag]" />
												<label for="twoclick_buttons_settings[twoclick_buttons_display_tag]"><?php _e('Display on Tag-Archive', TWOCLICK_TEXTDOMAIN); ?></label> <span class="description">(<?php _e('Note: Not every theme supports this option.',  TWOCLICK_TEXTDOMAIN); ?>)</span>
											</div>
											<div>
												<p>
													<?php _e('On singleposts the buttons will be shown by default. There is no option needed.', TWOCLICK_TEXTDOMAIN); ?>
												</p>
											</div>


											<!-- Custom Post Types -->
											<?php
											$array_CustomPostTypes = $this->_get_custom_post_types();

											if($array_CustomPostTypes) {
												?>
												<div>
													<p>
														<strong><?php _e('Custom Post Type Handling', TWOCLICK_TEXTDOMAIN); ?></strong>
													</p>
												</div>
												<?php
												$count_i = 1;
												foreach((array) $array_CustomPostTypes as $key => $value) {
													?>
													<div<?php if($count_i == 1) {echo ' style="margin-top:10px;"';}?>>
														<input type="checkbox" value="1" <?php if((isset($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_cpt'][$value])) && ($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_cpt'][$value] == '1')) {echo 'checked="checked"';} ?> name="twoclick_buttons_settings[twoclick_buttons_exclude_cpt][<?php echo $value; ?>]" id="twoclick_buttons_settings[twoclick_buttons_exclude_cpt][<?php echo $value; ?>]" />
														<label for="twoclick_buttons_settings[twoclick_buttons_exclude_cpt][<?php echo $value; ?>]"><?php printf(__('Exclude on Custom Post Type "%1$s"', TWOCLICK_TEXTDOMAIN), '<em>' . $value . '</em>'); ?></label>
													</div>
													<?php
													$count_i++;
												} // END foreach((array) $array_CustomPostTypes as $key => $value)
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		} // END private function render_general_settings()

		/**
		 * Rendering the button settings for each button
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function render_button_settings() {
			?>
			<!-- Language -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Language Settings <em>(Failover)</em>', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<div style="clear:both;">
									<div>
										<label for="twoclick_buttons_settings[twoclick_buttons_language]" style="display:inline-block; width:100px;"><?php _e('Lanuage:', TWOCLICK_TEXTDOMAIN); ?></label>
										<select name="twoclick_buttons_settings[twoclick_buttons_language]">
											<option <?php if(!isset($this->array_TwoclickButtonsOptions['twoclick_buttons_language'])) echo 'selected="selected"'; ?> value="">&nbsp;</option>
											<optgroup label="<?php _e('German', TWOCLICK_TEXTDOMAIN); ?>">
												<option <?php if(isset($this->array_TwoclickButtonsOptions['twoclick_buttons_language']) && $this->array_TwoclickButtonsOptions['twoclick_buttons_language'] == 'de_DE') echo 'selected="selected"'; ?> value="de_DE"><?php _e('Germany (de_DE)', TWOCLICK_TEXTDOMAIN); ?></option>
												<option <?php if(isset($this->array_TwoclickButtonsOptions['twoclick_buttons_language']) && $this->array_TwoclickButtonsOptions['twoclick_buttons_language'] == 'de_AT') echo 'selected="selected"'; ?> value="de_AT"><?php _e('Austria (de_AT)', TWOCLICK_TEXTDOMAIN); ?></option>
												<option <?php if(isset($this->array_TwoclickButtonsOptions['twoclick_buttons_language']) && $this->array_TwoclickButtonsOptions['twoclick_buttons_language'] == 'de_CH') echo 'selected="selected"'; ?> value="de_CH"><?php _e('Switzerland (de_CH)', TWOCLICK_TEXTDOMAIN); ?></option>
												<option value="">&nbsp;</option>
											</optgroup>
											<optgroup label="<?php _e('English', TWOCLICK_TEXTDOMAIN); ?>">
												<option <?php if(isset($this->array_TwoclickButtonsOptions['twoclick_buttons_language']) && $this->array_TwoclickButtonsOptions['twoclick_buttons_language'] == 'en_GB') echo 'selected="selected"'; ?> value="en_GB"><?php _e('United Kingdom (en_GB)', TWOCLICK_TEXTDOMAIN); ?></option>
												<option <?php if(isset($this->array_TwoclickButtonsOptions['twoclick_buttons_language']) && $this->array_TwoclickButtonsOptions['twoclick_buttons_language'] == 'en_US') echo 'selected="selected"'; ?> value="en_US"><?php _e('United States (en_US)', TWOCLICK_TEXTDOMAIN); ?></option>
											</optgroup>
										</select>
									</div>
									<div style="margin-left:104px;">
										<p>
											<?php _e('If you have problems with the active buttons - facebook doens\'t load - try to set the language manually. If anything works fine, ignore this setting.', TWOCLICK_TEXTDOMAIN); ?>
										</p>
										<p>
											<?php printf(__('Your current language <em>(set in your wp_config.php)</em>: %1$s', TWOCLICK_TEXTDOMAIN), get_locale()); ?>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Facebook -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Facebook', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<div style="clear:both;">
									<div>
										<label for="twoclick_buttons_settings[twoclick_buttons_facebook_action]" style="display:inline-block; width:100px;"><?php _e('Button:', TWOCLICK_TEXTDOMAIN); ?></label>
										<select name="twoclick_buttons_settings[twoclick_buttons_facebook_action]">
											<option <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_facebook_action'] == 'recommend') echo 'selected="selected"'; ?> value="recommend"><?php _e('Recommend', TWOCLICK_TEXTDOMAIN); ?></option>
											<option <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_facebook_action'] == 'like') echo 'selected="selected"'; ?> value="like"><?php _e('Like', TWOCLICK_TEXTDOMAIN); ?></option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Twitter -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Twitter', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<div>
									<div style="display:inline-block; width:100px;">
										RT @:
									</div>
									<div style="display:inline-block;">
										<input type="text" value="<?php echo $this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_reply']; ?>" name="twoclick_buttons_settings[twoclick_buttons_twitter_reply]" id="twoclick_buttons_settings[twoclick_buttons_twitter_reply]" class="required" />
										<span class="description"><?php _e('Please use \'yourname\', <strong>not</strong> \'RT @yourname\'.', TWOCLICK_TEXTDOMAIN); ?></span>
									</div>
								</div>

								<div>
									<div style="display:inline-block; width:100px;">
										<?php _e('Tweettext:', TWOCLICK_TEXTDOMAIN); ?>
									</div>
									<div style="display:inline-block;">
										<input type="radio" value="default" <?php if ($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'default') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_twitter_tweettext]" id="twoclick_buttons_settings[twoclick_buttons_twitter_tweettext_default]" />
										<select name="twoclick_buttons_settings[twoclick_buttons_twitter_tweettext_default_as]">
											<option <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_default_as'] == 'posttitle-blogtitle') echo 'selected="selected"'; ?> value="posttitle-blogtitle"><?php _e('Posttitle &raquo; Blogtitle', TWOCLICK_TEXTDOMAIN); ?></option>
											<option <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_default_as'] == 'posttitle') echo 'selected="selected"'; ?> value="posttitle"><?php _e('Posttitle', TWOCLICK_TEXTDOMAIN); ?></option>
										</select>
										<label for="twoclick_buttons_settings[twoclick_buttons_twitter_tweettext_default]"><?php _e('The title of the page the button is on.', TWOCLICK_TEXTDOMAIN); ?></label>
									</div>

									<div style="margin-left:104px;">
										<input type="radio" value="own" <?php if ($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'own') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_twitter_tweettext]" id="twoclick_buttons_settings[twoclick_buttons_twitter_tweettext_own]" />
										<input type="text" value="<?php echo $this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_owntext']; ?>" name="twoclick_buttons_settings[twoclick_buttons_twitter_tweettext_owntext]" id="twoclick_buttons_settings[twoclick_buttons_twitter_tweettext_owntext]" />
										<span class="description"><?php _e('This is the text that people will include in their Tweet when they share from your website.', TWOCLICK_TEXTDOMAIN); ?></span>
										<?php
										if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'own' && strlen($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_owntext']) == 0) {
											?>
											<div class="error">
												<p style="font-weight:bold;">
													<?php _e('Custom tweettext missing !!!', TWOCLICK_TEXTDOMAIN); ?>
												</p>
												<p>
													<?php _e('Please enter a custom tweettext. Otherweise the plugin will use default settings for tweetext as &quot;<strong>Posttitle &raquo; Blogtitle</strong>&quot;', TWOCLICK_TEXTDOMAIN); ?>
												</p>
											</div>
											<?php
										} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'own' && strlen($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_owntext']) == 0)
										?>
									</div>
									<div style="margin-left:104px;">
										<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_hashtags'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_twitter_hashtags]" id="twoclick_buttons_settings[twoclick_buttons_twitter_hashtags]" />
										<label for="twoclick_buttons_settings[twoclick_buttons_twitter_hashtags]"><?php _e('Use tags as #hashtags', TWOCLICK_TEXTDOMAIN); ?></label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Flattr -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Flattr', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<label for="twoclick_buttons_settings[twoclick_buttons_flattr_uid]" style="display:inline-block; width:100px;"><?php _e('User:', TWOCLICK_TEXTDOMAIN); ?></label>
								<input type="text" value="<?php echo $this->array_TwoclickButtonsOptions['twoclick_buttons_flattr_uid']; ?>" name="twoclick_buttons_settings[twoclick_buttons_flattr_uid]" id="twoclick_buttons_settings[twoclick_buttons_flattr_uid]" class="required" />
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Pinterest -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Pinterest', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<div style="display:inline-block; width:100px;">
									<?php _e('Description:', TWOCLICK_TEXTDOMAIN); ?>
								</div>
								<div style="display:inline-block;">
									<select name="twoclick_buttons_settings[twoclick_buttons_pinterest_description]">
										<option <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_pinterest_description'] == 'posttitle') echo 'selected="selected"'; ?> value="posttitle"><?php _e('Posttitle', TWOCLICK_TEXTDOMAIN); ?></option>
										<option <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_pinterest_description'] == 'posttitle-tags') echo 'selected="selected"'; ?> value="posttitle-tags"><?php _e('Posttitle and #Tags', TWOCLICK_TEXTDOMAIN); ?></option>
										<option <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_pinterest_description'] == 'posttitle-excerpt') echo 'selected="selected"'; ?> value="posttitle-excerpt"><?php _e('Posttitle &raquo; Excerpt', TWOCLICK_TEXTDOMAIN); ?></option>
									</select>
									<label for="twoclick_buttons_settings[twoclick_buttons_pinterest_description]"><?php _e('The description wich is send to Pinterest.', TWOCLICK_TEXTDOMAIN); ?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		} // END private function render_button_settings()

		/**
		 * Rendering the infotext settings
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function render_infotext_settings() {
			?>
			<!-- Infotexte -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Infotext', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<div class="twoclick-infotexte">
									<?php
									foreach((array) $this->array_SupportedNetworks as $var_sKey => $var_sValue) {
										?>
										<!-- <?php echo $var_sValue; ?> -->
										<div class="twoclick-infotext-input">
											<div class="label">
												<label for="twoclick_buttons_settings[twoclick_buttons_infotext_<?php echo $var_sKey; ?>]"><?php _e($var_sValue, TWOCLICK_TEXTDOMAIN); ?></label>
											</div>
											<div class="input">
												<textarea class="code large-text" rows="5" name="twoclick_buttons_settings[twoclick_buttons_infotext_<?php echo $var_sKey; ?>]" style="width:450px;"><?php echo esc_textarea($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_' . $var_sKey]); ?></textarea>
											</div>
										</div>
										<?php
									} // END foreach((array) $this->array_SupportedNetworks as $var_sKey => $var_sValue)
									?>
									<!-- Infobutton -->
									<div class="twoclick-infotext-input">
										<div class="label">
											<label for="twoclick_buttons_settings[twoclick_buttons_infotext_infobutton]"><?php _e('Infobutton:', TWOCLICK_TEXTDOMAIN); ?></label>
										</div>
										<div class="input">
											<textarea class="code large-text" rows="5" name="twoclick_buttons_settings[twoclick_buttons_infotext_infobutton]" style="width:450px;"><?php echo esc_textarea($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_infobutton']); ?></textarea>
										</div>
									</div>

									<!--  Permaoption -->
									<div class="twoclick-infotext-input">
										<div class="label">
											<label for="twoclick_buttons_settings[twoclick_buttons_infotext_permaoption]"><?php _e('Permaoption:', TWOCLICK_TEXTDOMAIN); ?></label>
										</div>
										<div class="input">
											<textarea class="code large-text" rows="5" name="twoclick_buttons_settings[twoclick_buttons_infotext_permaoption]" style="width:450px;"><?php echo esc_textarea($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_permaoption']); ?></textarea>
										</div>
									</div>

									<!-- Infolink -->
									<div class="twoclick-infotext-input">
										<div class="label">
											<label for="twoclick_buttons_settings[twoclick_buttons_infolink]" style="display:inline-block; width:100px;"><?php _e('Infolink:', TWOCLICK_TEXTDOMAIN); ?></label>
										</div>
										<div class="input">
											<input style="width:450px;" type="text" value="<?php echo $this->array_TwoclickButtonsOptions['twoclick_buttons_infolink']; ?>" name="twoclick_buttons_settings[twoclick_buttons_infolink]" id="twoclick_buttons_settings[twoclick_buttons_infolink]" /><br />
											<span class="description"><?php _e('Links starting with http://', TWOCLICK_TEXTDOMAIN); ?></span>
										</div>
									</div>

									<!-- Introtext -->
									<div class="twoclick-infotext-input">
										<div class="label">
											<label for="twoclick_buttons_settings[twoclick_buttons_introtext]"><?php _e('Introtext:', TWOCLICK_TEXTDOMAIN); ?></label>
										</div>
										<div class="input">
											<textarea class="code large-text" rows="5" name="twoclick_buttons_settings[twoclick_buttons_introtext]" style="width:450px;"><?php echo esc_textarea($this->array_TwoclickButtonsOptions['twoclick_buttons_introtext']); ?></textarea><br />
											<span class="description"><?php _e('This "Introtext" will be displayed before the buttons. You can use some HTML here. Paragraphs will be included automaticly.', TWOCLICK_TEXTDOMAIN); ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		} // END private function render_infotext_settings()

		/**
		 * Rendering other settings
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function render_other_settings() {
			?>
			<!-- Artikelbild -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Postthumbnail <em>(optional)</em>', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<div>
									<div style="display:inline-block; width:100px; vertical-align:top;">
										<?php _e('Link:', TWOCLICK_TEXTDOMAIN); ?>
									</div>
									<div style="display:inline-block;">
										<input type="text" value="<?php echo $this->array_TwoclickButtonsOptions['twoclick_buttons_postthumbnail']; ?>" name="twoclick_buttons_settings[twoclick_buttons_postthumbnail]" id="twoclick_buttons_postthumbnail" />
										<input id="upload-image-button" type="button" value="<?php _e('Upload Image', TWOCLICK_TEXTDOMAIN); ?>" /><br />
										<span class="description"><?php _e('Links starting with http://', TWOCLICK_TEXTDOMAIN); ?></span>
									</div>
								</div>
								<div style="margin-left:100px;">
									<?php
									if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_postthumbnail'])) {
										?>
										<p>
											<img src="<?php echo $this->array_TwoclickButtonsOptions['twoclick_buttons_postthumbnail']; ?>" />
										</p>
										<?php
									} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_postthumbnail'))
									?>
									<p>
										<?php _e('This image is taken for Facebook, Google+ and Pinterest if there is no postthumbnail or other image inside the article or page. If empty, no image will be used for and the pinterest-button will be disabled for this article.', TWOCLICK_TEXTDOMAIN); ?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Infoschaltflaeche -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Infobox', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<div>
									<div style="display:inline-block; width:100px; vertical-align:top;">
										<?php _e('Enable:', TWOCLICK_TEXTDOMAIN); ?>
									</div>
									<div style="display:inline-block;;">
										<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_infobox'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_infobox]" id="twoclick_buttons_settings[twoclick_buttons_display_infobox]" />
									</div>
								</div>

								<div style="margin-left:100px;">
									<p>
										<?php _e('Activate or deactivate the infobutton.', TWOCLICK_TEXTDOMAIN); ?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Permalinks with $_GET -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Linkoptions <em>($_GET Handling > http://yourdomain.com/permalink/<strong>?foo=bar</strong>)</em>', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<div>
									<div style="display:inline-block; width:100px; vertical-align:top;">
										<?php _e('Enable:', TWOCLICK_TEXTDOMAIN); ?>
									</div>
									<div style="display:inline-block;;">
										<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_permalink_with_get'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_permalink_with_get]" id="twoclick_buttons_settings[twoclick_buttons_permalink_with_get]" />
									</div>
								</div>

								<div style="margin-left:100px;">
									<p>
										<?php _e('If you have permalinks with options <em>(?foo=bar for example)</em>, enable this. Note, this can be lead to misbehaviour.', TWOCLICK_TEXTDOMAIN); ?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Analytics Tracking -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Campaign-Tracking <em>(Piwik / Google Analytics)</em>', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<div>
									<div style="display:inline-block; width:100px; vertical-align:top;">
										<?php _e('Enable:', TWOCLICK_TEXTDOMAIN); ?>
									</div>
									<div style="display:inline-block;;">
										<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_url_tracking'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_url_tracking]" id="twoclick_buttons_settings[twoclick_buttons_url_tracking]" />
									</div>
								</div>

								<div style="margin-left:100px;">
									<p>
										<?php
										printf(__('With this option enabled, an url-parameter is set to track your article as a campaign for each button. Supported tracking tools are %1$s and %2$s. The following networks support this tracking: Twitter, Google+, Xing', TWOCLICK_TEXTDOMAIN),
											'<a href="http://piwik.org/">Piwik Analytics</a>',
											'<a href="http://www.google.com/analytics/">Google Analytics</a>'
										);
										?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- OpenGraph Tags -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('OpenGraph-Tags <em>(optional)</em>', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<div>
									<div style="display:inline-block; width:100px; vertical-align:top;">
										<?php _e('Disable:', TWOCLICK_TEXTDOMAIN); ?>
									</div>
									<div style="display:inline-block;;">
										<input type="checkbox" value="1" <?php if($this->array_TwoclickButtonsOptions['twoclick_buttons_opengraph_disable'] == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_opengraph_disable]" id="twoclick_buttons_settings[twoclick_buttons_opengraph_disable]" />
									</div>
								</div>

								<div style="margin-left:100px;">
									<p>
										<?php _e('The OpenGraph-Metatags are necessary for some networks, such as Facebook, Google+, Xing and Pinterest.', TWOCLICK_TEXTDOMAIN); ?>
										<br />
										<?php _e('Some other plugins or themes may have enabled the OpenGraph-Metatags by default. Please make sure this is the only plugin to add these tags to your website. Otherwise disble the OpenGraph-Metatags here.', TWOCLICK_TEXTDOMAIN); ?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Custom CSS -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Custom CSS (optional)', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<p>
									<?php
									_e('If you want to customize the output of your buttons, you can enter some CSS-rules here.', TWOCLICK_TEXTDOMAIN);
									?>
								</p>
								<p>
									<textarea class="code large-text" rows="5" name="twoclick_buttons_settings[twoclick_buttons_custom_css]"><?php echo esc_textarea($this->array_TwoclickButtonsOptions['twoclick_buttons_custom_css']); ?></textarea>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Support -->
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><?php _e('Support', TWOCLICK_TEXTDOMAIN); ?></span></h3>
							<div class="inside">
								<p>
									<?php
									$array_PluginData = $this->_get_plugin_data();

									printf(__('If you have any questions about the settings or the plugin, feel free to %1$s.', TWOCLICK_TEXTDOMAIN),
										'<a href="' . esc_url($array_PluginData['PluginURI']) . '">' . __('leave me a comment', TWOCLICK_TEXTDOMAIN) . '</a>'
									);
									?>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		} // END private function render_other_settings()

		/**
		 * Seiten hierarchisch auflisten
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function _get_pages() {
			$var_sPostType = 'page';

			$args = array(
				'order' => 'ASC',
				'orderby' => 'title',
				'posts_per_page' => '9999',
				'post_type' => $var_sPostType,
				'post_status' => 'publish',
				'suppress_filters' => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false
			);

			$get_posts = new WP_Query;
			$posts = $get_posts->query($args);

			if(!$get_posts->post_count || !$posts) {
				echo '<p>' . __('No items.', TWOCLICK_TEXTDOMAIN) . '</p>';

				return;
			} // END if(!$get_posts->post_count || !$posts)

			$db_fields = false;

			if(is_post_type_hierarchical($var_sPostType)) {
				$db_fields = array(
					'parent' => 'post_parent',
					'id' => 'ID'
				);
			} // END if(is_post_type_hierarchical($var_sPostType))

			$walker = new Twoclick_Social_Media_Buttons_Pages_Walker($db_fields);

			$removed_args = array(
				'action',
				'customlink-tab',
				'edit-menu-item',
				'menu-item',
				'page-tab',
				'_wpnonce',
			);
			?>
			<div class="posttypediv">
				<div id="<?php echo $var_sPostType; ?>-all" class="select-pages">
					<ul id="<?php echo $var_sPostType; ?>checklist" class="list:<?php echo $var_sPostType; ?> categorychecklist form-no-clear">
						<?php
						$args['walker'] = $walker;

						$posts = apply_filters('nav_menu_items_' . $var_sPostType, $posts, $args);
						$checkbox_items = walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $posts), 0, (object) $args);

						echo $checkbox_items;
						?>
					</ul>
				</div>
			</div>
			<?php
		} // END private function _get_pages()

		/**
		 * <[ Helper ]>
		 * Genutzte Custom Post Types zurückgeben.
		 *
		 * @since 1.1
		 * @author ppfeufer
		 *
		 * @param boolean $return
		 * @return Ambigous <multitype:, array, multitype:unknown , unknown>
		 */
		private function _get_custom_post_types() {
			$array_Arguments = array(
				'public' => true,
				'_builtin' => false
			);

			$var_sOutput = 'names'; // names or objects, note names is the default
			$var_sOperator = 'and'; // 'and' or 'or'
			$array_CustomPostTypes = get_post_types($array_Arguments, $var_sOutput, $var_sOperator);

			if(empty($array_CustomPostTypes)) {
				return false;
			}

			return $array_CustomPostTypes;
		} // END private function _get_custom_post_types()

		/**
		 * Link zur Adminseite in der Pluginübersicht hinzufügen.
		 *
		 * @since 1.2.0
		 * @author ppfeufer
		 */
		function _settings_link($links, $file) {
			if($file == '2-click-socialmedia-buttons/2-click-socialmedia-buttons.php' && function_exists('admin_url')) {
				$settings_link = '<a href="' . admin_url('options-general.php?page=twoclick_buttons') . '">' . __('Settings', TWOCLICK_TEXTDOMAIN) . '</a>';

				array_unshift( $links, $settings_link); // before the other links
			} // END if($file == '2-click-socialmedia-buttons/2-click-socialmedia-buttons.php' && function_exists('admin_url'))

			return $links;
		} // END function _settings_link($links, $file)

		/**
		 * Changelog bei Pluginupdate ausgeben.
		 *
		 * @since 0.1
		 * @author ppfeufer
		 */
		function _update_notice() {
			$array_2CSMB_Data = $this->_get_plugin_data();
			$var_sUserAgent = 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20100101 Firefox/5.0 WorPress Plugin 2-Click Social Media Buttons (Version: ' . $array_2CSMB_Data['Version'] . ') running on: ' . get_bloginfo('url');
			$url_readme = 'http://plugins.trac.wordpress.org/browser/2-click-socialmedia-buttons/trunk/readme.txt?format=txt';
			$data = '';

			if(ini_get('allow_url_fopen')) {
				$data = file_get_contents($url_readme);
			} else {
				if(function_exists('curl_init')) {
					$cUrl_Channel = curl_init();
					curl_setopt($cUrl_Channel, CURLOPT_URL, $url_readme);
					curl_setopt($cUrl_Channel, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($cUrl_Channel, CURLOPT_USERAGENT, $var_sUserAgent);
					$data = curl_exec($cUrl_Channel);
					curl_close($cUrl_Channel);
				} // END if(function_exists('curl_init'))
			} // END if(ini_get('allow_url_fopen'))

			if($data) {
				$matches = null;
				$regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote($array_2CSMB_Data['Version']) . '\s*=|$)~Uis';

				if(preg_match($regexp, $data, $matches)) {
					$changelog = (array) preg_split('~[\r\n]+~', trim($matches[1]));

					echo '</div><div class="update-message" style="font-weight: normal;"><strong>' . __('What\'s new:', TWOCLICK_TEXTDOMAIN) . '</strong>';
					$ul = false;
					$version = 99;

					foreach($changelog as $index => $line) {
						if(version_compare($version, $array_2CSMB_Data['Version'], ">")) {
							if(preg_match('~^\s*\*\s*~', $line)) {
								if(!$ul) {
									echo '<ul style="list-style: disc; margin-left: 20px;">';
									$ul = true;
								} // END if(!$ul)

								$line = preg_replace('~^\s*\*\s*~', '', $line);

								$line = preg_replace('/\\[(.*?)\\]\\((.*?)\\)/', '<a href="\\2">\\1</a>', $line);
								$line = preg_replace('/`(.*?)`/', '<code>\\1</code>', $line);
								$line = preg_replace('/\\*\\*(.*?)\\*\\*/', ' <strong>\\1</strong>', $line);
								$line = preg_replace('/\\*(.*?)\\*/', ' <em>\\1</em>', $line);
								echo '<li>' . $line . '</li>';
							} else {
								if($ul) {
									echo '</ul>';
									$ul = false;
								} // END if($ul)

								$version = trim($line, " =");
								if(version_compare($version, preg_replace('/-beta-(.*)/', '', $array_2CSMB_Data['Version']), '>=')) {
									echo '<p style="margin: 5px 0;"><h4>' . $version . '</h4></p>';
								} // END if(version_compare($version, preg_replace('/-beta-(.*)/', '', $array_2CSMB_Data['Version']), '>'))
							} // END if(preg_match('~^\s*\*\s*~', $line))
						} // END if(version_compare($version, $array_2CSMB_Data['Version'],">"))
					} // END foreach($changelog as $index => $line)

					if($ul) {
						echo '</ul><div style="clear: left;"></div>';
					} // END if($ul)

					echo '</div>';
				} // END if(preg_match($regexp, $data, $matches))
			} else {
				/**
				 * Returning if we can't use file_get_contents or cURL
				 */
				return;
			} // END if($data)
		} // END function _update_notice()
	} // END class Twoclick_Social_Media_Buttons_Backend

	new Twoclick_Social_Media_Buttons_Backend();
} // END if(!class_exists('Twoclick_Social_Media_Buttons_Backend'))