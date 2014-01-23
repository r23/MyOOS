<?php
/*
Plugin Name: Antispam Bee
Text Domain: antispam_bee
Domain Path: /lang
Description: Easy and extremely productive spam-fighting plugin with many sophisticated solutions. Includes protection again trackback spam.
Author: Sergej M&uuml;ller
Author URI: http://wpcoder.de
Plugin URI: http://antispambee.com
Version: 2.6.0
*/


/* Sicherheitsabfrage */
if ( ! class_exists('WP') ) {
	die();
}


/**
* Antispam_Bee
*
* @since   0.1
* @change  2.4
*/

class Antispam_Bee {


	/* Init */
	public static $defaults;
	private static $_base;
	private static $_secret;
	private static $_reason;


	/**
	* "Konstruktor" der Klasse
	*
	* @since   0.1
	* @change  2.6.0
	*/

  	public static function init()
  	{
  		/* Delete spam reason */
  		add_action(
  			'unspam_comment',
  			array(
  				__CLASS__,
  				'delete_spam_reason_by_comment'
  			)
  		);

		/* AJAX & Co. */
		if ( (defined('DOING_AJAX') && DOING_AJAX) or (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ) {
			return;
		}

		/* Initialisierung */
		self::_init_internal_vars();

		/* Cronjob */
		if ( defined('DOING_CRON') ) {
			add_action(
				'antispam_bee_daily_cronjob',
				array(
					__CLASS__,
					'start_daily_cronjob'
				)
			);

		/* Admin */
		} elseif ( is_admin() ) {
			/* Menü */
			add_action(
				'admin_menu',
				array(
					__CLASS__,
					'add_sidebar_menu'
				)
			);

			/* Dashboard */
			if ( self::_current_page('dashboard') ) {
				add_action(
					'init',
					array(
						__CLASS__,
						'load_plugin_lang'
					)
				);
				add_action(
					'dashboard_glance_items',
					array(
						__CLASS__,
						'add_dashboard_count'
					)
				);
				add_action(
					'wp_dashboard_setup',
					array(
						__CLASS__,
						'add_dashboard_chart'
					)
				);

			/* Plugins */
			} else if ( self::_current_page('plugins') ) {
				add_action(
					'init',
					array(
						__CLASS__,
						'load_plugin_lang'
					)
				);
				add_filter(
					'plugin_row_meta',
					array(
						__CLASS__,
						'init_row_meta'
					),
					10,
					2
				);
				add_filter(
					'plugin_action_links_' .self::$_base,
					array(
						__CLASS__,
						'init_action_links'
					)
				);

			/* Optionen */
			} else if ( self::_current_page('options') ) {
				add_action(
					'admin_init',
					array(
						__CLASS__,
						'load_plugin_lang'
					)
				);
				add_action(
					'admin_init',
					array(
						__CLASS__,
						'init_plugin_sources'
					)
				);

			} else if ( self::_current_page('admin-post') ) {
				require_once( dirname(__FILE__). '/inc/gui.class.php' );

				add_action(
					'admin_post_ab_save_changes',
					array(
						'Antispam_Bee_GUI',
						'save_changes'
					)
				);

			} else if ( self::_current_page('edit-comments') ) {
				if ( ! empty($_GET['comment_status']) && $_GET['comment_status'] === 'spam' && ! self::get_option('no_notice') ) {
					/* Include file */
					require_once( dirname(__FILE__). '/inc/columns.class.php' );

					/* Load textdomain */
					self::load_plugin_lang();

					/* Add plugin columns */
					add_filter(
						'manage_edit-comments_columns',
						array(
							'Antispam_Bee_Columns',
							'register_plugin_columns'
						)
					);
					add_filter(
						'manage_comments_custom_column',
						array(
							'Antispam_Bee_Columns',
							'print_plugin_column'
						),
						10,
						2
					);
				}
			}

		/* Frontend */
		} else {
			add_action(
				'template_redirect',
				array(
					__CLASS__,
					'prepare_comment_field'
				)
			);
			add_action(
				'init',
				array(
					__CLASS__,
					'precheck_incoming_request'
				)
			);
			add_action(
				'preprocess_comment',
				array(
					__CLASS__,
					'handle_incoming_request'
				),
				1
			);
			add_action(
				'antispam_bee_count',
				array(
					__CLASS__,
					'the_spam_count'
				)
			);
		}
	}




	############################
	########  INSTALL  #########
	############################


	/**
	* Aktion bei der Aktivierung des Plugins
	*
	* @since   0.1
	* @change  2.4
	*/

	public static function activate()
	{
		/* Option anlegen */
		add_option(
			'antispam_bee',
			array(),
			'',
			'no'
		);

		/* Cron aktivieren */
		if ( self::get_option('cronjob_enable') ) {
			self::init_scheduled_hook();
		}
	}


	/**
	* Aktion bei der Deaktivierung des Plugins
	*
	* @since   0.1
	* @change  2.4
	*/

	public static function deactivate()
	{
		self::clear_scheduled_hook();
	}


	/**
	* Aktion beim Löschen des Plugins
	*
	* @since   2.4
	* @change  2.4
	*/

	public static function uninstall()
	{
		/* Global */
		global $wpdb;

		/* Remove settings */
		delete_option('antispam_bee');

		/* Clean DB */
		$wpdb->query("OPTIMIZE TABLE `" .$wpdb->options. "`");
	}




	############################
	#########  INTERN  #########
	############################


	/**
	* Initialisierung der internen Variablen
	*
	* @since   2.4
	* @change  2.5.2
	*/

	private static function _init_internal_vars()
	{
		self::$_base   = plugin_basename(__FILE__);
		self::$_secret = substr(md5(get_bloginfo('url')), 0, 5). '-comment';

		self::$defaults = array(
			'options' => array(
				/* Allgemein */
				'advanced_check' 	=> 1,
				'regexp_check'		=> 1,
				'spam_ip' 			=> 1,
				'already_commented'	=> 1,
				'ignore_pings' 		=> 0,
				'always_allowed' 	=> 0,

				'dashboard_chart' 	=> 1,
				'dashboard_count' 	=> 0,

				/* Filter */
				'country_code' 		=> 0,
				'country_black'		=> '',
				'country_white'		=> '',

				'translate_api' 	=> 0,
				'translate_lang'	=> '',

				'dnsbl_check'		=> 0,
				'bbcode_check'		=> 1,

				/* Erweitert */
				'flag_spam' 		=> 1,
				'email_notify' 		=> 1,
				'no_notice' 		=> 1,
				'cronjob_enable' 	=> 0,
				'cronjob_interval'	=> 0,

				'ignore_filter' 	=> 0,
				'ignore_type' 		=> 0,

				'reasons_enable'	=> 0,
				'ignore_reasons'	=> array()
			),
			'reasons' => array(
				'css'		=> 'CSS Hack',
				'empty'		=> 'Empty Data',
				'server'	=> 'Server IP',
				'localdb'	=> 'Local DB Spam',
				'country'	=> 'Country Check',
				'dnsbl'		=> 'DNSBL Spam',
				'bbcode'	=> 'BBCode',
				'lang'		=> 'Comment Language',
				'regexp'	=> 'RegExp'
			)
		);
	}


	/**
	* Prüfung und Rückgabe eines Array-Keys
	*
	* @since   2.4.2
	* @change  2.4.2
	*
	* @param   array   $array  Array mit Werten
	* @param   string  $key    Name des Keys
	* @return  mixed           Wert des angeforderten Keys
	*/

	public static function get_key($array, $key)
	{
		if ( empty($array) or empty($key) or empty($array[$key]) ) {
			return null;
		}

		return $array[$key];
	}


	/**
	* Lokalisierung der Admin-Seiten
	*
	* @since   0.1
	* @change  2.4
	*
	* @param   string   $page  Kennzeichnung der Seite
	* @return  boolean         TRUE Bei Erfolg
	*/

	private static function _current_page($page)
	{
		switch ($page) {
			case 'dashboard':
				return ( empty($GLOBALS['pagenow']) or ( !empty($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == 'index.php' ) );

			case 'options':
				return ( !empty($_REQUEST['page']) && $_REQUEST['page'] == 'antispam_bee' );

			case 'plugins':
				return ( !empty($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == 'plugins.php' );

			case 'admin-post':
				return ( !empty($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == 'admin-post.php' );

			case 'edit-comments':
				return ( !empty($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == 'edit-comments.php' );

			default:
				return false;
		}
	}


	/**
	* Einbindung der Sprachdatei
	*
	* @since   0.1
	* @change  2.4
	*/

	public static function load_plugin_lang()
	{
		load_plugin_textdomain(
			'antispam_bee',
			false,
			'antispam-bee/lang'
		);
	}


	/**
	* Hinzufügen des Links zu den Einstellungen
	*
	* @since   1.1
	* @change  1.1
	*/

	public static function init_action_links($data)
	{
		/* Rechte? */
		if ( !current_user_can('manage_options') ) {
			return $data;
		}

		return array_merge(
			$data,
			array(
				sprintf(
					'<a href="%s">%s</a>',
					add_query_arg(
						array(
							'page' => 'antispam_bee'
						),
						admin_url('options-general.php')
					),
					__('Settings')
				)
			)
		);
	}


	/**
	* Meta-Links des Plugins
	*
	* @since   0.1
	* @change  2.4.4
	*
	* @param   array   $input  Bereits vorhandene Links
	* @param   string  $file   Aktuelle Seite
	* @return  array   $data   Modifizierte Links
	*/

	public static function init_row_meta($input, $file)
	{
		/* Rechte */
		if ( $file != self::$_base ) {
			return $input;
		}

		return array_merge(
			$input,
			array(
				'<a href="https://flattr.com/t/1323822" target="_blank">Flattr</a>',
				'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=5RDDW9FEHGLG6" target="_blank">PayPal</a>'
			)
		);
	}




	############################
	#######  RESSOURCEN  #######
	############################


	/**
	* Registrierung von Ressourcen (CSS & JS)
	*
	* @since   1.6
	* @change  2.4.5
	*/

	public static function init_plugin_sources()
	{
		/* Infos auslesen */
		$plugin = get_plugin_data(__FILE__);

		/* JS einbinden */
		wp_register_script(
			'ab_script',
			plugins_url('js/scripts.min.js', __FILE__),
			array('jquery'),
			$plugin['Version']
		);

		/* CSS einbinden */
		wp_register_style(
			'ab_style',
			plugins_url('css/styles.min.css', __FILE__),
			array(),
			$plugin['Version']
		);
	}


	/**
	* Initialisierung der Optionsseite
	*
	* @since   0.1
	* @change  2.4.3
	*/

	public static function add_sidebar_menu()
	{
		/* Menü anlegen */
		$page = add_options_page(
			'Antispam Bee',
			'Antispam Bee',
			'manage_options',
			'antispam_bee',
			array(
				'Antispam_Bee_GUI',
				'options_page'
			)
		);

		/* JS einbinden */
		add_action(
			'admin_print_scripts-' . $page,
			array(
				__CLASS__,
				'add_options_script'
			)
		);

		/* CSS einbinden */
		add_action(
			'admin_print_styles-' . $page,
			array(
				__CLASS__,
				'add_options_style'
			)
		);

		/* PHP laden */
		add_action(
			'load-' .$page,
			array(
				__CLASS__,
				'init_options_page'
			)
		);
	}


	/**
	* Initialisierung von JavaScript
	*
	* @since   1.6
	* @change  2.4
	*/

	public static function add_options_script()
	{
		wp_enqueue_script('ab_script');
	}


	/**
	* Initialisierung von Stylesheets
	*
	* @since   1.6
	* @change  2.4
	*/

	public static function add_options_style()
	{
		wp_enqueue_style('ab_style');
	}


	/**
	* Einbindung der GUI
	*
	* @since   2.4
	* @change  2.4
	*/

	public static function init_options_page()
	{
		require_once( dirname(__FILE__). '/inc/gui.class.php' );
	}




	############################
	#######  DASHBOARD  ########
	############################


	/**
	* Anzeige des Spam-Counters auf dem Dashboard
	*
	* @since   0.1
	* @change  2.6.0
	*/

	public static function add_dashboard_count()
	{
		/* Aktiv? */
		if ( ! self::get_option('dashboard_count') ) {
			return;
		}

		/* Add list item icon */
		echo '<style>#dashboard_right_now .ab-count a:before {content: "\f117"}</style>';

		/* Ausgabe */
		echo sprintf(
			'<li class="ab-count">
				<a href="%s">
					%d %s
				</a>
			</li>',
			add_query_arg(
				array(
					'page' => 'antispam_bee'
				),
				admin_url('options-general.php')
			),
			esc_html( self::_get_spam_count() ),
			esc_html__('Blocked', 'antispam_bee')
		);
	}


	/**
	* Initialisierung des Dashboard-Chart
	*
	* @since   1.9
	* @change  2.5.6
	*/

	public static function add_dashboard_chart()
	{
		/* Filter */
		if ( ! current_user_can('publish_posts') OR ! self::get_option('dashboard_chart') ) {
			return;
		}

		/* Widget hinzufügen */
		wp_add_dashboard_widget(
			'ab_widget',
			'Antispam Bee',
			array(
				__CLASS__,
				'show_spam_chart'
			)
		);

		/* JS laden */
		add_action(
			'wp_print_scripts',
			array(
				__CLASS__,
				'add_dashboard_script'
			)
		);

		/* CSS laden */
		add_action(
			'admin_head',
			array(
				__CLASS__,
				'add_dashboard_style'
			)
		);
	}


	/**
	* Print dashboard styles
	*
	* @since   1.9.0
	* @change  2.5.8
	*/

	public static function add_dashboard_style()
	{
		/* Get plugin data */
		$plugin = get_plugin_data(__FILE__);

		/* Register styles */
		wp_register_style(
			'ab_chart',
			plugins_url('css/dashboard.min.css', __FILE__),
			array(),
			$plugin['Version']
		);

		/* Embed styles */
  		wp_print_styles('ab_chart');
	}


	/**
	* Print dashboard scripts
	*
	* @since   1.9.0
	* @change  2.5.8
	*/

	public static function add_dashboard_script()
	{
		/* Get stats */
		if ( ! self::get_option('daily_stats') ) {
			return;
		}

		/* Get plugin data */
		$plugin = get_plugin_data(__FILE__);

		/* Register scripts */
		wp_register_script(
			'sm_raphael_js',
			plugins_url('js/raphael.min.js', __FILE__),
			array(),
			$plugin['Version'],
			true
		);
		wp_register_script(
			'sm_raphael_helper',
			plugins_url('js/raphael.helper.min.js', __FILE__),
			array(),
			$plugin['Version'],
			true
		);
		wp_register_script(
			'ab_chart_js',
			plugins_url('js/dashboard.min.js', __FILE__),
			array('jquery'),
			$plugin['Version'],
			true
		);

		/* Embed scripts */
		wp_enqueue_script('sm_raphael_js');
		wp_enqueue_script('sm_raphael_helper');
		wp_enqueue_script('ab_chart_js');
	}


	/**
	* Print dashboard html
	*
	* @since   1.9.0
	* @change  2.5.8
	*/

	public static function show_spam_chart()
	{
		/* Get stats */
		$items = (array)self::get_option('daily_stats');

		/* Emty array? */
		if ( empty($items) ) {
			echo sprintf(
				'<div id="ab_chart"><p>%s</p></div>',
				esc_html__('No data available.', 'antispam_bee')
			);

			return;
		}

		/* Sort stats */
		ksort($items, SORT_NUMERIC);

		/* HTML start */
		$html = "<table id=ab_chart_data>\n";


		/* Timestamp table */
		$html .= "<tfoot><tr>\n";
		foreach($items as $date => $count) {
			$html .= "<th>" .$date. "</th>\n";
		}
		$html .= "</tr></tfoot>\n";

		/* Counter table */
		$html .= "<tbody><tr>\n";
		foreach($items as $date => $count) {
			$html .= "<td>" .(int) $count. "</td>\n";
		}
		$html .= "</tr></tbody>\n";


		/* HTML end */
		$html .= "</table>\n";

		/* Print html */
		echo '<div id="ab_chart">' .$html. '</div>';
	}




	############################
	########  OPTIONS  #########
	############################


	/**
	* Rückgabe der Optionen
	*
	* @since   2.4
	* @change  2.4
	*
	* @return  array  $options  Array mit Optionen
	*/

	public static function get_options()
	{
		if ( !$options = wp_cache_get('antispam_bee') ) {
			$options = wp_parse_args(
				get_option('antispam_bee'),
				self::$defaults['options']
			);

			wp_cache_set(
				'antispam_bee',
				$options
			);
		}

		return $options;
	}


	/**
	* Rückgabe eines Optionsfeldes
	*
	* @since   0.1
	* @change  2.4.2
	*
	* @param   string  $field  Name des Feldes
	* @return  mixed           Wert des Feldes
	*/

	public static function get_option($field)
	{
		$options = self::get_options();

		return self::get_key($options, $field);
	}


	/**
	* Aktualisiert ein Optionsfeld
	*
	* @since   0.1
	* @change  2.4
	*
	* @param   string  $field  Name des Feldes
	* @param   mixed           Wert des Feldes
	*/

	private static function _update_option($field, $value)
	{
		self::update_options(
			array(
				$field => $value
			)
		);
	}


	/**
	* Aktualisiert mehrere Optionsfelder
	*
	* @since   0.1
	* @change  2.4
	*
	* @param   array  $data  Array mit Feldern
	*/

	public static function update_options($data)
	{
		/* Option zuweisen */
		$options = array_merge(
			(array)get_option('antispam_bee'),
			$data
		);

		/* DB updaten */
		update_option(
			'antispam_bee',
			$options
		);

		/* Cache updaten */
		wp_cache_set(
			'antispam_bee',
			$options
		);
	}




	############################
	########  CRONJOBS  ########
	############################


	/**
	* Ausführung des täglichen Cronjobs
	*
	* @since   0.1
	* @change  2.4
	*/

	public static function start_daily_cronjob()
	{
		/* Kein Cronjob? */
		if ( !self::get_option('cronjob_enable') ) {
			return;
		}

		/* Timestamp updaten */
		self::_update_option(
			'cronjob_timestamp',
			time()
		);

		/* Spam löschen */
		self::_delete_old_spam();
	}


	/**
	* Löschung alter Spamkommentare
	*
	* @since   0.1
	* @change  2.4
	*/

	private static function _delete_old_spam()
	{
		/* Anzahl der Tage */
		$days = (int)self::get_option('cronjob_interval');

		/* Kein Wert? */
		if ( empty($days) ) {
			return false;
		}

		/* Global */
		global $wpdb;

		/* Kommentare löschen */
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM `$wpdb->comments` WHERE `comment_approved` = 'spam' AND SUBDATE(NOW(), %d) > comment_date_gmt",
				$days
			)
		);

		/* DB optimieren */
		$wpdb->query("OPTIMIZE TABLE `$wpdb->comments`");
	}


	/**
	* Initialisierung des Cronjobs
	*
	* @since   0.1
	* @change  2.4
	*/

	public static function init_scheduled_hook()
	{
		if ( ! wp_next_scheduled('antispam_bee_daily_cronjob') ) {
			wp_schedule_event(
				time(),
				'daily',
				'antispam_bee_daily_cronjob'
			);
		}
	}


	/**
	* Löschung des Cronjobs
	*
	* @since   0.1
	* @change  2.4
	*/

	public static function clear_scheduled_hook()
	{
		if ( wp_next_scheduled('antispam_bee_daily_cronjob') ) {
			wp_clear_scheduled_hook('antispam_bee_daily_cronjob');
		}
	}




	############################
	######  SPAMPRÜFUNG  #######
	############################


	/**
	* Überprüfung der POST-Werte
	*
	* @since   0.1
	* @change  2.4.2
	*/

	public static function precheck_incoming_request()
	{
		/* Nur Frontend */
		if ( is_feed() or is_trackback() or self::_is_mobile() ) {
			return;
		}

		/* Allgemeine Werte */
		$request_url = self::get_key($_SERVER, 'REQUEST_URI');
		$hidden_field = self::get_key($_POST, 'comment');
		$plugin_field = self::get_key($_POST, self::$_secret);

		/* Falsch verbunden */
		if ( empty($_POST) or empty($request_url) or strpos($request_url, 'wp-comments-post.php') === false ) {
			return;
		}

		/* Felder prüfen */
		if ( empty($hidden_field) && !empty($plugin_field) ) {
			$_POST['comment'] = $plugin_field;
			unset($_POST[self::$_secret]);
		} else {
			$_POST['bee_spam'] = 1;
		}
	}


	/**
	* Prüfung der eingehenden Anfragen auf Spam
	*
	* @since   0.1
	* @change  2.4.2
	*
	* @param   array  $comment  Unbehandelter Kommentar
	* @return  array  $comment  Behandelter Kommentar
	*/

	public static function handle_incoming_request($comment)
	{
		/* Server-Werte */
		$url = self::get_key($_SERVER, 'REQUEST_URI');

		/* Leere Werte? */
		if ( empty($url) ) {
			return self::_handle_spam_request(
				$comment,
				'empty'
			);
		}

		/* Ping-Optionen */
		$ping = array(
			'types'   => array('pingback', 'trackback', 'pings'),
			'allowed' => !self::get_option('ignore_pings')
		);

		/* Kommentar */
		if ( strpos($url, 'wp-comments-post.php') !== false && !empty($_POST) ) {
			/* Filter ausführen */
			$status = self::_verify_comment_request($comment);

			/* Spam lokalisiert */
			if ( !empty($status['reason']) ) {
				return self::_handle_spam_request(
					$comment,
					$status['reason']
				);
			}

		/* Trackback */
		} else if ( in_array(self::get_key($comment, 'comment_type'), $ping['types']) && $ping['allowed'] ) {
			/* Filter ausführen */
			$status = self::_verify_trackback_request($comment);

			/* Spam lokalisiert */
			if ( !empty($status['reason']) ) {
				return self::_handle_spam_request(
					$comment,
					$status['reason'],
					true
				);
			}
		}

		return $comment;
	}


	/**
	* Bereitet die Ersetzung des KOmmentarfeldes vor
	*
	* @since   0.1
	* @change  2.4
	*/

	public static function prepare_comment_field()
	{
		/* Nur Frontend */
		if ( is_feed() or is_trackback() or is_robots() or self::_is_mobile() ) {
			return;
		}

		/* Nur Beiträge */
		if ( !is_singular() && !self::get_option('always_allowed') ) {
			return;
		}

		/* Fire! */
		ob_start(
			array(
				'Antispam_Bee',
				'replace_comment_field'
			)
		);
	}


	/**
	* ersetzt das Kommentarfeld
	*
	* @since   2.4
	* @change  2.4.1
	*
	* @param   string  $data  HTML-Code der Webseite
	* @return  string         Behandelter HTML-Code
	*/

	public static function replace_comment_field($data)
	{
		/* Leer? */
		if ( empty($data) ) {
			return;
		}

		/* Convert */
		return preg_replace(
			'#<textarea(.+?)name=["\']comment["\'](.+?)</textarea>#s',
			'<textarea$1name="' .self::$_secret. '"$2</textarea><textarea name="comment" style="display:none" rows="1" cols="1"></textarea>',
			(string) $data,
			1
		);
	}


	/**
	* Prüfung der Trackbacks
	*
	* @since   2.4
	* @change  2.5.4
	*
	* @param   array  $comment  Daten des Trackbacks
	* @return  array            Array mit dem Verdachtsgrund [optional]
	*/

	private static function _verify_trackback_request($comment)
	{
		/* IP */
		$ip = self::get_key($_SERVER, 'REMOTE_ADDR');

		/* Kommentarwerte */
		$url = self::get_key($comment, 'comment_author_url');
		$body = self::get_key($comment, 'comment_content');

		/* Leere Werte ? */
		if ( empty($url) or empty($body) ) {
			return array(
				'reason' => 'empty'
			);
		}

		/* IP? */
		if ( empty($ip) or (function_exists('filter_var') && !filter_var($ip, FILTER_VALIDATE_IP)) ) {
			return array(
				'reason' => 'empty'
			);
		}

		/* Optionen */
		$options = self::get_options();

		/* BBCode Spam */
		if ( $options['bbcode_check'] && self::_is_bbcode_spam($body) ) {
			return array(
				'reason' => 'bbcode'
			);
		}

		/* IP != Server */
		if ( $options['advanced_check'] && self::_is_fake_ip($ip, parse_url($url, PHP_URL_HOST)) ) {
			return array(
				'reason' => 'server'
			);
		}

		/* IP im lokalen Spam */
		if ( $options['spam_ip'] && self::_is_db_spam($ip, $url) ) {
			return array(
				'reason' => 'localdb'
			);
		}

		/* DNSBL Spam */
		if ( $options['dnsbl_check'] && self::_is_dnsbl_spam($ip) ) {
			return array(
				'reason' => 'dnsbl'
			);
		}

		/* Country Code prüfen */
		if ( $options['country_code'] && self::_is_country_spam($ip) ) {
			return array(
				'reason' => 'country'
			);
		}
	}


	/**
	* Prüfung den Kommentar
	*
	* @since   2.4
	* @change  2.5.6
	*
	* @param   array  $comment  Daten des Kommentars
	* @return  array            Array mit dem Verdachtsgrund [optional]
	*/

	private static function _verify_comment_request($comment)
	{
		/* IP */
		$ip = self::get_key($_SERVER, 'REMOTE_ADDR');

		/* Kommentarwerte */
		$url = self::get_key($comment, 'comment_author_url');
		$body = self::get_key($comment, 'comment_content');
		$email = self::get_key($comment, 'comment_author_email');
		$author = self::get_key($comment, 'comment_author');

		/* Leere Werte ? */
		if ( empty($body) ) {
			return array(
				'reason' => 'empty'
			);
		}

		/* IP? */
		if ( empty($ip) or (function_exists('filter_var') && !filter_var($ip, FILTER_VALIDATE_IP)) ) {
			return array(
				'reason' => 'empty'
			);
		}

		/* Leere Werte ? */
		if ( get_option('require_name_email') && ( empty($email) OR empty($author) ) ) {
			return array(
				'reason' => 'empty'
			);
		}

		/* Optionen */
		$options = self::get_options();

		/* Bereits kommentiert? */
		if ( $options['already_commented'] && !empty($email) && self::_is_approved_email($email) ) {
			return;
		}

		/* Bot erkannt */
		if ( !empty($_POST['bee_spam']) ) {
			return array(
				'reason' => 'css'
			);
		}

		/* BBCode Spam */
		if ( $options['bbcode_check'] && self::_is_bbcode_spam($body) ) {
			return array(
				'reason' => 'bbcode'
			);
		}

		/* Erweiterter Schutz */
		if ( $options['advanced_check'] && self::_is_fake_ip($ip) ) {
			return array(
				'reason' => 'server'
			);
		}

		/* Regexp für Spam */
		if ( $options['regexp_check'] && self::_is_regexp_spam(
			array(
				'ip'	 => $ip,
				'host'	 => parse_url($url, PHP_URL_HOST),
				'body'	 => $body,
				'email'	 => $email,
				'author' => $author
			)
		) ) {
			return array(
				'reason' => 'regexp'
			);
		}

		/* IP im lokalen Spam */
		if ( $options['spam_ip'] && self::_is_db_spam($ip, $url, $email) ) {
			return array(
				'reason' => 'localdb'
			);
		}

		/* DNSBL Spam */
		if ( $options['dnsbl_check'] && self::_is_dnsbl_spam($ip) ) {
			return array(
				'reason' => 'dnsbl'
			);
		}

		/* Country Code prüfen */
		if ( $options['country_code'] && self::_is_country_spam($ip) ) {
			return array(
				'reason' => 'country'
			);
		}

		/* Translate API */
		if ( $options['translate_api'] && self::_is_lang_spam($body) ) {
			return array(
				'reason' => 'lang'
			);
		}
	}


	/**
	* Anwendung von Regexp, auch benutzerdefiniert
	*
	* @since   2.5.2
	* @change  2.5.6
	*
	* @param   array	$comment  Array mit Kommentardaten
	* @return  boolean       	  TRUE bei verdächtigem Kommentar
	*/

	private static function _is_regexp_spam($comment)
	{
		/* Felder */
		$fields = array(
			'ip',
			'host',
			'body',
			'email',
			'author'
		);

		/* Regexp */
		$patterns = array(
			0 => array(
				'host'	=> '^(www\.)?\d+\w+\.com$',
				'body'	=> '^\w+\s\d+$',
				'email'	=> '@gmail.com$'
			),
			1 => array(
				'body'	=> '\<\!.+?mfunc.+?\>'
			)
		);

		/* Spammy author */
		if ( $quoted_author = preg_quote($comment['author'], '/') ) {
			$patterns[] = array(
				'body' => sprintf(
					'<a.+?>%s<\/a>$',
					$quoted_author
				)
			);
			$patterns[] = array(
				'body' => sprintf(
					'%s https?:.+?$',
					$quoted_author
				)
			);
			$patterns[] = array(
				'email'	 => '@gmail.com$',
				'author' => '^[a-z0-9-\.]+\.[a-z]{2,6}$',
				'host'	 => sprintf(
					'^%s$',
					$quoted_author
				)
			);
		}

		/* Hook */
		$patterns = apply_filters(
			'antispam_bee_patterns',
			$patterns
		);

		/* Leer? */
		if ( ! $patterns ) {
			return false;
		}

		/* Ausdrücke loopen */
		foreach ($patterns as $pattern) {
			$hits = array();

			/* Felder loopen */
			foreach ($pattern as $field => $regexp) {
				/* Empty value? */
				if ( empty($field) OR !in_array($field, $fields) OR empty($regexp) ) {
					continue;
				}

				/* Ignore non utf-8 chars */
				$comment[$field] = ( function_exists('iconv') ? iconv('utf-8', 'utf-8//TRANSLIT', $comment[$field]) : $comment[$field] );

				/* Empty value? */
				if ( empty($comment[$field]) ) {
					continue;
				}

				/* Perform regex */
				if ( preg_match('/' .$regexp. '/isu', $comment[$field]) ) {
					$hits[$field] = true;
				}
			}

			if ( count($hits) === count($pattern) ) {
				return true;
			}
		}

		return false;
	}


	/**
	* Prüfung eines Kommentars auf seine Existenz im lokalen Spam
	*
	* @since   2.0.0
	* @change  2.5.4
	*
	* @param   string	$ip     Kommentar-IP
	* @param   string	$url    Kommentar-URL [optional]
	* @param   string	$email  Kommentar-Email [optional]
	* @return  boolean          TRUE bei verdächtigem Kommentar
	*/

	private static function _is_db_spam($ip, $url = '', $email = '')
	{
		/* Global */
		global $wpdb;

		/* Default */
		$filter = array('`comment_author_IP` = %s');
		$params = array($ip);

		/* URL abgleichen */
		if ( ! empty($url) ) {
			$filter[] = '`comment_author_url` = %s';
			$params[] = $url;
		}

		/* E-Mail abgleichen */
		if ( ! empty($email) ) {
			$filter[] = '`comment_author_email` = %s';
			$params[] = $email;
		}

		/* Query ausführen */
		$result = $wpdb->get_var(
			$wpdb->prepare(
				sprintf(
					"SELECT `comment_ID` FROM `$wpdb->comments` WHERE `comment_approved` = 'spam' AND (%s) LIMIT 1",
					implode(' OR ', $filter)
				),
				$params
			)
		);

		return !empty($result);
	}


	/**
	* Prüfung auf erlaubten Ländercodes
	*
	* @since   0.1
	* @change  2.5.1
	*
	* @param   string	$ip  IP-Adresse
	* @return  boolean       TRUE bei unerwünschten Ländercodes
	*/

	private static function _is_country_spam($ip)
	{
		/* Optionen */
		$options = self::get_options();

		/* White & Black */
		$white = preg_split(
			'/ /',
			$options['country_white'],
			-1,
			PREG_SPLIT_NO_EMPTY
		);
		$black = preg_split(
			'/ /',
			$options['country_black'],
			-1,
			PREG_SPLIT_NO_EMPTY
		);

		/* Leere Listen? */
		if ( empty($white) && empty($black) ) {
			return false;
		}

		/* IP abfragen */
		$response = wp_remote_get(
			esc_url_raw(
				sprintf(
					'https://geoip.maxmind.com/a?l=%s&i=%s',
					strrev('1Lbn0ZsL08e1'),
					self::_anonymize_ip($ip)
				),
				'https'
			)
		);

		/* Fehler? */
		if ( is_wp_error($response) ) {
			return false;
		}

		/* Land auslesen */
		$country = wp_remote_retrieve_body($response);

		/* Kein Land? */
		if ( empty($country) ) {
			return false;
		}

		/* Blacklist */
		if ( !empty($black) ) {
			return ( in_array($country, $black) ? true : false );
		}

		/* Whitelist */
		return ( in_array($country, $white) ? false : true );
	}


	/**
	* Prüfung auf DNSBL Spam
	*
	* @since   2.4.5
	* @change  2.4.5
	*
	* @param   string   $ip  IP-Adresse
	* @return  boolean       TRUE bei gemeldeter IP
	*/

	private static function _is_dnsbl_spam($ip)
	{
		/* Start request */
		$response = wp_remote_get(
			esc_url_raw(
				sprintf(
					'http://www.stopforumspam.com/api?ip=%s&f=json',
					$ip
				),
				'http'
			)
		);

		/* Response error? */
		if ( is_wp_error($response) ) {
			return false;
		}

		/* Get JSON */
		$json = wp_remote_retrieve_body($response);

		/* Decode JSON */
		$result = json_decode($json);

		/* Empty data */
		if ( empty($result->success) ) {
			return false;
		}

		/* Return status */
		return (bool) $result->ip->appears;
	}


	/**
	* Prüfung auf BBCode Spam
	*
	* @since   2.5.1
	* @change  2.5.1
	*
	* @param   string   $body  Inhalt eines Kommentars
	* @return  boolean         TRUE bei BBCode im Inhalt
	*/

	private static function _is_bbcode_spam($body)
	{
		return (bool) preg_match('/\[url[=\]].*\[\/url\]/is', $body);
	}


	/**
	* Prüfung auf eine bereits freigegebene E-Mail-Adresse
	*
	* @since   2.0
	* @change  2.5.1
	*
	* @param   string   $email  E-Mail-Adresse
	* @return  boolean          TRUE bei einem gefundenen Eintrag
	*/

	private static function _is_approved_email($email)
	{
		/* Global */
		global $wpdb;

		/* Suchen */
		$result = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT `comment_ID` FROM `$wpdb->comments` WHERE `comment_approved` = '1' AND `comment_author_email` = %s LIMIT 1",
				(string)$email
			)
		);

		/* Gefunden? */
		if ( $result ) {
			return true;
		}

		return false;
	}


	/**
	* Prüfung auf eine gefälschte IP
	*
	* @since   2.0
	* @change  2.5.1
	*
	* @param   string   $ip    IP-Adresse
	* @param   string   $host  Host [optional]
	* @return  boolean         TRUE bei gefälschter IP
	*/

	private static function _is_fake_ip($ip, $host = false)
	{
		/* Remote Host */
		$hostbyip = gethostbyaddr($ip);

		/* IPv6 */
		if ( !self::_is_ipv4($ip) ) {
			return $ip != $hostbyip;
		}

		/* IPv4 / Kommentar */
		if ( empty($host) ) {
			$found = strpos(
				$ip,
				self::_cut_ip(
					gethostbyname($hostbyip)
				)
			);

		/* IPv4 / Trackback */
		} else {
			/* IP-Vergleich */
			if ( $hostbyip == $ip ) {
				return true;
			}

			/* Treffer suchen */
			$found = strpos(
				$ip,
				self::_cut_ip(
					gethostbyname($host)
				)
			);
		}

		return $found === false;
	}


	/**
	* Prüfung auf unerwünschte Sprachen
	*
	* @since   2.0
	* @change  2.4.2
	*
	* @param   string   $content  Inhalt des Kommentars
	* @return  boolean 	          TRUE bei Spam
	*/

	private static function _is_lang_spam($content)
	{
		/* Init */
		$lang = self::get_option('translate_lang');

		/* Formatieren */
		$content = wp_strip_all_tags($content);

		/* Keine Daten? */
		if ( empty($lang) or empty($content) ) {
			return false;
		}

		/* Formatieren */
		$content = rawurlencode(
			( function_exists('mb_substr') ? mb_substr($content, 0, 200) : substr($content, 0, 200) )
		);

		/* IP abfragen */
		$response = wp_remote_get(
			esc_url_raw(
				sprintf(
					'http://translate.google.com/translate_a/t?client=x&text=%s',
					$content
				),
				'http'
			)
		);

		/* Fehler? */
		if ( is_wp_error($response) ) {
			return false;
		}

		/* Parsen */
		preg_match(
			'/"src":"(\\D{2})"/',
			wp_remote_retrieve_body($response),
			$matches
		);

		/* Fehler? */
		if ( empty($matches[1]) ) {
			return false;
		}

		return ( strtolower($matches[1]) != $lang );
	}


	/**
	* Kürzung der IP-Adressen
	*
	* @since   0.1
	* @change  2.5.1
	*
	* @param   string   $ip       Original IP
	* @param   boolean  $cut_end  Kürzen vom Ende?
	* @return  string             Gekürzte IP
	*/

	private static function _cut_ip($ip, $cut_end = true)
	{
		/* Trenner */
		$separator = ( self::_is_ipv4($ip) ? '.' : ':' );

		return str_replace(
			( $cut_end ? strrchr( $ip, $separator) : strstr( $ip, $separator) ),
			'',
			$ip
		);
	}


	/**
	* Anonymisierung der IP-Adressen
	*
	* @since   2.5.1
	* @change  2.5.1
	*
	* @param   string  $ip  Original IP
	* @return  string       Anonyme IP
	*/

	private static function _anonymize_ip($ip)
	{
		if ( self::_is_ipv4($ip) ) {
			return self::_cut_ip($ip). '.0';
		}

		return self::_cut_ip($ip, false). ':0:0:0:0:0:0:0';
	}


	/**
	* Dreht die IP-Adresse
	*
	* @since   2.4.5
	* @change  2.4.5
	*
	* @param   string   $ip  IP-Adresse
	* @return  string        Gedrehte IP-Adresse
	*/

	private static function _reverse_ip($ip)
	{
		return implode(
			'.',
			array_reverse(
				explode(
					'.',
					$ip
				)
			)
		);
	}


	/**
	* Prüfung auf eine IPv4-Adresse
	*
	* @since   2.4
	* @change  2.4
	*
	* @param   string   $ip  Zu prüfende IP
	* @return  integer       Anzahl der Treffer
	*/

	private static function _is_ipv4($ip)
	{
		return preg_match('/^\d{1,3}(\.\d{1,3}){3,3}$/', $ip);
	}


	/**
	* Prüfung auf Mobile
	*
	* @since   0.1
	* @change  2.4
	*
	* @return  boolean  TRUE, wenn "wptouch" aktiv ist
	*/

	private static function _is_mobile()
	{
		return strpos(TEMPLATEPATH, 'wptouch');
	}




	############################
	#####  SPAM-BEHANDLUNG  ####
	############################


	/**
	* Ausführung des Lösch-/Markier-Vorgangs
	*
	* @since   0.1
	* @change  2.6.0
	*
	* @param   array    $comment  Unbehandelte Kommentardaten
	* @param   string   $reason   Verdachtsgrund
	* @param   boolean  $is_ping  Ping (ja oder nein) [optional]
	* @return  array    $comment  Behandelte Kommentardaten
	*/

	private static function _handle_spam_request($comment, $reason, $is_ping = false)
	{
		/* Optionen */
		$options = self::get_options();

		/* Einstellungen */
		$spam_remove = !$options['flag_spam'];
		$spam_notice = !$options['no_notice'];

		/* Filter-Einstellungen */
		$ignore_filter = $options['ignore_filter'];
		$ignore_type = $options['ignore_type'];
		$ignore_reason = in_array($reason, (array)$options['ignore_reasons']);

		/* Spam merken */
		self::_update_spam_log($comment);
		self::_update_spam_count();
		self::_update_daily_stats();

		/* Spam löschen */
		if ( $spam_remove ) {
			self::_go_in_peace();
		}

		/* Typen behandeln */
		if ( $ignore_filter && (( $ignore_type == 1 && $is_ping ) or ( $ignore_type == 2 && !$is_ping )) ) {
			self::_go_in_peace();
		}

		/* Spamgrund */
		if ( $ignore_reason ) {
			self::_go_in_peace();
		}

		/* Spam-Grund */
		self::$_reason = $reason;

		/* Spam markieren */
		add_filter(
			'pre_comment_approved',
			create_function(
				'',
				'return "spam";'
			)
		);

		/* E-Mail senden */
		add_filter(
			'trackback_post',
			array(
				__CLASS__,
				'send_mail_notification'
			)
		);
		add_filter(
			'comment_post',
			array(
				__CLASS__,
				'send_mail_notification'
			)
		);

		/* Spam reason as comment meta */
		if ( $spam_notice ) {
			add_filter(
				'comment_post',
				array(
					__CLASS__,
					'add_spam_reason_to_comment'
				)
			);
		}

		return $comment;
	}


	/**
	* Logfile mit erkanntem Spam
	*
	* @since   2.5.7
	* @change  2.5.7
	*
	* @param   array   $comment  Array mit Kommentardaten
	* @return  mixed   			 FALSE im Fehlerfall
	*/

	private static function _update_spam_log($comment)
	{
		/* Skip logfile? */
		if ( ! defined('ANTISPAM_BEE_LOG_FILE') OR ! ANTISPAM_BEE_LOG_FILE OR ! is_writable(ANTISPAM_BEE_LOG_FILE) ) {
			return false;
		}

		/* Compose entry */
		$entry = sprintf(
			'%s comment for post=%d from host=%s marked as spam%s',
			current_time('mysql'),
			$comment['comment_post_ID'],
			self::get_key($_SERVER, 'REMOTE_ADDR'),
			PHP_EOL
		);

		/* Write */
		file_put_contents(
			ANTISPAM_BEE_LOG_FILE,
			$entry,
			FILE_APPEND | LOCK_EX
		);
	}


	/**
	* Sendet den 403-Header und beendet die Verbindung
	*
	* @since   2.5.6
	* @change  2.5.6
	*/

	private static function _go_in_peace()
	{
		status_header(403);
		die('Spam deleted.');
	}


	/**
	* Add spam reason as comment data
	*
	* @since   2.6.0
	* @change  2.6.0
	*
	* @param   integer  $comment_id  Comment ID
	*/

	public static function add_spam_reason_to_comment( $comment_id )
	{
		add_comment_meta(
			$comment_id,
			'antispam_bee_reason',
			self::$_reason
		);
	}


	/**
	* Delete spam reason as comment data
	*
	* @since   2.6.0
	* @change  2.6.0
	*
	* @param   integer  $comment_id  Comment ID
	*/

	public static function delete_spam_reason_by_comment( $comment_id )
	{
		delete_comment_meta(
			$comment_id,
			'antispam_bee_reason'
		);
	}


	/**
	* Versand einer Benachrichtigung via E-Mail
	*
	* @since   0.1
	* @change  2.5.7
	*
	* @hook    string  antispam_bee_notification_subject  Custom subject for notification mails
	*
	* @param   intval  $id  ID des Kommentars
	* @return  intval  $id  ID des Kommentars
	*/

	public static function send_mail_notification($id)
	{
		/* Optionen */
		$options = self::get_options();

		/* Keine Benachrichtigung? */
		if ( !$options['email_notify'] ) {
			return $id;
		}

		/* Kommentar */
		$comment = get_comment($id, ARRAY_A);

		/* Keine Werte? */
		if ( empty($comment) ) {
			return $id;
		}

		/* Parent-Post */
		if ( ! $post = get_post($comment['comment_post_ID']) ) {
			return $id;
		}

		/* Sprache laden */
		self::load_plugin_lang();

		/* Betreff */
		$subject = sprintf(
			'[%s] %s',
			get_bloginfo('name'),
			__('Comment marked as spam', 'antispam_bee')
		);

		/* Content */
		if ( !$content = strip_tags(stripslashes($comment['comment_content'])) ) {
			$content = sprintf(
				'-- %s --',
				__('Content removed by Antispam Bee', 'antispam_bee')
			);
		}

		/* Body */
		$body = sprintf(
			"%s \"%s\"\r\n\r\n",
			__('New spam comment on your post', 'antispam_bee'),
			strip_tags($post->post_title)
		).sprintf(
			"%s: %s\r\n",
			__('Author'),
			( empty($comment['comment_author']) ? '' : strip_tags($comment['comment_author']) )
		).sprintf(
			"URL: %s\r\n",
			esc_url($comment['comment_author_url']) /* empty check exists */
		).sprintf(
			"%s: %s\r\n",
			__('Type', 'antispam_bee'),
			__( ( empty($comment['comment_type']) ? 'Comment' : 'Trackback' ), 'antispam_bee' )
		).sprintf(
			"Whois: http://whois.arin.net/rest/ip/%s\r\n",
			$comment['comment_author_IP']
		).sprintf(
			"%s: %s\r\n\r\n",
			__('Spam Reason', 'antispam_bee'),
			__(self::$defaults['reasons'][self::$_reason], 'antispam_bee')
		).sprintf(
			"%s\r\n\r\n\r\n",
			$content
		).(
			EMPTY_TRASH_DAYS ? (
				sprintf(
					"%s: %s\r\n",
					__('Trash it', 'antispam_bee'),
					admin_url('comment.php?action=trash&c=' .$id)
				)
			) : (
				sprintf(
					"%s: %s\r\n",
					__('Delete it', 'antispam_bee'),
					admin_url('comment.php?action=delete&c=' .$id)
				)
			)
		).sprintf(
				"%s: %s\r\n",
			__('Approve it', 'antispam_bee'),
			admin_url('comment.php?action=approve&c=' .$id)
		).sprintf(
			"%s: %s\r\n\r\n",
			__('Spam list', 'antispam_bee'),
			admin_url('edit-comments.php?comment_status=spam')
		).sprintf(
			"%s\r\n%s\r\n",
			__('Notify message by Antispam Bee', 'antispam_bee'),
			__('http://antispambee.com', 'antispam_bee')
		);

		/* Send */
		wp_mail(
			get_bloginfo('admin_email'),
			apply_filters(
				'antispam_bee_notification_subject',
				$subject
			),
			$body
		);

		return $id;
	}




	############################
	#######  STATISTIK  ########
	############################


	/**
	* Rückgabe der Anzahl von Spam-Kommentaren
	*
	* @since   0.1
	* @change  2.4
	*
	* @param   intval  $count  Anzahl der Spam-Kommentare
	*/

	private static function _get_spam_count()
	{
		/* Init */
		$count = self::get_option('spam_count');

		/* Fire */
		return ( get_locale() == 'de_DE' ? number_format($count, 0, '', '.') : number_format_i18n($count) );
	}


	/**
	* Ausgabe der Anzahl von Spam-Kommentaren
	*
	* @since   0.1
	* @change  2.4
	*/

	public static function the_spam_count()
	{
		echo esc_html( self::_get_spam_count() );
	}


	/**
	* Aktualisierung der Anzahl von Spam-Kommentaren
	*
	* @since   0.1
	* @change  2.4
	*/

	private static function _update_spam_count()
	{
		self::_update_option(
			'spam_count',
			intval( self::get_option('spam_count') + 1 )
		);
	}


	/**
	* Aktualisierung der Statistik
	*
	* @since   1.9
	* @change  2.4
	*/

	private static function _update_daily_stats()
	{
		/* Init */
		$stats = (array)self::get_option('daily_stats');
		$today = (int)strtotime('today');

		/* Hochzählen */
		if ( array_key_exists($today, $stats) ) {
			$stats[$today] ++;
		} else {
			$stats[$today] = 1;
		}

		/* Sortieren */
		krsort($stats, SORT_NUMERIC);

		/* Speichern */
		self::_update_option(
			'daily_stats',
			array_slice($stats, 0, 31, true)
		);
	}
}


/* Fire */
add_action(
	'plugins_loaded',
	array(
		'Antispam_Bee',
		'init'
	)
);

/* Activation */
register_activation_hook(
	__FILE__,
	array(
		'Antispam_Bee',
		'activate'
	)
);

/* Deactivation */
register_deactivation_hook(
	__FILE__,
	array(
		'Antispam_Bee',
		'deactivate'
	)
);

/* Uninstall */
register_uninstall_hook(
	__FILE__,
	array(
		'Antispam_Bee',
		'uninstall'
	)
);