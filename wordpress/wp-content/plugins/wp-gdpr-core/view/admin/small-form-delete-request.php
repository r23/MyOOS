<?php
/**
 * small form to use in table
 * to use in gdpr-template.php
 */
$gdpra= __('Are you sure?', 'wp_gdpr');
$gdprb= __('This will send a delete request for your selected data', 'wp_gdpr');
$gdprc= __('Yes, send delete request!', 'wp_gdpr');
$gdprd= __('Request sent!', 'wp_gdpr');
$gdpre= __('Your delete request has been sent', 'wp_gdpr');
?>

<script type="text/javascript">
var gdpra=<?php echo json_encode($gdpra); ?>;
var gdprb=<?php echo json_encode($gdprb); ?>;
var gdprc=<?php echo json_encode($gdprc); ?>;
var gdprd=<?php echo json_encode($gdprd); ?>;
var gdpre=<?php echo json_encode($gdpre); ?>;
</script>

<form method="post" id="wgdpr_delete_comments_form">
    <input type="hidden"  name="gdpr_email" value="<?php echo $email; ?>">
    <button type="submit" id="button-submit" class="swa-confirm btn btn-primary waves-effect waves-light" name="send_gdp_del_request" value="<?php _e('Send delete request', 'wp_gdpr'); ?>"><i class="fa fa-trash m-r-5"></i>
    <?php _e('Send delete request', 'wp_gdpr'); ?>
    </button>
    <input type="hidden" name="mail_action" value="gdpr">
    <input type="hidden" name="send_gdp_del_request" value="<?php _e('Send delete request', 'wp_gdpr'); ?>">
</form>