<?php
/**
 * Plugin Name: Shariff Wrapper
 * Plugin URI: http://www.3uu.org/plugins.htm
 * Description: This is a wrapper to Shariff. It enables shares with Twitter, Facebook ... on posts, pages and themes with no harm for visitors privacy.
 * Version: 3.4.2
 * Author: 3UU, JP
 * Author URI: http://www.DatenVerwurstungsZentrale.com/
 * License: http://opensource.org/licenses/MIT
 * Donate link: http://folge.link/?bitcoin:1Ritz1iUaLaxuYcXhUCoFhkVRH6GWiMTP
 * Domain Path: /locale/
 * Text Domain: shariff3UU
 *
 * ### Supported options ###
 *   services: [facebook|twitter|googleplus|whatsapp|threema|pinterest|linkedin|xing|reddit|stumbleupon|tumblr|vk|diaspora|addthis|flattr|patreon|paypal|paypalme|bitcoin|mailform|mailto|printer|info|rss]
 *   info_url: http://ct.de/-2467514
 *   lang: de|en
 *   theme: default|color|grey|white|round
 *   orientation: vertical
 *   twitter_via: screenname
 *   flattr_user: username
 *   style: CSS code that will be used in a DIV container around shariff with the class ShariffSC
 */

// prevent direct calls to shariff.php
if ( ! class_exists('WP') ) { die(); }

// get options (needed for front- and backend)
if ( ! get_option( 'shariff3UU_basic' ) ) {
	// version < 2.3
	$shariff3UU = get_option( 'shariff3UU' );
	$shariff3UU_basic = array();
	$shariff3UU_design = array();
	$shariff3UU_advanced = array();
	$shariff3UU_mailform = array();
	$shariff3UU_statistic = array();
}
else {
	// version >= 2.3
	$shariff3UU_basic = (array) get_option( 'shariff3UU_basic' );
	$shariff3UU_design = (array) get_option( 'shariff3UU_design' );
	$shariff3UU_advanced = (array) get_option( 'shariff3UU_advanced' );
	$shariff3UU_mailform = (array) get_option( 'shariff3UU_mailform' );
	$shariff3UU_statistic = (array) get_option( 'shariff3UU_statistic' );
	$shariff3UU = array_merge( $shariff3UU_basic, $shariff3UU_design, $shariff3UU_advanced, $shariff3UU_mailform, $shariff3UU_statistic );
}

// update function to perform tasks _once_ after an update, based on version number to work for automatic as well as manual updates
function shariff3UU_update() {

	/******************** ADJUST VERSION ********************/
	$code_version = "3.4.2"; // set code version - needs to be adjusted for every new version!
	/******************** ADJUST VERSION ********************/

	// check if the installed version is older than the code version and include updates.php if neccessary
	if ( empty( $GLOBALS["shariff3UU"]["version"] ) || ( isset( $GLOBALS["shariff3UU"]["version"] ) && version_compare( $GLOBALS["shariff3UU"]["version"], $code_version ) == '-1' ) ) {
		// include updates.php
		include( plugin_dir_path( __FILE__ ) . 'updates.php' );
	}
}
add_action( 'admin_init', 'shariff3UU_update' );

// allowed tags for headline
$allowed_tags = array(
	// direct formatting e.g. <strong>
	'strong' => array(),
	'em'     => array(),
	'b'      => array(),
	'i'      => array(),
	'br'     => array(),
	// elements that can be formatted via CSS
	'span' => array
		(
			'class' => array(),
			'style' => array(),
			'id' => array()
		),
	'div' => array
		(
			'class' => array(),
			'style' => array(),
			'id' => array()
		),
	'p' => array
		(
			'class' => array(),
			'style' => array(),
			'id' => array()
		),
	'h1' => array
		(
			'class' => array(),
			'style' => array(),
			'id' => array()
		),
	'h2' => array
		(
			'class' => array(),
			'style' => array(),
			'id' => array()
		),
	'h3' => array
		(
			'class' => array(),
			'style' => array(),
			'id' => array()
		),
	'h4' => array
		(
			'class' => array(),
			'style' => array(),
			'id' => array()
		),
	'h5' => array
		(
			'class' => array(),
			'style' => array(),
			'id' => array()
		),
	'h6' => array
		(
			'class' => array(),
			'style' => array(),
			'id' => array()
		),
	'hr' => array
		(
			'class' => array(),
			'style' => array(),
			'id' => array()
		)
);

// the admin page
if ( is_admin() ){
	// include admin_menu.php
	include( plugin_dir_path( __FILE__ ) . 'admin/admin_menu.php' );
}

// include admin_notices.php
include( plugin_dir_path( __FILE__ ) . 'admin/admin_notices.php' );

// translations
function shariff3UU_init_locale() {
	if ( function_exists( 'load_plugin_textdomain' ) ) {
		load_plugin_textdomain( 'shariff3UU', false, dirname( plugin_basename( __FILE__ ) ) . '/locale' );
	}
}

// register shortcode
add_shortcode( 'shariff', 'Render3UUShariff' );

// helper function to create the WP representation of the shorttag by the sidewide configured options
function buildShariffShorttag() {
	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];

	// build the shorttag
	$shorttag = '[shariff';

	// orientation
	if ( isset($shariff3UU["vertical"] ) )		if ( $shariff3UU["vertical"] == '1' ) $shorttag .= ' orientation="vertical"';
	// theme
	if ( ! empty($shariff3UU["theme"] ) )		$shorttag .= ' theme="' . $shariff3UU["theme"] . '"';
	// buttonsize
	if ( isset($shariff3UU["buttonsize"] ) )	if ( $shariff3UU["buttonsize"] == '1' ) $shorttag .= ' buttonsize="small"';
	// lang
	if ( ! empty($shariff3UU["lang"] ) ) 		$shorttag .= ' lang="' . $shariff3UU["lang"] . '"';
	// services
	if ( ! empty($shariff3UU["services"] ) ) 	$shorttag .= ' services="' . $shariff3UU["services"] . '"';
	// backend
	if ( isset($shariff3UU["backend"] ) )		if ( $shariff3UU["backend"] == 'on' || $shariff3UU["backend"] == '1' ) $shorttag .= ' backend="on"';
	// info-url
	// rtzTodo: data-info-url + check that info is in the services
	if ( ! empty($shariff3UU["info_url"] ) ) 	$shorttag .= ' info_url="' . $shariff3UU["info_url"] . '"';
	// style
	if ( ! empty($shariff3UU["style"] ) ) 		$shorttag .= ' style="' . $shariff3UU["style"] . '"';
	// twitter-via
	if ( ! empty($shariff3UU["twitter_via"] ) )	$shorttag .= ' twitter_via="' . $shariff3UU["twitter_via"] . '"';
	// flatter-username
	if ( ! empty($shariff3UU["flattruser"] ) )	$shorttag .= ' flattruser="' . $shariff3UU["flattruser"] . '"';
	// set timestamp of last modification
	$shorttag .= ' timestamp="' . get_the_modified_date( 'U', true ) . '"';

	// close the shorttag
	$shorttag .= ']';

	return $shorttag;
}

