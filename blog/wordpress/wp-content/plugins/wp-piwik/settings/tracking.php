<?php
$bolCURL = function_exists('curl_init');
$bolFOpen = ini_get('allow_url_fopen');
if (!$bolFOpen && !$bolCURL) {
?><tr>	
	<td colspan="2">
		<strong><?php _e('Error: cURL is not enabled and fopen is not allowed to open URLs. WP-Piwik won\'t be able to connect to Piwik.'); ?></strong>
	</td>
</tr><?php } else { ?>
<tr><td colspan="2"><?php _e('Add tracking code', 'wp-piwik'); ?>: &nbsp; 
	<input 
		onchange="javascript:$j('#wp-piwik-tracking-settings').toggleClass('wp-piwik-form-table-hide');"
	type="checkbox" value="1" id="wp-piwik_addjs" name="wp-piwik_addjs"<?php echo (self::$settings->getGlobalOption('add_tracking_code')?' checked="checked"':''); ?> />
	<label for="wp-piwik_addjs"><?php _e('If your template uses wp_footer(), WP-Piwik can automatically add the Piwik javascript code to your blog.', 'wp-piwik'); ?></label>
</td></tr>
</table>
<?php 
if (self::$settings->getGlobalOption('add_tracking_code')) {
	$strJavaScript = $this->callPiwikAPI('SitesManager.getJavascriptTag');
	if (is_array($strJavaScript)) {
		if (isset($strJavaScript['result']) && $strJavaScript['result'] == 'error')
			self::showErrorMessage(__($strJavaScript['message'],'wp-piwik'));
	} else {	
		// Save javascript code
		self::$settings->setOption('tracking_code', $strJavaScript);
	}
	self::$settings->save();
}
?>
<table id="wp-piwik-tracking-settings" class="wp-piwik-form-table form-table<?php echo (!self::$settings->getGlobalOption('add_tracking_code')?' wp-piwik-form-table-hide':''); ?>">
<tr><th><?php _e('Tracking code preview', 'wp-piwik'); ?>:</th><td>
<textarea id="wp-piwik_jscode" name="wp-piwik_jscode" readonly="readonly" rows="13" cols="80">
<?php echo (is_plugin_active_for_network('wp-piwik/wp-piwik.php')?'*** SITE SPECIFIC EXAMPLE CODE ***'."\n":'').htmlentities(self::$settings->getOption('tracking_code')); ?>
</textarea>
</td></tr>

<tr><th><?php _e('&lt;noscript&gt; code preview', 'wp-piwik'); ?>:</th><td>
<textarea id="wp-piwik_nocode" name="wp-piwik_nocode" readonly="readonly" rows="2" cols="80">
<?php echo (is_plugin_active_for_network('wp-piwik/wp-piwik.php')?'*** SITE SPECIFIC EXAMPLE CODE ***'."\n":'').htmlentities(self::$settings->getOption('noscript_code')); ?>
</textarea>
</td></tr>

<tr><th><?php _e('Default tracking', 'wp-piwik'); ?>:</th><td>
	<input type="radio" value="0" id="wp-piwik_default" name="wp-piwik_trackingmode"<?php echo (self::$settings->getGlobalOption('track_mode')==0?' checked="checked"':''); ?> />
	<label for="wp-piwik_compress"><?php _e('WP-Piwik uses the Piwik default tracking code.', 'wp-piwik'); ?> <a href="http://demo.piwik.org/js/README">js/README</a>.</label>
</td></tr>

<tr><th><?php _e('Use js/index.php', 'wp-piwik'); ?>:</th><td>
	<input type="radio" value="1" id="wp-piwik_compress" name="wp-piwik_trackingmode"<?php echo (self::$settings->getGlobalOption('track_mode')==1?' checked="checked"':''); ?> />
	<label for="wp-piwik_compress"><?php _e('WP-Piwik can automatically use js/index.php instead of piwik.js and piwik.php. See', 'wp-piwik'); ?> <a href="http://demo.piwik.org/js/README">js/README</a>.</label>
</td></tr>

<tr><th><?php _e('Use proxy script', 'wp-piwik'); ?>:</th><td>
	<input type="radio" value="2" id="wp-piwik_proxy" name="wp-piwik_trackingmode"<?php echo (self::$settings->getGlobalOption('track_mode')==2?' checked="checked"':''); ?> />
	<label for="wp-piwik_compress"><?php _e('WP-Piwik will use the piwik.php proxy script. See', 'wp-piwik'); ?> <a href="http://piwik.org/faq/how-to/#faq_132">Piwik FAQ</a>.</label>
</td></tr>

<tr><th><?php _e('JavaScript code position', 'wp-piwik'); ?>:</th><td>
	<select id="wp-piwik_codeposition" name="wp-piwik_codeposition"<?php echo (self::$settings->getGlobalOption('track_codeposition')?' checked="checked"':''); ?>>
		<option value="footer"<?php echo (self::$settings->getGlobalOption('track_codeposition') == 'footer'?' selected="selected"':''); ?>><?php _e('Footer', 'wp-piwik'); ?></option>
		<option value="header"<?php echo (self::$settings->getGlobalOption('track_codeposition') == 'header'?' selected="selected"':''); ?>><?php _e('Header', 'wp-piwik'); ?></option>
	</select>
	<label for="wp-piwik_codeposition"><?php echo _e('Choose whether the JavaScript code is added to the footer or the header.', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Add &lt;noscript&gt;', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_noscript" name="wp-piwik_noscript"<?php echo (self::$settings->getGlobalOption('track_noscript')?' checked="checked"':''); ?> />
	<label for="wp-piwik_noscript"><?php echo _e('Adds the &lt;noscript&gt; code to your footer.', 'wp-piwik'); ?> <?php _e('Disabled in proxy mode.', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Add rec parameter to noscript code', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_nojavascript" name="wp-piwik_nojavascript"<?php echo (self::$settings->getGlobalOption('track_nojavascript')?' checked="checked"':''); ?> />
	<label for="wp-piwik_nojavascript"><?php echo _e('Enable tracking for visitors without JavaScript (not recommended). See', 'wp-piwik'); ?> <a href="http://piwik.org/faq/how-to/#faq_176">Piwik FAQ</a>. <?php _e('Disabled in proxy mode.', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Disable cookies', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_disable_cookies" name="wp-piwik_disable_cookies"<?php echo (self::$settings->getGlobalOption('disable_cookies')?' checked="checked"':''); ?> />
	<label for="wp-piwik_disable_cookies"><?php echo _e('Disable all tracking cookies for a visitor.', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Limit cookie lifetime', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_limit_cookies" name="wp-piwik_limit_cookies"<?php echo (self::$settings->getGlobalOption('limit_cookies')?' checked="checked"':''); ?> />
	<label for="wp-piwik_limit_cookies"><?php echo _e('Limit cookie lifetime as follows', 'wp-piwik'); ?>:</label><br />
	<?php echo _e('Visitor timeout (seconds)', 'wp-piwik'); ?>: <input type="text" name="wp-piwik_limit_cookies_visitor" value="<?php echo self::$settings->getGlobalOption('limit_cookies_visitor'); ?>"><br /><?php echo _e('Session timeout (seconds)', 'wp-piwik'); ?>: <input type="text" name="wp-piwik_limit_cookies_session" value="<?php echo self::$settings->getGlobalOption('limit_cookies_session'); ?>"></td></tr>

<tr><th><?php _e('Track visitors across all subdomains', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_track_across" name="wp-piwik_track_across"<?php echo (self::$settings->getGlobalOption('track_across')?' checked="checked"':''); ?> />
	<label for="wp-piwik_track_across"><?php echo _e('Adds *.-prefix to cookie domain.', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Track visitors across all alias URLs', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_track_across_alias" name="wp-piwik_track_across_alias"<?php echo (self::$settings->getGlobalOption('track_across_alias')?' checked="checked"':''); ?> />
	<label for="wp-piwik_track_across_alias"><?php echo _e('Adds *.-prefix to tracked domain.', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Track search', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_search" name="wp-piwik_search"<?php echo (self::$settings->getGlobalOption('track_search')?' checked="checked"':''); ?> />
	<label for="wp-piwik_search"><?php echo _e('Use Piwik\'s advanced Site Search Analytics feature. See', 'wp-piwik'); ?> <a href="http://piwik.org/docs/javascript-tracking/#toc-tracking-internal-search-keywords-categories-and-no-result-search-keywords">Piwik Docs</a>.</label>
</td></tr>

<tr><th><?php _e('Track 404', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_404" name="wp-piwik_404"<?php echo (self::$settings->getGlobalOption('track_404')?' checked="checked"':''); ?> />
	<label for="wp-piwik_404"><?php echo _e('WP-Piwik can automatically add a 404-category to track 404-page-visits.', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Add annotation on new post', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_annotations" name="wp-piwik_annotations"<?php echo (self::$settings->getGlobalOption('add_post_annotations')?' checked="checked"':''); ?> />
	<label for="wp-piwik_annotations"><?php echo _e('Add a Piwik annotation on each new post, see', 'wp-piwik'); ?> <a href="http://piwik.org/docs/annotations/">Piwik Docs</a>.</label>
</td></tr>

<tr><th><?php _e('Show custom variables box', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_customvars" name="wp-piwik_customvars"<?php echo (self::$settings->getGlobalOption('add_customvars_box')?' checked="checked"':''); ?> />
	<label for="wp-piwik_customvars"><?php echo _e('Show a custom vars edit box on post edit page.', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Avoid mod_security', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_reqpost" name="wp-piwik_reqpost"<?php echo (self::$settings->getGlobalOption('track_post')?' checked="checked"':''); ?> />
	<label for="wp-piwik_reqpost"><?php _e('WP-Piwik can automatically force the Tracking Code to sent data in POST. See', 'wp-piwik'); ?> <a href="http://piwik.org/faq/troubleshooting/#faq_100">Piwik FAQ</a>. <?php _e('Disabled in proxy mode.', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Add data-cfasync=false', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_datacfasync" name="wp-piwik_datacfasync"<?php echo (self::$settings->getGlobalOption('track_datacfasync')?' checked="checked"':''); ?> />
	<label for="wp-piwik_datacfasync"><?php _e('Adds data-cfasync=false to the script tag, e.g., to ask Rocket Loader to ignore the script.', 'wp-piwik'); ?>
</td></tr>

<tr><th><?php _e('CDN URL', 'wp-piwik'); ?>:</th><td>
	http://<input type="text" value="<?php echo self::$settings->getGlobalOption('track_cdnurl'); ?>" id="wp-piwik_cdnurl" name="wp-piwik_cdnurl" /> https://<input type="text" value="<?php echo self::$settings->getGlobalOption('track_cdnurlssl'); ?>" id="wp-piwik_cdnurlssl" name="wp-piwik_cdnurlssl" /><br />
	<label for="wp-piwik_reqpost"><?php _e('Leave blank if you do not want to define a CDN URL or you do not know what this is.', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Track admin pages', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_trackadmin" name="wp-piwik_trackadmin"<?php echo (self::$settings->getGlobalOption('track_admin')?' checked="checked"':''); ?> />
	<label for="wp-piwik_trackadmin"><?php _e('Enable to track users on admin pages (remember to configure the tracking filter appropriately).', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Track RSS feeds', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_trackfeed" name="wp-piwik_trackfeed"<?php echo (self::$settings->getGlobalOption('track_feed')?' checked="checked"':''); ?> />
	<label for="wp-piwik_trackfeed"><?php _e('Enable to track posts in feeds via tracking pixel.', 'wp-piwik'); ?></label>
</td></tr>

<tr><th><?php _e('Track RSS feed links as campaign', 'wp-piwik'); ?>:</th><td>
	<input type="checkbox" value="1" id="wp-piwik_trackfeed_addcampaign" name="wp-piwik_trackfeed_addcampaign"<?php echo (self::$settings->getGlobalOption('track_feed_addcampaign')?' checked="checked"':''); ?> />
	<label for="wp-piwik_trackfeed_addcampaign"><?php _e('This will add Piwik campaign parameters to the RSS feed links.', 'wp-piwik'); ?> (<a href="https://piwik.org/docs/tracking-campaigns/">Piwik: Tracking campaign</a>)</label><br />
	<?php _e('Campaign', 'wp-piwik'); ?>: <input type="text" value="<?php echo self::$settings->getGlobalOption('track_feed_campaign'); ?>" id="wp-piwik_trackfeed_campaign" name="wp-piwik_trackfeed_campaign"> <?php _e('Keyword: post name.', 'wp-piwik'); ?>
</td></tr>

<tr><th><?php _e('Tracking filter', 'wp-piwik'); ?>:</th><td>
<?php
	global $wp_roles;
	$aryFilter = self::$settings->getGlobalOption('capability_stealth');
	foreach($wp_roles->role_names as $strKey => $strName)  {
		echo '<input type="checkbox" '.(isset($aryFilter[$strKey]) && $aryFilter[$strKey]?'checked="checked" ':'').'value="1" name="wp-piwik_filter['.$strKey.']" /> '.$strName.' &nbsp; ';
	}
?><br><?php _e('Choose users by user role you do <strong>not</strong> want to track.','wp-piwik'); ?></td></tr>
</table>
<table class="wp-piwik-form-table form-table">
<?php } ?>