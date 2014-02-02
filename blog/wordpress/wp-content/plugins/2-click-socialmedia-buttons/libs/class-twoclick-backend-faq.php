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
 * The F.A.Q. Class
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!class_exists('Twoclick_Social_Media_Buttons_Backend_Faq')) {
	class Twoclick_Social_Media_Buttons_Backend_Faq extends Twoclick_Social_Media_Buttons_Backend {

		/**
		 * Konstruktor
		 */
		function __construct() {
			if($this->_is_twoclick_settings_page()) {
				$this->_get_faq();
			} // END if($this->_is_twoclick_settings_page())
		} // END function __construct()

		/**
		 * <[ Helper ]>
		 * FAQ anzeigen
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function _get_faq() {
			$array_Faq = $this->_get_faq_from_readme();

			if($array_Faq === false) {
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

			// Hinweis auf die Donate-Seite :-)
			if(is_array($array_Faq)) {
				$array_Faq[] = array(
					'question' => 'Kann ich dem Entwickler ein kleines Dankeschön zukommen lassen?',
					'answer' => 'Aber natürlich. Wirf dazu einfach mal einen Blick in den Tab "<a href="' . admin_url('options-general.php?page=twoclick_buttons&tab=donate') . '">Spenden</a>".'
				);
				?>
				<div class="metabox-holder clearfix">
					<div id="post-body">
						<div id="post-body-content">
							<div class="postbox clearfix">
								<h3><span><strong>Übersicht</strong></span></h3>
								<div class="inside">
									<ul>
										<?php
										foreach((array) $array_Faq as $var_sKey => $array_Value) {
											?>
											<li><a href="#question-<?php echo $var_sKey; ?>"><?php echo $array_Value['question']; ?></a></li>
											<?php
										} // END foreach((array) $array_Faq as $var_sKey => $array_Value)
										?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				foreach((array) $array_Faq as $var_sKey => $array_Value) {
					?>
					<div id="question-<?php echo $var_sKey; ?>" class="metabox-holder clearfix">
						<div id="post-body">
							<div id="post-body-content">
								<div class="postbox clearfix">
									<h3><span><strong>Frage:</strong><br /><?php echo $array_Value['question']; ?></span></h3>
									<div class="inside">
										<p>
										<?php echo $array_Value['answer']; ?>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				} // END foreach((array) $array_Faq as $var_sKey => $array_Value)
			} // END if(is_array($array_Faq))
		} // END private function _get_faq()

		/**
		 * <[ Helper ]>
		 * FAQ aus der readme.txt friemeln
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function _get_faq_from_readme() {
			$var_sFaq = @wp_remote_retrieve_body(wp_remote_get(TWOCLICK_PLUGIN_URI . 'readme.txt'));

			if(is_wp_error($var_sFaq)) {
				return false;
			} // END if(is_wp_error($var_sFaq))

			if(!empty($var_sFaq)) {
				$var_sFaq = str_replace(substr($var_sFaq, 0, strpos($var_sFaq, '== Frequently Asked Questions ==')), '', $var_sFaq);
				$var_sFaq = str_replace('== Frequently Asked Questions ==', '', $var_sFaq);
				$var_sFaq = substr($var_sFaq, 0, strpos($var_sFaq, '=='));

				$array_Faq = explode('= ', $var_sFaq);

				$array_FaqSorted = array();
				foreach((array) $array_Faq as $array_F_A_Q) {
					$array_Temp = array();
					$array_Temp = explode("\n", $array_F_A_Q);
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

						$var_sQuestion = trim($array_Temp['0']);
// 						$var_sAnswer = trim(preg_replace('/\\((.*?)\\)/', '\\1', $array_Temp['1']));
						$var_sAnswer = trim($array_Temp['1']);

						unset($array_Temp['0']);
						unset($array_Temp['1']);

						$array_Changes = array();
						foreach((array) $array_Temp as $var_iKey => $var_sValue) {
							$array_Changes[] = trim($var_sValue);
						} // END foreach((array) $array_Temp as $var_iKey => $var_sValue)

						$array_FaqSorted[] = array(
							'question' => $var_sQuestion,
							'answer' => $var_sAnswer,
						);
					} // END if(!empty($array_Temp['0']))
				} // END foreach((array) $array_Faq as $array_F_A_Q)

				unset($array_F_A_Q);
				unset($array_Faq);
				unset($array_Temp);

				return $array_FaqSorted;
			} else {
				return false;
			} // END if(!empty($var_sReadme))
		} // END private function _get_faq_from_readme()
	} // END class Twoclick_Social_Media_Buttons_Backend_Faq extends Twoclick_Social_Media_Buttons_Backend

	new Twoclick_Social_Media_Buttons_Backend_Faq();
} // END if(!class_exists('Twoclick_Social_Media_Buttons_Backend_Faq'))