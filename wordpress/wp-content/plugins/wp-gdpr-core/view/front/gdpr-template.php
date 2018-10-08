<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}
get_header();


/**
 * Translation strings for Privacy center page.
 * From php to javascript.
 */
$search_text   = __( 'Search:', 'wp_gdpr' );
$showing_text  = __( 'Showing _START_ to _END_ of _TOTAL_ entries', 'wp_gdpr' );
$previous_text = __( 'Previous', 'wp_gdpr' );
$next_text     = __( 'Next', 'wp_gdpr' );
$copy_text     = __( 'copy', 'wp_gdpr' );
$print_text    = __( 'print', 'wp_gdpr' );
?>

<script type="text/javascript">
    var search_text =<?php echo json_encode( $search_text ); ?>;
    var showing_text =<?php echo json_encode( $showing_text ); ?>;
    var previous_text =<?php echo json_encode( $previous_text ); ?>;
    var next_text =<?php echo json_encode( $next_text ); ?>;
    var copy_text =<?php echo json_encode( $copy_text ); ?>;
    var print_text =<?php echo json_encode( $print_text ); ?>;
</script>


<?php use wp_gdpr\lib\Gdpr_Options_Helper; ?>
<div class="wrapper" id="wp-gdpr">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 m-b-0">
                <h1 class="display-4">
                    <center><?php _e( 'YOUR', 'wp_gdpr' ); ?>
                        <strong><?php _e( 'PRIVACY CENTER', 'wp_gdpr' ); ?></strong>
                    </center>
                </h1>
                <p class="lead m-t-0">
                <center><span
                            style="letter-spacing: 2px; font-size:16px;"><?php _e( 'ALL PERSONAL DATA LINKED TO ', 'wp_gdpr' ); ?>
                        <STRONG><?php echo $controller->email_request; ?><img class="lock"
                                                                              src="<?php echo GDPR_URL . 'assets/images/lock.svg'; ?>"
                                                                              data-toggle="tooltip"
                                                                              data-placement="right"
                                                                              title=""
                                                                              data-original-title="<?php _e( 'This is your personal data page. Only you are able to access this.', 'wp_gdpr' ); ?>"></STRONG></span>
                </center>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Hier begint de tabel -->
                <div class="m-b-20">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title m-0 p-t-10">
                                <img class="address-card"
                                     src="<?php echo GDPR_URL . 'assets/images/address-card.svg'; ?>"><?php _e( 'Personal Data Entries', 'wp_gdpr' ); ?>
                            </h5>
                            <span class="text-muted m-l-10 p-t-10"
                                  style="float:right;"><?php _e( 'All personal data collected through WordPress comments & other plugins', 'wp_gdpr' ); ?>
                                .</span>
                        </div>


                        <div>

                            <div class="card-box">
                                <ul class="nav nav-tabs tabs-bordered">
									<?php
									//Echo header from every input
									$active_pill = true;
									foreach ( apply_filters( 'gdpr_global_models', array() ) as $model ) {
										if ( ! Gdpr_Options_Helper::is_option_off( 'switch_on_comments' ) && is_a( $model, 'wp_gdpr\controller\Controller_Comments' ) ) {
											continue;
										} else {
											echo $model->get_frontend_header( $active_pill );
											$active_pill = false;
										}
									}
									?>
                                </ul>
                                <div class="tab-content">
									<?php
									$active_tab = true;
									//Echo header from every input
									foreach ( apply_filters( 'gdpr_global_models', array() ) as $key => $model ) {
										if ( ! Gdpr_Options_Helper::is_option_off( 'switch_on_comments' ) && is_a( $model, 'wp_gdpr\controller\Controller_Comments' ) ) {
											continue;
										} else {
											$model->show_entries( $controller->email_request, $active_tab, 'datatable-buttons' . $key );
											$active_tab = false;
										}
									}
									?>
                                    <!-- end row -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php get_footer(); ?>
