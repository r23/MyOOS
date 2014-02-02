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
 * The Donate Class
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!class_exists('Twoclick_Social_Media_Buttons_Backend_Donate')) {
	class Twoclick_Social_Media_Buttons_Backend_Donate extends Twoclick_Social_Media_Buttons_Backend {
		private $var_sDonateLinkFlattr = 'http://flattr.com/thing/390240/WordPress-Plugin-2-Click-Social-Media-Buttons;';
		private $var_sDonateLinkPaypal = 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DC2AEJD2J66RE';

		function __construct() {
			if($this->_is_twoclick_settings_page()) {
				$this->render_donate_page();
			}
		} // END function __construct()

		/**
		 * Rendering donation Page
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function render_donate_page() {
			?>
			<div class="metabox-holder">
				<div id="post-body">
					<div id="post-body-content">
						<div class="postbox clearfix">
							<h3><span>OpenSource vs. Arbeitsaufwand</span></h3>
							<div class="inside">
								<p>
									Dieses Plugin ist OpenSource. Das heißt, für euch als Nutzer, es steht komplett kostenlos zur Verfügung und darf ohne Gebühren in jeder WordPress-Installation verwendet werden. Dennoch sind in dieses Plugin im Laufe der Versionen und Verbesserungen einige hundert Stunden Arbeit eingeflossen, welche ich als Entwickler von meiner Freizeit "abzweige".
								</p>
								<p>
									Ich würde mich daher über ein kleines Dankeschön in Form einer Spende freuen. Das tut niemandem weh und ihr als Nutzer unsterstützt so - neben den vielen Ideen die mich erreichen - die Weiterentwicklung dieses Plugins :-)
								</p>
								<p>
									<a href="<?php echo $this->var_sDonateLinkFlattr; ?>" target="_blank"><img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a>
									<a class="PayPalButton" href="<?php echo $this->var_sDonateLinkPaypal; ?>" target="_blank"><img src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_GB/i/btn/btn_donate_SM.gif" /></a>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		} // END private function render_donate_page()
	} // END class Twoclick_Social_Media_Buttons_Backend_Donate extends Twoclick_Social_Media_Buttons_Backend

	new Twoclick_Social_Media_Buttons_Backend_Donate();
} // END if(!class_exists('Twoclick_Social_Media_Buttons_Backend_Donate'))