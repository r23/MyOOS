<?php
/**
 * The ACF Module
 *
 * @since      1.0.33
 * @package    RankMath
 * @subpackage RankMath\ACF
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\ACF;

use RankMath\Helper;
use RankMath\Admin\Admin_Helper;
use RankMath\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * ACF class.
 */
class ACF {
	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		if ( ! Admin_Helper::is_post_edit() && ! Admin_Helper::is_term_edit() ) {
			return;
		}

		$this->action( 'rank_math/admin/enqueue_scripts', 'enqueue' );
	}

	/**
	 * Enqueue styles and scripts for the metabox.
	 */
	public function enqueue() {
		if ( Admin_Helper::is_post_edit() ) {
			wp_enqueue_script( 'rank-math-acf-post-analysis', rank_math()->plugin_url() . 'assets/admin/js/acf-analysis.js', [ 'rank-math-post-metabox' ], rank_math()->version, true );
		}

		if ( Admin_Helper::is_term_edit() ) {
			wp_enqueue_script( 'rank-math-acf-term-analysis', rank_math()->plugin_url() . 'assets/admin/js/acf-analysis.js', [ 'rank-math-term-metabox' ], rank_math()->version, true );
		}

		Helper::add_json( 'acf', $this->get_config() );
	}

	/**
	 * Get Config data
	 *
	 * @return array The config data.
	 */
	private function get_config() {
		$config = [
			'pluginName'      => 'rank-math-acf',
			'refreshRate'     => 1000,
			'headlines'       => [],
			'enableReload'    => true,
			'blacklistFields' => $this->get_blacklist_fields(),
		];

		return $this->do_filter( 'acf/config', $config );
	}

	/**
	 * Get blacklisted fields.
	 *
	 * @return array The Blacklisted fields.
	 */
	private function get_blacklist_fields() {
		$blacklist_type = [
			'number',
			'password',
			'file',
			'select',
			'checkbox',
			'radio',
			'true_false',
			'post_object',
			'page_link',
			'relationship',
			'user',
			'date_picker',
			'color_picker',
			'message',
			'tab',
			'repeater',
			'flexible_content',
			'group',
		];

		return [
			'type' => $blacklist_type,
			'name' => [],
		];
	}
}