// add mail form if view=mail
function shariff3UUaddMailForm( $content, $error ) {
	// check if mailform is disabled
	if ( isset( $GLOBALS["shariff3UU_mailform"]["disable_mailform"] ) && $GLOBALS["shariff3UU_mailform"]["disable_mailform"] == '1' ) {
		echo '<div id="shariff_mailform" class="shariff_mailform"><div class="shariff_mailform_disabled">';
		echo __( 'Mail form disabled.', 'shariff3UU' );
		echo '</div></div>';
		$mailform = '';
	}
	else {
		// set default language to English as fallback
		$lang = 'EN';

		// available languages
		$available_lang = array( 'EN', 'DE', 'FR', 'IT' );

		// check plugin options
		if ( isset( $GLOBALS["shariff3UU_mailform"]["mailform_language"] ) && $GLOBALS["shariff3UU_mailform"]["mailform_language"] != 'auto' ) {
			$lang = $GLOBALS["shariff3UU_mailform"]["mailform_language"];
		}
		// if language is set to automatic try geoip
		// http://datenverwurstungszentrale.com/stadt-und-land-mittels-geoip-ermitteln-268.htm
		elseif ( function_exists('geoip_country_code_by_name') ) {
#			if ( WP_DEBUG == TRUE ) echo '<div>Currently using the following country code: ' . geoip_country_code_by_name( $_SERVER["REMOTE_ADDR"] ) . '</div>';
			switch ( @geoip_country_code_by_name( $_SERVER[REMOTE_ADDR] ) ) {
				case 'DE': $lang = 'DE';
				break;
				case 'AT': $lang = 'DE';
				break;
				case 'CH': $lang = 'DE';
				break;
				case 'FR': $lang = 'FR';
				break;
				case 'IT': $lang = 'IT';
				break;
				default: $lang = 'EN';
			}
		}
		// if no geoip try http_negotiate_language
		elseif ( function_exists('http_negotiate_language') ) {
			$lang = http_negotiate_language( $available_lang );
		}

		// sonst per "WP-Plugin GeoIP Detection"
		// siehe https://wordpress.org/plugins/geoip-detect/
		// rtzrtz: erstmal raus, weil nicht mit WPMU https://wordpress.org/support/topic/will-this-work-with-multisite-2?replies=6
	#	elseif(function_exists("geoip_detect2_get_info_from_ip")){
	#		if(WP_DEBUG==TRUE)echo '<br>nutze gerade geoip_detect2_get_info_from_ip<br>';
	#      	$record = geoip_detect2_get_info_from_ip($_SERVER["REMOTE_ADDR"]);
	#    	switch($record->country->isoCode){case 'DE': $lang='DE'; break; case 'AT': $lang='DE'; break; case 'CH': $lang='DE'; break; default: $lang='EN';}
	#	}

		// include selected language
		include( plugin_dir_path( __FILE__ ) . '/locale/mailform-' . $lang . '.php' );

		// use wp_nonce_url / wp_verify_nonce to prevent automated spam by url
		$submit_link = wp_nonce_url( get_permalink(), 'shariff3UU_send_mail', 'shariff_mf_nonce' ) . '#shariff_mailform';

		// sender address optional?
		$mf_optional_text = '';
		$mf_sender_required = '';
		if ( isset( $GLOBALS["shariff3UU_mailform"]["require_sender"] ) && $GLOBALS["shariff3UU_mailform"]["require_sender"] == '1' ) {
			// does not work in Safari, but nice to have in all other cases, because less requests
			$mf_sender_required = ' required';
		}
		else {
			$mf_optional_text = $mf_optional[$lang];
		}

		// field content to prefill fields in case of an error
		if ( isset( $error['mf_content_mailto'] ) ) $mf_content_mailto = $error['mf_content_mailto'];
		else $mf_content_mailto = '';
		if ( isset( $error['mf_content_from'] ) ) $mf_content_from = $error['mf_content_from'];
		else $mf_content_from = '';
		if ( isset( $error['mf_content_sender'] ) ) $mf_content_sender = $error['mf_content_sender'];
		else $mf_content_sender = '';
		if ( isset( $error['mf_content_mail_comment'] ) ) $mf_content_mail_comment = $error['mf_content_mail_comment'];
		else $mf_content_mail_comment = '';

		// create the form
		$mailform = '<div id="shariff_mailform" class="shariff_mailform">';
		// wait error
		if ( ! empty ( $error['wait'] ) ) {
			$mailform .= '<div class="shariff_mailform_error">' . sprintf($mf_wait[$lang], $error['wait']) . '</div>';
		}
		// no to address error
		$mf_to_error_html = '';
		if ( ! empty ( $error['to'] ) && $error['to'] == '1' ) {
			$mf_to_error_html = '<span class="shariff_mailform_error"> ' . $mf_to_error[$lang] . '</span>';
		}
		// no from address error
		$mf_from_error_html = '';
		if ( ! empty ( $error['from'] ) && $error['from'] == '1' ) {
			$mf_from_error_html = '<span class="shariff_mailform_error"> ' . $mf_from_error[$lang] . '</span>';
		}
		$mailform .= '<form action="' . $submit_link . '" method="POST">
						<fieldset>
							<div class="shariff_mailform_headline"><legend>' . $mf_headline[$lang] . '</legend></div>' . $mf_headinfo[$lang] . '
							<input type="hidden" name="act" value="sendMail">
							<input type="hidden" name="lang" value="' . $lang . '">
							<p><label for="mailto">' . $mf_rcpt[$lang] . '</label><br>
							<input type="text" name="mailto" id="mailto" value="' . $mf_content_mailto . '" size="27" placeholder="' . $mf_rcpt_ph[$lang] . '" required>' . $mf_to_error_html . '</p>
							<p><label for="from">' . $mf_from[$lang] . $mf_optional_text . '</label><br>
							<input type="email" name="from" if="from" value="' . $mf_content_from . '" size="27" placeholder="' . $mf_from_ph[$lang] . '" ' . $mf_sender_required .'>' . $mf_from_error_html . '</p>
							<p><label for="name">' . $mf_name[$lang] . '</label><br>
							<input type="text" name="sender" id="sender" value="' . $mf_content_sender . '" size="27" placeholder="' . $mf_name_ph[$lang] . '"></p>
							<p><label for="mail_comment">' . $mf_comment[$lang] . '</label><br>
							<textarea name="mail_comment" rows="4">' . $mf_content_mail_comment . '</textarea></p>
						</fieldset>
						<p><input type="submit" value="' . $mf_send[$lang].'" /></p>
						<p>' . $mf_info[$lang] . '</p>
						</form>
					</div>';
	}
	return $mailform . $content;
}

