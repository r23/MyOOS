<?php

	require_once('WP_Piwik_Logger.php');
	
	class WP_Piwik_Logger_Dummy extends WP_Piwik_Logger {

		public function loggerOutput($loggerTime, $loggerMessage) {}
		
    }