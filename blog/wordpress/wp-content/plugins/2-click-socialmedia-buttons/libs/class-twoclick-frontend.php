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
 * The Frontend Class
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!class_exists('Twoclick_Social_Media_Buttons_Frontend')) {
	class Twoclick_Social_Media_Buttons_Frontend {
		private $var_sOptionsName = 'twoclick_buttons_settings';
		private $var_sPostExcerpt;

		private $array_TwoclickButtonsOptions;

		/**
		 * PHP 5 Constructor
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function __construct() {
			$this->array_TwoclickButtonsOptions = get_option($this->var_sOptionsName);

			if(!is_admin()) {
				/**
				 * Plugin initialisieren
				 *
				 * @since 0.1
				 * @author ppfeufer
				 */
				add_action('init', array(
					$this,
					'_enqueue'
				));

				/**
				 * Sidebarwidget, wenn es angezeigt werden soll
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_sidebar_widget'] == true) {
					require_once(plugin_dir_path(__FILE__) . 'class-twoclick-sidebar-widget.php');
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_sidebar_widget'])

				/**
				 * Wenn die OpenGraph Tags nicht abgeschalten werden sollen, OpenGraph-Klasse laden
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_opengraph_disable'] == false) {
// 					require_once(plugin_dir_path(__FILE__) . 'class-twoclick-opengraph.php');
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_sidebar_widget'])

				/**
				 * Aktionen in den Header des Frontends schreiben
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				add_action('wp_head', array(
					$this,
					'_enqueue_head'
				));

				/**
				 * Aktionen in den Header des Frontends schreiben
				 *
				 * @since 1.1
				 * @author ppfeufer
				 */
// 				add_action('wp_footer', array(
// 					$this,
// 					'_enqueue_footer'
// 				));

				/**
				 * Kurzbeschreibung über den Buttons anzeigen, sofern ausgefüllt
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_introtext'])) {
					add_action('twoclick_intro', array(
						$this,
						'_get_the_intro'
					));
				}

				/**
				 * Buttons an den Content übergeben
				 *
				 * @since 0.1
				 * @author ppfeufer
				 */
				add_filter('the_content', array(
					$this,
					'_get_buttons'
				), 12);
			} // END if(!is_admin())
		} // END function __construct()

		/**
		 * <[ Helper ]>
		 * Das jQuery-Plugin zu Wordpress hinzufügen.
		 * Das CSS zu WordPress hinzufügen.
		 *
		 * Das CSS wird durch einen Filter an WordPress übergeben.
		 * Dieser trägt den Namen 'twoclick-css' und kann beeinflusst werden.
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function _enqueue() {
			if(!is_admin()) {
				// JS File
// 				$var_sJavaScript = plugins_url('/js/social_bookmarks.js', dirname(__FILE__));
				$var_sJavaScript = plugins_url('/js/social_bookmarks-min.js', dirname(__FILE__));

				// CSS File
// 				$var_sCss = apply_filters('twoclick-css', plugins_url('/css/socialshareprivacy.css', dirname(__FILE__)));
				$var_sCss = apply_filters('twoclick-css', plugins_url('/css/socialshareprivacy-min.css', dirname(__FILE__)));

				/**
				 * jQuery Plugin
				 */
				wp_register_script('twoclick-social-media-buttons-jquery', $var_sJavaScript, array(
					'jquery'
				), $this->_get_plugin_version(), true);

				wp_enqueue_script('twoclick-social-media-buttons-jquery');

				/**
				 * CSS
				 */
				wp_register_style('twoclick-social-media-buttons', $var_sCss, '', $this->_get_plugin_version());
				wp_enqueue_style('twoclick-social-media-buttons');
			} // END if(!is_admin())
		} // END function _enqueue()

		/**
		 * <[ Helper ]>
		 * Daten in den <head>-Bereich des HTML vom Frontend schreiben
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function _enqueue_head() {
			/**
			 * OpenGraph-Tags in den <head> des Frontends schreiben, sofern dies gewünscht ist.
			 *
			 * @since 0.7
			 * @author ppfeufer
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_opengraph_disable'] == false) {
				$this->_get_opengraph_tags();
			}

			/**
			 * Custom CSS
			 * Benutzerdefiniertes CSS in den <head> des Frontends einfügen, sofern ausgefüllt.
			 *
			 * @since 1.0
			 * @author ppfeufer
			 */
			if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_custom_css'])) {
				$this->_get_custom_css();
			} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_custom_css']))
		} // END function _enqueue_head()

		/**
		 * <[ Helper ]>
		 * Daten vor das Ende des </html>
		 *
		 * @since 1.1
		 * @author ppfeufer
		 */