// helper functions to make it work with PHP < 5.3
// better would be: add_filter( 'wp_mail_from_name', function( $name ) { return sanitize_text_field( $_REQUEST['sender'] ); };
function set_wp_mail_from_name( $name ) { return sanitize_text_field( $_REQUEST['sender'] ); }
function set2_wp_mail_from_name( $name ) { return sanitize_text_field( $_REQUEST['from'] ); }
function set3_wp_mail_from_name( $name ) { return sanitize_text_field( $GLOBALS["shariff3UU"]["mail_sender_name"] ); }
function set4_wp_mail_from_name( $name ) { return sanitize_text_field( get_bloginfo('name') ); }
function set_wp_mail_from( $email ) { return sanitize_text_field( $GLOBALS["shariff3UU"]["mail_sender_from"] ); }

// send mail
function sharif3UUprocSentMail( $content ) {
	// Der Zusatztext darf keine Links enthalten, sonst zu verlockend fuer Spamer
	// optional robinson einbauen
	// optional auf eingeloggte User beschraenken, dann aber auch nicht allgemein anzeigen

	// get vars from form
	$mf_nonce           = sanitize_text_field( $_REQUEST['shariff_mf_nonce'] );
	$mf_content_mailto  = sanitize_text_field( $_REQUEST['mailto'] );
	$mf_content_from    = sanitize_text_field( $_REQUEST['from'] );
	$mf_content_sender  = sanitize_text_field( $_REQUEST['sender'] );
	$mf_lang            = sanitize_text_field( $_REQUEST['lang'] );

	// clean up comments
	$mf_comment_content = $_REQUEST['mail_comment'] ;
	// falls zauberhaft alte Serverkonfiguration, erstmal die Slashes entfernen...
	if ( get_magic_quotes_gpc() == 1 ) $mf_comment_content = stripslashes( $mf_comment_content );
	// ...denn sonst kan wp_kses den content nicht entschaerfen
	$mf_comment_content = wp_kses( $mf_comment_content, '', '' );

	// check if nonce is valid
	if ( isset( $mf_nonce ) && wp_verify_nonce( $mf_nonce, 'shariff3UU_send_mail' ) ) {
		// field content to prefill forms in case of an error
		$error['mf_content_mailto']       = $mf_content_mailto;
		$error['mf_content_from']         = $mf_content_from;
		$error['mf_content_sender']       = $mf_content_sender;
		$error['mf_content_mail_comment'] = $mf_comment_content;

		// rate limiter
		$wait = limitRemoteUser();
		if ( $wait > '5') {
			$error['error'] = '1';
			$error['wait'] = $wait;
		}
		else {	 // Nicer sender name and adress
			 if ( ! empty( $mf_content_sender ) ) 						{ add_filter( 'wp_mail_from_name', 'set_wp_mail_from_name' );
			 } elseif ( ! empty( $mf_content_from ) ) 					{ add_filter( 'wp_mail_from_name', 'set2_wp_mail_from_name' );
			 } elseif ( ! empty( $GLOBALS["shariff3UU_mailform"]["mail_sender_name"] ) ) 	{ add_filter( 'wp_mail_from_name', 'set3_wp_mail_from_name' );
			 } else 									{ add_filter( 'wp_mail_from_name', 'set4_wp_mail_from_name' ); }
			 // also hier drüber haste jetzt zwar 7 Zeilen eingespart, aber du kannst mir nicht erzählen, dass das jetzt besser zu lesen ist ;-)

			 // Achtung: NICHT die Absenderadresse selber umschreiben!
			 // Das fuehrt bei allen sauber aufgesetzten Absender-MTAs zu Problemen mit SPF und/oder DKIM.

			 // default sender address
			 if ( ! empty( $GLOBALS["shariff3UU"]["mail_sender_from"] ) ) {
				 add_filter( 'wp_mail_from', 'set_wp_mail_from' );
			 }

			// build the array with recipients
			$arr = explode( ',', $mf_content_mailto );
			if ( $arr == FALSE ) $arr = array( $mf_content_mailto );
			// max 5
			for ( $i = 0; $i < count($arr); $i++ ) {
				if ( $i == '5' ) break;
				$tmp_mail = sanitize_email( $arr[$i] );
				// no need to add invalid stuff to the array
				if ( is_email( $tmp_mail ) != false ) {
					$mailto[] = $tmp_mail;
				}
			}

			// set langugage from form
			if ( ! empty( $mf_lang ) ) {
				$lang = $mf_lang;
			}
			else {
				$lang ='EN';
			}

			// fallback to EN if a language is not supported by this plugin translations
			if ( $lang != 'DE' && $lang != 'FR' && $lang != 'IT' ) { $lang = 'EN'; }

			// include selected language
			include( plugin_dir_path( __FILE__ ) . '/locale/mailform-' . $lang . '.php' );

			$subject = html_entity_decode( get_the_title() );

			// The following post was suggested to you by
			$message[ $lang ] = $mf_mailbody1[ $lang ];

			if ( ! empty( $mf_content_sender ) ) {
				$message[ $lang ] .= $mf_content_sender;
			}
			elseif ( ! empty( $mf_content_from ) ) {
				$message[ $lang ] .= sanitize_text_field( $mf_content_from );
			}
			else {
				// somebody
				$message[ $lang ] .= $mf_mailbody2[ $lang ];
			}
			// :
			$message[ $lang ] .= $mf_mailbody3[ $lang ];

			$message[ $lang ] .= " \r\n\r\n";
			$message[ $lang ] .= get_permalink() . "\r\n\r\n";

			// add comment
			if ( ! empty( $mf_comment_content ) ) {
				$message[ $lang ] .= $mf_comment_content . "\r\n\r\n";
			}

			// post content
			if ( isset( $GLOBALS["shariff3UU_mailform"]["mail_add_post_content"] ) && $GLOBALS["shariff3UU_mailform"]["mail_add_post_content"] == '1') {
				// strip all html tags
				$post_content = wordwrap( strip_tags( get_the_content() ), 72, "\r\n" );
				// strip shariff shortcodes
				$post_content = html_entity_decode( preg_replace( "#\[shariff.*?\]#s", "", $post_content ) );
				$message[ $lang ] .= $post_content;
				$message[ $lang ] .= " \r\n";
			}

			$message[ $lang ] .= "\r\n-- \r\n";

			// mail footer / disclaimer
			$message[ $lang ] .= $mf_footer[ $lang ];

			// To-Do: Hinweis auf Robinson-Liste

			// avoid auto-responder
			$headers = "Precedence: bulk\r\n";

			// if sender address provided, set as return-path, elseif sender required set error
			if ( ! empty( $mf_content_from ) && is_email( $mf_content_from ) != false ) {
				$headers .= "Reply-To: <" . $mf_content_from . ">\r\n";
			}
			elseif ( isset( $GLOBALS["shariff3UU_mailform"]["require_sender"] ) && $GLOBALS["shariff3UU_mailform"]["require_sender"] == '1' ) {
				$error['error'] = '1';
				$error['from'] = '1';
			}

			// set error, if no usuable recipient e-mail
			if ( empty( $mailto['0'] ) ) {
				$error['error'] = '1';
				$error['to'] = '1';
			}
		}
		// if we have errors provide the mailform again with error message
		if ( isset( $error['error'] ) && $error['error'] == '1' ) {
			$content = shariff3UUaddMailForm( $content, $error );
		}
		// if everything is fine, send the e-mail
		else {
			$mailnotice = '<div id="shariff_mailform" class="shariff_mailform">';
			// The e-mail was successfully send to:
			$mailnotice .= '<div class="shariff_mailform_headline">' . $mf_mail_send[ $lang ] . '</div>';
			// Send the mail ($mailto in this function is allways an array)
			foreach ( $mailto as $rcpt ) {
				wp_mail( $rcpt, $subject, $message["$lang"], $headers ); // The function is available after the hook 'plugins_loaded'.
				$mailnotice .= $rcpt . '<br>';
			}
			$mailnotice .= '</div>';
			// add to content
			$content = $mailnotice . $content;
		}
	}
	return $content;
}

