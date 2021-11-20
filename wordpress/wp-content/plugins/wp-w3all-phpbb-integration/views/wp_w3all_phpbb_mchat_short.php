<?php
global $w3all_iframe_custom_top_gap;
// do not use wp is_ssl() because it fail on some server 
$w3all_orig = strpos($w3all_url_to_cms,'https') !== false ? 'https://'. $document_domain : 'http://' . $document_domain;
$w3all_orig_www = strpos($w3all_url_to_cms,'https') !== false ? 'https://www.'. $document_domain : 'http://www.' . $document_domain;
$w3all_url_to_cms_clean = $w3all_url_to_cms;
$w3all_url_to_cms_clean0 = strpos($w3all_url_to_cms_clean, 'https://') !== false ? str_replace('https://', 'http://', $w3all_url_to_cms_clean) : str_replace('http://', 'https://', $w3all_url_to_cms_clean);

if($wp_w3all_mchat_shortmode == 1)
{ 
	$w3all_url_to_cms_mchat = ''; // if toogle button
	?>
<div id="w3allmchatshortwrapper" class="w3mchatbox">
<div class="w3mchatbox-inner"><script>var w3all_create_mchattoggleBox = true;</script> <!-- DO NOT remove this - to display/create element button -->
<?php
} else {
	$w3all_url_to_cms_mchat = $w3all_url_to_cms . '/app.php/mchat#w3allmchatif';
	?>
<div id="" class="">
<div class="">	
<?php
}
/*
// substantially instead to open iframe already with src attr, it is set via js when chat toggled->open
// if the shortcode mode is for toggled button
// see /wp-content/plugins/wp-w3all-phpbb-integration/addons/custom_js_css.php
  src="<?php echo $w3all_url_to_cms . '/app.php/mchat#w3allmchatif'; ?>">
*/
?>	
<noscript><h3>It seem that your browser have Javascript disabled: you can't use the chat widget. Enable Javascript on your browser. <a href="<?php echo $w3all_url_to_cms;?>">Visit the forum here</a>.<br /><br /></h3></noscript>
<?php if($wp_w3all_mchat_shortmode == 1) // preloader only for the button toogle
{ ?>
<div id="w3_toogle_wrap_loader" class="w3_no_wrap_loader"><div class="w3_loader"></div></div>
<?php } ?>
<iframe id="w3all_phpbb_mchat_iframe" name="w3all_phpbb_mchat_iframe" style="width:1px;min-width:100%;*width:100%;border:0;" scrolling="no" src="<?php echo $w3all_url_to_cms_mchat; ?>"></iframe>

<?php
echo "<script type=\"text/javascript\">
function w3all_ajaxup_from_phpbb(res){

      var w3all_lochash = /.*(#w3all_lochash)=([0-9]+).*/ig.exec(res);
      if(w3all_lochash !== null && w3all_lochash[2] != 0){ 
         jQuery('html, body').animate({ scrollTop: w3all_lochash[2]}, 400);
       } else {
         jQuery('html, body').animate({ scrollTop: ".$w3all_iframe_custom_top_gap."}, 400);
       }
       
   } // END w3all_ajaxup_from_phpbb(res){
document.domain = '".$document_domain."';
// document.domain = 'mydomain.com'; // NOTE: reset/setup this with domain if js error when WP is installed like on mysite.domain.com and phpBB on domain.com: js origin error can come out for example when WordPress is on subdomain install and phpBB on domain. The origin fix is needed: (do this also on phpBB overall_footer.html added code)
  
  var w3all_orig_domains = ['".$w3all_orig."','".$w3all_orig_www."','".$w3all_url_to_cms_clean."','".$w3all_url_to_cms_clean0."','https://localhost','http://localhost'];

iFrameResize({
				log                     : false,
				inPageLinks             : true,
        targetOrigin: '".$w3all_url_to_cms."', 
        checkOrigin : w3all_orig_domains, // if js error: 'Failed to execute 'postMessage' on 'DOMWindow': The target origin provided does not match the recipient window's origin. Need to fit YOUR domain, ex: mydomain.comcheckOrigin : w3all_orig_domains,
     // see: https://github.com/davidjbradshaw/iframe-resizer#heightcalculationmethod
});
</script>";
?>
</div>
</div>