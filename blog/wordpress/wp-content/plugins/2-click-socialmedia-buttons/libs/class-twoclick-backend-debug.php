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
 * The Debug Class
 * Provides some usefull debug information in the settings page
 *
 * @since 1.1
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!class_exists('Twoclick_Social_Media_Buttons_Backend_Debug')) {
	class Twoclick_Social_Media_Buttons_Backend_Debug extends Twoclick_Social_Media_Buttons_Backend {

		/**
		 * Konstruktor
		 *
		 * @since 1.1
		 * @author ppfeufer
		 */
		function __construct() {
			if($this->_is_twoclick_settings_page()) {
				$this->_get_debug_output();
			} // END if($this->_is_twoclick_settings_page())
		} // END function __construct()

		/**
		 * Alle Debuginformationen ausgeben
		 *
		 * @since 1.1
		 * @author ppfeufer
		 *
		 * @uses _get_installed_themes
		 * @uses _get_installed_plugins
		 * @uses _get_userdata
		 * @uses _get_serverdata
		 */
		private function _get_debug_output() {
			if($this->_is_twoclick_settings_page()) {
				echo $this->_get_description();
				echo $this->_get_wordpress_data();
				echo $this->_get_active_theme();
				echo $this->_get_plugin_options();
				echo $this->_get_installed_plugins();
// 				echo $this->_get_userdata();
// 				echo $this->_get_serverdata();
			} // END if($this->_is_twoclick_settings_page())
		} // END private function _get_debug_output()

		/**
		 * Informationen zum Debug.
		 *
		 * @since 1.1
		 * @author ppfeufer
		 *
		 * @return string
		 */
		private function _get_description() {
			ob_start();
			?>
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><strong><?php _e('What the hell is this?', TWOCLICK_TEXTDOMAIN)?></strong></span></h3>
							<div class="inside">
								<p>
									<?php _e('If you need some help with this plugin, in case something doesn\'t work as expected, I need some informations from you.', TWOCLICK_TEXTDOMAIN); ?>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$var_sReturn = ob_get_contents();

			ob_end_clean();

			return $var_sReturn;
		} // END private function _get_description()

		/**
		 * Informationen zum WordPress.
		 *
		 * @since 1.1
		 * @author ppfeufer
		 *
		 * @return string
		 */
		private function _get_wordpress_data() {
			ob_start();
			?>
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><strong><?php _e('Your WordPress', TWOCLICK_TEXTDOMAIN)?></strong></span></h3>
							<div class="inside">
								<p>
									<?php _e('Version', TWOCLICK_TEXTDOMAIN); ?>: <?php echo $GLOBALS['wp_version']; ?><?php if(is_multisite()) {echo ' (Multisite)';}?><br />
									<?php _e('URL', TWOCLICK_TEXTDOMAIN); ?>: <?php echo home_url('/'); ?>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$var_sReturn = ob_get_contents();

			ob_end_clean();

			return $var_sReturn;
		} // END private function _get_wordpress_data()

		/**
		 * Pluginoptionen ausgeben.
		 *
		 * @since 1.4
		 * @author ppfeufer
		 *
		 * @return string
		 */
		private function _get_plugin_options() {
			ob_start();
			?>
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><strong><?php _e('Pluginoptions', TWOCLICK_TEXTDOMAIN)?></strong></span></h3>
							<div class="inside">
								<?php
								echo '<pre>';
								print_r($this->array_htmlspecialchars($this->_get_option()));
								echo '</pre>';
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$var_sReturn = ob_get_contents();

			ob_end_clean();

			return $var_sReturn;
		} // END private function _get_plugin_options()

		/**
		 * Informationen über die instalierten Themes sammeln.
		 *
		 * @since 1.1
		 * @author ppfeufer
		 *
		 * @return string
		 */
		private function _get_active_theme() {
			ob_start();
			?>
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><strong><?php _e('Your active theme', TWOCLICK_TEXTDOMAIN)?></strong></span></h3>
							<div class="inside">
								<?php
								$obj_ThemeData = '';
								if(function_exists('wp_get_theme')){
									$obj_ThemeData = wp_get_theme();
								} else {
									$obj_ThemeData = (object) get_theme_data(get_template_directory() . '/style.css');
								}

								echo '<pre>';
								print_r($obj_ThemeData);
								echo '</pre>';
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$var_sReturn = ob_get_contents();

			ob_end_clean();

			return $var_sReturn;
		} // END private function _get_active_theme()

		/**
		 * Informationen über die instalierten Plugins sammeln.
		 *
		 * @since 1.1
		 * @author ppfeufer
		 *
		 * @return string
		 */
		private function _get_installed_plugins() {
			ob_start();
			?>
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><strong><?php _e('Your installed plugins', TWOCLICK_TEXTDOMAIN)?></strong></span></h3>
							<div class="inside">
								<?php
								$array_PluginData = get_plugins();

								foreach((array) $array_PluginData as $var_sPLuginPath => $array_Data) {
									?>
									<div class="twoclick-debug plugindata clearfix">
										<div>
											<span><?php _e('Plugin', TWOCLICK_TEXTDOMAIN)?>: </span><span><?php echo $array_Data['Name']; ?> (<?php echo $array_Data['Version']; ?>)</span>
										</div>
										<div>
											<span><?php _e('Status', TWOCLICK_TEXTDOMAIN)?>: </span><span><?php if($this->_is_active_plugin($var_sPLuginPath)) {_e('activated', TWOCLICK_TEXTDOMAIN);} else {_e('deactivated', TWOCLICK_TEXTDOMAIN);}?></span>
										</div>
										<div>
											<span><?php _e('Author', TWOCLICK_TEXTDOMAIN)?>: </span><span><?php echo strip_tags($array_Data['Author']); ?></span>
										</div>
										<div>
											<span><?php _e('URL', TWOCLICK_TEXTDOMAIN)?>: </span><span><?php echo $array_Data['PluginURI']; ?></span>
										</div>
										<div>
											<span><?php _e('Path', TWOCLICK_TEXTDOMAIN)?>: </span><span><?php echo $var_sPLuginPath; ?></span>
										</div>
									</div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$var_sReturn = ob_get_contents();

			ob_end_clean();

			return $var_sReturn;
		} // END private function _get_installed_plugins()

		/**
		 * Informationen über den Nutzer sammeln.
		 *
		 * @since 1.1
		 * @author ppfeufer
		 *
		 * @return string
		 */
		private function _get_userdata() {
			ob_start();

			$var_sUserAgent = wp_filter_nohtml_kses($_SERVER['HTTP_USER_AGENT']);
			?>
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><strong><?php _e('Userdata', TWOCLICK_TEXTDOMAIN)?></strong></span></h3>
							<div class="inside">
								<p>
									<?php echo $var_sUserAgent; ?>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$var_sReturn = ob_get_contents();

			ob_end_clean();

			return $var_sReturn;
		} // END private function _get_userdata()

		/**
		 * Informationen über den Server sammeln.
		 *
		 * @since 1.1
		 * @author ppfeufer
		 *
		 * @return string
		 */
		private function _get_serverdata() {
			ob_start();
			?>
			<div class="metabox-holder clearfix">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span><strong><?php _e('Serverdata', TWOCLICK_TEXTDOMAIN)?></strong></span></h3>
							<div class="inside">
								<p>
									Serverdaten
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$var_sReturn = ob_get_contents();

			ob_end_clean();

			return $var_sReturn;
		} // END private function _get_serverdata()

		/**
		 * <[ Helper ]>
		 * Prüfe ob ein Plugin aktiv ist oder nicht.
		 *
		 * @since 1.1
		 * @author ppfeufer
		 *
		 * @param string $var_sPlugin
		 * @return boolean
		 */
		private function _is_active_plugin($var_sPlugin) {
			$var_bReturn = in_array($var_sPlugin, apply_filters('active_plugins', get_option('active_plugins')));

			return $var_bReturn;
		} // END private function _is_active_plugin($var_sPlugin)

		/**
		 * <[ Helper ]>
		 * htmlspecialchars() auch für Arrays
		 *
		 * @since 1.4
		 * @author ppfeufer
		 *
		 * @param array $array_Input
		 * @param string $var_sQuoteStyle
		 * @param string $var_sCharset
		 * @return array
		 */
		private function array_htmlspecialchars($array_Input, $var_sQuoteStyle = ENT_QUOTES, $var_sCharset = 'UTF-8') {
			if(is_array($array_Input)) {
				foreach ($array_Input as $key => $value) {
					if(is_array($value)) {
						$this->array_htmlspecialchars($array_Input[$key]);
					} else {
						$array_Output[$key] = htmlspecialchars($value, $var_sQuoteStyle, $var_sCharset);
					} // END if(is_array($value))
				} // END foreach ($array_Input as $key => $value)

				return $array_Output;
			} // END if(is_array($array_Input))

			return false;
		} // END private function array_htmlspecialchars($array_Input, $var_sQuoteStyle = ENT_QUOTES, $var_sCharset = 'UTF-8')
	} // END class Twoclick_Social_Media_Buttons_Backend_Debug extends Twoclick_Social_Media_Buttons_Backend

	// Debugklasse starten
	new Twoclick_Social_Media_Buttons_Backend_Debug();
} // END if(!class_exists('Twoclick_Social_Media_Buttons_Backend_Debug'))