// set a timeout until new mails are possible
function limitRemoteUser() {
	$shariff3UU_mailform = $GLOBALS["shariff3UU_mailform"];
	//rtzrtz: umgeschrieben aus dem DOS-Blocker. Nochmal gruebeln, ob wir das ohne memcache mit der Performance schaffen. Daher auch nur Grundfunktionalitaet.
	if ( ! isset( $shariff3UU_mailform['REMOTEHOSTS'] ) ) $shariff3UU_mailform['REMOTEHOSTS'] = '';
	$HOSTS = json_decode( $shariff3UU_mailform['REMOTEHOSTS'], true );
	// wartezeit in sekunden
	$wait = '2';
	if ( $HOSTS[$_SERVER['REMOTE_ADDR']]-time()+$wait > 0 ) {
		if ( $HOSTS[$_SERVER['REMOTE_ADDR']]-time() < 86400 ) {
			$wait = ($HOSTS[$_SERVER['REMOTE_ADDR']]-time()+$wait)*2;
		}
  	}

  	$HOSTS[$_SERVER['REMOTE_ADDR']] = time()+$wait;
  	// etwas Muellentsorgung
  	if ( count( $HOSTS )%10 == 0 ) {
  		while ( list( $key, $value ) = each( $HOSTS ) ) {
  			if ( $value-time()+$wait < 0 ) {
  				unset( $HOSTS[$key] );
  				update_option( 'shariff3UU_mailform', $shariff3UU_mailform );
  			}
  		}
  	}
	$REMOTEHOSTS = json_encode( $HOSTS );
	$shariff3UU_mailform['REMOTEHOSTS'] = $REMOTEHOSTS;
	// update nur, wenn wir nicht unter heftigen DOS liegen
  	if ( $HOSTS[$_SERVER['REMOTE_ADDR']]-time() < '60' ) {
  		update_option( 'shariff3UU_mailform', $shariff3UU_mailform );
  	}
  	return $HOSTS[$_SERVER['REMOTE_ADDR']]-time();
}

