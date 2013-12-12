<?php


/* Quit */
defined('ABSPATH') OR exit;


/**
* Cachify
*/

final class Cachify {


	/**
	* Plugin-Optionen
	*
	* @since  2.0
	* @var    array
	*/

	private static $options;


	/**
	* Cache-Methode
	*
	* @since  2.0
	* @var    object
	*/

	private static $method;


	/**
	* Method settings
	*
	* @since  2.0.9
	* @var    integer
	*/

	const METHOD_DB = 0;
	const METHOD_APC = 1;
	const METHOD_HDD = 2;
	const METHOD_MMC = 3;


	/**
	* Minify settings
	*
	* @since  2.0.9
	* @var    integer
	*/

	const MINIFY_DISABLED = 0;
	const MINIFY_HTML_ONLY = 1;
	const MINIFY_HTML_JS = 2;


	/**
	* Pseudo-Konstruktor der Klasse
	*
	* @since   2.0.5
	* @change  2.0.5
	*/

	public static function instance()
	{
		new self();
	}


	/**
	* Konstruktor der Klasse
	*
	* @since   1.0.0
	* @change  2.1.2
	*/

	public function __construct()
	{
		/* Filter */
		if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) OR ( defined('DONOTCACHEPAGE') && DONOTCACHEPAGE ) ) {
			return;
		}

		/* Set defaults */
		self::_set_defaults();

		/* Publish-Hooks */
		self::_publish_hooks();

		/* Flush Hook */
		add_action(
			'cachify_flush_cache',
			array(
				__CLASS__,
				'flush_cache'
			)
		);
		add_action(
			'_core_updated_successfully',
			array(
				__CLASS__,
				'flush_cache'
			)
		);
		add_action(
			'switch_theme',
			array(
				__CLASS__,
				'flush_cache'
			)
		);

		/* Backend */
		if ( is_admin() ) {
			add_action(
				'wpmu_new_blog',
				array(
					__CLASS__,
					'install_later'
				)
			);
			add_action(
				'delete_blog',
				array(
					__CLASS__,
					'uninstall_later'
				)
			);

			add_action(
				'admin_init',
				array(
					__CLASS__,
					'register_settings'
				)
			);
			add_action(
				'admin_init',
				array(
					__CLASS__,
					'receive_flush'
				)
			);
			add_action(
				'admin_menu',
				array(
					__CLASS__,
					'add_page'
				)
			);
			add_action(
				'admin_enqueue_scripts',
				array(
					__CLASS__,
					'add_css'
				)
			);

			add_action(
				'transition_comment_status',
				array(
					__CLASS__,
					'touch_comment'
				),
				10,
				3
			);
			add_action(
				'edit_comment',
				array(
					__CLASS__,
					'edit_comment'
				)
			);
			add_action(
				'admin_bar_menu',
				array(
					__CLASS__,
					'add_menu'
				),
				90
			);
			add_action(
				'dashboard_glance_items',
				array(
					__CLASS__,
					'add_count'
				)
			);

			add_filter(
				'plugin_row_meta',
				array(
					__CLASS__,
					'row_meta'
				),
				10,
				2
			);
			add_filter(
				'plugin_action_links_' .CACHIFY_BASE,
				array(
					__CLASS__,
					'action_links'
				)
			);

		/* Frontend */
		} else {
			add_action(
				'pre_comment_approved',
				array(
					__CLASS__,
					'pre_comment'
				),
				99,
				2
			);
			add_action(
				'template_redirect',
				array(
					__CLASS__,
					'manage_cache'
				),
				0
			);
			add_action(
				'robots_txt',
				array(
					__CLASS__,
					'robots_txt'
				)
			);
		}
	}


	/**
	* Deactivation hook
	*
	* @since   2.1.0
	* @change  2.1.0
	*/

	public static function on_deactivation()
	{
		self::flush_cache();
	}


	/**
	* Activation hook
	*
	* @since   1.0
	* @change  2.1.0
	*/

	public static function on_activation()
	{
		/* Multisite & Network */
		if ( is_multisite() && ! empty($_GET['networkwide']) ) {
			/* Blog-IDs */
			$ids = self::_get_blog_ids();

			/* Loopen */
			foreach ($ids as $id) {
				switch_to_blog( (int)$id );
				self::_install_backend();
			}

			/* Wechsel zurück */
			restore_current_blog();

		} else {
			self::_install_backend();
		}
	}


	/**
	* Plugin-Installation bei neuen MU-Blogs
	*
	* @since   1.0
	* @change  1.0
	*/

	public static function install_later($id) {
		/* Kein Netzwerk-Plugin */
		if ( ! is_plugin_active_for_network(CACHIFY_BASE) ) {
			return;
		}

		/* Wechsel */
		switch_to_blog( (int)$id );

		/* Installieren */
		self::_install_backend();

		/* Wechsel zurück */
		restore_current_blog();
	}


	/**
	* Eigentliche Installation der Optionen
	*
	* @since   1.0
	* @change  2.0
	*/

	private static function _install_backend()
	{
		add_option(
			'cachify',
			array()
		);

		/* Flush */
		self::flush_cache();
	}


	/**
	* Deinstallation des Plugins pro MU-Blog
	*
	* @since   1.0
	* @change  2.1.0
	*/

	public static function on_uninstall()
	{
		/* Global */
		global $wpdb;

		/* Multisite & Network */
		if ( is_multisite() && !empty($_GET['networkwide']) ) {
			/* Alter Blog */
			$old = $wpdb->blogid;

			/* Blog-IDs */
			$ids = self::_get_blog_ids();

			/* Loopen */
			foreach ($ids as $id) {
				switch_to_blog($id);
				self::_uninstall_backend();
			}

			/* Wechsel zurück */
			switch_to_blog($old);
		} else {
			self::_uninstall_backend();
		}
	}


	/**
	* Deinstallation des Plugins bei MU & Network
	*
	* @since   1.0
	* @change  1.0
	*/

	public static function uninstall_later($id)
	{
		/* Kein Netzwerk-Plugin */
		if ( ! is_plugin_active_for_network(CACHIFY_BASE) ) {
			return;
		}

		/* Wechsel */
		switch_to_blog( (int)$id );

		/* Installieren */
		self::_uninstall_backend();

		/* Wechsel zurück */
		restore_current_blog();
	}


	/**
	* Eigentliche Deinstallation des Plugins
	*
	* @since   1.0
	* @change  1.0
	*/

	private static function _uninstall_backend()
	{
		/* Option */
		delete_option('cachify');

		/* Cache leeren */
		self::flush_cache();
	}


	/**
	* Rückgabe der IDs installierter Blogs
	*
	* @since   1.0
	* @change  1.0
	*
	* @return  array  Blog-IDs
	*/

	private static function _get_blog_ids()
	{
		/* Global */
		global $wpdb;

		return $wpdb->get_col("SELECT blog_id FROM `$wpdb->blogs`");
	}


	/**
	* Eigenschaften des Objekts
	*
	* @since   2.0
	* @change  2.0.7
	*/

	private static function _set_defaults()
	{
		/* Optionen */
		self::$options = self::_get_options();

		/* APC */
		if ( self::$options['use_apc'] === self::METHOD_APC && Cachify_APC::is_available() ) {
			self::$method = new Cachify_APC;

		/* HDD */
		} else if ( self::$options['use_apc'] === self::METHOD_HDD && Cachify_HDD::is_available() ) {
			self::$method = new Cachify_HDD;

		/* MEMCACHED */
		} else if ( self::$options['use_apc'] === self::METHOD_MMC && Cachify_MEMCACHED::is_available() ) {
			self::$method = new Cachify_MEMCACHED;

		/* DB */
		} else {
			self::$method = new Cachify_DB;
		}
	}


	/**
	* Generierung von Publish-Hooks für Custom Post Types
	*
	* @since   2.0.3
	* @change  2.0.3
	*/

	private static function _publish_hooks() {
		/* Verfügbare CPT */
		$available_cpt = get_post_types(
			array('public' => true)
		);

		/* Leer? */
		if ( empty($available_cpt) ) {
			return;
		}

		/* Loopen */
		foreach ( $available_cpt as $cpt ) {
			add_action(
				'publish_' .$cpt,
				array(
					__CLASS__,
					'publish_cpt'
				),
				10,
				2
			);
			add_action(
				'publish_future_' .$cpt,
				array(
					__CLASS__,
					'publish_cpt'
				)
			);
		}
	}


	/**
	* Rückgabe der Optionen
	*
	* @since   2.0
	* @change  2.1.2
	*
	* @return  array  $diff  Array mit Werten
	*/

	private static function _get_options()
	{
		return wp_parse_args(
			get_option('cachify'),
			array(
				'only_guests'	 	=> 1,
				'compress_html'	 	=> self::MINIFY_DISABLED,
				'cache_expires'	 	=> 12,
				'without_ids'	 	=> '',
				'without_agents' 	=> '',
				'use_apc'		 	=> self::METHOD_DB,
				'reset_on_comment'  => 0
			)
		);
	}


	/**
	* Hinzufügen der Action-Links
	*
	* @since   1.0
	* @change  2.0.2
	*
	* @param   string  $data  Ursprungsinhalt der dynamischen robots.txt
	* @return  string  $data  Modifizierter Inhalt der robots.txt
	*/

	public static function robots_txt($data)
	{
		/* HDD only */
		if ( self::$options['use_apc'] !== self::METHOD_HDD ) {
			return $data;
		}

		/* Pfad */
		$path = parse_url(site_url(), PHP_URL_PATH);

		/* Ausgabe */
		$data .= sprintf(
			'Disallow: %s/wp-content/cache/%s',
			( empty($path) ? '' : $path ),
			"\n"
		);

		return $data;
	}


	/**
	* Hinzufügen der Action-Links
	*
	* @since   1.0
	* @change  1.0
	*
	* @param   array  $data  Bereits existente Links
	* @return  array  $data  Erweitertes Array mit Links
	*/

	public static function action_links($data)
	{
		/* Rechte? */
		if ( ! current_user_can('manage_options') ) {
			return $data;
		}

		return array_merge(
			$data,
			array(
				sprintf(
					'<a href="%s">%s</a>',
					add_query_arg(
						array(
							'page' => 'cachify'
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
	* @since   0.5
	* @change  2.0.5
	*
	* @param   array   $input  Bereits vorhandene Links
	* @param   string  $page   Aktuelle Seite
	* @return  array   $data   Modifizierte Links
	*/

	public static function row_meta($input, $page)
	{
		/* Rechte */
		if ( $page != CACHIFY_BASE ) {
			return $input;
		}

		return array_merge(
			$input,
			array(
				'<a href="https://flattr.com/t/1327625" target="_blank">Flattr</a>',
				'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=5RDDW9FEHGLG6" target="_blank">PayPal</a>'
			)
		);
	}


	/**
	* Hinzufügen eines Admin-Bar-Menüs
	*
	* @since   1.2
	* @change  2.1.2
	*
	* @param   object  Objekt mit Menü-Eigenschaften
	*/

	public static function add_menu($wp_admin_bar)
	{
		/* Aussteigen */
		if ( ! is_admin_bar_showing() OR ! is_super_admin() ) {
			return;
		}

		/* Hinzufügen */
		$wp_admin_bar->add_menu(
			array(
				'id' 	 => 'cachify',
				'href'   => add_query_arg('_cachify', 'flush'),
				'parent' => 'top-secondary',
				'title'	 => '<span class="ab-icon dashicons dashicons-trash" style="top:2px;margin:0"></span>',
				'meta'   => array( 'title' => 'Cachify-Cache leeren' )
			)
		);
	}


	/**
	* Anzeige des Spam-Counters auf dem Dashboard
	*
	* @since   2.0.0
	* @change  2.1.2
	*/

	public static function add_count()
	{
		/* Cache size */
		$size = self::get_cache_size();

		/* Caching method */
		$method = call_user_func(
			array(
				self::$method,
				'stringify‎_method'
			)
		);

		/* Print the link */
		echo sprintf(
			'<li>
				<a href="%s" class="cachify-icon cachify-icon--%s" title="Caching-Methode: %s">
					%s Cache
				</a>
			</li>',
			add_query_arg(
				array(
					'page' => 'cachify'
				),
				admin_url('options-general.php')
			),
			esc_attr(strtolower($method)),
			esc_attr($method),
			( empty($size) ? 'Leerer' : size_format($size) )
		);
	}


	/**
	* Rückgabe der Cache-Größe
	*
	* @since   2.0.6
	* @change  2.0.6
	*
	* @param   integer  $size  Cache-Größe in Bytes
	*/

	public static function get_cache_size()
	{
		if ( ! $size = get_transient('cachify_cache_size') ) {
			/* Auslesen */
			$size = (int) call_user_func(
				array(
					self::$method,
					'get_stats'
				)
			);

			/* Speichern */
			set_transient(
			  'cachify_cache_size',
			  $size,
			  60 * 15
			);
		}

		return $size;
	}


	/**
	* Verarbeitung der Plugin-Meta-Aktionen
	*
	* @since   0.5
	* @change  1.2
	*
	* @param   array  $data  Metadaten der Plugins
	*/

	public static function receive_flush($data)
	{
		/* Leer? */
		if ( empty($_GET['_cachify']) or $_GET['_cachify'] !== 'flush' ) {
			return;
		}

		/* Global */
		global $wpdb;

		/* Multisite & Network */
		if ( is_multisite() && is_plugin_active_for_network(CACHIFY_BASE) ) {
			/* Alter Blog */
			$old = $wpdb->blogid;

			/* Blog-IDs */
			$ids = self::_get_blog_ids();

			/* Loopen */
			foreach ($ids as $id) {
				switch_to_blog($id);
				self::flush_cache();
			}

			/* Wechsel zurück */
			switch_to_blog($old);

			/* Notiz */
			add_action(
				'network_admin_notices',
				array(
					__CLASS__,
					'flush_notice'
				)
			);
		} else {
			/* Leeren */
			self::flush_cache();

			/* Notiz */
			add_action(
				'admin_notices',
				array(
					__CLASS__,
					'flush_notice'
				)
			);
		}
	}


	/**
	* Hinweis nach erfolgreichem Cache-Leeren
	*
	* @since   1.2
	* @change  1.2
	*/

	public static function flush_notice()
	{
		/* Kein Admin */
		if ( ! is_super_admin() ) {
			return false;
		}

		echo '<div id="message" class="updated"><p><strong>Cachify-Cache geleert.</strong></p></div>';
	}


	/**
	* Löschung des Cache beim Kommentar-Editieren
	*
	* @since   0.1.0
	* @change  2.1.2
	*
	* @param   integer  $id  ID des Kommentars
	*/

	public static function edit_comment($id)
	{
		if ( self::$options['reset_on_comment'] ) {
			self::flush_cache();
		} else {
			self::_delete_cache(
				get_permalink(
					get_comment($id)->comment_post_ID
				)
			);
		}
	}


	/**
	* Löschung des Cache beim neuen Kommentar
	*
	* @since   0.1.0
	* @change  2.1.2
	*
	* @param   mixed  $approved  Kommentar-Status
	* @param   array  $comment   Array mit Eigenschaften
	* @return  mixed  $approved  Kommentar-Status
	*/

	public static function pre_comment($approved, $comment)
	{
		/* Approved comment? */
		if ( $approved === 1 ) {
			if ( self::$options['reset_on_comment'] ) {
				self::flush_cache();
			} else {
				self::_delete_cache(
					get_permalink($comment['comment_post_ID'])
				);
			}
		}

		return $approved;
	}


	/**
	* Löschung des Cache beim Editieren der Kommentare
	*
	* @since   0.1
	* @change  2.1.2
	*
	* @param   string  $new_status  Neuer Status
	* @param   string  $old_status  Alter Status
	* @param   object  $comment     Array mit Eigenschaften
	*/

	public static function touch_comment($new_status, $old_status, $comment)
	{
		if ( $new_status != $old_status ) {
			if ( self::$options['reset_on_comment'] ) {
				self::flush_cache();
			} else {
				self::_delete_cache(
					get_permalink($comment->comment_post_ID)
				);
			}
		}
	}


	/**
	* Leerung des Cache bei neuen CPTs
	*
	* @since   2.0.3
	* @change  2.0.3
	*
	* @param   integer  $id    PostID
	* @param   object   $post  Object mit CPT-Metadaten [optional]
	*/

	public static function publish_cpt($id, $post = false)
	{
		/* Leer? */
		if ( empty($post) ) {
			return;
		}

		/* Status */
		if ( in_array( $post->post_status, array('publish', 'future') ) ) {
			self::flush_cache();
		}
	}


	/**
	* Rückgabe der Cache-Gültigkeit
	*
	* @since   2.0
	* @change  2.0
	*
	* @return  intval    Gültigkeit in Sekunden
	*/

	private static function _cache_expires()
	{
		return 60 * 60 * self::$options['cache_expires'];
	}


	/**
	* Rückgabe des Cache-Hash-Wertes
	*
	* @since   0.1
	* @change  2.0
	*
	* @param   string  $url  URL für den Hash-Wert [optional]
	* @return  string        Cachify-Hash-Wert
	*/

	private static function _cache_hash($url = '')
	{
		return md5(
			empty($url) ? ( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ) : ( parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH) )
		) . '.cachify';
	}


	/**
	* Splittung nach Komma
	*
	* @since   0.9.1
	* @change  1.0
	*
	* @param   string  $input  Zu splittende Zeichenkette
	* @return  array           Konvertierter Array
	*/

	private static function _preg_split($input)
	{
		return (array)preg_split('/,/', $input, -1, PREG_SPLIT_NO_EMPTY);
	}


	/**
	* Prüfung auf Index
	*
	* @since   0.6
	* @change  1.0
	*
	* @return  boolean  TRUE bei Index
	*/

	private static function _is_index()
	{
		return basename($_SERVER['SCRIPT_NAME']) != 'index.php';
	}


	/**
	* Prüfung auf Mobile Devices
	*
	* @since   0.9.1
	* @change  2.0.7
	*
	* @return  boolean  TRUE bei Mobile
	*/

	private static function _is_mobile()
	{
		return ( strpos(TEMPLATEPATH, 'wptouch') or strpos(TEMPLATEPATH, 'carrington') or strpos(TEMPLATEPATH, 'jetpack') or strpos(TEMPLATEPATH, 'handheld') );
	}


	/**
	* Prüfung auf eingeloggte und kommentierte Nutzer
	*
	* @since   2.0.0
	* @change  2.0.5
	*
	* @return  boolean  $diff  TRUE bei "vermerkten" Nutzern
	*/

	private static function _is_logged_in()
	{
		/* Eingeloggt */
		if ( is_user_logged_in() ) {
			return true;
		}

		/* Cookie? */
		if ( empty($_COOKIE) ) {
			return false;
		}

		/* Loopen */
		foreach ( $_COOKIE as $k => $v) {
			if ( preg_match('/^(wp-postpass|wordpress_logged_in|comment_author)_/', $k) ) {
				return true;
			}
		}
	}


	/**
	* Definition der Ausnahmen für den Cache
	*
	* @since   0.2
	* @change  2.1.1
	*
	* @return  boolean  TRUE bei Ausnahmen
	*
	* @hook    boolean  cachify_skip_cache
	*/

	private static function _skip_cache()
	{
		/* No cache hook */
		if ( apply_filters('cachify_skip_cache', false) ) {
			return true;
		}

		/* Conditional Tags */
		if ( self::_is_index() or is_search() or is_404() or is_feed() or is_trackback() or is_robots() or is_preview() or post_password_required() ) {
			return true;
		}

		/* Plugin options */
		$options = self::$options;

		/* Request vars */
		if ( !empty($_POST) OR (!empty($_GET) && get_option('permalink_structure')) ) {
			return true;
		}

		/* Logged in */
		if ( $options['only_guests'] && self::_is_logged_in() ) {
			return true;
		}

		/* Mobile request */
		if ( self::_is_mobile() ) {
			return true;
		}

		/* Post IDs */
		if ( $options['without_ids'] && is_singular() ) {
			if ( in_array( $GLOBALS['wp_query']->get_queried_object_id(), self::_preg_split($options['without_ids']) ) ) {
				return true;
			}
		}

		/* User Agents */
		if ( $options['without_agents'] && isset($_SERVER['HTTP_USER_AGENT']) ) {
			if ( array_filter( self::_preg_split($options['without_agents']), create_function('$a', 'return strpos($_SERVER["HTTP_USER_AGENT"], $a);') ) ) {
				return true;
			}
		}

		return false;
	}


	/**
	* Minimierung des HTML-Codes
	*
	* @since   0.9.2
	* @change  2.0.9
	*
	* @param   string  $data  Zu minimierender Datensatz
	* @return  string  $data  Minimierter Datensatz
	*
	* @hook    array   cachify_minify_ignore_tags
	*/

	private static function _minify_cache($data) {
		/* Disabled? */
		if ( ! self::$options['compress_html'] ) {
			return($data);
		}

		/* Ignore this html tags */
		$ignore_tags = (array)apply_filters(
			'cachify_minify_ignore_tags',
			array(
				'textarea',
				'pre'
			)
		);

		/* Add the script tag */
		if ( self::$options['compress_html'] !== self::MINIFY_HTML_JS ) {
			$ignore_tags[] = 'script';
		}

		/* Empty blacklist? | TODO: Make better */
		if ( ! $ignore_tags ) {
			return($data);
		}

		/* Convert to string */
		$ignore_regex = implode('|', $ignore_tags);

		/* Minify */
		$cleaned = preg_replace(
			array(
				'/<!--[^\[><](.*?)-->/s',
				'#(?ix)(?>[^\S ]\s*|\s{2,})(?=(?:(?:[^<]++|<(?!/?(?:' .$ignore_regex. ')\b))*+)(?:<(?>' .$ignore_regex. ')\b|\z))#'
			),
			array(
				'',
				' '
			),
			(string) $data
		);

		/* Fault */
		if ( strlen($cleaned) <= 1 ) {
			return($data);
		}

		return $cleaned;
	}


	/**
	* Löschung des Cache für eine URL
	*
	* @since   0.1
	* @change  2.0
	*
	* @param  string  $url  URL für den Hash-Wert
	*/

	private static function _delete_cache($url)
	{
		call_user_func(
			array(
				self::$method,
				'delete_item'
			),
			self::_cache_hash($url),
			$url
		);
	}


	/**
	* Zurücksetzen des kompletten Cache
	*
	* @since   0.1
	* @change  2.0
	*/

	public static function flush_cache()
	{
		/* DB */
		Cachify_DB::clear_cache();

		/* APC */
		Cachify_APC::clear_cache();

		/* HDD */
		Cachify_HDD::clear_cache();

		/* MEMCACHED */
		Cachify_MEMCACHED::clear_cache();

		/* Transient */
		delete_transient('cachify_cache_size');
	}


	/**
	* Zuweisung des Cache
	*
	* @since   0.1
	* @change  2.0
	*
	* @param   string  $data  Inhalt der Seite
	* @return  string  $data  Inhalt der Seite
	*/

	public static function set_cache($data)
	{
		/* Leer? */
		if ( empty($data) ) {
			return '';
		}

		/* Speicherung */
		call_user_func(
			array(
				self::$method,
				'store_item'
			),
			self::_cache_hash(),
			self::_minify_cache($data),
			self::_cache_expires()
		);

		return $data;
	}


	/**
	* Verwaltung des Cache
	*
	* @since   0.1
	* @change  2.0
	*/

	public static function manage_cache()
	{
		/* Kein Caching? */
		if ( self::_skip_cache() ) {
			return;
		}

		/* Daten im Cache */
		$cache = call_user_func(
			array(
				self::$method,
				'get_item'
			),
			self::_cache_hash()
		);

		/* Kein Cache? */
		if ( empty($cache) ) {
			ob_start('Cachify::set_cache');
			return;
		}

		/* Cache verarbeiten */
		call_user_func(
			array(
				self::$method,
				'print_cache'
			),
			$cache
		);
	}


	/**
	* Einbindung von CSS
	*
	* @since   1.0
	* @change  2.1.2
	*/

	public static function add_css($hook)
	{
		/* Hook check */
		if ( $hook !== 'index.php' ) {
			return;
		}

		/* Get plugin data */
		$data = get_plugin_data(CACHIFY_FILE);

		/* Register css */
		wp_register_style(
			'cachify_css',
			plugins_url('css/styles.min.css', CACHIFY_FILE),
			array(),
			$data['Version']
		);

		/* Enable css */
		wp_enqueue_style('cachify_css');
	}


	/**
	* Einfügen der Optionsseite
	*
	* @since   1.0
	* @change  2.0.2
	*/

	public static function add_page()
	{
		$page = add_options_page(
			'Cachify',
			'Cachify',
			'manage_options',
			'cachify',
			array(
				__CLASS__,
				'options_page'
			)
		);
	}


	/**
	* Verfügbare Cache-Methoden
	*
	* @since  2.0.0
	* @change 2.0.9
	*
	* @param  array  $methods  Array mit verfügbaren Arten
	*/

	private static function _method_select()
	{
		/* Defaults */
		$methods = array(
			self::METHOD_DB  => 'Datenbank',
			self::METHOD_APC => 'APC',
			self::METHOD_HDD => 'Festplatte',
			self::METHOD_MMC => 'Memcached'
		);

		/* APC */
		if ( ! Cachify_APC::is_available() ) {
			unset($methods[1]);
		}

		/* Memcached? */
		if ( ! Cachify_MEMCACHED::is_available() ) {
			unset($methods[3]);
		}

		/* HDD */
		if ( ! Cachify_HDD::is_available() ) {
			unset($methods[2]);
		}

		return $methods;
	}


	private static function _minify_select()
	{
		return array(
			self::MINIFY_DISABLED  => 'Keine',
			self::MINIFY_HTML_ONLY => 'HTML',
			self::MINIFY_HTML_JS   => 'HTML und JavaScript'
		);
	}


	/**
	* Registrierung der Settings
	*
	* @since   1.0
	* @change  1.0
	*/

	public static function register_settings()
	{
		register_setting(
			'cachify',
			'cachify',
			array(
				__CLASS__,
				'validate_options'
			)
		);
	}


	/**
	* Valisierung der Optionsseite
	*
	* @since   1.0.0
	* @change  2.1.2
	*
	* @param   array  $data  Array mit Formularwerten
	* @return  array         Array mit geprüften Werten
	*/

	public static function validate_options($data)
	{
		/* Cache leeren */
		self::flush_cache();

		/* Hinweis */
		if ( self::$options['use_apc'] != $data['use_apc'] && $data['use_apc'] >= self::METHOD_APC ) {
			add_settings_error(
				'cachify_method_tip',
				'cachify_method_tip',
				'Die Server-Konfigurationsdatei (z.B. .htaccess) muss jetzt erweitert werden [<a href="http://playground.ebiene.de/cachify-wordpress-cache/" target="_blank">?</a>]',
				'updated'
			);
		}

		/* Rückgabe */
		return array(
			'only_guests'	 	=> (int)(!empty($data['only_guests'])),
			'compress_html'	 	=> (int)$data['compress_html'],
			'cache_expires'	 	=> (int)(@$data['cache_expires']),
			'without_ids'	 	=> (string)sanitize_text_field(@$data['without_ids']),
			'without_agents' 	=> (string)sanitize_text_field(@$data['without_agents']),
			'use_apc'	 	 	=> (int)$data['use_apc'],
			'reset_on_comment'  => (int)(!empty($data['reset_on_comment']))
		);
	}


	/**
	* Darstellung der Optionsseite
	*
	* @since   1.0
	* @change  2.1.2
	*/

	public static function options_page()
	{ ?>
		<style>
			#cachify_settings input[type="text"],
			#cachify_settings input[type="number"] {
				height: 30px;
			}
		</style>

		<div class="wrap" id="cachify_settings">
			<h2>
				Cachify
			</h2>

			<form method="post" action="options.php">
				<?php settings_fields('cachify') ?>

				<?php $options = self::_get_options() ?>

				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							Cache-Aufbewahrungsort
						</th>
						<td>
							<label for="cachify_cache_method">
								<select name="cachify[use_apc]" id="cachify_cache_method">
									<?php foreach( self::_method_select() as $k => $v ) { ?>
										<option value="<?php echo esc_attr($k) ?>" <?php selected($options['use_apc'], $k); ?>><?php echo esc_html($v) ?></option>
									<?php } ?>
								</select>
							</label>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							Cache-Gültigkeit
						</th>
						<td>
							<label for="cachify_cache_expires">
								<input type="number" min="0" step="1" name="cachify[cache_expires]" id="cachify_cache_expires" value="<?php echo $options['cache_expires'] ?>" class="small-text" />
								Stunden
							</label>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							Cache-Generierung
						</th>
						<td>
							<fieldset>
								<label for="cachify_only_guests">
									<input type="checkbox" name="cachify[only_guests]" id="cachify_only_guests" value="1" <?php checked('1', $options['only_guests']); ?> />
									Kein Cache-Aufbau durch eingeloggte Nutzer
								</label>

								<br />

								<label for="cachify_reset_on_comment">
									<input type="checkbox" name="cachify[reset_on_comment]" id="cachify_reset_on_comment" value="1" <?php checked('1', $options['reset_on_comment']); ?> />
									Neue Kommentare leeren den Cache
								</label>
							</fieldset>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							Cache-Ausnahmen
						</th>
						<td>
							<fieldset>
								<label for="cachify_without_ids">
									<input type="text" name="cachify[without_ids]" id="cachify_without_ids" value="<?php echo $options['without_ids'] ?>" />
									Post/Pages-IDs
								</label>

								<br />

								<label for="cachify_without_agents">
									<input type="text" name="cachify[without_agents]" id="cachify_without_agents" value="<?php echo $options['without_agents'] ?>" />
									Browser User-Agents
								</label>
							</fieldset>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							Quelltext-Minimierung
						</th>
						<td>
							<label for="cachify_compress_html">
								<select name="cachify[compress_html]" id="cachify_compress_html">
									<?php foreach( self::_minify_select() as $k => $v ) { ?>
										<option value="<?php echo esc_attr($k) ?>" <?php selected($options['compress_html'], $k); ?>>
											<?php echo esc_html($v) ?>
										</option>
									<?php } ?>
								</select>
							</label>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
						</th>
						<td>
							<a href="http://playground.ebiene.de/cachify-wordpress-cache/" target="_blank">Dokumentation</a> &bull; <a href="http://playground.ebiene.de/cachify-wordpress-cache/#book" target="_blank">Handbücher</a> &bull; <a href="https://flattr.com/t/1327625" target="_blank">Flattr</a> &bull; <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=5RDDW9FEHGLG6" target="_blank">PayPal</a>
						</td>
					</tr>
				</table>
			</form>
		</div><?php
	}
}