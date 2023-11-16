<?php defined( 'ABSPATH' ) or die( 'forbidden' ); ?>

<div class="w3all_custom_iframe_wrapper" id="" style="<?php echo $ltm['css_iframe_wrapper_div']; ?>">

<noscript><h3>Javascript disabled on your browser: enable Javascript on your browser.<br /><br /></h3></noscript>

<iframe id="" name="w3all_custom_iframe" style="<?php echo $iframe_style; ?>" scrolling="no" src="<?php echo $ltm['url_to_display']; ?>"></iframe>

<?php
if( strtolower($ltm['resizer']) == 'yes' ):

echo "<script type=\"text/javascript\">
document.domain = '".$document_domain."';

iFrameResize({
        log                     : false,
        inPageLinks             : true,
        targetOrigin: '".$ltm['url_to_display']."',
        checkOrigin : ".$w3check_origin.",
     // heightCalculationMethod: 'documentElementOffset', // If iframe not resize correctly, un-comment (or change with one of others available resize methods)
     // see: https://github.com/davidjbradshaw/iframe-resizer#heightcalculationmethod
     // https://github.com/davidjbradshaw/iframe-resizer/blob/master/docs/parent_page/options.md#checkorigin
});
</script>";

endif;

?>

</div><!-- END <div class="w3all_custom_iframe_wrapper" -->