// add shorttag to posts
function shariffPosts( $content ) {
	$shariff3UU = $GLOBALS["shariff3UU"];

	// disable share buttons on password protected posts if configured in the admin menu
	if ( ( post_password_required( get_the_ID() ) == '1' || ! empty( $GLOBALS["post"]->post_password ) ) && isset( $shariff3UU["disable_on_protected"] ) && $shariff3UU["disable_on_protected"] == '1') {
		$shariff3UU["add_before"]["posts"] = '0';
		$shariff3UU["add_before"]["posts_blogpage"] = '0';
		$shariff3UU["add_before"]["pages"] = '0';
		$shariff3UU["add_after"]["posts"] = '0';
		$shariff3UU["add_after"]["posts_blogpage"] = '0';
		$shariff3UU["add_after"]["pages"] = '0';
		$shariff3UU["add_after"]["custom_type"] = '0';
	}

	// prepend the mail form
	if ( isset( $_REQUEST['view'] ) && $_REQUEST['view'] == 'mail' ) {
		// only add to single posts view
		if ( is_singular() ) $content = shariff3UUaddMailForm( $content, '0' );
	}
	// send the email
	if ( isset( $_REQUEST['act'] ) && $_REQUEST['act'] == 'sendMail' ) $content = sharif3UUprocSentMail( $content );

	// if we want see it as text - replace the slash
	if ( strpos( $content,'/hideshariff' ) == true ) {
		$content = str_replace( "/hideshariff", "hideshariff", $content );
	}
	// but not, if the hidshariff sign is in the text |or| if a special formed "[shariff..."  shortcut is found
	elseif( ( strpos( $content, 'hideshariff' ) == true) ) {
		// remove the sign
		$content = str_replace( "hideshariff", "", $content);
		// and return without adding Shariff
		return $content;
	}

	// now add Shariff
	if ( ! is_singular() ) {
		// on blog page
		if( isset( $shariff3UU["add_before"]["posts_blogpage"] ) && $shariff3UU["add_before"]["posts_blogpage"] == '1')	$content = buildShariffShorttag() . $content;
		if( isset( $shariff3UU["add_after"]["posts_blogpage"] ) && $shariff3UU["add_after"]["posts_blogpage"] == '1' )	$content .= buildShariffShorttag();
	} elseif ( is_singular( 'post' ) ) {
		// on single post
		if ( isset( $shariff3UU["add_before"]["posts"] ) && $shariff3UU["add_before"]["posts"] == '1' )	$content = buildShariffShorttag() . $content;
		if ( isset( $shariff3UU["add_after"]["posts"] ) && $shariff3UU["add_after"]["posts"] == '1' )	$content .= buildShariffShorttag();
	} elseif ( is_singular( 'page' ) ) {
		// on pages
		if ( isset( $shariff3UU["add_before"]["pages"] ) && $shariff3UU["add_before"]["pages"] == '1' )	$content = buildShariffShorttag() . $content;
		if ( isset( $shariff3UU["add_after"]["pages"] ) && $shariff3UU["add_after"]["pages"] == '1' )	$content .= buildShariffShorttag();
	} else {
		// on custom_post_types
		$all_custom_post_types = get_post_types( array ( '_builtin' => FALSE ) );
		if ( is_array( $all_custom_post_types ) ) {
			$custom_types = array_keys( $all_custom_post_types );
			// type of current post
			$current_post_type = get_post_type();
			// add shariff, if custom type and option checked in the admin menu
			if ( isset( $shariff3UU['add_after'][$current_post_type] ) && $shariff3UU['add_after'][$current_post_type] == 1 ) {
				$content .= buildShariffShorttag();
			}
		}
	}

	return $content;
}
add_filter( 'the_content', 'shariffPosts' );

// add shorttag to excerpt
function shariffExcerpt( $content ) {
	$shariff3UU = $GLOBALS["shariff3UU"];
	// remove headline in post
	if ( isset( $shariff3UU["headline"] ) ) {
		$content = str_replace( strip_tags( $shariff3UU["headline"] ), " ", $content );
	}
	// add shariff before the excerpt, if option checked in the admin menu
	if ( isset( $shariff3UU["add_before"]["excerpt"] ) && $shariff3UU["add_before"]["excerpt"] == '1' ) {
		$content = do_shortcode( buildShariffShorttag() ) . $content;
	}
	// add shariff after the excerpt, if option checked in the admin menu
	if ( isset( $shariff3UU["add_after"]["excerpt"] ) && $shariff3UU["add_after"]["excerpt"] == '1' ) {
		$content .= do_shortcode( buildShariffShorttag() );
	}
	return $content;
}
add_filter( 'get_the_excerpt', 'shariffExcerpt' );

// add mailform to bbpress_replies
function bbp_add_mailform_to_bbpress_replies() {
	$content = '';
	// prepend the mail form
	if ( isset( $_REQUEST['view'] ) && $_REQUEST['view'] == 'mail' ) {
		// only add to single posts view
		$content = shariff3UUaddMailForm( $content, '0' );
	}
	// send the email
	if ( isset( $_REQUEST['act'] ) && $_REQUEST['act'] == 'sendMail' ) $content = sharif3UUprocSentMail( $content );
	echo $content;
}
add_action('bbp_theme_after_reply_content', 'bbp_add_mailform_to_bbpress_replies');

// add shariff buttons after bbpress replies
function bbp_add_shariff_after_replies() {
	$shariff3UU = $GLOBALS["shariff3UU"];
	if( isset( $shariff3UU["add_after"]["bbp_reply"] ) && $shariff3UU["add_after"]["bbp_reply"] == '1') echo Render3UUShariff( '' );
}
add_action('bbp_theme_after_reply_content', 'bbp_add_shariff_after_replies');

