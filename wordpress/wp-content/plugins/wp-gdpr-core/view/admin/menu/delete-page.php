<?php namespace wp_gdpr\view\admin;
use wp_gdpr\lib\Gdpr_Helper;
/**
 * this template is to show manu page in admin-menu
 */
?>
<div class="wrap">
    <h2><b><?php _e( 'List of delete requests', 'wp_gdpr' ); ?></b> <?php _e( 'from website users', 'wp_gdpr' ); ?></h2>
    <p align="center"><img class="a_background_img" src="<?php echo GDPR_URL . 'assets/images/logo-trans-bg.png'; ?>"></p>
    <div id="nav_menu">
        <a id="a_delete" href="<?php echo admin_url('admin.php?page=wp_gdpr')?>" class="active_tab"><span class="dashicons dashicons-trash"></span>&nbsp;Delete requests</a>
        <a id="a_datarequest" href="<?php echo admin_url('admin.php?page=wp_gdpr&page_type=datarequest')?>"><span class="dashicons dashicons-search"></span>&nbsp;Data Requests</a>
        <a id="a_settings" href="<?php echo admin_url('admin.php?page=settings_wp-gdpr')?>"><span class="dashicons dashicons-admin-generic"></span>&nbsp;Settings</a>
        <a id="a_help" href="<?php echo admin_url('admin.php?page=help')?>"><span class="dashicons dashicons-editor-help"></span>&nbsp;Help</a>
        <a id="a_addon" href="<?php echo admin_url( 'admin.php?page=addon' ) ?>"><span class="dashicons dashicons-screenoptions"></span>&nbsp;Add-ons</a>
    </div>
    <div id="nav_menu_extra">
        <a id="a_review" target="_blank" href="https://wordpress.org/support/plugin/wp-gdpr-core/reviews/#new-post"><span class="dashicons dashicons-admin-comments"></span>&nbsp;Review
            our plugin</a>
        <a id="a_homepage" target="_blank" href="https://wp-gdpr.eu/"><span class="dashicons dashicons-admin-home"></span>&nbsp;Visit our homepage</a>
    </div>
    <br>
    <div id="user_info" class="postbox postbox user_info">
        <div class="user_info_header">
            <h3>What are delete requests?</h3>
            <button id="usr_info_header_btn">dismiss</button>
        </div>
        <div class="user_info_content">
            <img class="a_info" src="<?php echo GDPR_URL . 'assets/images/icon-info-bg.png'; ?>">
            <p>Delete request by users to delete/anonymise their data.
            You will see a complete list of all requests on this page where you can comply with the request.
            Every request is logged for data-register purposes.
                If you still are not sure what this is check out our <a href="<?php echo admin_url( 'admin.php?page=help' ) ?>"><b>Help page</b></a>
                or our <a href="https://wp-gdpr.eu/tutorials/" target="_blank"><b>Online tutorials</b></a>.</p>
        </div>
    </div>
	<?php

	use wp_gdpr\lib\Gdpr_Container;

	$controller = Gdpr_Container::make( 'wp_gdpr\controller\Controller_Menu_Page' );
	?>

	<?php $controller->build_table_with_delete_requests(); ?>
</div>
<p class="appsaloon_footer">WP-GDPR <?php echo Gdpr_Helper::get_core_version() ?> developed by <a href="https://appsaloon.be/" target="_blank"><b>Appsaloon</b></a></p>