<?php

namespace wp_gdpr\lib;


/**
 * lib element to add menu page
 */
class Gdpr_Menu_Backend {
	const MENU_PAGE_TITLE = 'WP GDPR';

	const PAGE_SLUG = 'wp_gdpr';

	const MENU_TITLE = 'WP GDPR';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_callback' ) );
	}


	/**
	 * add menu page
	 */
	public function add_menu_callback() {

// Base 64 encoded SVG img
		$icon_svg = "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjAuMSwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAzMyAzMyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzMgMzM7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbDojRkZGRkZGO30KCS5zdDF7ZmlsbDojRkZGRkZGO3N0cm9rZTojRkZDRTA0O3N0cm9rZS13aWR0aDowLjIyNjg7c3Ryb2tlLW1pdGVybGltaXQ6MTA7fQoJLnN0Mntmb250LWZhbWlseTonUm9ib3RvLVJlZ3VsYXInO30KCS5zdDN7Zm9udC1zaXplOjYuNDcyN3B4O30KCS5zdDR7Zm9udC1mYW1pbHk6J1JvYm90by1CbGFjayc7fQo8L3N0eWxlPgo8Zz4KCTxnIGlkPSJMYXllcl8yXzFfIj4KCQk8cG9seWdvbiBjbGFzcz0ic3QwIiBwb2ludHM9IjAsMTYuNSAxLjUsMTYgMS41LDE0LjUgMi40LDE1LjcgMy45LDE1LjMgMywxNi41IDMuOSwxNy43IDIuNCwxNy4zIDEuNSwxOC41IDEuNSwxNyAJCSIvPgoJCTxwb2x5Z29uIGNsYXNzPSJzdDAiIHBvaW50cz0iMiwyMy42IDMuNCwyMy4yIDMuNCwyMS42IDQuNCwyMi45IDUuOSwyMi40IDUsMjMuNiA1LjksMjQuOSA0LjQsMjQuNCAzLjQsMjUuNiAzLjQsMjQuMSAJCSIvPgoJCTxwb2x5Z29uIGNsYXNzPSJzdDAiIHBvaW50cz0iNy4zLDI4LjkgOC44LDI4LjQgOC44LDI2LjkgOS44LDI4LjEgMTEuMywyNy42IDEwLjMsMjguOSAxMS4zLDMwLjEgOS44LDI5LjYgOC44LDMwLjkgOC44LDI5LjMgCQkKCQkJIi8+CgkJPHBvbHlnb24gY2xhc3M9InN0MCIgcG9pbnRzPSIyLDkuNCAzLjUsOC45IDMuNSw3LjQgNC40LDguNiA1LjksOC4xIDUsOS40IDUuOSwxMC42IDQuNCwxMC4xIDMuNSwxMS40IDMuNSw5LjggCQkiLz4KCQk8cG9seWdvbiBjbGFzcz0ic3QwIiBwb2ludHM9IjguNywyLjMgOS43LDMuNiAxMS4xLDMuMSAxMC4yLDQuMyAxMS4xLDUuNiA5LjcsNS4xIDguNyw2LjQgOC43LDQuOCA3LjIsNC40IDguNywzLjkgCQkiLz4KCQk8cG9seWdvbiBjbGFzcz0ic3QwIiBwb2ludHM9IjE2LjEsMC40IDE3LDEuNiAxOC41LDEuMSAxNy42LDIuNCAxOC41LDMuNiAxNywzLjEgMTYuMSw0LjQgMTYuMSwyLjkgMTQuNiwyLjQgMTYuMSwxLjkgCQkiLz4KCQk8cG9seWdvbiBjbGFzcz0ic3QwIiBwb2ludHM9IjI1LjYsMyAyNC43LDQuMiAyNS42LDUuNSAyNC4xLDUgMjMuMiw2LjIgMjMuMiw0LjcgMjEuNyw0LjIgMjMuMiwzLjggMjMuMiwyLjIgMjQuMSwzLjUgCQkiLz4KCQk8cG9seWdvbiBjbGFzcz0ic3QwIiBwb2ludHM9IjMzLDE3LjYgMzEuNSwxNy4xIDMwLjYsMTguNCAzMC42LDE2LjggMjkuMSwxNi40IDMwLjYsMTUuOSAzMC42LDE0LjQgMzEuNSwxNS42IDMzLDE1LjEgMzIuMSwxNi40IAoJCQkJCSIvPgoJCTxwb2x5Z29uIGNsYXNzPSJzdDEiIHBvaW50cz0iMzEuMSwyNC44IDI5LjYsMjQuMyAyOC42LDI1LjUgMjguNiwyNCAyNy4xLDIzLjUgMjguNiwyMy4xIDI4LjYsMjEuNSAyOS42LDIyLjggMzEsMjIuMyAzMC4xLDIzLjUgCgkJCQkJIi8+CgkJPHBvbHlnb24gY2xhc3M9InN0MCIgcG9pbnRzPSIzMSw4LjIgMzAuMSw5LjUgMzEsMTAuNyAyOS41LDEwLjIgMjguNiwxMS41IDI4LjYsOS45IDI3LjEsOS41IDI4LjYsOSAyOC42LDcuNSAyOS41LDguNyAJCSIvPgoJCTxwb2x5Z29uIGNsYXNzPSJzdDAiIHBvaW50cz0iMTYuMSwzMi42IDE2LjEsMzEuMSAxNC42LDMwLjYgMTYuMSwzMC4xIDE2LjEsMjguNiAxNywyOS44IDE4LjUsMjkuNCAxNy42LDMwLjYgMTguNiwzMS45IDE3LDMxLjQgCgkJCQkJIi8+CgkJPHBvbHlnb24gY2xhc3M9InN0MCIgcG9pbnRzPSIyNS42LDMwIDI0LjEsMjkuNSAyMy4yLDMwLjggMjMuMiwyOS4yIDIxLjcsMjguOCAyMy4yLDI4LjMgMjMuMiwyNi44IDI0LjEsMjggMjUuNiwyNy41IDI0LjcsMjguOCAKCQkJCQkiLz4KCQk8dGV4dCB0cmFuc2Zvcm09Im1hdHJpeCgxLjAyOTYgMCAwIDEgMTEuOTg5MyAxNS40Mjc3KSIgY2xhc3M9InN0MCBzdDIgc3QzIj5XUDwvdGV4dD4KCQk8dGV4dCB0cmFuc2Zvcm09Im1hdHJpeCgxLjAyOTYgMCAwIDEgOC41MTA3IDIxLjMxNDQpIiBjbGFzcz0ic3QwIHN0NCBzdDMiPkdEUFI8L3RleHQ+Cgk8L2c+Cgk8ZyBpZD0iTGFhZ18zIj4KCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMjQuOSwxNS41Ii8+CgkJPHBhdGggY2xhc3M9InN0MCIgZD0iTTguMiwxNS41Ii8+CgkJPHBvbHlnb24gY2xhc3M9InN0MCIgcG9pbnRzPSIyNS4yLDE2LjEgOC44LDE2LjEgOC42LDE2IDI1LDE2IAkJIi8+Cgk8L2c+CjwvZz4KPC9zdmc+Cg==";

		add_menu_page( self::MENU_PAGE_TITLE, self::MENU_TITLE, 'manage_options', self::PAGE_SLUG, '', $icon_svg );

		add_submenu_page( static::PAGE_SLUG, __( 'Requests', 'wp_gdpr' ), __( 'Requests', 'wp_gdpr' ), 'manage_options', self::PAGE_SLUG, array(
			$this,
			'set_wp_gdpr_listOfDataRequests'
		) );

//		$hook = add_submenu_page( static::PAGE_SLUG, __( 'Dataregister', 'wp_gdpr' ), __( 'Dataregister', 'wp_gdpr' ), 'manage_options', 'datareg', array(
//			$this,
//			'set_wp_dataregister'
//		) );
//
//		add_action( 'load-' . $hook, array( $this, 'load_dataregister_css' ) );

		add_submenu_page( static::PAGE_SLUG, __( 'Add-ons', 'wp_gdpr' ), __( 'Add-ons', 'wp_gdpr' ), 'manage_options', 'addon', array(
			$this,
			'set_wp_addon'
		) );

		add_submenu_page( static::PAGE_SLUG, __( 'Settings', 'wp_gdpr' ), __( 'Settings', 'wp_gdpr' ), 'manage_options', 'settings_wp-gdpr', array(
			$this,
			'set_wp_gdpr_settings'
		) );

		add_submenu_page( static::PAGE_SLUG, __( 'Help Center', 'wp_gdpr' ), __( 'Help Center', 'wp_gdpr' ), 'manage_options', 'help', array(
			$this,
			'set_wp_gdpr_help'
		) );


	}

	/**
	 * generate output for menu page from template
	 *
	 * @since v1.5      some menu's are merged
	 */
	public function set_wp_gdpr_listOfDataRequests() {
		if ( isset( $_GET['page_type'] ) && $_GET['page_type'] == 'datarequest' ) {
			require_once GDPR_DIR . 'view/admin/menu/data-page.php';
		} else {
			require_once GDPR_DIR . 'view/admin/menu/delete-page.php';
		}
	}

	public function set_wp_addon() {
		if ( isset( $_GET['page_type'] ) && $_GET['page_type'] == 'addonlist' ) {
			require_once GDPR_DIR . 'view/admin/menu/plugin-page.php';
		} else {
			require_once GDPR_DIR . 'view/admin/menu/addon_page.php';
		}
	}

	public function set_wp_gdpr_settings() {
		require_once GDPR_DIR . 'view/admin/menu/settings-page.php';
	}

	public function set_wp_gdpr_help() {
		require_once GDPR_DIR . 'view/admin/menu/help_page.php';
	}

	public function set_wp_dataregister() {
		require_once GDPR_DIR . 'view/admin/menu/dataregister.php';
	}

	public function load_dataregister_css() {
		wp_enqueue_style( 'data-register-style', GDPR_URL . 'assets/css/data-register.css' );
	}
}
