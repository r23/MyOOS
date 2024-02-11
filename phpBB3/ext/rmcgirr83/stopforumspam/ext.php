<?php

/**
*
* Stop forum Spam extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Rich McGirr (RMcGirr83)
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace rmcgirr83\stopforumspam;

/**
* Extension class for stop forum spam
*/

class ext extends \phpbb\extension\base
{
	/** @var string Require phpBB 3.3.0 */
	const PHPBB_MIN_VERSION = '3.3.0';
	/**
	* Enable extension if phpBB version requirement is met
	*
	* @return bool
	* @access public
	*/
	public function is_enableable()
	{
		$config = $this->container->get('config');

		$enableable = (phpbb_version_compare($config['version'], self::PHPBB_MIN_VERSION, '>='));
		if (!$enableable)
		{
			$language = $this->container->get('language');
			$language->add_lang('stopforumspam', 'rmcgirr83/stopforumspam');

			trigger_error($language->lang('EXTENSION_REQUIREMENTS', self::PHPBB_MIN_VERSION), E_USER_WARNING);
		}

		return $enableable;
	}
}
