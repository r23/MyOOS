<?php namespace wp_gdpr\view\admin;

use wp_gdpr\lib\Gdpr_Helper;

/**
 * this template is to show manu page in admin-menu
 */
?>
<div class="wrap">
    <h2><b><?php _e( 'Add-ons', 'wp_gdpr' ); ?></b> <?php _e( 'for your favourite plugins', 'wp_gdpr' ); ?></h2>
    <p align="center"><img class="a_background_img" src="<?php echo GDPR_URL . 'assets/images/logo-trans-bg.png'; ?>">
    </p>
    <div id="nav_menu">
        <a id="a_addon" class="active_tab" href="<?php echo admin_url( 'admin.php?page=addon' ) ?>"><span
                    class="dashicons dashicons-screenoptions"></span>Available add-ons</a>
        <a id="a_plugins" href="<?php echo admin_url( 'admin.php?page=addon&page_type=addonlist' ) ?>"><span
                    class="dashicons dashicons-admin-plugins"></span>&nbsp;Your plugins</a>
        <a id="a_settings" href="<?php echo admin_url( 'admin.php?page=settings_wp-gdpr' ) ?>"><span
                    class="dashicons dashicons-admin-generic"></span>&nbsp;Settings</a>
        <a id="a_help" href="<?php echo admin_url( 'admin.php?page=help' ) ?>"><span
                    class="dashicons dashicons-editor-help"></span>&nbsp;Help</a>
    </div>
    <div id="nav_menu_extra">
        <a id="a_review" target="_blank"
           href="https://wordpress.org/support/plugin/wp-gdpr-core/reviews/#new-post"><span
                    class="dashicons dashicons-admin-comments"></span>&nbsp;Review
            our plugin</a>
        <a id="a_homepage" target="_blank" href="https://wp-gdpr.eu/"><span
                    class="dashicons dashicons-admin-home"></span>&nbsp;Visit our homepage</a>
    </div>
    <br>
    <div id="user_info" class="postbox user_info">
        <div class="user_info_header">
            <h3>Why do I need add-ons?</h3>
            <button id="usr_info_header_btn">dismiss</button>
        </div>
        <div class="user_info_content">
            <img class="a_info" src="<?php echo GDPR_URL . 'assets/images/icon-info-bg.png'; ?>">
            <p>Alot of plugins collect personal data. Because there is a big variety of plugins we created add-ons to
                make those plugins GDPR ready
                with our WP-GDPR plugin. If you still are not sure what this is check out our <a
                        href="<?php echo admin_url( 'admin.php?page=help' ) ?>"><b>Help page</b></a>
                or our <a href="https://wp-gdpr.eu/tutorials/" target="_blank"><b>Online tutorials</b></a>.</p>
        </div>
    </div>
    <div id="addons_box">
		<?php
		$plugins = Gdpr_Helper::get_plugin_addon_status();
		if ( is_array( $plugins ) && count( $plugins ) != 0 ) :
			foreach ( $plugins as $plugin_data ):
				if ( ! empty( $plugin_data['name'] ) ): ?>
                    <div class="addon_box">
                        <div class="led <?php echo $plugin_data['status'] ?>"></div>
                        <h4><?php echo $plugin_data['name'] ?></h4>
                        <img src="<?php echo GDPR_URL . $plugin_data['plugin_icon']; ?>">
                        <p><?php echo $plugin_data['plugin_tutorial_intro']; ?></p>
                        <div class="gf_footer addon_footer">
							<?php if ( $plugin_data['status'] == 'not-installed' ): ?>
                                <a href="<?php echo $plugin_data['plugin_link'] ?>" target="_blank"
                                   class="button not-installed"><span class="dashicons dashicons-cart"></span>&nbsp;Buy
                                    now</a>
							<?php elseif ( $plugin_data['status'] == 'active' ): ?>
                                <a class="button active"><span class="dashicons dashicons-controls-repeat"></span>&nbsp;Active</a>
							<?php elseif ( $plugin_data['status'] == 'inactive' ): ?>
                                <a class="button in-active"
                                   href="<?php echo Gdpr_Helper::generatePluginActivationLinkUrl( $plugin_data['plugin_wp_gdpr'] ) ?>"><span
                                            class="dashicons dashicons-controls-repeat"></span>&nbsp;Activate</a>
							<?php endif; ?>
                            <a class="more_inf" target="_blank" href="<?php echo $plugin_data['plugin_link'] ?>">More
                                info</a>
                        </div>
                    </div>
				<?php
				endif;
			endforeach;
		endif;
		?>
        <div class="request_addon addon_box">
            <h4>Request an add-on</h4>
            <img src="<?php echo GDPR_URL . 'assets/images/gdpr-logo.png'; ?>">
            <p>Request your favourite plugin on our add-on page.</p>
            <div class="request_footer addon_footer">
                <a class="more_inf" href="https://wp-gdpr.eu/add-ons/">Visit the add-on page</a>
            </div>
        </div>
    </div>
</div>


<p class="appsaloon_footer">WP-GDPR <?php echo Gdpr_Helper::get_core_version() ?> developed by <a
            href="https://appsaloon.be/" target="_blank"><b>Appsaloon</b></a></p>