// 		function _enqueue_footer() {
// 			echo '<div id="fb-root"></div>';
// 			global $wp_scripts;
// 			echo $wp_scripts->registered['jquery']->ver;
// 			echo '<pre>';
// 			print_r($wp_scripts);
// 			echo '</pre>';
// 		}

		/**
		 * <[ Helper ]>
		 * Benutzerdefiniertes CSS an die Action wp_head übergeben.
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function _get_custom_css() {
			if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_custom_css'])) {
				?>
				<!-- Custom CSS (added by 2-Click Social Media Buttons) -->
				<style type="text/css">
				<?php echo $this->array_TwoclickButtonsOptions['twoclick_buttons_custom_css'] . "\n"; ?>
				</style>
				<!-- /Custom CSS -->
				<?php
			} else {
				return false;
			} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_custom_css']))
		} // END private function _get_custom_css()

		/**
		 * <[ Helper ]>
		 * OpenGraph-Tags an die Action wp_head übergeben.
		 *
		 * @since 0.7
		 * @author ppfeufer
		 */
		private function _get_opengraph_tags() {
			global $post;

			// Nur Einzelartikel
			if(is_feed() || is_trackback() || !is_singular()) {
				return;
			} // END if(is_feed() || is_trackback() || !is_singular())

			$var_sPostThumbnail = $this->_get_article_image();
			if($var_sPostThumbnail) {
				echo "\n" . '<!-- Article Thumbnail -->' . "\n";
				echo sprintf('<link href="%s" rel="image_src" />%s', esc_url($var_sPostThumbnail), "\n");
			}

			/**
			 * Post Excerpt suchen und eventuell setzen, da sonst bei Facebook und G+ nichts steht.
			 * Sollte der Post keinen eigenen Excerpt haben, wird einer aus dem Artikel extrahiert.
			 * Dieser wird dann, ganz Twitterstyle, auf 140 Zeichen begrenzt.
			 */
			if(has_excerpt()) {
				$this->var_sPostExcerpt = $post->post_excerpt;
			} else {
				$this->var_sPostExcerpt = $this->_get_post_excerpt($post->post_content, 400);
			} // END if(has_excerpt())

			/**
			 * Beschreibung und Titel
			 * Hier wird geprüft ob SEO Plugins diese verändert haben.
			 * Berücksichtigt werden wpSEO und All in One SEO Pack
			 *
			 * @since 1.0
			 * @author ppfeufer
			 */
			$var_sTitle = wp_filter_nohtml_kses(get_the_title());
			$var_sDescription = esc_attr($this->var_sPostExcerpt);

			// Title durch wpSEO
			if(class_exists('wpSEO_Base') && (trim(get_post_meta(get_the_ID(), '_wpseo_edit_title', true)))) {
				$var_sTitle = trim(get_post_meta(get_the_ID(), '_wpseo_edit_title', true));
			} // END if(class_exists('wpSEO_Base'))

			// Title durch All in One SEO Pack
			if(function_exists('aiosp_meta') && (trim(get_post_meta(get_the_ID(), '_aioseop_title', true)))) {
				$var_sTitle = trim(get_post_meta(get_the_ID(), '_aioseop_title', true));
			} // END if(function_exists('aiosp_meta'))

			// Beschreibung durch wpSEO
			if(class_exists('wpSEO_Base') && (trim(get_post_meta(get_the_ID(), '_wpseo_edit_description', true)))) {
				$var_sDescription = trim(get_post_meta(get_the_ID(), '_wpseo_edit_description', true));
			} // END if(class_exists('wpSEO_Base'))

			// Bescheibung durch All in One SEO Pack
			if(function_exists('aiosp_meta') && (trim(get_post_meta(get_the_ID(), '_aioseop_description', true)))) {
				$var_sDescription = trim(get_post_meta(get_the_ID(), '_aioseop_description', true));
			} // END if(function_exists('aiosp_meta'))

			/**
			 * OpenGraph-Tags
			 *
			 * @since 0.7
			 */
			echo "\n" . '<!-- OpenGraph Tags (added by 2-Click Social Media Buttons) -->' . "\n";
			echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '"/>' . "\n";
			echo '<meta property="og:locale" content="' . get_locale() . '"/>' . "\n";
			echo '<meta property="og:locale:alternate" content="' . get_locale() . '"/>' . "\n";
			echo '<meta property="og:type" content="article"/>' . "\n";
			echo '<meta property="og:title" content="' . apply_filters('twoclick-opengraph-title', strip_tags($var_sTitle)) . '"/>' . "\n";
			echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '"/>' . "\n";

			if($var_sPostThumbnail) {
				echo '<meta property="og:image" content="' . esc_url($var_sPostThumbnail) . '"/>' . "\n";
			} // END if($var_sPostThumbnail)

			echo '<meta property="og:description" content="' . apply_filters('twoclick-opengraph-description', strip_tags($var_sDescription)) . '"/>' . "\n";
			echo '<!-- /OpenGraph Tags -->' . "\n\n";
		} // END private function _get_opengraph_tags()

		/**
		 * <[ Helper ]>
		 * Returning the current pluginversion
		 *
		 * @author ppfeufer
		 * @since 1.0
		 *
		 * @return string
		 */
		private function _get_plugin_version() {
			$array_PluginData = $this->_get_plugin_data();

			return $array_PluginData['Version'];
		} // END private function _get_plugin_version()

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
		 * Getting an excerpt to use for the buttons
		 *
		 * @since 0.1
		 * @author ppfeufer
		 *
		 * @param string $var_sExcerpt
		 * @param int $var_iMaxLength
		 * @return string
		 */
		private function _get_post_excerpt($var_sExcerpt, $var_iMaxLength) {
			if(function_exists('strip_shortcodes')) {
				$var_sExcerpt = strip_shortcodes($var_sExcerpt);
			} // END if(function_exists('strip_shortcodes'))

			$var_sExcerpt = trim($var_sExcerpt);

			// Now lets strip any tags which dont have balanced ends
			// Need to put NGgallery tags in there - there are a lot of them and they are all different.
			$array_OpenTag = array(
				'[simage',
				'[[CP',
				'[gallery',
				'[imagebrowser',
				'[slideshow',
				'[tags',
				'[albumtags',
				'[singlepic',
				'[album'
			);

			$array_CloseTag = array(
				']',
				']]',
				']',
				']',
				']',
				']',
				']',
				']',
				']'
			);

			foreach(array_keys($array_OpenTag) as $var_sKey) {
				if(preg_match_all('/' . preg_quote($array_OpenTag[$var_sKey]) . '(.*?)' . preg_quote($array_CloseTag[$var_sKey]) . '/i', $var_sExcerpt, $array_Matches)) {
					$var_sExcerpt = str_replace($array_Matches[0], "", $var_sExcerpt);
				} // END if(preg_match_all('/' . preg_quote($array_OpenTag[$var_sKey]) . '(.*?)' . preg_quote($array_CloseTag[$var_sKey]) . '/i', $var_sExcerpt, $array_Matches))
			} // END foreach(array_keys($array_OpenTag) as $var_sKey)

			$var_sExcerpt = preg_replace('#(<wpg.*?>).*?(</wpg2>)#', '$1$2', $var_sExcerpt);

			// Support for qTrans
			if(function_exists('qtrans_use')) {
				global $q_config;

				$var_sExcerpt = qtrans_use($q_config['default_language'], $var_sExcerpt);
			} // END if(function_exists('qtrans_use'))

			$var_sExcerpt = strip_tags($var_sExcerpt);

			// Now lets strip off the youtube stuff.
			preg_match_all('#http://(www.youtube|youtube|[A-Za-z]{2}.youtube)\.com/(watch\?v=|w/\?v=|\?v=)([\w-]+)(.*?)player_embedded#i', $var_sExcerpt, $array_Matches);
			$var_sExcerpt = str_replace($array_Matches[0], '', $var_sExcerpt);

			preg_match_all('#http://(www.youtube|youtube|[A-Za-z]{2}.youtube)\.com/(watch\?v=|w/\?v=|\?v=|embed/)([\w-]+)(.*?)#i', $var_sExcerpt, $array_Matches);
			$var_sExcerpt = str_replace($array_Matches[0], '', $var_sExcerpt);

			if(strlen($var_sExcerpt) > $var_iMaxLength) {
				# If we've got multibyte support then we need to make sure we get the right length - Thanks to Kensuke Akai for the fix
				if(function_exists('mb_strimwidth')) {
					$var_sExcerpt = mb_strimwidth($var_sExcerpt, 0, $var_iMaxLength, ' ...');
				} else {
					$var_sExcerpt = current(explode('SJA26666AJS', wordwrap($var_sExcerpt, $var_iMaxLength, 'SJA26666AJSÄ'))) . ' ...';
				} // END if(function_exists('mb_strimwidth'))
			} // END if(strlen($var_sExcerpt) > $var_iMaxLength)

			return strip_tags($var_sExcerpt);
		} // END private function _get_post_excerpt($var_sExcerpt, $var_iMaxLength)

		/**
		 * <[ Helper ]>
		 * Tweettext einbinden
		 *
		 * @since 0.14
		 * @author ppfeufer
		 */
		private function _get_tweettext() {
			$twitter_hashtags = $this->_get_hashtags();
			$var_sTweettext = '';

			if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'own') {
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'own' && strlen($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_owntext']) == 0) {
					$var_sTweettext = get_the_title(get_the_ID()) . ' » ' . get_bloginfo('name') . $twitter_hashtags;
				} else {
					$var_sTweettext = $this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_owntext'] . $twitter_hashtags;
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'own' && strlen($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_owntext']) == 0)
			} else {
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_default_as'] == 'posttitle-blogtitle') {
					$var_sTweettext = get_the_title(get_the_ID()) . ' » ' . get_bloginfo('name') . $twitter_hashtags;
				} elseif($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_default_as'] == 'posttitle') {
					$var_sTweettext = get_the_title(get_the_ID()) . $twitter_hashtags;
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_default_as'] == 'posttitle-blogtitle')
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'own')

			return $this->_shorten_tweettext(html_entity_decode(wp_filter_nohtml_kses($var_sTweettext), ENT_QUOTES, get_bloginfo('charset')));
		} // END private function _get_tweettext()

		/**
		 * <[ Helper ]>
		 * Tweettext kürzen
		 *
		 * @since 0.14
		 * @author ppfeufer
		 *
		 * @param string $var_sTweettext
		 * @return string
		 */
		private function _shorten_tweettext($var_sTweettext) {
			$array_TweettextData = array(
				'length_tweettext_maximal' => 136,
				'length_tweettext' => strlen(rawurlencode($var_sTweettext)),
// 				'length_tweettext' => strlen($var_sTweettext),
				'length_twitter_name' => (!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_reply'])) ? strlen(' via @' . $this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_reply']) : 0,
				'length_tweetlink' => 20,
				'length_more' => strlen(' ...')
			);

			$length_new_tweettext = $array_TweettextData['length_tweettext_maximal'] - $array_TweettextData['length_twitter_name'] - $array_TweettextData['length_tweetlink'] - $array_TweettextData['length_more'];

			if($array_TweettextData['length_tweettext'] > $length_new_tweettext) {
				$words = explode(' ', $var_sTweettext);
				$ttext = '';

				foreach($words as $word) {
					if(strlen(rawurlencode($ttext)) + strlen(rawurlencode(' ' . $word)) < $length_new_tweettext) {
						$ttext .= ' ' . $word;
					} else {
						break;
					} // END if(strlen(rawurlencode($ttext)) + strlen(rawurlencode(' ' . $word)) < $length_new_tweettext)
				} // END foreach($words as $word)

				$var_sTweettext = $ttext . ' ...';
			} // END if($array_TweettextData['length_tweettext'] > $length_new_tweettext)

			return $var_sTweettext;
		} // END private function _shorten_tweettext($var_sTweettext)

		/**
		 * Tags des Artikels in #Hashtags umwandeln
		 *
		 * @since 0.14
		 * @author ppfeufer
		 */
		private function _get_hashtags() {
			/**
			 * Sollen #Hashtags angezeigt werden?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_hashtags'] == true) {
				$var_sHashtags = strip_tags(get_the_tag_list(' #', ' #', ''));
			} else {
				$var_sHashtags = '';
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_hashtags'] == true)

			return $var_sHashtags;
		} // END private function _get_hashtags()

		/**
		 * Description for Pinterest
		 *
		 * @since 0.32
		 * @author ppfeufer
		 */
		function _get_pinterest_description() {
			$var_sPinterestDescription = '';

			switch($this->array_TwoclickButtonsOptions['twoclick_buttons_pinterest_description']) {
				case 'posttitle-tags':
					$var_sPinterestDescription = wp_filter_nohtml_kses(get_the_title(get_the_ID())) . ' ' . strip_tags(get_the_tag_list(' #', ' #', ''));
					break;

				case 'posttitle-excerpt':
					$var_sPinterestDescription = wp_filter_nohtml_kses(get_the_title(get_the_ID())) . ' &raquo; ' . $this->_get_post_excerpt(get_the_content(), 70);
					break;

				default:
					$var_sPinterestDescription = wp_filter_nohtml_kses(get_the_title(get_the_ID()));
					break;
			} // END switch($this->array_TwoclickButtonsOptions['twoclick_buttons_pinterest_description'])

// 			return rawurlencode($var_sPinterestDescription);
			return $var_sPinterestDescription;
		} // END function _get_pinterest_description()


		/**
		 * <[ Helper ]>
		 * Filter und Container für das Intro bereit stellen
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @return string
		 */
		private function _get_intro() {
			if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_introtext'])) {
				return '<div class="twoclick-intro">' . apply_filters('twoclick_intro', '') . '</div>';
			} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_intro']))

			return false;
		} // END private function _get_intro()

		/**
		 * <[ Helper ]>
		 * Infotext über den Buttons im Artikel anzeigen.
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @return Ambigous <string, mixed>
		 */
		function _get_the_intro() {
			if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_introtext'])) {
				return wpautop($this->array_TwoclickButtonsOptions['twoclick_buttons_introtext']);
			} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_introtext']))
		} // END function _get_the_intro()

		/**
		 * <[ Helper ]>
		 * Sprache der Facebook-Buttons.
		 *
		 * Facebook braucht diese Angleichungen,
		 * da dort nicht alle Locales untestützt werden.
		 *
		 * @since 1.5
		 * @author ppfeufer
		 */
		private function _get_locale() {
			if(isset($this->array_TwoclickButtonsOptions['twoclick_buttons_language'])) {
				$var_sLocale = $this->array_TwoclickButtonsOptions['twoclick_buttons_language'];
			} else {
				$var_sLocale = get_locale();
			}

			switch($var_sLocale) {
				case 'de_DE':		// Deutschland
				case 'de_AT':		// Österreich
				case 'de_CH':		// Schweiz
					$var_sLocaleReturn = 'de_DE';
					break;

				case 'en_US':		// USA
				case 'en_CA':		// Kanada
					$var_sLocaleReturn = 'en_US';
					break;

				case 'en_GB':		// England
				case 'en_AU':		// Australien
				case 'en_IE':		// Irland
				case 'en_ZA':		// Südarfika
				case 'en_EN':		// England (Failover für falsche locale)
					$var_sLocaleReturn = 'en_GB';
					break;
			} // END switch(get_locale())

			return $var_sLocaleReturn;
		} // END private function _get_locale()

		/**
		 * <[ Helper ]>
		 * Artikelbild aus dem Artikel extrahieren,
		 * sofern überhaupt ein Bild vorhanden ist.
		 *
		 * @since 0.32
		 * @author ppfeufer
		 */
		private function _get_article_image() {
			global $post;

			$array_Image = '';

			/**
			 * Abfrage ob das Theme Post Thumbnails unterstützt.
			 * Einige Themes tun das einfach nicht.
			 *
			 * @since 0.7.1
			 * @author ppfeufer
			 *
			 * @return string|boolean
			 */
			if(function_exists('get_post_thumbnail_id')) {
				$array_Image = wp_get_attachment_image_src(get_post_thumbnail_id($GLOBALS['post']->ID), '');
			} // END if(function_exists('get_post_thumbnail_id'))

			if(is_array($array_Image)) {
				$var_sArticleImage = $array_Image['0'];
			} else {
				$var_sDefaultThumbnail = '';
				$var_sOutput = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $GLOBALS['post']->post_content, $array_Matches);

				if($var_sOutput > 0) {
					$var_sPostThumbnail = $array_Matches[1][0];
				} else {
					if($this->array_TwoclickButtonsOptions['twoclick_buttons_postthumbnail'] != '') {
						$var_sPostThumbnail = $this->array_TwoclickButtonsOptions['twoclick_buttons_postthumbnail'];
					} else {
						$var_sPostThumbnail = false;
					} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_postthumbnail'] != '')
				} // END if($var_sOutput > 0)

				/**
				 * Check if we have a thumbnailimage and not the original.
				 * If we do, remove the dimensions to get the original file.
				 *
				 * @since 1.1
				 */
				$var_sPattern = '/-[0-9\/]+x[0-9\/]+/';

				preg_match_all($var_sPattern, $var_sPostThumbnail, $matches);

				$var_sArticleImage = str_replace(array_pop($matches[0]), '', $var_sPostThumbnail);
			} // END if(is_array($array_Image))

			return $var_sArticleImage;
		} // END private function _get_article_thumbnail()

		/**
		 * <[ Helper ]>
		 * JavaScript für Ausgabe generieren.
		 *
		 * @since 0.4
		 * @author ppfeufer
		 */
		function _get_js($var_sPostID = '') {
			if(!is_admin()) {
				if(empty($this->var_sPostExcerpt)) {
					$this->var_sPostExcerpt = rawurlencode($this->_get_post_excerpt(get_the_content(), 400));
				} // END if(empty($this->var_sPostExcerpt))

				if(!empty($var_sPostID)) {
					$var_sPostID = get_the_ID();
				} // END if(!empty($var_sPostID))

				// Some needed variables
				$var_sTitle = rawurlencode(wp_filter_nohtml_kses(get_the_title($var_sPostID)));
				$var_sTweettext = rawurlencode($this->_get_tweettext());
				$var_sArticleImage = $this->_get_article_image();
				$array_ButtonData = array();

				$var_sShowFacebookPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_facebook_perm']) ? 'on' : 'off';
				$var_sShowTwitterPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_twitter_perm']) ? 'on' : 'off';
				$var_sShowGoogleplusPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_googleplus_perm']) ? 'on' : 'off';
				$var_sShowFlattrPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_flattr_perm']) ? 'on' : 'off';
				$var_sShowXingPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_xing_perm']) ? 'on' : 'off';
				$var_sShowPinterestPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_pinterest_perm']) ? 'on' : 'off';
				$var_sShowT3nPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_t3n_perm']) ? 'on' : 'off';
				$var_sShowLinkedinPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_linkedin_perm']) ? 'on' : 'off';

				/**
				 * Settings for singular
				 */
				if(!is_singular()) {
					$var_sShowFacebookPerm = 'off';
					$var_sShowTwitterPerm = 'off';
					$var_sShowGoogleplusPerm = 'off';
					$var_sShowFlattrPerm = 'off';
					$var_sShowXingPerm = 'off';
					$var_sShowPinterestPerm = 'off';
					$var_sShowT3nPerm = 'off';
					$var_sShowLinkedinPerm = 'off';
				} // END if(!is_singular())

				/**
				 * Link zusammenbauen, auch wenn Optionen übergeben werden.
				 *
				 * @since 0.16
				 * @since 1.3 (modified)
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_permalink_with_get'] === true) {
					if(isset($_GET) && count($_GET) != '0') {
						/**
						 * Entferne ungewollte $_GET-Variablen
						 *
						 * @since 1.2
						 */
						unset($_GET['utm_campaign']);		// Google Analytics
						unset($_GET['utm_source']);			// Google Analytics
						unset($_GET['utm_term']);			// Google Analytics
						unset($_GET['utm_content']);		// Google Analytics
						unset($_GET['utm_medium']);			// Google Analytics

						unset($_GET['fb_action_ids']);		// Facebook
						unset($_GET['fb_action_types']);	// Facebook
						unset($_GET['fb_source']);			// Facebook
						unset($_GET['action_object_map']);	// Facebook
						unset($_GET['action_type_map']);	// Facebook
						unset($_GET['action_ref_map']);		// Facebook
						unset($_GET['action_ref_map']);		// Facebook

						/**
						 * Entferne die $_GET Variablen von WordPress,
						 * wenn keine eigene Permalinkstruktur verwendet wird.
						 *
						 * @since 1.2.2
						 * @author ppfeufer
						 */
						unset($_GET['p']);					// WordPress
						unset($_GET['page_id']);			// WordPress
						unset($_GET['paged']);				// WordPress

						// Custom Post Types
						$array_Arguments = array(
							'public' => true,
							'_builtin' => false
						);

						$var_sOutput = 'names'; // names or objects, note names is the default
						$var_sOperator = 'and'; // 'and' or 'or'
						$array_CustomPostTypes = get_post_types($array_Arguments, $var_sOutput, $var_sOperator);

						if(!empty($array_CustomPostTypes)) {
							foreach((array) $array_CustomPostTypes as $key => $value) {
								unset($_GET[$value]);
							}
						}

						/**
						 * Baue Link neu zusammen
						 */
						if(count($_GET) != 0) {
							$array_QueryVars = $_GET;

							$var_sGetVars = '?' . http_build_query($_GET);
							$var_bGetOptionsInLink = true;
						} else {
							$var_sGetVars = '';

							if(!get_option('permalink_structure')) {
								$var_bGetOptionsInLink = true;
							} else {
								$var_bGetOptionsInLink = false;
							}
						} // END if(count($_GET) != 0)

						$var_sGetVars = http_build_query($_GET);
						$var_sPermalink = get_permalink($var_sPostID) . $var_sGetVars;
					} else {
						$var_sPermalink = get_permalink($var_sPostID);
						$var_bGetOptionsInLink = false;
					} // END if(isset($_GET) && count($_GET) != '0')
				} else {
					$var_sPermalink = get_permalink($var_sPostID);
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_permalink_with_get'] === true)

				/**
				 * <[ Failover ]>
				 * Sprache der Facebook-Buttons.
				 *
				 * Facebook braucht diese Angleichungen,
				 * da dort nicht alle Locales untestützt werden.
				 *
				 * @since 1.5
				 * @author ppfeufer
				 * @uses _get_locale
				 */
				$var_sLocale = $this->_get_locale();

				/**
				 * Sprache für Xing und Twitter
				 * Diese nutzen leider keine Lingua-Codes :-(
				 */
				$var_sButtonLanguage = 'de';
				if($var_sLocale != 'de_DE') {
					$var_sButtonLanguage = 'en';
				} // END if(get_locale() != 'de_DE')

				$var_sFacebookAction = ($this->array_TwoclickButtonsOptions['twoclick_buttons_facebook_action']) ? $this->array_TwoclickButtonsOptions['twoclick_buttons_facebook_action'] : 'recommend';

				/**
				 * Options for Facebook
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_facebook']) {
					$array_ButtonData['services']['facebook'] = array(
						'status' => 'on',
						'txt_info' => apply_filters('twoclick-facebook-infotext', stripslashes(wp_filter_kses($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_facebook']))),
						'perma_option' => $var_sShowFacebookPerm,
						'action' => $this->array_TwoclickButtonsOptions['twoclick_buttons_facebook_action'],
						'language' => $var_sLocale
					);
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_facebook'])

				/**
				 * Options for Twitter
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_twitter']) {
					$array_ButtonData['services']['twitter'] = array(
						'reply_to' => $this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_reply'],
						'tweet_text' => apply_filters('twoclick-twitter-tweettext', rawurlencode($this->_get_tweettext())),
// 						'tweet_text' => apply_filters('twoclick-twitter-tweettext', $this->_get_tweettext()),
						'status' => 'on',
						'txt_info' => apply_filters('twoclick-twitter-infotext', stripslashes(wp_filter_kses($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_twitter']))),
						'perma_option' => $var_sShowTwitterPerm,
						'language' => $var_sButtonLanguage
					);

					// Campaign Tracking
					if($this->array_TwoclickButtonsOptions['twoclick_buttons_url_tracking'] === false) {
						$array_ButtonData['services']['twitter']['referrer_track'] = '';
					}
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_twitter'])

				/**
				 * Options for Google+
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_googleplus']) {
					$array_ButtonData['services']['gplus'] = array(
						'status' => 'on',
						'txt_info' => apply_filters('twoclick-googleplus-infotext', stripslashes(wp_filter_kses($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_googleplus']))),
						'perma_option' => $var_sShowGoogleplusPerm
					);

					// Campaign Tracking
					if($this->array_TwoclickButtonsOptions['twoclick_buttons_url_tracking'] === false) {
						$array_ButtonData['services']['gplus']['referrer_track'] = '';
					}
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_googleplus'])

				/**
				 * Options for Flattr
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_flattr']) {
					$array_ButtonData['services']['flattr'] = array(
						'uid' => $this->array_TwoclickButtonsOptions['twoclick_buttons_flattr_uid'],
						'status' => 'on',
						'the_title' => apply_filters('twoclick-flattr-title', $var_sTitle),
						'the_excerpt' => apply_filters('twoclick-flattr-description', htmlspecialchars($this->var_sPostExcerpt)),
						'txt_info' => apply_filters('twoclick-flattr-infotext', stripslashes(wp_filter_kses($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_flattr']))),
						'perma_option' => $var_sShowFlattrPerm
					);
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_flattr'])

				/**
				 * Options for Xing
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_xing']) {
					$array_ButtonData['services']['xing'] = array(
						'status' => 'on',
						'txt_info' => apply_filters('twoclick-xing-infotext', stripslashes(wp_filter_kses($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_xing']))),
						'perma_option' => $var_sShowXingPerm,
						'language' => $var_sButtonLanguage,
					);

					// Campaign Tracking
					if($this->array_TwoclickButtonsOptions['twoclick_buttons_url_tracking'] === false) {
						$array_ButtonData['services']['xing']['referrer_track'] = '';
					}
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_xing'])

				/**
				 * Options for Pinterest
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_pinterest'] && $var_sArticleImage) {
					$array_ButtonData['services']['pinterest'] = array(
						'status' => 'on',
						'the_excerpt' => apply_filters('twoclick-pinterest-description', $this->_get_pinterest_description()),
						'txt_info' => apply_filters('twoclick-pinterest-infotest', stripslashes(wp_filter_kses($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_pinterest']))),
						'perma_option' => $var_sShowPinterestPerm,
						'media' => $var_sArticleImage
					);
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_pinterest'] && $var_sArticleImage)

				/**
				 * Options for t3n
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_t3n']) {
					$array_ButtonData['services']['t3n'] = array(
						'status' => 'on',
						'txt_info' => apply_filters('twoclick-t3n-infotext', stripslashes(wp_filter_kses($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_t3n']))),
						'perma_option' => $var_sShowT3nPerm
					);
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_t3n'])

				/**
				 * Options for LinkedIn
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_linkedin']) {
					$array_ButtonData['services']['linkedin'] = array(
						'status' => 'on',
						'txt_info' => apply_filters('twoclick-linkedin-infotext', stripslashes(wp_filter_kses($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_linkedin']))),
						'perma_option' => $var_sShowLinkedinPerm
					);
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_linkedin'])

				$array_ButtonData['txt_help'] = apply_filters('twoclick-infobutton-infotext', stripslashes(wp_filter_kses($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_infobutton'])));
				$array_ButtonData['settings_perma'] = apply_filters('twoclick-permaoption-infotext', stripslashes(wp_filter_kses($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_permaoption'])));
				$array_ButtonData['info_link'] = apply_filters('twoclick-infolink', esc_url($this->array_TwoclickButtonsOptions['twoclick_buttons_infolink']));
				$array_ButtonData['uri'] = esc_url($var_sPermalink);
				$array_ButtonData['post_id'] = $var_sPostID;
				$array_ButtonData['post_title_referrer_track'] = urlencode(wp_filter_nohtml_kses(get_the_title($var_sPostID)));

				if($this->array_TwoclickButtonsOptions['twoclick_buttons_url_tracking'] === true) {
					$array_ButtonData['concat'] = ($var_bGetOptionsInLink === true) ? '%26' : '%3F';
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_url_tracking'] === true)

				$array_ButtonData['display_infobox'] = (isset($this->array_TwoclickButtonsOptions['twoclick_buttons_display_infobox']) && $this->array_TwoclickButtonsOptions['twoclick_buttons_display_infobox'] === true) ? 'on' : 'off';

// 				$var_sJavaScript = '/* <![CDATA[ */' . "\n" . 'jQuery(document).ready(function($){var jQueryVersion = $().jquery;if(jQueryVersion < \'' . TWOCLICK_JQUERY_REQUIERED . '\') {return false; } else {if($(\'.twoclick_social_bookmarks_post_' . $var_sPostID . '\')){$(\'.twoclick_social_bookmarks_post_' . $var_sPostID . '\').socialSharePrivacy(' . json_encode($array_ButtonData) . ');}}});' . "\n" . '/* ]]> */';
				$var_sJavaScript = '/* <![CDATA[ */' . "\n" . 'jQuery(document).ready(function($){if($(\'.twoclick_social_bookmarks_post_' . $var_sPostID . '\')){$(\'.twoclick_social_bookmarks_post_' . $var_sPostID . '\').socialSharePrivacy(' . json_encode($array_ButtonData) . ');}});' . "\n" . '/* ]]> */';

				return $this->_get_intro() . '<div class="twoclick_social_bookmarks_post_' . $var_sPostID . ' social_share_privacy clearfix ' . $this->_get_plugin_version() . ' locale-' . get_locale() . ' sprite-' . $var_sLocale . '"></div><div class="twoclick-js"><script type="text/javascript">' . $var_sJavaScript . '</script></div>';
			} // END if(!is_admin())
		} // END function _get_js($var_sPostID = '')

		/**
		 * <[ Helper ]>
		 * Buttons in WordPress einbauen.
		 *
		 * @since 0.1
		 * @since 0.22 (modified)
		 * @author ppfeufer
		 */
		function _get_buttons($content) {
			global $post;

			/**
			 * Manual Option
			 */
			if(isset($this->array_TwoclickButtonsOptions['twoclick_buttons_where']) && $this->array_TwoclickButtonsOptions['twoclick_buttons_where'] == 'template') {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_where'] == 'template')

			/**
			 * Sind wir auf einer CMS-Seite?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_page'] == false && is_page()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_page'] == null && is_page())

			/**
			 * Sind wir auf der Startseite?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_index'] == false && is_home()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_index'] == null && is_home())

			/**
			 * Sind wir im Jahresarchiv?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_year'] == false && is_year()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_year'] == null && is_year())

			/**
			 * Sind wir im Monatsarchiv?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_month'] == false && is_month()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_month'] == null && is_month())

			/**
			 * Sind wir im Tagesarchiv?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_day'] == false && is_day()) {
				return $content;
			}

			/**
			 * Sind wir auf der Suchseite?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_search'] == false && is_search()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_search'] == null && is_search())

			/**
			 * Sind wir auf der Tagseite?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_tag'] == false && is_tag()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_tag'] == null && is_tag())

			/**
			 * Sind wir auf der Kategorieseite?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_category'] == false && is_category()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_category'] == null && is_category())

			/**
			 * Soll der Button im Feed ausgeblendet werden?
			 */
			if(is_feed()) {
				return $content;
			} // END if(is_feed())

			$button = $this->generate_html(get_the_ID());
			$var_sWhere = 'twoclick_buttons_where';

			if($post->post_status == 'private' && $this->array_TwoclickButtonsOptions['twoclick_buttons_display_private'] == false) {
				return $content;
			}

			if(post_password_required() && $this->array_TwoclickButtonsOptions['twoclick_buttons_display_password'] == false) {
				return $content;
			}

			/**
			 * Wurde der Shortcode genutzt
			 */
			if($this->array_TwoclickButtonsOptions[$var_sWhere] == 'shortcode') {
				return str_replace('[twoclick_buttons]', $button, $content);
			} else {
				/**
				 * In den Content einbinden
				 */
				/**
				 * Gewählte Seiten ausnehmen
				 *
				 * @since 1.0
				 */
				if((isset($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page']) && is_array($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'])) && (array_key_exists($post->ID, $this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'])) && ($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'][$post->ID] == true)) {
					return $content;
				} // END if((is_array($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'])) && (array_key_exists($post->ID, $this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'])) && ($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'][$post->ID] == true))

				/**
				 * Gewählte CPT ausnehmen
				 *
				 * @since 1.1
				 */
				if((isset($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_cpt']) && is_array($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_cpt'])) && (array_key_exists($post->post_type, $this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_cpt'])) && ($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_cpt'][$post->post_type] == true)) {
					return $content;
				}

				if($this->array_TwoclickButtonsOptions[$var_sWhere] == 'beforeandafter') {
					/**
					 * Vor und nach dem Beitrag einfügen
					 */
					return $button . $content . $button;
				} else if($this->array_TwoclickButtonsOptions[$var_sWhere] == 'before') {
					/**
					 * Vor dem Beitrag einfügen
					 */
					return $button . $content;
				} else {
					/**
					 * Nach dem Beitrag einfügen
					 */
					return $content . $button;
				} // END if($this->array_TwoclickButtonsOptions[$var_sWhere] == 'beforeandafter')
			}
		} // END function _get_buttons($content)

		/**
		 * Template-Tag
		 *
		 * Bindet die Buttons via Funktionsaufruf direkt im Template ein.
		 *
		 * Einbindung:
		 * 		<?php if(function_exists('get_twoclick_buttons')) {get_twoclick_buttons(get_the_ID());}?>
		 *
		 * @since 0.18
		 * @author ppfeufer
		 *
		 * @param int $var_iId
		 */
		function generate_html($var_sPostID = null) {
			if($var_sPostID == '') {
				$var_sPostID = get_the_ID();
			} // END if($var_sPostID == '')

			return $this->_get_js($var_sPostID);
		} // END function generate_html($var_sPostID = null)
	} // END class Twoclick_Social_Media_Buttons_Frontend

	/**
	 * Frontendklasse starten
	 */
	$obj_TwoclickFrontend = new Twoclick_Social_Media_Buttons_Frontend();

	/**
	 * Template-Tag
	 *
	 * Bindet die Buttons via Funktionsaufruf direkt im Template ein.
	 *
	 * Einbindung:
	 * 		<?php if(function_exists('get_twoclick_buttons')) {get_twoclick_buttons(get_the_ID());} ?>
	 *
	 * @since 0.18
	 * @author ppfeufer
	 *
	 * @param int $var_iId
	 */
	function get_twoclick_buttons($var_sPostID = null) {
		if($var_sPostID == '') {
			$var_sPostID = get_the_ID();
		}

		if(!empty($var_sPostID)) {
			global $obj_TwoclickFrontend;

			echo $obj_TwoclickFrontend->generate_html($var_sPostID);
		} else {
			return false;
		} // END if(!empty($var_iId))
	} // END function get_twoclick_buttons($var_iId = null)
} // END if(!class_exists('Twoclick_Social_Media_Buttons_Frontend'))