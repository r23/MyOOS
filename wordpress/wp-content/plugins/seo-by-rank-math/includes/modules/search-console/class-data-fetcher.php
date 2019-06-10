<?php
/**
 * The Search Console Module
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\modules
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Search_Console;

use Exception;
use RankMath\Helper;
use MyThemeShop\Helpers\Str;

defined( 'ABSPATH' ) || exit;

/**
 * Data_Fetcher class.
 */
class Data_Fetcher extends \WP_Background_Process {

	/**
	 * Action.
	 *
	 * @var string
	 */
	protected $action = 'fetch_search_console_data';

	/**
	 * Task to perform
	 *
	 * @param string $item Item to process.
	 *
	 * @return bool
	 */
	protected function task( $item ) {
		try {
			if ( Str::is_non_empty( $item ) ) {
				Helper::search_console()->get_analytics_data( $item );
			}
			return false;
		} catch ( Exception $error ) {
			return true;
		}
	}

	/**
	 * Is queue empty
	 *
	 * @return bool
	 */
	public function is_empty() {
		return $this->is_queue_empty();
	}

	/**
	 * Kill process.
	 *
	 * Stop processing queue items, clear cronjob and delete all batches.
	 */
	public function kill_process() {
		if ( ! $this->is_queue_empty() ) {
			$this->cancel_process();
		}
	}
}
