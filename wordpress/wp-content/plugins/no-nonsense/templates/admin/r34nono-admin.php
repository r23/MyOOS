<?php
// Don't load directly
if (!defined('ABSPATH')) { exit; }

// Only load in admin
if (!is_admin()) { exit; }

global $r34nono;
?>

<div class="wrap r34nono">
	<h2><?php echo get_admin_page_title(); ?></h2>

	<div class="metabox-holder columns-2">
	
		<div class="column-1">

			<div class="postbox">

				<nav class="r34nono-menu r34nono-primary-menu">
					<ul>
						<li><a href="#settings" data-current="current"><?php _e('Settings', 'no-nonsense'); ?></a></li>
						<li><a href="#utilities"><?php _e('Utilities', 'no-nonsense'); ?></a></li>
						<li><a href="#import-export"><?php _e('Import/Export', 'no-nonsense'); ?></a></li>
					</ul>
				</nav>
			
				<nav class="r34nono-menu r34nono-secondary-menu" data-section="#settings">
					<ul>
						<?php
						$i = 1;
						foreach (array_keys((array)r34nono_group_settings()) as $group) {
							?>
							<li><a href="#settings-<?php echo intval($i); ?>"><?php echo wp_kses_post($group); ?></a></li>
							<?php
							$i++;
						}
						?>
					</ul>
				</nav>
				
				<div class="inside">
	
					<section id="settings" data-current="current">
					
						<form method="post" action="" class="r34nono-admin" autocomplete="force-off">
							<?php wp_nonce_field('r34nono-nonce-settings','r34nono-nonce-settings'); ?>
					
							<h3 class="screen-reader-text"><?php _e('Settings', 'no-nonsense'); ?></h3>

							<div class="r34nono-flex-fill">
							
								<p><?php _e('Settings are persistent configuration changes that modify default behaviors on each page load.', 'no-nonsense'); ?></p>
							
								<div>
									<input type="submit" value="<?php echo esc_attr(__('Save Settings', 'no-nonsense')); ?>" class="button button-primary button-disabled" />
								</div>

							</div>
						
							<?php
							$i = 1;
							foreach ((array)r34nono_group_settings() as $group => $group_fns) {
								?>
								<table class="form-table r34nono-settings-table" id="settings-<?php echo intval($i); ?>"><tbody>
									<tr class="r34nono-table-header-row"><td colspan="2"><strong><?php echo wp_kses_post($group); ?></strong></td></tr>
									<?php
									foreach ((array)$group_fns as $name => $item) {
										if (empty($item['show_in_admin'])) { continue; }
										$current_value = get_option($name);
										?>
										<tr>
											<td style="white-space: nowrap;">
												<label for="<?php echo esc_attr($name); ?>_0" class="r34nono-toggle-off<?php if ($current_value == 0) { echo ' selected'; } ?>"><input type="radio" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>_0" value="0"<?php if ($current_value == 0) { echo ' checked="checked"'; } ?> />&nbsp;<?php _e('Off', 'no-nonsense'); ?></label><label for="<?php echo esc_attr($name); ?>_1" class="r34nono-toggle-on<?php if ($current_value == 1) { echo ' selected'; } ?>"><input type="radio" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>_1" value="1"<?php if ($current_value == 1) { echo ' checked="checked"'; } ?> />&nbsp;<?php _e('On', 'no-nonsense'); ?></label>
											</td>
											<td style="width: 100%;">
												<strong><?php echo wp_kses_post($item['title']); ?></strong>
												<span class="help<?php if (!empty($item['has_warning'])) { echo ' warning'; } ?>"><span class="help_content"><?php echo wp_kses_post($item['description']); ?></span></span>
												<?php
												if (!empty($item['options'])) {
													$fn_options = get_option($name . '_options');
													?>
													<div class="r34nono-options-toggle" data-fn="<?php echo esc_attr($name); ?>">
														<!-- This hidden field is for handling situations where all options are being deselected -->
														<input type="hidden" name="<?php echo esc_attr($name); ?>_options[]" value="" />
														<?php
														foreach ((array)$item['options'] as $opt_name => $opt_label) {
															?>
															<label for="<?php echo esc_attr($name); ?>_options_<?php echo esc_attr($opt_name); ?>"><input type="checkbox" name="<?php echo esc_attr($name); ?>_options[<?php echo esc_attr($opt_name); ?>]" id="<?php echo esc_attr($name); ?>_options_<?php echo esc_attr($opt_name); ?>" value="1"<?php
															if (!empty($fn_options[$opt_name])) { echo ' checked="checked"'; }
															?> />&nbsp;<?php echo wp_kses_post($opt_label); ?></label>
															<?php
														}
														?>
													</div>
													<?php
												}
												?>
											</td>
										</tr>
										<?php
									}
									?>
								</tbody></table>
								<?php
								$i++;
							}
							?>
				
						</form>
					
					</section>
					
					<section id="utilities">
				
						<form method="post" action="" class="r34nono-admin" autocomplete="force-off">
							<?php wp_nonce_field('r34nono-nonce-utilities','r34nono-nonce-utilities'); ?>
					
							<h3 class="screen-reader-text"><?php _e('Utilities', 'no-nonsense'); ?></h3>
							
							<div class="r34nono-flex-fill">
					
								<div>
								
									<p><?php _e('Utilities are one-time actions to clean up default content, plugins and options in the default WordPress installation.', 'no-nonsense'); ?></p>
					
									<p><span class="help warning inline nohover"></span><strong><?php _e('Utilities with this icon make permanent, irreversible changes, and are intended for use only on a new WordPress installation. Proceed with caution.', 'no-nonsense'); ?></strong></p>
								
								</div>

								<div style="text-align: right;">
									<input type="submit" value="<?php echo esc_attr(__('Run Selected Utilities', 'no-nonsense')); ?>" class="button button-primary button-disabled" onclick="if (!jQuery(this).hasClass('button-disabled') && !confirm('<?php esc_attr_e('Are you sure? This cannot be undone.'); ?>')) { return false; }" />
								</div>
							
							</div>

							<table class="form-table r34nono-utilities-table"><tbody>
					
								<?php
								foreach ((array)$r34nono->utilities as $name => $item) {
									if (empty($item['show_in_admin'])) { continue; }
									?>
									<tr>
										<td style="white-space: nowrap;">
											<input type="checkbox" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>" />
										</td>
										<td style="width: 100%;">
											<strong><label for="<?php echo esc_attr($name); ?>"><?php echo wp_kses_post($item['title']); ?></label></strong>
											<span class="help<?php if (!empty($item['has_warning'])) { echo ' warning'; } ?>"><span class="help_content"><?php echo wp_kses_post($item['description']); ?></span></span>
										</td>
									</tr>
									<?php
								}
								?>
					
							</tbody></table>
										
						</form>
					
					</section>
					
					<section id="import-export">
				
						<form method="post" action="" class="r34nono-admin" autocomplete="force-off">
							<?php wp_nonce_field('r34nono-nonce-import-export','r34nono-nonce-import-export'); ?>
					
							<h3 class="screen-reader-text"><?php _e('Import/Export Settings', 'no-nonsense'); ?></h3>
				
							<h4><?php _e('Import Settings', 'no-nonsense'); ?></h4>
				
							<p><?php _e('Paste your saved JSON code in the input box below, then click the button to upload a file containing your saved JSON settings. (Note: Invalid or improperly formatted JSON code will be rejected.)', 'no-nonsense'); ?></p>
						
							<div class="r34nono-flex-fill">
					
								<div>
								
									<label for="import-settings-json" class="screen-reader-text">Paste JSON code to import</label>
									<textarea name="r34nono-import-settings-json" id="import-settings-json" style="font-family: monospace; width: 100%; height: clamp(5rem, 25vh, 15rem);"></textarea>
													
								</div>

								<div style="text-align: right;">
									<input type="submit" value="<?php echo esc_attr(__('Import Settings JSON', 'no-nonsense')); ?>" class="button button-primary" onclick="if (!jQuery(this).hasClass('button-disabled') && !confirm('<?php esc_attr_e('Are you sure? This cannot be undone.'); ?>')) { return false; }" />
								</div>
							
							</div>

							<hr />

							<h4><?php _e('Export Settings', 'no-nonsense'); ?></h4>
							
							<p><?php _e('Copy the following JSON code and paste it into a text file to save for later use.', 'no-nonsense'); ?></p>
							
							<p id="export-settings-warning" style="display: none;"><span class="help warning inline nohover"></span><strong><?php _e('You have unsaved changes under the Settings tab. Please switch to Settings and click the "Save Settings" button before exporting.', 'no-nonsense'); ?></strong></p>

							<label for="export-settings-json" class="screen-reader-text">Copy JSON code to export</label>
							<textarea id="export-settings-json" readonly="readonly" style="font-family: monospace; height: clamp(5rem, 25vh, 15rem); width: 100%;" onclick="this.select();"><?php echo $r34nono->export_options_json(); ?></textarea>
										
						</form>
					
					</section>
					
				</div>
			
			</div>

		</div>
	
		<div class="column-2">

			<div class="postbox">

				<div class="inside">
	
					<div><a href="https://wordpress.org/support/plugin/no-nonsense" target="_blank"><img src="<?php echo dirname(dirname(plugin_dir_url(__FILE__))); ?>/assets/no-nonsense-icon.svg" alt="No Nonsense" style="display: block; height: auto; margin: 1.5em auto 0.5em auto; width: 80px;" /></a></div>

					<p><?php echo sprintf(__('For support with the %1$s plugin, please use the %2$sWordPress Support Forums%3$s or email %4$s.', 'no-nonsense'), '<strong>No Nonsense</strong>', '<a href="https://wordpress.org/support/plugin/no-nonsense" target="_blank">', '</a>', '<a href="mailto:support@room34.com">support@room34.com</a>'); ?></p>
		
				</div>

			</div>

			<div class="postbox">

				<div class="inside">
	
					<div><a href="https://room34.com/about/payments/?type=WordPress+Plugin&plugin=No+Nonsense&amt=9" target="_blank"><img src="<?php echo dirname(dirname(plugin_dir_url(__FILE__))); ?>/assets/room34-logo-on-white.svg" alt="Room 34 Creative Services" style="display: block; height: auto; margin: 1.5em auto 0.5em auto; width: 160px;" /></a></div>
					
					<p><?php _e('This plugin is free to use. However, if you find it to be of value, we welcome your donation (suggested amount: USD $9), to help fund future development.', 'no-nonsense'); ?></p>

					<p style="text-align: center;"><a href="https://room34.com/about/payments/?type=WordPress+Plugin&plugin=No+Nonsense&amt=9" target="_blank" class="button"><?php _e('Make a Donation', 'no-nonsense'); ?></a></p>
					
					<p style="text-align: center;"><strong><?php _e('Thank You!', 'no-nonsense'); ?></strong></p>
		
				</div>
		
			</div>
		
			<p><small>No Nonsense v. <?php echo wp_kses_post(get_option('r34nono_version')); ?></small></p>
		
		</div>
	
	</div>

</div>