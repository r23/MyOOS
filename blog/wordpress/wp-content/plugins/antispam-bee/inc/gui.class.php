<?php


/* Sicherheitsabfrage */
if ( ! class_exists('Antispam_Bee') ) {
	die();
}


/**
* Antispam_Bee_GUI
*
* @since  2.4
*/

class Antispam_Bee_GUI extends Antispam_Bee {


	/**
	* Speicherung der GUI
	*
	* @since   0.1
	* @change  2.5.2
	*/

	public static function save_changes()
	{
		/* Kein POST? */
		if ( empty($_POST) ) {
			wp_die(__('Cheatin&#8217; uh?'));
		}

		/* Capability check */
		if ( ! current_user_can('manage_options') ) {
			wp_die(__('Cheatin&#8217; uh?'));
		}

		/* Referer prüfen */
		check_admin_referer('antispam_bee');

		/* Optionen ermitteln */
		$options = array(
			'flag_spam' 		=> (int)(!empty($_POST['ab_flag_spam'])),
			'email_notify' 		=> (int)(!empty($_POST['ab_email_notify'])),
			'cronjob_enable' 	=> (int)(!empty($_POST['ab_cronjob_enable'])),
			'cronjob_interval'	=> (int)self::get_key($_POST, 'ab_cronjob_interval'),

			'no_notice' 		=> (int)(!empty($_POST['ab_no_notice'])),

			'dashboard_count' 	=> (int)(!empty($_POST['ab_dashboard_count'])),
			'dashboard_chart' 	=> (int)(!empty($_POST['ab_dashboard_chart'])),
			'advanced_check' 	=> (int)(!empty($_POST['ab_advanced_check'])),
			'regexp_check' 		=> (int)(!empty($_POST['ab_regexp_check'])),
			'spam_ip' 			=> (int)(!empty($_POST['ab_spam_ip'])),
			'already_commented'	=> (int)(!empty($_POST['ab_already_commented'])),
			'time_check'		=> (int)(!empty($_POST['ab_time_check'])),
			'always_allowed' 	=> (int)(!empty($_POST['ab_always_allowed'])),

			'ignore_pings' 		=> (int)(!empty($_POST['ab_ignore_pings'])),
			'ignore_filter' 	=> (int)(!empty($_POST['ab_ignore_filter'])),
			'ignore_type' 		=> (int)self::get_key($_POST, 'ab_ignore_type'),

			'reasons_enable' 	=> (int)(!empty($_POST['ab_reasons_enable'])),
			'ignore_reasons' 	=> (array)self::get_key($_POST, 'ab_ignore_reasons'),

			'bbcode_check'		=> (int)(!empty($_POST['ab_bbcode_check'])),
			'dnsbl_check'		=> (int)(!empty($_POST['ab_dnsbl_check'])),

			'country_code' 		=> (int)(!empty($_POST['ab_country_code'])),
			'country_black'		=> sanitize_text_field(self::get_key($_POST, 'ab_country_black')),
			'country_white'		=> sanitize_text_field(self::get_key($_POST, 'ab_country_white')),

			'translate_api' 	=> (int)(!empty($_POST['ab_translate_api'])),
			'translate_lang'	=> sanitize_text_field(self::get_key($_POST, 'ab_translate_lang'))
		);

		/* Keine Tagmenge eingetragen? */
		if ( empty($options['cronjob_interval']) ) {
			$options['cronjob_enable'] = 0;
		}


		/* Translate API */
		if ( !empty($options['translate_lang']) ) {
			if ( !preg_match('/^(de|en|fr|it|es)$/', $options['translate_lang']) ) {
				$options['translate_lang'] = '';
			}
		}
		if ( empty($options['translate_lang']) ) {
			$options['translate_api'] = 0;
		}

		/* Liste der Spamgründe */
		if ( empty($options['reasons_enable']) ) {
			$options['ignore_reasons'] = array();
		}

		/* Blacklist reinigen */
		if ( !empty($options['country_black']) ) {
			$options['country_black'] = preg_replace(
				'/[^A-Z ]/',
				'',
				strtoupper($options['country_black'])
			);
		}

		/* Whitelist reinigen */
		if ( !empty($options['country_white']) ) {
			$options['country_white'] = preg_replace(
				'/[^A-Z ]/',
				'',
				strtoupper($options['country_white'])
			);
		}

		/* Leere Listen? */
		if ( empty($options['country_black']) && empty($options['country_white']) ) {
			$options['country_code'] = 0;
		}


		/* Cron stoppen? */
		if ( $options['cronjob_enable'] && !self::get_option('cronjob_enable') ) {
			self::init_scheduled_hook();
		} else if ( !$options['cronjob_enable'] && self::get_option('cronjob_enable') ) {
			self::clear_scheduled_hook();
		}

		/* Optionen speichern */
		self::update_options($options);

		/* Redirect */
		wp_safe_redirect(
			add_query_arg(
				array(
					'updated' => 'true'
				),
				wp_get_referer()
			)
		);

		die();
	}


