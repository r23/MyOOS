<?php
/**
 * The WooCommerce Module
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\WooCommerce
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\WooCommerce;

use RankMath\Helper;
use RankMath\Module\Base;
use MyThemeShop\Helpers\Arr;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 */
class Admin extends Base {

	/**
	 * The Constructor.
	 */
	public function __construct() {

		$directory = dirname( __FILE__ );
		$this->config(
			[
				'id'        => 'woocommerce',
				'directory' => $directory,
				'help'      => [
					'title' => esc_html__( 'WooCommerce', 'rank-math' ),
					'view'  => $directory . '/views/help.php',
				],
			]
		);
		parent::__construct();

		// Permalink Manager.
		$this->filter( 'rank_math/settings/general', 'add_general_settings' );
		$this->filter( 'rank_math/flush_fields', 'flush_fields' );
	}

	/**
	 * Add module settings into general optional panel.
	 *
	 * @param array $tabs Array of option panel tabs.
	 *
	 * @return array
	 */
	public function add_general_settings( $tabs ) {
		Arr::insert(
			$tabs,
			[
				'woocommerce' => [
					'icon'  => 'dashicons dashicons-cart',
					'title' => esc_html__( 'WooCommerce', 'rank-math' ),
					'desc'  => esc_html__( 'Choose how you want Rank Math to handle your WooCommerce SEO. These options help you create cleaner, SEO friendly URLs, and optimize your WooCommerce product pages.', 'rank-math' ),
					'file'  => $this->directory . '/views/options-general.php',
				],
			],
			7
		);
		return $tabs;
	}

	/**
	 * Fields after updation of which we need to flush rewrite rules.
	 *
	 * @param array $fields Fields to flush rewrite rules on.
	 *
	 * @return array
	 */
	public function flush_fields( $fields ) {
		$fields[] = 'wc_remove_product_base';
		$fields[] = 'wc_remove_category_base';
		$fields[] = 'wc_remove_category_parent_slugs';

		return $fields;
	}
}
