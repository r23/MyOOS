<?php
if (!defined('ABSPATH')) {
	die('-1');
}

if (!class_exists('WPMI_Backend')) {

	class WPMI_Backend extends WPMI
	{

		private static $instance;
		protected static $fields = array('icon');

		function wp_update_nav_menu_item($menu_id, $menu_item_db_id, $menu_item_args)
		{

			if (!wp_doing_ajax()) {

				$menu_item_wpmi = array();

				check_admin_referer('update-nav_menu', 'update-nav-menu-nonce');

				if (!empty($_POST['wpmi'][$menu_item_db_id])) {

					$menu_item_wpmi['label'] = absint($_POST['wpmi'][$menu_item_db_id]['label']);
					$menu_item_wpmi['position'] = sanitize_html_class($_POST['wpmi'][$menu_item_db_id]['position']);
					$menu_item_wpmi['icon'] = esc_attr($_POST['wpmi'][$menu_item_db_id]['icon']);
					$menu_item_wpmi['color'] = sanitize_text_field($_POST['wpmi'][$menu_item_db_id]['color']);
					$menu_item_wpmi['bgcolor'] = sanitize_text_field($_POST['wpmi'][$menu_item_db_id]['bgcolor']);
					
					$this->update($menu_item_db_id, $menu_item_wpmi);
				}
			}
		}

		function update($id, $value)
		{

			$value = apply_filters('wp_menu_icons_item_meta_values', $value, $id);

			if (!empty($value)) {
				update_post_meta($id, WPMI_DB_KEY, $value);
			} else {
				delete_post_meta($id, WPMI_DB_KEY);
			}
		}

		function enqueue()
		{

			global $pagenow;

			if ($pagenow != 'nav-menus.php')
				return;

			wp_enqueue_media();

			wp_enqueue_style('wpmi-icomoon', get_template_directory_uri() . '/css/admin-nav-menus.css', WPMI_PLUGIN_VERSION, 'all');
			
			wp_enqueue_style('wpmi-admin', get_template_directory_uri() . '/inc/plugins/menu-icons/assets/css/admin.css', array('wp-color-picker'), WPMI_PLUGIN_VERSION, 'all');

			wp_enqueue_script('wpmi', get_template_directory_uri() . '/inc/plugins/menu-icons/assets/js/modal.js', array(
				'jquery',
				'backbone',
				'underscore',
				'wp-util',
				'wp-color-picker'
			), 2);
			wp_localize_script('wpmi', 'wpmi_l10n', array(
				'legacy_pick' => esc_html__('Select'),
				'legacy_current' => esc_html__('Color'),
				'nonce' => wp_create_nonce('wpmi'),
			));
			wp_localize_script('wpmi', 'wpmi_options', array(
				'predefined_colors' => $this->get_predefined_colors()
			));
			
		}

		function walker($walker)
		{

			$walker = 'Menu_Item_Custom_Fields_Walker';

			if (!class_exists($walker)) {
				require_once('walker.php');
			}

			return $walker;
		}

		public function print_media_templates()
		{

			global $pagenow;

			if ($pagenow != 'nav-menus.php')
				return;

?>
			<script type="text/html" id='tmpl-wpmi-modal-backdrop'>
				<div class="media-modal-backdrop">&nbsp;</div>
			</script>
			<script type="text/html" id='tmpl-wpmi-modal-window'>
				<div id="<?php echo esc_attr(WPMI_DOMAIN . '_modal'); ?>" class="media-modal wp-core-ui">
					<button type="button" class="media-modal-close close">
						<span class="media-modal-icon">
							<span class="screen-reader-text"><?php esc_html_e('Close media panel'); ?></span>
						</span>
					</button>
					<div class="media-frame mode-select wp-core-ui hide-menu">
						<div class="media-frame-title">
							<h1><?php esc_html_e('Choose Icon'); ?>
							</h1>
						</div>
						<div class="media-modal-content">
							<div class="media-frame mode-select wp-core-ui">
								<div class="media-frame-menu">
									<div class="media-menu">
										<a href="#" class="media-menu-item active"><?php esc_html_e('Featured Image'); ?></a>
									</div>
								</div>
								<div class="media-frame-content" data-columns="12">
									<div class="attachments-browser">
										<div class="media-toolbar">
											<div class="media-toolbar-primary search-form">
												<input type="search" placeholder="<?php esc_html_e('Search...'); ?>" id="media-search-input" class="search">
											</div>
										</div>
										<ul tabindex="-1" class="attachments">
											<?php foreach (_WPMI()->get_icons() as $id => $icon) : ?>
												<li tabindex="0" role="checkbox" aria-label="<?php echo esc_attr($icon); ?>" aria-checked="false" data-id="<?php echo esc_attr($id); ?>" class="attachment save-ready icon _<?php echo esc_attr(str_replace(' ', '_', trim($icon))); ?>">
													<div class="attachment-preview js--select-attachment type-image subtype-jpeg landscape">
														<div class="thumbnail">
															<i class="<?php echo esc_attr($icon); ?>"></i>
														</div>
													</div>
													<button type="button" class="check" tabindex="-1">
														<span class="media-modal-icon"></span>
														<span class="screen-reader-text"><?php esc_html_e('Deselect'); ?></span>
													</button>
												</li>
											<?php endforeach; ?>
										</ul>
										<div class="media-sidebar">
											<div tabindex="0" class="attachment-details save-ready">
												<h2>
													<?php esc_html_e('Icon'); ?>
													<span class="settings-save-status">
														<span class="spinner"></span>
														<span class="saved">
															<?php esc_html_e('Saved'); ?>
														</span>
													</span>
												</h2>
											</div>
										</div>
									</div>
								</div>
								<div class="media-frame-toolbar">
									<div class="media-toolbar">
										<div class="media-toolbar-secondary"></div>
										<div class="media-toolbar-primary search-form">
											<button type="button" class="button media-button button-large button-primary media-button-select save"><?php esc_html_e('Save'); ?></button>
											<button type="button" class="button media-button button-large button-secondary remove"><?php esc_html_e('Remove'); ?></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</script>
			<script type="text/html" id='tmpl-wpmi-modal-preview'>
				<div class="attachment-info">
					<div class="thumbnail thumbnail-image">
						<i class="{{ data.icon }}"></i>
					</div>
				</div>
			</script>
			<script type="text/html" id='tmpl-wpmi-modal-settings'>
				<div class="attachment-info">

					<form>
						<span class="setting">
							<label><?php esc_html_e('Hide Label'); ?></label>
							<select id="<?php echo esc_attr(WPMI_DOMAIN . '-input-label'); ?>" class="<?php echo esc_attr(WPMI_DOMAIN . '-input'); ?>" name="<?php echo esc_attr(WPMI_DOMAIN . '[label]'); ?>">
								<option <# if ( data.label !=1) { #>selected<# } #> value=""><?php esc_html_e('No'); ?></option>
								<option <# if ( data.label==1) { #>selected<# } #> value="1"><?php esc_html_e('Yes'); ?></option>
							</select>
						</span>
						<span class="setting">
							<label><?php esc_html_e('Position'); ?></label>
							<select id="<?php echo esc_attr(WPMI_DOMAIN . '-input-position'); ?>" class="<?php echo esc_attr(WPMI_DOMAIN . '-input'); ?>" name="<?php echo esc_attr(WPMI_DOMAIN . '[position]'); ?>">
								<option <# if ( data.position=='before' ) { #>selected<# } #> value="before"><?php esc_html_e('Before'); ?></option>
								<option <# if ( data.position=='after' ) { #>selected<# } #> value="after"><?php esc_html_e('After'); ?></option>
							</select>
						</span>
						<div class="setting-color-picker">
							<label><?php esc_html_e('Icon Color'); ?></label>
							<div>
								<input id="<?php echo esc_attr(WPMI_DOMAIN . '-input-color'); ?>" class="<?php echo esc_attr(WPMI_DOMAIN . '-input'); ?>" name="<?php echo esc_attr(WPMI_DOMAIN . '[color]'); ?>" type="text" value="{{ data.color }}" data-alpha="true">
							</div>
						</div>
						<div class="setting-color-picker">
							<label><?php esc_html_e('Icon Background Color'); ?></label>
							<div>
								<input id="<?php echo esc_attr(WPMI_DOMAIN . '-input-bgcolor'); ?>" class="<?php echo esc_attr(WPMI_DOMAIN . '-input'); ?>" name="<?php echo esc_attr(WPMI_DOMAIN . '[bgcolor]'); ?>" type="text" value="{{ data.bgcolor }}" data-alpha="true">
							</div>
						</div>

						<input id="<?php echo esc_attr(WPMI_DOMAIN . '-input-icon'); ?>" class="<?php echo esc_attr(WPMI_DOMAIN . '-input'); ?>" name="<?php echo esc_attr(WPMI_DOMAIN . '[icon]'); ?>" type="hidden" value="{{ data.icon }}">
						
						

					</form>
				</div>
			</script>
		<?php
			$this->print_media_css();
		}

		public function print_media_css(){
			?>
			<style type="text/css">
			.hide{
				display: none;
			}
			.cpti-color-dropdown {
			  box-sizing: border-box;
			  position: relative;
			  width: 200px;
			  color: #2e2e2e;
			  outline: none;
			  cursor: pointer;
			}
			.cpti-color-dropdown > span {
				box-sizing: border-box;
				width: 100%;
				display: block;
				padding: 5px 5px 5px 8px;
				border-radius: 4px;
				border: 1px solid #7e8993;
				background-color: #fff;
				color: #32373c;
				font-size: 13px;
			}
			.cpti-color-dropdown > span > span {
			  box-sizing: border-box;
			  padding: 0 12px;
			  margin-right: 5px;
			}
			.cpti-color-dropdown > span:after {
			  content: "";
			  width: 0;
			  height: 0;
			  position: absolute;
			  right: 16px;
			  /*top: calc(50% + 4px);*/
			  top: 18px;
			  margin-top: -6px;
			  border-width: 0px 6px 6px 6px;
			  border-style: solid;
			  border-color: #2e2e2e transparent;
			}
			.cpti-color-dropdown.closed > span:after { 
				border-width: 6px 6px 0px 6px;
			}

			.cpti-color-dropdown .cpti-color-list {
			  box-sizing: border-box;
			  /*position: absolute;*/
			  z-index: 10;
			  top: 100%;
			  left: 0;
			  right: 0;
			  background: #fff;
			  font-weight: normal;
			  list-style-type: none;
			  padding-left: 0;
			  margin: 0 4px;
			  border: 1px solid #ababab;
			  border-top: 0;
			  max-height: 100px;
			  overflow: auto;
			}
			.cpti-color-dropdown.closed .cpti-color-list{
			  display: none;;
			}

			.cpti-color-dropdown .cpti-color-list li {
			  box-sizing: border-box;
			  display: block;
			  text-decoration: none;
			  color: #2e2e2e;
			  padding: 5px;
			  cursor: pointer;
			}

			.cpti-color-dropdown .cpti-color-list li > span {
			  box-sizing: border-box;
			  padding: 0 12px;
			  margin-right: 5px;
			  background: #fff none repeat scroll 0% 0%;
			}

			.cpti-color-dropdown .cpti-color-list li:hover {
			  background: #eee;
			  cursor: pointer;
			}
			.cpti-custom-color-picker-wrap{
				margin-top: 5px;
			}
			

			.cpti-add-icon-label {
			    float: left;
			    margin-right: 4px;
			    font-style: italic;
			}

			</style>
			<?php 
		}

		function icon($menu_item_id, $item, $depth, $args)
		{
		?>
			<span class="menu-item-wpmi_open" title="<?php echo esc_attr('Set Icon'); ?>">
				<?php if (!empty($item->wpmi->icon)) : ?>
					<i class="menu-item-wpmi_icon <?php echo esc_attr($item->wpmi->icon); ?>"></i>
				<?php endif; ?>
				<i class="menu-item-wpmi_plus dashicons dashicons-plus"></i>
			</span>
		<?php
		}

		function fields($menu_item_id, $item, $depth, $args)
		{
		?>
			<fieldset class="description description-wide">
				<span class="cpti-add-icon-label">Icon</span><button type="button" class="button-link menu-item-wpmi_open"  style="display: inline;">Add/Edit</button>
			</fieldset>
			<?php
			foreach ($this->default_values as $key => $value) {
			?>
				<input id="<?php echo esc_attr(WPMI_DOMAIN . '-input-' . $key); ?>" class="<?php echo esc_attr(WPMI_DOMAIN . '-input'); ?>" type="hidden" name="<?php echo esc_attr(WPMI_DOMAIN . '[' . $menu_item_id . '][' . $key . ']'); ?>" value="<?php echo esc_attr($item->wpmi->{$key}); ?>">
<?php
			}
		}

		function init()
		{
			add_action('admin_enqueue_scripts', array($this, 'enqueue'));
			add_filter('wp_edit_nav_menu_walker', array($this, 'walker'), 99);
			add_action('wp_nav_menu_item_custom_fields', array($this, 'fields'), 10, 4);
			add_action('wp_nav_menu_item_custom_title', array($this, 'icon'), 10, 4);
			add_action('wp_update_nav_menu_item', array($this, 'wp_update_nav_menu_item'), 10, 3);
			add_action('print_media_templates', array($this, 'print_media_templates'));
		}

		public static function instance()
		{
			if (!isset(self::$instance)) {
				self::$instance = new self();
				self::$instance->init();
			}
			return self::$instance;
		}
	}

	WPMI_Backend::instance();
}
