<div class="">
<noscript><h3>It seem that your browser have Javascript disabled: you can't use the chat widget. Enable Javascript on your browser. <a href="<?php echo $w3all_url_to_cms;?>">Visit the forum here</a>.<br /><br /></h3></noscript>
<iframe id="w3all_phpbb_mchat_iframe" name="w3all_phpbb_mchat_iframe" style="width:1px;min-width:100%;*width:100%;border:0;" scrolling="no" src="<?php echo $w3all_url_to_cms . '/app.php/mchat#w3allmchatif'; ?>"></iframe>

<?php 
// do not use wp is_ssl() because it fail on some server 
$w3all_orig = strpos($w3all_url_to_cms,'https') !== false ? 'https://'. $document_domain : 'http://' . $document_domain;
$w3all_orig_www = strpos($w3all_url_to_cms,'https') !== false ? 'https://www.'. $document_domain : 'http://www.' . $document_domain;
$w3all_url_to_cms_clean = $w3all_url_to_cms;
$w3all_url_to_cms_clean0 = strpos($w3all_url_to_cms_clean, 'https://') !== false ? str_replace('https://', 'http://', $w3all_url_to_cms_clean) : str_replace('http://', 'https://', $w3all_url_to_cms_clean);

echo "<script type=\"text/javascript\">
document.domain = '".$document_domain."';
  var w3all_orig_domains = ['".$w3all_orig."','".$w3all_orig_www."','".$w3all_url_to_cms_clean."','".$w3all_url_to_cms_clean0."','https://localhost','http://localhost'];

iFrameResize({
				inPageLinks             : true,
        targetOrigin: '".$w3all_url_to_cms."', 
        checkOrigin : w3all_orig_domains, // if js error: 'Failed to execute 'postMessage' on 'DOMWindow': The target origin provided does not match the recipient window's origin. Need to fit YOUR domain, ex: mydomain.comcheckOrigin : w3all_orig_domains,
     // heightCalculationMethod: 'documentElementOffset', // If iframe not resize correctly, un-comment (or change with one of others available resize methods) 
     // see: https://github.com/davidjbradshaw/iframe-resizer#heightcalculationmethod
});
</script>";
?>
</div>
