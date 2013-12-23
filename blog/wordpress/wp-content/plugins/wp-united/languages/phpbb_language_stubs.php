<?php

	/**
	 *	English language Stubs - WP-United.
	 *	
	 *	This file just provides the language strings we use in phpBB again to facilitate automatic translation
	 *	by parsing of __();
	 *	
	 *  Strings are automatically parsed from here, together with the rest of WP-United, into the 
	 *  wp-united.pot catalogue template, which translators can use for translations.
	 *
	 *	Translators do not need to do anything with this file.
	 *
	 *	This file is *not* run in WP-United and may not be included. It can be safely deleted.
	 */

	
	if ( !defined('ABSPATH') && !defined('IN_PHPBB') ) {
		exit;
	}
	
	// Base translations
	_e('WordPress Blog', 'wp-united');
	_e("Visit User's Blog", 'wp-united');
	_e('ERROR: Duplicated function name detected. Please visit www.wp-united.com to report the error.', 'wp-united');
	_e('Integration by %sWP-United%s', 'wp-united');
	
	// Permissions
	_e('Can integrate as a WordPress subscriber (can view profile, write comments)', 'wp-united');
	_e('Can integrate as a WordPress contributor (can write but not publish posts)', 'wp-united');
	_e('Can integrate as a WordPress author (can write blog posts)', 'wp-united');
	_e('Can integrate as a WordPress editor (can edit others\' posts)', 'wp-united');
	_e('Can integrate as a WordPress administrator', 'wp-united');
	_e('Can post blog posts to this forum', 'wp-united');
	_e('Can reply to blog posts cross-posted to this forum from WordPress', 'wp-united');

// end of file