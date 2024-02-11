<?php

// Don't load directly
if (!defined('ABSPATH')) { exit; }

class R34NoNo {

	const NAME = 'No Nonsense';
	const VERSION = '3.3.1';
	
	public $functions = array();
	public $utilities = array();

	public function __construct() {
	
		// Settings details
		$this->settings = array(

			'r34nono_admin_bar_logout_link' => array(
				'title' => __('Admin bar logout link', 'no-nonsense'),
				'description' => __('Adds a color-highlighted logout link directly into the admin bar, next to the username. Helpful to remind users to log out when their session is done.', 'no-nonsense'),
				'hook_type' => 'action',
				'hook' => 'init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Admin Bar', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_auto_core_update_send_email_only_on_error' => array(
				'title' => __('Auto core update send email only on error', 'no-nonsense'),
				'description' => __('By default, site admins receive a notification email every time WordPress runs auto-updates. Turn this on to only receive emails if there is an error during the update process.', 'no-nonsense'),
				'hook_type' => 'filter',
				'hook' => 'auto_core_update_send_email',
				'priority' => 10,
				'pn' => 4,
				'group' => __('Security and Updates', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_disable_site_search' => array(
				'title' => __('Disable site search', 'no-nonsense'),
				'description' => __('If your site does not need search functionality, turn this on to cause all standard WordPress search URLs to redirect to the home page without performing a search. Does not affect admin search functionality. Also deregisters the search widget.', 'no-nonsense'),
				'hook_type' => 'action',
				'hook' => 'init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Front End', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_disallow_full_site_editing' => array(
				'title' => __('Disallow full site editing (FSE)', 'no-nonsense'),
				'description' => sprintf(__('Removes the "Edit site" link in the admin bar, the "Editor" link under "Appearance," and the FSE notice in the Customizer. Also redirects any direct attempts to access the FSE page to the admin dashboard. If this option is active, you do not need to use the %1$sRemove "Edit site" link%2$s option under "Admin Bar."', 'no-nonsense'), '<strong>', '</strong>'),
				'hook_type' => 'action',
				'hook' => 'init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Block Editor', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_hide_admin_bar_for_logged_in_non_editors' => array(
				'title' => __('Hide admin bar for logged-in non-editors', 'no-nonsense'),
				'description' => sprintf(__('Hides the admin bar on front-end pages for logged-in users with no editing capabilities. Admin bar will still display for these users when they access their profile page. %1$sNote:%2$s With this option turned on, you will need to provide another way on the front end of your site for logged-in users to access their profile page and the logout link.', 'no-nonsense'), '<strong>', '</strong>'),
				'hook_type' => 'action',
				'hook' => 'init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Admin Bar', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_limit_admin_elements_for_logged_in_non_editors' => array(
				'title' => __('Limit admin elements for logged-in non-editors', 'no-nonsense'),
				'description' => __('Hides parts of the admin sidebar menu and WordPress footer from logged-in users with no editing capabilities.', 'no-nonsense'),
				'hook_type' => 'action',
				'hook' => 'admin_menu',
				'priority' => 11,
				'pn' => 0,
				'group' => __('Admin Access', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_login_replace_wp_logo_link' => array(
				'title' => __('Replace WP logo with site icon on login screen', 'no-nonsense'),
				'description' => __('Replaces the WordPress logo and link on the login screen with the designated site icon (if set) and site link. If no icon is present, the WP logo and link are simply removed.', 'no-nonsense'),
				'hook_type' => 'action',
				'hook' => 'login_enqueue_scripts',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Login', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_prevent_block_directory_access' => array(
				'title' => __('Prevent block directory access', 'no-nonsense'),
				'description' => sprintf(__('Removes the directory for installing new blocks when searching for blocks in the block editor sidebar.', 'no-nonsense'), '<strong>', '</strong>'),
				'hook_type' => 'action',
				'hook' => 'admin_init',
				'priority' => 9,
				'pn' => 0,
				'group' => __('Block Editor', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_redirect_admin_to_homepage_for_logged_in_non_editors' => array(
				'title' => __('Redirect admin to home page for logged-in non-editors', 'no-nonsense'),
				'description' => __('Logged-in users with no editing capabilities (e.g. Subscribers) will be redirected to the site home page if they try to access any admin pages, other than their own profile page.', 'no-nonsense'),
				'options' => array(
					'prevent_profile_access' => __('Also prevent access to profile screen', 'no-nonsense'),
				),
				'hook_type' => 'action',
				'hook' => 'admin_init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Admin Access', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_remove_admin_color_scheme_picker' => array(
				'title' => __('Remove admin color scheme picker', 'no-nonsense'),
				'description' => __('Removes the color scheme picker from the user profile page.', 'no-nonsense'),
				'hook_type' => 'action',
				'hook' => 'admin_init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Admin Features', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_remove_admin_email_check_interval' => array(
				'title' => __('Remove admin email check interval', 'no-nonsense'),
				'description' => __('Skips the periodic verification of admin email address upon login.', 'no-nonsense'),
				'hook_type' => 'filter',
				'hook' => 'admin_email_check_interval',
				'priority' => 10,
				'pn' => 0,
				'cb' => '__return_false',
				'group' => __('Login', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_remove_admin_wp_logo' => array(
				'title' => __('Remove admin bar WordPress logo', 'no-nonsense'),
				'description' => __('Removes WordPress icon and link from the admin bar.', 'no-nonsense'),
				'hook_type' => 'action',
				'hook' => 'admin_bar_menu',
				'priority' => 11,
				'pn' => 1,
				'group' => __('Admin Bar', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_remove_attachment_pages' => array(
				'title' => __('Redirect attachment pages to file URL', 'no-nonsense'),
				'description' => __('By default, WordPress creates an "attachment page" for every file uploaded to the Media Library. This setting will redirect those pages to the file URL itself.', 'no-nonsense'),
				'options' => array(
					'attachment_page_404' => __('Return HTTP 404 error instead of redirecting', 'no-nonsense'),
				),
				'hook_type' => 'action',
				'hook' => 'template_redirect',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Front End', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_remove_comments_from_admin' => array(
				'title' => __('Remove Comments from admin', 'no-nonsense'),
				'description' => sprintf(__('Removes links to Comments in the admin bar and admin sidebar menu. Does not actually deactivate comment functionality; this should be done under %1$sSettings %2$s Discussion%3$s.', 'no-nonsense'), '<a href="' . admin_url('options-discussion.php') . '" target="_blank">', '&gt;', '</a>'),
				'hook_type' => 'action',
				'hook' => 'init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Admin Features', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_remove_comments_from_front_end' => array(
				'title' => __('Remove comments from front end', 'no-nonsense'),
				'description' => __('Removes all standard comment output from front-end pages. May not function properly if theme uses non-standard methods to display comments.', 'no-nonsense'),
				'hook_type' => 'filter',
				'hook' => 'init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Front End', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_remove_dashboard_widgets' => array(
				'title' => __('Remove Dashboard widgets', 'no-nonsense'),
				'description' => __('Removes the selected widgets from the WordPress admin dashboard.', 'no-nonsense'),
				'options' => array(
					'dashboard_activity' => __('Activity', 'no-nonsense'),
					'dashboard_right_now' => __('At a Glance', 'no-nonsense'),
					'dashboard_incoming_links' => __('Incoming Links', 'no-nonsense'),
					'dashboard_plugins' => __('Plugins', 'no-nonsense'),
					'dashboard_quick_press' => __('Quick Draft', 'no-nonsense'),
					'dashboard_recent_comments' => __('Recent Comments', 'no-nonsense'),
					'dashboard_recent_drafts' => __('Recent Drafts', 'no-nonsense'),
					'dashboard_site_health' => __('Site Health', 'no-nonsense'),
					'welcome_panel' => __('Welcome', 'no-nonsense'),
					'dashboard_primary' => __('WordPress Events and News', 'no-nonsense'),
				),
				'hook_type' => 'action',
				'hook' => 'admin_init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Admin Features', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_remove_default_block_patterns' => array(
				'title' => __('Remove default block patterns', 'no-nonsense'),
				'description' => sprintf(__('Removes the default block patterns from the block editor, leaving only custom block patterns defined by your theme.', 'no-nonsense'), '<strong>', '</strong>'),
				'hook_type' => 'action',
				'hook' => 'init',
				'priority' => 9,
				'pn' => 0,
				'group' => __('Block Editor', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_remove_duotone_svg_filters' => array(
				'title' => __('Remove duotone SVG filters', 'no-nonsense'),
				'description' => __('Removes hardcoded HTML SVG tags for block editor duotone effects that normally get loaded on every page for Safari users.', 'no-nonsense'),
				'hook_type' => 'action',
				'hook' => 'after_setup_theme',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Block Editor', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_remove_edit_site' => array(
				'title' => __('Remove "Edit site" link', 'no-nonsense'),
				'description' => sprintf(__('Removes the full site editing (FSE) link that appears in the admin bar on sites that use block themes, to avoid accidentally clicking it when intending to click "Edit Page/Post," but leaves other FSE features in place. To disallow FSE entirely, select %1$sDisallow full site editing (FSE)%2$s under "Admin Features" instead.', 'no-nonsense'), '<strong>', '</strong>'),
				'hook_type' => 'action',
				'hook' => 'admin_bar_menu',
				'priority' => 999,
				'pn' => 1,
				'group' => __('Block Editor', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_remove_head_tags' => array(
				'title' => __('Remove head tags', 'no-nonsense'),
				'description' => sprintf(__('Removes the selected %1$s tags from the %2$s on all front-end pages.', 'no-nonsense'), '<code>&lt;link&gt;</code>', '<code>&lt;head&gt;</code>'),
				'options' => array(
					'rsd_link' => __('EditURI/RSD', 'no-nonsense'),
					'oembed_linktypes' => __('oEmbed Discovery Links', 'no-nonsense'),
					'resource_hints' => __('Resource Hints', 'no-nonsense'),
					'rest_output_link_wp_head' => __('REST API', 'no-nonsense'),
					'feed_links' => __('RSS Feeds', 'no-nonsense'),
					'wlwmanifest_link' => __('WLW Manifest', 'no-nonsense'),
					'wp_generator' => __('WP Generator', 'no-nonsense'),
					'wp_shortlink_wp_head' => __('WP Shortlink', 'no-nonsense'),
				),
				'hook_type' => 'action',
				'hook' => 'init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Front End', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_remove_front_end_edit_links' => array(
				'title' => __('Remove front end Edit links', 'no-nonsense'),
				'description' => __('Removes Edit links that appear within the page layout of certain themes for logged-in users. Does not affect Edit links in the admin bar.', 'no-nonsense'),
				'hook_type' => 'filter',
				'hook' => 'edit_post_link',
				'priority' => 10,
				'pn' => 0,
				'cb' => '__return_false',
				'group' => __('Front End', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_remove_global_styles' => array(
				'title' => __('Remove global styles (inline CSS)', 'no-nonsense'),
				'description' => sprintf(__('Removes inline CSS the Block Editor inserts into the head of every page. Note that this may not remove %1$sall%2$s inline CSS; styles inserted by your theme or plugins will still be present.', 'no-nonsense'), '<em>', '</em>'),
				'hook_type' => 'action',
				'hook' => 'wp_enqueue_scripts',
				'priority' => 10,
				'pn' => 1,
				'group' => __('Block Editor', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_remove_howdy' => array(
				'title' => __('Remove "Howdy"', 'no-nonsense'),
				'description' => __('Removes "Howdy" greeting text (or the corresponding text in other languages) next to username in admin bar.', 'no-nonsense'),
				'hook_type' => 'action',
				'hook' => 'admin_bar_menu',
				'priority' => 10,
				'pn' => 1,
				'group' => __('Admin Bar', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_remove_posts_from_admin' => array(
				'title' => __('Remove Posts from admin', 'no-nonsense'),
				'description' => sprintf(__('If you use WordPress as a general-purpose CMS without a blog component, this option will hide the Posts link in the main admin navigation. It does %1$snot%2$s deactivate the "Posts" post type itself, nor restrict any front-end content. If you are using an SEO plugin, you will need to adjust its settings to exclude Posts from your sitemap XML.', 'no-nonsense'), '<strong>', '</strong>'),
				'hook_type' => 'action',
				'hook' => 'init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Admin Features', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_remove_widgets_block_editor' => array(
				'title' => __('Remove Widgets block editor', 'no-nonsense'),
				'description' => __('Restores the previous default functionality of the Widgets page.', 'no-nonsense'),
				'hook_type' => 'action',
				'hook' => 'after_setup_theme',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Block Editor', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_remove_wp_emoji' => array(
				'title' => __('Remove WP emoji', 'no-nonsense'),
				'description' => __('Removes built-in emoji-related WordPress JavaScript code that normally gets loaded on every page. Also removes emoji tools in the TinyMCE editor.', 'no-nonsense'),
				'hook_type' => 'action',
				'hook' => 'init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Front End', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_xmlrpc_disabled' => array(
				'title' => __('Disable XML-RPC', 'no-nonsense'),
				'description' => sprintf(__('Most WordPress sites do not use XML-RPC, although some plugins (e.g. Jetpack) and mobile applications may require it. Per changes in WordPress 3.5, turning this option on will only disable XML-RPC requests that require authentication. Use the %1$sAlso kill any incoming XML-RPC request%2$s option below to cause all incoming XML-RPC requests to exit early. (Note: Because this is a plugin-based solution, XML-RPC requests still must partially load, to the point where this plugin is active, before it can kill the process. For better performance during a DDOS attack, you may wish to block calls to %3$s directly in your site&rsquo;s %4$s file.', 'no-nonsense'), '<strong>', '</strong>', '<code>xmlrpc.php</code>', '<code>.htaccess</code>'),
				'hook_type' => 'action',
				'hook' => 'plugins_loaded',
				'options' => array(
					'kill_requests' => __('Also kill any incoming XML-RPC request', 'no-nonsense'),
				),
				'priority' => 11,
				'pn' => 0,
				'group' => __('Security and Updates', 'no-nonsense'),
				'show_in_admin' => true,
			),

		);
		
		// Conditional settings
		
		// Don't show if constant is already set in wp-config.php
		if (!defined('CORE_UPGRADE_SKIP_NEW_BUNDLED')) {
			$this->settings['r34nono_core_upgrade_skip_new_bundled'] = array(
				'title' => __('Core upgrade skip new bundled', 'no-nonsense'),
				'description' => sprintf(__('Skips installing things like new themes that are bundled by default with WordPress core upgrades. This can also be handled manually by adding the %1$sCORE_UPGRADE_SKIP_NEW_BUNDLED%2$s constant in your %3$swp-config.php%4$s file.', 'no-nonsense'), '<code>', '</code>', '<code>', '</code>'),
				'hook_type' => 'action',
				'hook' => 'init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Security and Updates', 'no-nonsense'),
				'show_in_admin' => true,
			);
		}
		
		// Don't show if constant is already set in wp-config.php
		if (!defined('DISALLOW_FILE_EDIT')) {
			$this->settings['r34nono_disallow_file_edit'] = array(
				'title' => __('Disallow theme and plugin file editing', 'no-nonsense'),
				'description' => __('Removes the ability for site admins to edit theme and plugin files directly within WordPress.', 'no-nonsense'),
				'hook_type' => 'action',
				'hook' => 'init',
				'priority' => 10,
				'pn' => 0,
				'group' => __('Admin Features', 'no-nonsense'),
				'show_in_admin' => true,
			);
		}
		
		// Utility details
		$this->utilities = array(

			'r34nono_deactivate_and_delete_akismet' => array(
				'title' => __('Deactivate and delete Akismet Anti-Spam plugin', 'no-nonsense'),
				'description' => __('Deactivates and deletes the Akismet Anti-Spam plugin that is included in the default WordPress installation.', 'no-nonsense'),
				'show_in_admin' => true,
			),

			'r34nono_deactivate_and_delete_hello_dolly' => array(
				'title' => __('Deactivate and delete Hello Dolly plugin', 'no-nonsense'),
				'description' => __('Deactivates and deletes the Hello Dolly plugin that is included in the default WordPress installation.', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_delete_sample_content' => array(
				'title' => __('Delete sample content', 'no-nonsense'),
				'description' => sprintf(__('Deletes the sample page, post, and comment that are included by default in a new WordPress installation. %1$sWARNING:%2$s This utility deletes these posts based solely on their IDs. If you have %3$sedited%4$s the samples and are actively using them, they will still be deleted!', 'no-nonsense'), '<br /><br /><strong style="color: var(--r34nono-accent-color-1);">', '</strong>', '<em>', '</em>'),
				'show_in_admin' => true,
				'has_warning' => true,
			),
			
			'r34nono_disable_all_comments_and_trackbacks' => array(
				'title' => __('Disable all comments and trackbacks', 'no-nonsense'),
				'description' => __('Sets comments and trackbacks to "closed" on all existing content. Also turns off the WordPress "Allow link notifications from other blogs (pingbacks and trackbacks) on new posts" and "Allow people to submit comments on new posts" options.', 'no-nonsense'),
				'show_in_admin' => true,
			),
			
			'r34nono_remove_default_tagline' => array(
				'title' => __('Remove default tagline', 'no-nonsense'),
				'description' => sprintf(__('Removes the default WordPress tagline ("%1$s"). You will probably want to add your own tagline in its place eventually, but it is easy to forget and it often appears in unexpected places. %2$sNote:%3$s The default tagline was removed in WordPress 6.1. Using this utility with WordPress 6.1 or later will have no effect.', 'no-nonsense'), __('Just another WordPress site', 'no-nonsense'), '<br /><br /><strong>', '</strong>'),
				'show_in_admin' => true,
			),

			'r34nono_set_permalink_structure_to_postname' => array(
				'title' => sprintf(__('Set permalink structure to %1$s', 'no-nonsense'), '<code style="font-weight: normal;">/%postname%/</code>'),
				'description' => __('Sets the permalink structure to the most commonly used option on modern websites, and flushes rewrite rules.', 'no-nonsense'),
				'show_in_admin' => true,
			),

		);
		
		// Conditional utilities
		
		// Don't show if on Multisite, because inactive themes may be in use on other sites in the network
		if (!defined('WP_ALLOW_MULTISITE') || WP_ALLOW_MULTISITE == false) {
			$this->utilities['r34nono_delete_inactive_themes'] = array(
				'title' => __('Delete inactive themes', 'no-nonsense'),
				'description' => sprintf(__('Deletes all installed themes except the currently active theme. If your site will be using a custom theme, be sure to activate it prior to running this utility. If the current theme is a child theme, its parent theme will not be deleted. %1$sWARNING:%2$s WordPress Site Health recommends keeping at least one default theme installed. If you wish to follow the recommendation, be sure to reinstall the default theme of your choice after running this utility.', 'no-nonsense'), '<br /><br /><strong style="color: var(--r34nono-accent-color-1);">', '</strong>'),
				'show_in_admin' => true,
				'has_warning' => true,
			);
		}
		
		// Admin colors CSS variables (used on admin page and logout button)
		add_action('admin_head', 'r34nono_admin_colors_css_variables');
	
		// Admin page
		add_action('admin_menu', array(&$this, 'admin_page'));

		// Enqueue admin scripts
		add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
		
		// Enqueue front-end scripts
		add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
		
		// Add our hooks, allowing plugins/theme to modify first
		add_action('after_setup_theme', array(&$this, 'add_hooks'), 9);
		
		// Add plugin action links
		add_filter('plugin_action_links_no-nonsense/no-nonsense.php', array($this, 'admin_plugin_action_links'));
	
	}
	
	public function add_hooks() {

		// Allow developers to extend/modify the array of settings
		$this->settings = apply_filters('r34nono_define_settings_array', $this->settings);
		
		// Add hooks based on settings
		if (is_array($this->settings) && count($this->settings) > 0) {
			foreach ((array)$this->settings as $name => $item) {
				$function = !empty($item['cb']) ? $item['cb'] : $name;
				if (!empty(get_option($name)) && function_exists($function)) {
					if ($item['hook_type'] == 'filter') {
						add_filter(
							htmlspecialchars($item['hook']),
							htmlspecialchars($function),
							intval($item['priority']),
							intval($item['pn'])
						);
					}
					else {
						add_action(
							htmlspecialchars($item['hook']),
							htmlspecialchars($function),
							intval($item['priority']),
							intval($item['pn'])
						);
					}
				}
			}
		}

		// Allow developers to extend/modify the array of utilities
		$this->utilities = apply_filters('r34nono_define_utilities_array', $this->utilities);
		
		// Sort the utilities list
		ksort($this->utilities);
		
	}
	
	public function admin_page() {
		add_options_page(
			__('No Nonsense', 'no-nonsense'),
			__('No Nonsense', 'no-nonsense'),
			'manage_options',
			'no-nonsense',
			array(&$this, 'admin_page_callback'),
			34
		);
	}
	
	public function admin_plugin_action_links($links) {
			array_unshift(
				$links,
				'<a href="' . admin_url('options-general.php?page=no-nonsense') . '/">' . __('Settings', 'no-nonsense') . '</a>'
			);
			return $links;
	}
	
	public function admin_enqueue_scripts() {
		wp_enqueue_script('r34nono-admin', plugin_dir_url(__FILE__) . 'assets/admin-script-min.js', array('jquery'), @R34NoNo::VERSION);
		wp_enqueue_style('r34nono-admin-style', plugin_dir_url(__FILE__) . 'assets/admin-style-min.css', false, @R34NoNo::VERSION);
		wp_enqueue_style('r34nono-admin-bar-style', plugin_dir_url(__FILE__) . 'assets/admin-bar-min.css', false, @R34NoNo::VERSION);
	}

	public function admin_page_callback() {
	
		// Run utilities
		if (isset($_POST['r34nono-nonce-utilities']) && wp_verify_nonce($_POST['r34nono-nonce-utilities'], 'r34nono-nonce-utilities')) {
			
			$utilities_completed = array();
			
			foreach ((array)$this->utilities as $name => $item) {
				if (isset($_POST[$name]) && $_POST[$name] == 'on') {
					if (function_exists($name)) {
						$status = $name();
						$utilities_completed[$name] = array(
							'title' => $item['title'],
							'status' => ($status !== false) ? '<span style="color: green; cursor: help; font-size: 1.25em;" title="' . __('Utility executed successfully.', 'no-nonsense') . '">&#9679;</span>' : '<span style="color: orange; cursor: help; font-size: 1.25em;" title="' . __('Utility executed, but with no effect or possible errors.', 'no-nonsense') . '">&#9679;</span>',
						);
					}
					else {
						$utilities_completed[$name] = array(
							'title' => $item['title'],
							'status' => '<span style="color: red; cursor: help; font-size: 1.25em;" title="' . __('Utility could not execute; requested function does not exist.', 'no-nonsense') . '">&#9679;</span>',
						);
					}
				}
			}

			// Display admin notice
			echo '<div class="notice notice-success"><p>' . __('Utilities completed:', 'no-nonsense') . '</p>';
			foreach ($utilities_completed as $item) {
				echo '<p>' . wp_kses_post($item['status']) . ' &nbsp; ' . wp_kses_post($item['title']) . '</p>';
			}
			echo '</div>';
		}
	
		// Update settings
		if (isset($_POST['r34nono-nonce-settings']) && wp_verify_nonce($_POST['r34nono-nonce-settings'], 'r34nono-nonce-settings')) {

			foreach ((array)$_POST as $key => $value) {
				$key = r34nono_sanitize_string($key);
				if (!in_array($key, array('r34nono-nonce-settings', '_wp_http_referer'))) {
					if (strpos($key, '_options') !== false) {
						update_option($key, filter_var_array($value, FILTER_SANITIZE_NUMBER_INT));
					}
					else {
						delete_option($key); // Need to reset to erase options being deselected
						update_option($key, filter_var($value, FILTER_SANITIZE_NUMBER_INT));
					}
				}
			}

			// Display admin notice
			echo '<div class="notice notice-success"><p>' . __('Settings updated. You may need to refresh the page to see changes.', 'no-nonsense') . '</p></div>';
		}
		
		// Import settings
		if (isset($_POST['r34nono-nonce-import-export']) && wp_verify_nonce($_POST['r34nono-nonce-import-export'], 'r34nono-nonce-import-export')) {

			if (!empty($_POST['r34nono-import-settings-json'])) {
				$import_settings = json_decode(stripslashes($_POST['r34nono-import-settings-json']), true, 3, JSON_INVALID_UTF8_IGNORE);
				$json_error = json_last_error_msg();
				
				// JSON is valid, continue
				if (empty($json_error) || $json_error == 'No error') {
					$invalid_lines = 0;
				
					foreach ((array)$import_settings as $key => $value) {
						$key = r34nono_sanitize_string($key);
						// Skip items without the 'r34nono_' prefix
						if (strpos($key, 'r34nono_') !== 0) {
							$invalid_lines++;
							continue;
						}
						// Sanitize arrays
						if (strpos($key, '_options') !== false) {
							update_option($key, filter_var_array($value, FILTER_SANITIZE_NUMBER_INT));
						}
						// Sanitize scalars
						else {
							delete_option($key); // Need to reset to erase options being deselected
							update_option($key, filter_var($value, FILTER_SANITIZE_NUMBER_INT));
						}
					}

					// Display admin notice
					if (empty($invalid_lines)) {
						echo '<div class="notice notice-success"><p>' . __('Your JSON settings were imported.', 'no-nonsense') . '</p></div>';
					}
					else {
						echo '<div class="notice notice-warning"><p>' . sprintf(__('Your JSON settings were imported, but %1$s invalid line(s) were skipped.', 'no-nonsense'), intval($invalid_lines)) . '</p></div>';
					}
				}
				
				// JSON is invalid, return error message
				else {
					echo '<div class="notice notice-error"><p>' . sprintf(__('A JSON error occurred during tne import process: %1$s Please correct any JSON formatting errors and try again.', 'no-nonsense'), '<br /><br /><code>' . $json_error . '</code><br /><br />') . '</p></div>';
				}
				
			}
			
			// Nothing was submitted
			else {

					// Display admin notice
					echo '<div class="notice notice-warning"><p>' . __('No JSON data was submitted. Settings have not been modified.', 'no-nonsense') . '</p></div>';
				
			}

		}
				
		// Load page template
		include_once(plugin_dir_path(__FILE__) . 'templates/admin/r34nono-admin.php');

	}
	
	public function enqueue_scripts() {
		if (is_user_logged_in()) {
			wp_enqueue_style('r34nono-admin-bar-style', plugin_dir_url(__FILE__) . 'assets/admin-bar-min.css', false, @R34NoNo::VERSION);
		}
	}
	
	public function export_options_json() {
		$settings = array();
		foreach ((array)$this->settings as $setting_name => $setting) {
			$settings[$setting_name] = get_option($setting_name);
			if (!empty($setting['options'])) {
				$settings[$setting_name . '_options'] = get_option($setting_name . '_options');
			}
		}
		return json_encode($settings, JSON_PRETTY_PRINT);
	}

}
