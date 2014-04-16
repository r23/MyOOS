<?php
if ( ! class_exists( "Yoast_Plugin_License_Manager", false ) ) {

	class Yoast_Plugin_License_Manager extends Yoast_License_Manager {

		/**
		 * Setup auto updater for plugins
		 */
		public function setup_auto_updater() {
			if ( $this->license_is_valid() ) {
				// setup auto updater
				require_once( dirname( __FILE__ ) . '/class-update-manager.php' );
				require_once( dirname( __FILE__ ) . '/class-plugin-update-manager.php' );
				new Yoast_Plugin_Update_Manager( $this->product, $this->get_license_key() );
			}
		}

		/**
		 * Setup hooks
		 */
		public function specific_hooks() {

			// deactivate the license remotely on plugin deactivation
			register_deactivation_hook( $this->product->get_slug(), array( $this, 'deactivate_license' ) );
		}

        /**
         * Show a form where users can enter their license key
         * Takes Multisites into account
         *
         * @param bool $embedded
         */
        public function show_license_form( $embedded = true ) {

            if( is_multisite() && is_plugin_active_for_network( $this->product->get_slug() ) && false == is_network_admin() ) {

                /**
                 * Site is a network installation and the plugin is network activated and the current page is not the main site licenses page
                 */

                // Check if current user can manage network sites
                if ( is_super_admin() ) {
                    echo "<p>" . sprintf( __( '%s is network activated, you can manage your license in the <a href="%s">network admin license page</a>.', $this->product->get_text_domain() ), $this->product->get_item_name(), $this->product->get_license_page_url() ) . "</p>";
                }else {
                    echo "<p>" . sprintf( __( '%s is network activated, please contact your site administrator to manage the license.', $this->product->get_text_domain() ), $this->product->get_item_name() ) . "</p>";
                }

            } else {
                /**
                 * Display license form
                 */
                parent::show_license_form( $embedded );
            }

        }
	}
}

