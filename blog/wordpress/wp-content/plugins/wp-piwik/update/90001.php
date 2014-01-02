<?php
$aryWPMUConfig = get_site_option('wpmu-piwik_global-settings',false);
if (is_plugin_active_for_network('wp-piwik/wp-piwik.php') && $aryWPMUConfig) {
	foreach ($aryWPMUConfig as $key => $value)
		self::$settings->setGlobalOption($key, $value);
	delete_site_option('wpmu-piwik_global-settings');
	self::$settings->setGlobalOption('auto_site_config', true);
} else self::$settings->setGlobalOption('auto_site_config', false);
self::$settings->setGlobalOption('dashboard_seo', false);
self::$settings->setGlobalOption('stats_seo', false);
self::$settings->setGlobalOption('track_404', self::$settings->getOption('track_404'));
self::$settings->setGlobalOption('track_compress', false);
self::$settings->setGlobalOption('track_post', false);