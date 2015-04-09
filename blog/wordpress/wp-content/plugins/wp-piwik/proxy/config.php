<?php
	
require('../../../../wp-load.php');
require_once('../classes/WP_Piwik_Settings.php');
require_once('../classes/WP_Piwik_Logger_Dummy.php');
	
$logger = new WP_Piwik_Logger_Dummy(__CLASS__);
$settings = new WP_Piwik_Settings($logger);

$PIWIK_URL = $settings->getGlobalOption('piwik_url');
$TOKEN_AUTH = $settings->getGlobalOption('piwik_token');
$timeout = $settings->getGlobalOption('connection_timeout');
ini_set('display_errors',0);