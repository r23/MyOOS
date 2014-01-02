<?php

	class WP_Piwik_Settings {
		
		private static $logger, $defaultSettings;
		
		private $globalSettings = array(
			'revision' => 90921,
			'plugin_display_name' => 'WP-Piwik',
			'add_tracking_code' => false,
			'last_settings_update' => 0,
			'piwik_token' => '',
			'piwik_url' => '',
			'piwik_path' => '',
			'piwik_mode' => 'http',
			'piwik_useragent' => 'php',
			'piwik_useragent_string' => 'WP-Piwik',
			'connection_timeout' => 5,
			'dashboard_widget' => false,
			'dashboard_chart' => false,
			'dashboard_seo' => false,
			'stats_seo' => false,
			'capability_stealth' => array(),
			'capability_read_stats' => array('administrator' => true),
			'piwik_shortcut' => false,
			'default_date' => 'yesterday',
			'auto_site_config' => true,
			'track_404' => false,
			'track_search' => false,
			'track_mode' => 0,
			'track_post' => false,
			'track_proxy' => false,
			'track_admin' => false,
			'track_feed' => false,
			'track_feed_goal' => '',
			'track_feed_revenue' => '',
			'track_feed_campaign' => 'feed',
			'track_feed_addcampaign' => 'false',
			'track_cdnurlssl' => '',
			'track_noscript' => false,
			'track_nojavascript' => false,
			'disable_timelimit' => false,
			'disable_ssl_verify' => false,
			'disable_cookies' => false,
			'toolbar' => false,
			'shortcodes' => false,
			'cache' => true,
			'perpost_stats' => false
		),
		$settings = array(
			'name' => '',
			'tracking_code' => '',
			'site_id' => NULL,
			'last_tracking_code_update' => 0,
			'dashboard_revision' => 0,
			'noscript_code' => ''
		),
		$settingsChanged = false;
	
		public function __construct($objLogger) {
			self::$logger = $objLogger;
			self::$logger->log('Store default settings');
			self::$defaultSettings = array('globalSettings' => $this->globalSettings, 'settings' => $this->settings);
			self::$logger->log('Load settings');
			$this->globalSettings = (is_plugin_active_for_network('wp-piwik/wp-piwik.php')?
				get_site_option('wp-piwik_global-settings', $this->globalSettings):
				get_option('wp-piwik_global-settings', $this->globalSettings)
			);
			$this->settings = get_option('wp-piwik_settings',$this->settings);
		}
		
		public function save() {
			if (!$this->settingsChanged) {
				self::$logger->log('No settings changed yet');
				return;
			}
			self::$logger->log('Save settings');
			if (is_plugin_active_for_network('wp-piwik/wp-piwik.php'))
				update_site_option('wp-piwik_global-settings', $this->globalSettings);
			else 
				update_option('wp-piwik_global-settings', $this->globalSettings);
			update_option('wp-piwik_settings', $this->settings);
			global $wp_roles;
			if (!is_object($wp_roles))
				$wp_roles = new WP_Roles();
			if (!is_object($wp_roles)) die("STILL NO OBJECT");
			foreach($wp_roles->role_names as $strKey => $strName)  {
				$objRole = get_role($strKey);
				foreach (array('stealth', 'read_stats') as $strCap) {
					$aryCaps = $this->getGlobalOption('capability_'.$strCap);
					if (isset($aryCaps[$strKey]) && $aryCaps[$strKey])
						$objRole->add_cap('wp-piwik_'.$strCap);
					else $objRole->remove_cap('wp-piwik_'.$strCap);
				}
			}
			$this->settingsChanges = false;
		}

		public function getGlobalOption($key) {
			return isset($this->globalSettings[$key])?$this->globalSettings[$key]:null;
		}	

		public function getOption($key) {
			return isset($this->settings[$key])?$this->settings[$key]:null;
		}	

		public function setGlobalOption($key, $value) {
			$this->settingsChanged = true;
			self::$logger->log('Changed global option '.$key.': '.(is_array($value)?serialize($value):$value));		
			$this->globalSettings[$key] = $value;
		}	

		public function setOption($key, $value) {
			$this->settingsChanged = true;
			self::$logger->log('Changed option '.$key.': '.$value);		
			$this->settings[$key] = $value;
		}
		
		public function resetSettings($bolFull = false) {
			self::$logger->log('Reset WP-Piwik settings');
			global $wpdb;
			$keepSettings = array(
				'piwik_token' => $this->getGlobalOption('piwik_token'),
				'piwik_url' => $this->getGlobalOption('piwik_url'),
				'piwik_path' => $this->getGlobalOption('piwik_path'),
				'piwik_mode' => $this->getGlobalOption('piwik_mode')
			);
			if (is_plugin_active_for_network('wp-piwik/wp-piwik.php')) {
				delete_site_option('wp-piwik_global-settings');
				$aryBlogs = $wpdb->get_results('SELECT blog_id FROM '.$wpdb->blogs.' ORDER BY blog_id');
				foreach ($aryBlogs as $aryBlog)
					delete_blog_option($aryBlog->blog_id, 'wp-piwik_settings');
				if (!$bolFull) update_site_option('wp-piwik_global-settings', $keepSettings);
			} else { 
				delete_option('wp-piwik_global-settings');
				delete_option('wp-piwik_settings');
			}
			$this->globalSettings = self::$defaultSettings['globalSettings'];
			$this->settings = self::$defaultSettings['settings'];
			if (!$bolFull) {
				self::$logger->log('Restore connection settings');
				foreach ($keepSettings as $key => $value)
					$this->setGlobalOption($key, $value);
			}
			$this->save();
		}
	}