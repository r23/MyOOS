<?php

	class WP_Piwik_Template {
		
		public static $logger, $settings, $wpPiwik;
		
		public function __construct($config) {
			self::$logger = $config['logger'];
			self::$settings = $config['settings'];
			self::$wpPiwik = $config['wp_piwik'];
		}

		public function output($array, $key, $default = '') {
			if (isset($array[$key]))
				return $array[$key];
			else
				return $default; 
		}
		
		public function tabRow($name, $value) {
			echo '<tr><td>'.$name.'</td><td>'.$value.'</td></tr>';
		}
		
		public function getRangeLast30() {
			$diff = (self::$settings->getGlobalOption('default_date') == 'yesterday') ? -86400 : 0;
			$end = time() + $diff;
			$start = time() - 2592000 + $diff;
			return date('Y-m-d', $start).','.date('Y-m-d', $end);
			
		}

	}