	/**
	* Generierung eine Selectbox
	*
	* @since   2.4.5
	* @change  2.4.5
	*
	* @param   string  $name      Name der Selectbox
	* @param   array   $data      Array mit Werten
	* @param   string  $selected  Selektierter Wert
	* @return  string  $html      Erzeugtes HTML
	*/

	private static function _build_select($name, $data, $selected)
	{
		/* Start HTML */
		$html = '<select name="' .$name. '">';

		/* Loop options */
		foreach( $data as $k => $v) {
			$html .= '<option value="' .esc_attr($k). '" ' .selected($selected, $k, false). '>' .esc_html__($v, 'antispam_bee'). '</option>';
		}

		/* Close HTML */
		$html .= '</select>';

		return $html;
	}


	/**
	* Anzeige der GUI
	*
	* @since   0.1
	* @change  2.6.4
	*/

	public static function options_page() { ?>
		<div class="wrap" id="ab_main">
			<h2>
				Antispam Bee
			</h2>

			<form action="<?php echo admin_url('admin-post.php') ?>" method="post">
				<input type="hidden" name="action" value="ab_save_changes" />

				<?php wp_nonce_field('antispam_bee') ?>

				<?php $options = self::get_options() ?>

				<div class="ab-wrap">
					<!--[if lt IE 9]>
						<p class="browsehappy">
							<a href="http://browsehappy.com">Browse Happy</a>
						</p>
					<![endif]-->

					<div class="ab-column ab-arrow">
						<h3 class="icon">
							<?php esc_html_e('Antispam filter', 'antispam_bee') ?>
						</h3>
						<h6>
							<?php esc_html_e('Filter in the execution order', 'antispam_bee') ?>
						</h6>

						<ul>
							<li>
								<input type="checkbox" name="ab_already_commented" id="ab_already_commented" value="1" <?php checked($options['already_commented'], 1) ?> />
								<label for="ab_already_commented">
									<?php esc_html_e('Trust approved commentators', 'antispam_bee') ?>
									<span><?php esc_html_e('No check for already commenting users', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_time_check" id="ab_time_check" value="1" <?php checked($options['time_check'], 1) ?> />
								<label for="ab_time_check">
									<?php esc_html_e('Consider the comment time', 'antispam_bee') ?>
									<span><?php esc_html_e('Not recommended when using page caching', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_bbcode_check" id="ab_bbcode_check" value="1" <?php checked($options['bbcode_check'], 1) ?> />
								<label for="ab_bbcode_check">
									<?php esc_html_e('BBCode is spam', 'antispam_bee') ?>
									<span><?php esc_html_e('Review the comment contents for BBCode links', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_advanced_check" id="ab_advanced_check" value="1" <?php checked($options['advanced_check'], 1) ?> />
								<label for="ab_advanced_check">
									<?php esc_html_e('Validate the ip address of commentators', 'antispam_bee') ?>
									<span><?php esc_html_e('Validity check for used ip address', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_regexp_check" id="ab_regexp_check" value="1" <?php checked($options['regexp_check'], 1) ?> />
								<label for="ab_regexp_check">
									<?php esc_html_e('Use regular expressions', 'antispam_bee') ?>
									<span><?php _e('Predefined and custom patterns by <a href="https://gist.github.com/4242142" target="_blank">plugin hook</a>', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_spam_ip" id="ab_spam_ip" value="1" <?php checked($options['spam_ip'], 1) ?> />
								<label for="ab_spam_ip">
									<?php esc_html_e('Look in the local spam database', 'antispam_bee') ?>
									<span><?php esc_html_e('Already marked as spam? Yes? No?', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_dnsbl_check" id="ab_dnsbl_check" value="1" <?php checked($options['dnsbl_check'], 1) ?> />
								<label for="ab_dnsbl_check">
									<?php esc_html_e('Use a public antispam database', 'antispam_bee') ?>
									<span><?php _e('Matching the ip address with <a href="https://dnsbl.tornevall.org" target="_blank">Tornevall</a>', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_country_code" id="ab_country_code" value="1" <?php checked($options['country_code'], 1) ?> />
								<label for="ab_country_code">
									<?php esc_html_e('Block comments from specific countries', 'antispam_bee') ?>
									<span><?php esc_html_e('Filtering the requests depending on country', 'antispam_bee') ?></span>
								</label>

								<ul>
									<li>
										<input type="text" name="ab_country_black" id="ab_country_black" value="<?php echo esc_attr($options['country_black']); ?>" class="ab-medium-field code" />
										<label for="ab_country_black">
											Blacklist <a href="http://www.iso.org/iso/country_names_and_code_elements" target="_blank">ISO Codes</a>
										</label>
									</li>
									<li>
										<input type="text" name="ab_country_white" id="ab_country_white" value="<?php echo esc_attr($options['country_white']); ?>" class="ab-medium-field code" />
										<label for="ab_country_white">
											Whitelist <a href="http://www.iso.org/iso/country_names_and_code_elements" target="_blank">ISO Codes</a>
										</label>
									</li>
								</ul>
							</li>

							<li>
								<input type="checkbox" name="ab_translate_api" id="ab_translate_api" value="1" <?php checked($options['translate_api'], 1) ?> />
								<label for="ab_translate_api">
									<?php esc_html_e('Allow comments only in certain language', 'antispam_bee') ?>
									<span><?php esc_html_e('Detection and approval in specified language', 'antispam_bee') ?></span>
								</label>

								<ul>
									<li>
										<select name="ab_translate_lang">
											<?php foreach( array('de' => 'German', 'en' => 'English', 'fr' => 'French', 'it' => 'Italian', 'es' => 'Spanish') as $k => $v ) { ?>
												<option <?php selected($options['translate_lang'], $k); ?> value="<?php echo esc_attr($k) ?>"><?php esc_html_e($v, 'antispam_bee') ?></option>
											<?php } ?>
										</select>
										<label for="ab_translate_lang">
											<?php esc_html_e('Language', 'antispam_bee') ?>
										</label>
									</li>
								</ul>
							</li>
						</ul>
					</div>


					<div class="ab-column ab-join">
						<h3 class="icon advanced">
							<?php esc_html_e('Advanced', 'antispam_bee') ?>
						</h3>
						<h6>
							<?php esc_html_e('Other antispam tools', 'antispam_bee') ?>
						</h6>

						<ul>
							<li>
								<input type="checkbox" name="ab_flag_spam" id="ab_flag_spam" value="1" <?php checked($options['flag_spam'], 1) ?> />
								<label for="ab_flag_spam">
									<?php esc_html_e('Mark as spam, do not delete', 'antispam_bee') ?>
									<span><?php esc_html_e('Keep the spam in my blog.', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_email_notify" id="ab_email_notify" value="1" <?php checked($options['email_notify'], 1) ?> />
								<label for="ab_email_notify">
									<?php esc_html_e('Notification by email', 'antispam_bee') ?>
									<span><?php esc_html_e('Sending an alert to the admin', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_no_notice" id="ab_no_notice" value="1" <?php checked($options['no_notice'], 1) ?> />
								<label for="ab_no_notice">
									<?php esc_html_e('Not save the spam reason', 'antispam_bee') ?>
									<span><?php esc_html_e('Spam reason as table column in the spam overview', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_cronjob_enable" id="ab_cronjob_enable" value="1" <?php checked($options['cronjob_enable'], 1) ?> />
								<label>
									<?php echo sprintf(
										esc_html__('Delete existing spam after %s days', 'antispam_bee'),
										'<input type="text" name="ab_cronjob_interval" value="' .esc_attr($options['cronjob_interval']). '" class="ab-mini-field" />'
									) ?>
									<span><?php esc_html_e('Cleaning up the database from old entries', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_ignore_filter" id="ab_ignore_filter" value="1" <?php checked($options['ignore_filter'], 1) ?> />
								<label>
									<?php echo sprintf(
										esc_html__('Limit on %s', 'antispam_bee'),
										self::_build_select(
											'ab_ignore_type',
											array(
												1 => 'Comments',
												2 => 'Pings'
											),
											$options['ignore_type']
										)
									); ?>
									<span><?php esc_html_e('Another type of spam will be deleted immediately', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_reasons_enable" id="ab_reasons_enable" value="1" <?php checked($options['reasons_enable'], 1) ?> />
								<label for="ab_reasons_enable">
									<?php esc_html_e('Delete comments by spam reasons', 'antispam_bee') ?>
									<span><?php esc_html_e('Multiple choice by pressing Ctrl/CMD', 'antispam_bee') ?></span>
								</label>

								<ul>
									<li>
										<select name="ab_ignore_reasons[]" id="ab_ignore_reasons" size="2" multiple>
											<?php foreach ( self::$defaults['reasons'] as $k => $v ) { ?>
												<option <?php selected(in_array($k, $options['ignore_reasons']), true); ?> value="<?php echo $k ?>"><?php esc_html_e($v, 'antispam_bee') ?></option>
											<?php } ?>
										</select>
										<label for="ab_ignore_reasons">
											<?php esc_html_e('Spam Reason', 'antispam_bee') ?>
										</label>
									</li>
								</ul>
							</li>
						</ul>
					</div>


					<div class="ab-column ab-diff">
						<h3 class="icon more">
							<?php esc_html_e('More', 'antispam_bee') ?>
						</h3>
						<h6>
							<?php esc_html_e('A few little things', 'antispam_bee') ?>
						</h6>

						<ul>
							<li>
								<input type="checkbox" name="ab_dashboard_chart" id="ab_dashboard_chart" value="1" <?php checked($options['dashboard_chart'], 1) ?> />
								<label for="ab_dashboard_chart">
									<?php esc_html_e('Statistics on the dashboard', 'antispam_bee') ?>
									<span><?php esc_html_e('Spam detection rate with daily values', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_dashboard_count" id="ab_dashboard_count" value="1" <?php checked($options['dashboard_count'], 1) ?> />
								<label for="ab_dashboard_count">
									<?php esc_html_e('Spam counter on the dashboard', 'antispam_bee') ?>
									<span><?php esc_html_e('Amount of identified spam comments', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_ignore_pings" id="ab_ignore_pings" value="1" <?php checked($options['ignore_pings'], 1) ?> />
								<label for="ab_ignore_pings">
									<?php esc_html_e('Do not check trackbacks / pingbacks', 'antispam_bee') ?>
									<span><?php esc_html_e('No spam check for trackback notifications', 'antispam_bee') ?></span>
								</label>
							</li>

							<li>
								<input type="checkbox" name="ab_always_allowed" id="ab_always_allowed" value="1" <?php checked($options['always_allowed'], 1) ?> />
								<label for="ab_always_allowed">
									<?php esc_html_e('Comment form used outside of posts', 'antispam_bee') ?>
									<span><?php esc_html_e('Check for comment forms on archive pages', 'antispam_bee') ?></span>
								</label>
							</li>
						</ul>
					</div>

					<div class="ab-column ab-column--service">
						<?php if ( get_locale() == 'de_DE' ) { ?>
							<p>
								<a href="http://playground.ebiene.de/antispam-bee-wordpress-plugin/" target="_blank">Online-Handbuch</a> &bull; <a href="http://cup.wpcoder.de/wordpress-antispam-guide/" target="_blank">Antispam-Guide</a>
							</p>
						<?php } ?>

						<p>
							<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=ZAQUT9RLPW8QN" target="_blank">PayPal</a> &bull; <a href="https://flattr.com/t/1323822" target="_blank">Flattr</a> &bull; <a href="https://www.amazon.de/registry/wishlist/2U5I7F9649LOJ/" target="_blank">Wishlist</a>
						</p>
					</div>

					<div class="ab-column ab-column--submit">
						<input type="submit" class="button button-primary" value="<?php _e('Save Changes') ?>" />
					</div>
				</div>
			</form>
		</div>
	<?php }
}