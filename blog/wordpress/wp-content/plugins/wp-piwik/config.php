<?php

	/*
		Configure WP-Piwik Logger
		0: Logger disabled
		1: Log to screen
		2: Log to file (logs/YYYYMMDD_wp-piwik.log)
	*/
	if (!defined('WP_PIWIK_ACTIVATE_LOGGER'))
		define('WP_PIWIK_ACTIVATE_LOGGER', 0);