// add the align-style options to the css file and the button stretch
function shariff3UU_align_styles() {
	$shariff3UU_design = $GLOBALS["shariff3UU_design"];
	$custom_css = '';

	// add styles, load it from external server, if configured
	if ( isset( $GLOBALS["shariff3UU_statistic"]["external_host"] ) ) {
		wp_enqueue_style( 'shariffcss', $GLOBALS["shariff3UU_statistic"]["external_host"].'css/shariff.min.local.css' );
	}
	else {
		wp_enqueue_style( 'shariffcss', plugins_url( '/css/shariff.min.local.css', __FILE__ ) );
	}

	// align option
	if ( isset( $shariff3UU_design["align"] ) && $shariff3UU_design["align"] != 'none' ) {
		 $align = $shariff3UU_design["align"];
		 $custom_css .= "
			 .shariff { justify-content: {$align} }
			 .shariff { -webkit-justify-content: {$align} }
			 .shariff { -ms-flex-pack: {$align} }
			 .shariff ul { justify-content: {$align} }
			 .shariff ul { -webkit-justify-content: {$align} }
			 .shariff ul { -ms-flex-pack: {$align} }
			 .shariff ul { -webkit-align-items: {$align} }
			 .shariff ul { align-items: {$align} }
			 ";
	}

	// align option for widget
	if ( isset( $shariff3UU_design["align_widget"] ) && $shariff3UU_design["align_widget"] != 'none' ) {
		 $align_widget = $shariff3UU_design["align_widget"];
		 $custom_css .= "
			 .widget .shariff { justify-content: {$align_widget} }
			 .widget .shariff { -webkit-justify-content: {$align_widget} }
			 .widget .shariff { -ms-flex-pack: {$align_widget} }
			 .widget .shariff ul { justify-content: {$align_widget} }
			 .widget .shariff ul { -webkit-justify-content: {$align_widget} }
			 .widget .shariff ul { -ms-flex-pack: {$align_widget} }
			 .widget .shariff ul { -webkit-align-items: {$align} }
			 .widget .shariff ul { align-items: {$align} }
			 ";
	}

	// button stretch
	if ( isset( $shariff3UU_design["buttonstretch"] ) && $shariff3UU_design["buttonstretch"] == '1' ) {
		 $buttonstretch = $shariff3UU_design["buttonstretch"];
		 $custom_css .= "
			 .shariff ul { flex: {$buttonstretch} 0 auto !important }
			 .shariff ul { -webkit-flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-horizontal li { flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-horizontal li { -webkit-flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-vertical { flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-vertical { -webkit-flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-vertical li { flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-vertical li { -webkit-flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-vertical li { width: 100% !important }
			 ";
	}

	// if not empty, add it to our plugin css
	if ( $custom_css != '') wp_add_inline_style( 'shariffcss', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'shariff3UU_align_styles' );

// render the shorttag to the HTML shorttag of Shariff
function Render3UUShariff( $atts, $content = null ) {
	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];

	// avoid errors if no attributes are given - instead use the old set of services to make it backward compatible
	if ( empty( $shariff3UU["services"] ) ) $shariff3UU["services"] = "twitter|facebook|googleplus|info";

	// use the backend option for every option that is not set in the shorttag
	$backend_options = $shariff3UU;
	if ( isset( $shariff3UU["vertical"] ) )		if($shariff3UU["vertical"] == '1' )		$backend_options["orientation"] = 'vertical';
	if ( isset( $shariff3UU["backend"] ) )		if($shariff3UU["backend"] == '1' )		$backend_options["backend"] = 'on';
	if ( isset( $shariff3UU["buttonsize"] ) )	if($shariff3UU["buttonsize"] == '1' )	$backend_options["buttonsize"] = 'small';
	if ( empty( $atts ) ) $atts = $backend_options;
	else $atts = array_merge( $backend_options, $atts );

	// remove empty elements (no need to write data-something="" to html)
	$atts = array_filter( $atts );

	// make sure that default WP jquery is loaded
	#rtzrtz_ Hm, die sollten wir dann potenziell vielleicht auch vom externen Server holen. Nochens gruebeln, was es fuer Seiteneffekte hat!
	#jp: Da wir grundsätzlich die WP-Variante von jQuery verwenden, sehe ich keinen Sinn darin, die bereits vorhande Verison erneut von extern zu laden, außerdem ist die Wahrscheinlichkeit hoch, dass es bereits von einem anderen Plugin geladen wurde. Im worst case laden wir jQuery nachher doppelt.
	wp_enqueue_script( 'jquery' );

	// the JS must be loaded at footer - make sure that wp_footer() is present in your theme!
	if ( isset( $GLOBALS["shariff3UU_statistic"]["external_host"] ) ) {
		wp_enqueue_script( 'shariffjs', $GLOBALS["shariff3UU_statistic"]["external_host"] . 'shariff.js', '', '', true );
	}
	else {
		wp_enqueue_script( 'shariffjs', plugins_url( '/shariff.js', __FILE__ ), '', '', true );
	}

	// clean up headline in case it was used in a shorttag
	if ( array_key_exists( 'headline', $atts ) ) {
		$atts['headline'] = wp_kses( $atts['headline'], $GLOBALS["allowed_tags"] );
	}

	// prevent an error notice while debug mode is on, because of "undefined variable" when using .=
	$output = '';

	// if we have a style attribute and / or a headline, add it
	if ( array_key_exists( 'style', $atts ) || array_key_exists( 'headline', $atts ) ) {
		// container
		$output .= '<div class="ShariffSC"';
		// style attributes
		if ( array_key_exists( 'style', $atts ) ) {
			$output .= ' style="' . esc_html( $atts['style'] ) . '">';
		}
		else {
			$output .= '>';
		}
		// headline
		if ( array_key_exists( 'headline', $atts ) ) {
			$output .= '<div class="ShariffHeadline">' . $atts['headline'] . '</div>';
		}
	}

	// start output of actual shorttag
	$output .= '<div class="shariff"';

	// set the url attribute. Usefull e.g. in widgets that should point main page instead of a single post
	if ( array_key_exists( 'url', $atts ) ) $output .= ' data-url="' . esc_url( $atts['url'] ) . '"';
	else $output .= ' data-url="' . esc_url( get_permalink() ) . '"';

	// same for the title attribute
	if ( array_key_exists( 'title', $atts ) ) $output .= ' data-title="' . esc_html($atts['title']) . '"';
	else $output .= ' data-title="' . strip_tags( get_the_title() ) . '"';

	// set the options

	if ( array_key_exists( 'info_url', $atts ) )    $output .= ' data-info-url="' .		esc_html( $atts['info_url'] ) . '"';
	if ( array_key_exists( 'orientation', $atts ) ) $output .= ' data-orientation="' .	esc_html( $atts['orientation'] ) . '"';
	if ( array_key_exists( 'theme', $atts ) )       $output .= ' data-theme="' .		esc_html( $atts['theme'] ) . '"';

	// if no language is set, try http_negotiate_language otherwise fallback language in JS is used
	if ( array_key_exists( 'lang', $atts ) ) {
		$output .= ' data-lang="' . esc_html( $atts['lang']) . '"';
	}
	elseif ( function_exists('http_negotiate_language') ) {
		$available_lang = array( 'en', 'de', 'fr', 'es', 'zh', 'hr', 'da', 'nl', 'fi', 'it', 'ja', 'ko', 'no', 'pl', 'pt', 'ro', 'ru', 'sk', 'sl', 'sr', 'sv', 'tr', );
		$lang = http_negotiate_language( $available_lang );
		$output .= ' data-lang="' . esc_html( $lang ) . '"';
	}

	if ( array_key_exists( 'twitter_via', $atts ) )     $output .= ' data-twitter-via="'    . esc_html( $atts['twitter_via'] ) . '"';
	if ( array_key_exists( 'flattruser', $atts ) )      $output .= ' data-flattruser="'     . esc_html( $atts['flattruser'] ) . '"';
	if ( array_key_exists( 'patreonid', $atts ) )       $output .= ' data-patreonid="'      . esc_html( $atts['patreonid'] ) . '"';
	if ( array_key_exists( 'paypalbuttonid', $atts ) )  $output .= ' data-paypalbuttonid="' . esc_html( $atts['paypalbuttonid'] ) . '"';
	if ( array_key_exists( 'paypalmeid', $atts ) )      $output .= ' data-paypalmeid="'     . esc_html( $atts['paypalmeid'] ) . '"';
	if ( array_key_exists( 'bitcoinaddress', $atts ) )  $output .= ' data-bitcoinaddress="' . esc_html( $atts['bitcoinaddress'] ) . '"';
	if ( array_key_exists( 'bitcoinaddress', $atts ) )  $output .= ' data-bitcoinurl="'     . esc_url( plugins_url( '/', __FILE__ ) ) . '"';
	if ( array_key_exists( 'rssfeed', $atts ) )         $output .= ' data-rssfeed="'        . esc_url( $atts['rssfeed'] ) . '"';
	else $output .= ' data-rssfeed="' . esc_url( get_bloginfo('rss_url') ) . '"';
	if ( array_key_exists( 'buttonsize', $atts ) )      $output .= ' data-buttonsize="'     . esc_html( $atts['buttonsize'] ) . '"';
	if ( array_key_exists( 'timestamp', $atts ) )      	$output .= ' data-timestamp="'      . esc_html( $atts['timestamp'] ) . '"';
	// if services are set only use these
	if ( array_key_exists( 'services', $atts ) ) {
		// build an array
		$s = explode( '|', $atts["services"] );
		$output .= ' data-services=\'[';
		// prevent error while debug mode is on
		$strServices = '';
		$flattr_error = '';
		$paypal_error = '';
		$paypalme_error = '';
		$bitcoin_error = '';
		$patreon_error = '';
		// walk
		while ( list( $key, $val ) = each( $s ) ) {
			// services without usernames, etc.
			if ( $val != 'flattr' && $val != 'paypal' && $val != 'bitcoin' && $val != 'patreon' && $val != 'paypalme' ) $strServices .= '"' . $val . '", ';
			// check if flattr username is set
			elseif ( $val == 'flattr' && array_key_exists( 'flattruser', $atts ) ) $strServices .= '"' . $val . '", ';
			elseif ( $val == 'flattr' ) $flattr_error = '1';
			// check if paypal button id is set
			elseif ( $val == 'paypal' && array_key_exists( 'paypalbuttonid', $atts ) ) $strServices .= '"' . $val . '", ';
			elseif ( $val == 'paypal' ) $paypal_error = '1';
			// check if paypal.me id is set
			elseif ( $val == 'paypalme' && array_key_exists( 'paypalmeid', $atts ) ) $strServices .= '"' . $val . '", ';
			elseif ( $val == 'paypalme' ) $paypalme_error = '1';
			// check if bitcoin address is set
			elseif ( $val == 'bitcoin' && array_key_exists( 'bitcoinaddress', $atts ) ) $strServices .= '"' . $val . '", ';
			elseif ( $val == 'bitcoin' ) $bitcoin_error = '1';
			// check if patreon username is set
			elseif ( $val == 'patreon' && array_key_exists( 'patreonid', $atts ) ) $strServices .= '"' . $val . '", ';
			elseif ( $val == 'patreon' ) $patreon_error = '1';
		}
		// remove the separator and add it to output
		$output .= substr( $strServices, 0, -2 );
		$output .= ']\'';
	}

	// get an image for pinterest (attribut -> featured image -> first image -> default image -> shariff hint)
	if ( array_key_exists( 'services', $atts ) ) if ( strstr( $atts["services"], 'pinterest') ) {
		if ( array_key_exists( 'media', $atts ) ) $output .= " data-media='" . esc_html( $atts['media'] ) . "'";
		else {
			$feat_image = wp_get_attachment_url( get_post_thumbnail_id() );
			if ( ! empty( $feat_image ) ) $output .= " data-media='" . esc_html( $feat_image ) . "'";
			else {
				$first_image = catch_image();
				if ( ! empty( $first_image ) ) $output .= " data-media='" . esc_html( $first_image ) . "'";
				else {
					if ( isset( $shariff3UU["default_pinterest"] ) ) $output .= " data-media='" . $shariff3UU["default_pinterest"] . "'";
					else $output .= " data-media='" . plugins_url( '/pictos/defaultHint.png', __FILE__ ) . "'";
				}
			}
		}
	}

	// enable share counts
	if ( array_key_exists( 'backend', $atts ) && $atts['backend'] == "on" ) {
		// set backend_url to external server, if configured, otherwiese use local backend
		if ( isset( $GLOBALS["shariff3UU_statistic"]["external_host"] ) ) {
			$data_backen_url = esc_url( $GLOBALS["shariff3UU_statistic"]["external_host"] . 'backend/index.php' );
		}
		else {
			$data_backen_url = esc_url( plugins_url( '/backend/index.php', __FILE__ ) );
		}
		// add to output
		$output .= " data-backend-url='$data_backen_url'";
	}

	// close the container
	$output .= '></div>';

	// display warning to admins if flattr is set, but no flattr username is provided
	if ( $flattr_error == '1' && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="flattr_warning">' . __('Username for Flattr is missing!', 'shariff3UU') . '</div>';
	}

	// display warning to admins if patreon is set, but no patreon username is provided
	if ( $patreon_error == '1' && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="flattr_warning">' . __('Username for patreon is missing!', 'shariff3UU') . '</div>';
	}

	// display warning to admins if paypal is set, but no paypal button id is provided
	if ( $paypal_error == '1' && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="flattr_warning">' . __('Button ID for PayPal is missing!', 'shariff3UU') . '</div>';
	}

	// display warning to admins if paypalme is set, but no paypalme id is provided
	if ( $paypalme_error == '1' && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="flattr_warning">' . __('PayPal.Me ID is missing!', 'shariff3UU') . '</div>';
	}

	// display warning to admins if bitcoin is set, but no bitcoin address is provided
	if ( $bitcoin_error == '1' && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="flattr_warning">' . __('Address for Bitcoin is missing!', 'shariff3UU') . '</div>';
	}

	// if we had a style attribute or a headline, close that too
	if ( array_key_exists( 'style', $atts ) || array_key_exists( 'headline', $atts ) ) $output .= '</div>';

	return $output;
}

// helper function to get the first image
function catch_image() {
	$files = get_children( 'post_parent=' . get_the_ID() . '&post_type=attachment&post_mime_type=image' );
	if ( $files ) {
		$keys = array_reverse( array_keys( $files ) );
		$num = $keys[0];
		$imageurl = wp_get_attachment_url( $num );
		return $imageurl;
	}
}

// widget
class ShariffWidget extends WP_Widget {
	public function __construct() {
		// translations
		if(function_exists('load_plugin_textdomain')) { load_plugin_textdomain('shariff3UU', false, dirname(plugin_basename(__FILE__)).'/locale' ); }

		$widget_options = array(
			'classname' => 'Shariff',
			'description' => __('Add Shariff as configured on the plugin options page.', 'shariff3UU')
			);

		$control_options = array();
		parent::__construct('Shariff', 'Shariff', $widget_options, $control_options);
	} // END __construct()

	// widget form - see WP_Widget::form()
	public function form($instance) {
		// widgets defaults
		$instance = wp_parse_args((array) $instance, array(
								 'shariff-title' => '',
								 'shariff-tag' => '[shariff]',
							 ));
		// set title
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Title', 'shariff3UU') . '</strong></p>';
		// set title
		echo '<p><input id="'. $this->get_field_id('shariff-title') .'" name="'. $this->get_field_name('shariff-title')
		.'" type="text" value="'. $instance['shariff-title'] .'" />(optional)</p>';
		// set shorttag
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>Shorttag</strong></p>';
		// set shorttag
		echo '<p><input id="'. $this->get_field_id('shariff-tag') .'" name="' . $this->get_field_name('shariff-tag')
				 . '" type="text" value=\''. str_replace('\'','"',$instance['shariff-tag']) .'\' size="30" />(optional)</p>';

		echo '<p style="clear:both;"></p>';
	} // END form($instance)

	// save widget configuration
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;

		// widget conf defaults
		$new_instance = wp_parse_args( (array) $new_instance, array( 'shariff-title' => '', 'shariff-tag' => '[shariff]') );

		// check input values
		$instance['shariff-title'] = (string) strip_tags( $new_instance['shariff-title'] );
		$instance['shariff-tag'] = (string) wp_kses( $new_instance['shariff-tag'], $GLOBALS["allowed_tags"] );

		// save config
		return $instance;
	}

	// draw widget
	public function widget( $args, $instance ) {
		// extract $args
		extract( $args );

		// get options
		$shariff3UU = $GLOBALS["shariff3UU"];

		// container
		echo $before_widget;

		// print title of widget, if provided
		if ( empty( $instance['shariff-title'] ) ) {
			$title = '';
		}
		else {
			apply_filters( 'shariff-title', $instance['shariff-title'] );
			$title = $instance['shariff-title'];
		}
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		// print shorttag

		// keep original shorttag for further reference
		$original_shorttag = $instance['shariff-tag'];

		// if nothing is configured, use the global options from admin menu
		if ( $instance['shariff-tag'] == '[shariff]' ) {
			$shorttag = buildShariffShorttag();
		}
		else {
			$shorttag = $instance['shariff-tag'];
		}

		// set url to current page to prevent sharing the first or last post on pages with multiple posts e.g. the blog page
		// ofc only when no manual url is provided in the shorttag
		$page_url = '';
		if ( strpos( $original_shorttag, ' url=' ) === false ) {
			$wpurl = get_bloginfo( 'wpurl' );
			$siteurl = get_bloginfo( 'url' );
			// for "normal" installations
			$page_url = $wpurl . esc_url_raw( $_SERVER['REQUEST_URI'] );
			// kill ?view=mail etc. if pressed a second time
			$page_url = strtok($page_url, '?');
			// if wordpress is installed in a subdirectory, but links are mapped to the main domain
			if ( $wpurl != $siteurl ) {
				$subdir = str_replace ( $siteurl , '' , $wpurl );
				$page_url = str_replace ( $subdir , '' , $page_url );
			}
			$page_url = ' url="' . $page_url;
			$page_url .= '"';
		}

		// same for title
		$page_title = '';
		if ( strpos( $original_shorttag, 'title=' ) === false ) {
			$wp_title = get_the_title(); # for WP4.4
			// rtzTODO: use wp_get_document_title() with backward compatibility
			// prior wp_title( '', false );
			// wp_title for all pages that have it
			if ( ! empty( $wp_title ) ) {
				$page_title = ltrim($wp_title);
			}
			// the site name for static start pages where wp_title is not set
			else {
				$page_title = get_bloginfo('name');
			}
			$page_title = ' title="' . $page_title . '"';
		}

		// same for media
		$media = '';
		if ( array_key_exists( 'services', $shariff3UU ) && strstr( $shariff3UU["services"], 'pinterest' ) && ( strpos( $original_shorttag,'media=' ) === false ) ) {
			$feat_image = wp_get_attachment_url( get_post_thumbnail_id() );
			if ( ! empty( $feat_image ) ) $media = ' media="' . esc_html( $feat_image ) . '"';
			else {
				$first_image = catch_image();
				if ( ! empty( $first_image ) ) $media = ' media="' . esc_html( $first_image ) . '"';
				else {
					if ( isset( $shariff3UU["default_pinterest"] ) ) $media = ' media="' . $shariff3UU["default_pinterest"] . '"';
					else $media = ' media="' . plugins_url( '/pictos/defaultHint.jpg', __FILE__ ) . '"';
				}
			}
		}

		// build shorttag and add url, title and media if necessary
		$shorttag = substr($shorttag,0,-1) . $page_title . $page_url . $media . ']';

		// replace mailform with mailto if on blog page to avoid broken button
		if ( ! is_singular() ) {
			$shorttag = str_replace( 'mailform' , 'mailto' , $shorttag );
		}

		// process the shortcode
		// but only if it is not password protected |or| "disable on password protected posts" is not set in the options
		if ( post_password_required( get_the_ID() ) != '1' || ( isset( $shariff3UU["disable_on_protected"] ) && $shariff3UU["disable_on_protected"] != '1' ) ) {
			echo do_shortcode( $shorttag );
		}

		// close Container
		echo $after_widget;
	} // END widget( $args, $instance )
} // END class ShariffWidget
add_action( 'widgets_init', create_function( '', 'return register_widget("ShariffWidget");' ) );

// clear transients upon deactivation
function shariff3UU_deactivate() {
	global $wpdb;
	// check for multisite
	if ( is_multisite() ) {
		$current_blog_id = get_current_blog_id();
		$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
		if ( $blogs ) {
			foreach ( $blogs as $blog ) {
				// switch to each blog
				switch_to_blog( $blog['blog_id'] );
				// purge transients
				purge_transients();
				// switch back to main
				restore_current_blog();
			}
		}
	} else {
		// purge transients
		purge_transients();
	}
}
register_deactivation_hook( __FILE__, 'shariff3UU_deactivate' );

// purge all the transients associated with our plugin
function purge_transients() {
	// make sure we have the $wpdb class ready
	if ( ! isset( $wpdb ) ) { global $wpdb; }

	// delete transients
	$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_timeout_shariff%"';
	$wpdb->query($sql);
	$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_shariff%"';
	$wpdb->query($sql);

	// clear object cache
	wp_cache_flush();
}

?>
