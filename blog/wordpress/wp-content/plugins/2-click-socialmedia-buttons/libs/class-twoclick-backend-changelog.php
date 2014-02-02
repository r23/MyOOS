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
 * The Changelog Class
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!class_exists('Twoclick_Social_Media_Buttons_Backend_Changelog')) {
	class Twoclick_Social_Media_Buttons_Backend_Changelog extends Twoclick_Social_Media_Buttons_Backend {

		/**
		 * Konstruktor
		 */
		function __construct() {
			if($this->_is_twoclick_settings_page()) {
				$this->_get_changelog();
			} // END if($this->_is_twoclick_settings_page())
		} // END function __construct()

		/**
		 * <[ Helper ]>
		 * Das Changelog ausgeben.
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function _get_changelog() {
			$array_Changelog = $this->_get_changelog_from_reame();

			if($array_Changelog === false) {
				?>
				<div class="metabox-holder clearfix">
					<div id="post-body">
						<div id="post-body-content">
							<div class="postbox clearfix">
								<h3><span><strong>Sorry</strong></span></h3>
								<div class="inside">
									<p>Es tut mir leid, aber die Datei readme.txt des Plugins konnte nicht ausgelesen werden. Dies kann viele Ursachen haben, in den meisten Fällen hilft es, den hister zu kontaktieren und darum zu bitten, dass die PHP-Funktion <a href="http://de.php.net/manual/de/function.file-get-contents.php">file_get_contents()</a> auf lokale Dateien im Webverzeichnis zugreifen kann.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php

				return ;
			}

			if(is_array($array_Changelog)) {
				foreach((array) $array_Changelog as $changelog) {
					?>
					<div class="metabox-holder clearfix">
						<div id="post-body">
							<div id="post-body-content">
								<div class="postbox clearfix">
									<h3>
										<span>
											<strong>Version: <?php echo $changelog['version']; ?> </strong>
											<?php
											if(isset($changelog['datum'])) {
												?>
												<br />
												<?php echo $changelog['datum']; ?>
												<?php
											} // END if(isset($changelog['datum']))
											?>
										</span>
									</h3>
									<div class="inside">
										<ul class="twoclick-changelog">
											<?php
											foreach((array) $changelog['changes'] as $change) {
												?>
												<li class="clearfix"><?php echo $change; ?></li>
												<?php
											} // END foreach((array) $changelog['changes'] as $change)
											?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				} // END foreach((array) $array_Changelog as $changelog)
			} // END if(is_array($array_Changelog))
		} // END private function _get_changelog()

		/**
		 * <[ Helper ]>
		 * Das Changelog aus der readme.txt rausfriemeln.
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function _get_changelog_from_reame() {
			$var_sReadme = @wp_remote_retrieve_body(wp_remote_get(TWOCLICK_PLUGIN_URI . 'readme.txt'));

			if(is_wp_error($var_sReadme)) {
				return false;
			} // END if(is_wp_error($var_sReadme))

			if(!empty($var_sReadme)) {
				$var_sReadme = str_replace(substr($var_sReadme, 0, strpos($var_sReadme, '== Changelog ==')), '', $var_sReadme);
				$var_sReadme = str_replace('== Changelog ==', '', $var_sReadme);
				$var_sReadme = substr($var_sReadme, 0, strpos($var_sReadme, '=='));

				$array_Readme = explode('= ', $var_sReadme);

				$array_ReadmeSorted = array();
				foreach((array) $array_Readme as $array_Read_Me) {
					$array_Temp = array();
					$array_Temp = explode("\n", $array_Read_Me);
					$array_Temp['0'] = str_replace(' =', '', $array_Temp['0']);

					if(!empty($array_Temp['0'])) {
						for($count_i = 0; $count_i < count($array_Temp); $count_i++) {
							if(empty($array_Temp[$count_i])) {
								unset($array_Temp[$count_i]);
							} else {
								$array_Temp[$count_i] = preg_replace('/^\\* (.*?)/', '\\1', $array_Temp[$count_i]);
								$array_Temp[$count_i] = preg_replace('/\\[(.*?)\\]\\((.*?)\\)/', '<a href="\\2">\\1</a>', $array_Temp[$count_i]);
								$array_Temp[$count_i] = preg_replace('/`(.*?)`/', '<code>\\1</code>', $array_Temp[$count_i]);
								$array_Temp[$count_i] = preg_replace('/\\*\\*(.*?)\\*\\*/', ' <strong>\\1</strong>', $array_Temp[$count_i]);
								$array_Temp[$count_i] = preg_replace('/\\*(.*?)\\*/', ' <em>\\1</em>', $array_Temp[$count_i]);
							} // END if(empty($array_Temp[$count_i]))
						} // END for($count_i = 0; $count_i < count($array_Temp); $count_i++)

						// Letztes Element ausm Array entfernen, dies enthält nur ein \n
						array_pop($array_Temp);

						$var_sVersion = trim($array_Temp['0']);
						$var_sDatum = trim(preg_replace('/\\((.*?)\\)/', '\\1', $array_Temp['1']));

						unset($array_Temp['0']);
						unset($array_Temp['1']);

						$array_Changes = array();
						foreach((array) $array_Temp as $var_iKey => $var_sValue) {
							$array_Changes[] = trim($var_sValue);
						} // END foreach((array) $array_Temp as $var_iKey => $var_sValue)

						$array_ReadmeSorted[] = array(
							'version' => $var_sVersion,
							'datum' => $var_sDatum,
							'changes' => $array_Changes
						);
					} // END if(!empty($array_Temp['0']))
				} // END foreach((array) $array_Readme as $array_Read_Me)

// 				$var_sReadme = preg_replace('/^\\*(.*?)\n/', '<li>\\1</li>', $var_sReadme);
// 				$var_sReadme = preg_replace('/\\*\\*(.*?)\\*\\*/', ' <strong>\\1</strong>', $var_sReadme);
// 				$var_sReadme = preg_replace('/\\*(.*?)\\*/', ' <em>\\1</em>', $var_sReadme);
// 				$var_sReadme = preg_replace('/=== (.*?) ===/', '<h2>\\1</h2>', $var_sReadme);
// 				$var_sReadme = preg_replace('/== (.*?) ==/', '<h3>\\1</h3>', $var_sReadme);
// 				$var_sReadme = preg_replace('/= (.*?) =/', '<h4>\\1</h4>', $var_sReadme);
// 				$var_sReadme = preg_replace('/\\[(.*?)\\]\\((.*?)\\)/', '<a href="\\2">\\1</a>', $var_sReadme);
// 				$var_sReadme = preg_replace('/`(.*?)`/', '<code>\\1</code>', $var_sReadme);

				// Speicher wieder freigeben, also bissle aufräumen
				unset($array_Changes);
				unset($array_Read_Me);
				unset($array_Readme);
				unset($array_Temp);

				return $array_ReadmeSorted;
			} else {
				return false;
			} // END if(!empty($var_sReadme))
		} // END private function _get_changelog_from_reame()
	} // END class Twoclick_Social_Media_Buttons_Backend_Changelog extends Twoclick_Social_Media_Buttons_Backend

	new Twoclick_Social_Media_Buttons_Backend_Changelog();
} // END if(!class_exists('Twoclick_Social_Media_Buttons_Backend_Changelog'))