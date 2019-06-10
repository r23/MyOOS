<?php
/**
 * The Rich Snippet Module
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\RichSnippet;

use RankMath\Helper;
use RankMath\Admin\Admin_Helper;
use RankMath\Module;
use MyThemeShop\Helpers\Arr;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 */
class Admin extends Module {

	/**
	 * The Constructor.
	 */
	public function __construct() {

		$directory = dirname( __FILE__ );
		$this->config([
			'id'        => 'rich-snippet',
			'directory' => $directory,
			'help'      => [
				'title' => esc_html__( 'Rich Snippet', 'rank-math' ),
				'view'  => $directory . '/views/help.php',
			],
		]);
		parent::__construct();

		$this->filter( 'rank_math/metabox/tabs', 'add_metabox_tab' );
		$this->action( 'rank_math/metabox/process_fields', 'save_advanced_meta' );
	}

	/**
	 * Add rich snippet tab to the metabox.
	 *
	 * @param array $tabs Array of tabs.
	 *
	 * @return array
	 */
	public function add_metabox_tab( $tabs ) {

		if ( Admin_Helper::is_term_profile_page() ) {
			return $tabs;
		}

		Arr::insert( $tabs, [
			'richsnippet' => [
				'icon'       => 'dashicons',
				'title'      => esc_html__( 'Rich Snippet', 'rank-math' ),
				'desc'       => esc_html__( 'This tab contains snippet options.', 'rank-math' ),
				'file'       => $this->directory . '/views/metabox-options.php',
				'capability' => 'onpage_snippet',
			],
		], 3 );

		return $tabs;
	}

	/**
	 * Save handler for metadata.
	 *
	 * @param CMB2 $cmb CMB2 instance.
	 */
	public function save_advanced_meta( $cmb ) {
		$instructions = $this->can_save_data( $cmb );
		if ( empty( $instructions ) ) {
			return;
		}

		foreach ( $instructions as $key => $instruction ) {
			if ( ! $instruction['name'] || ! $instruction['text'] || empty( trim( $instruction['name'] ) ) ) {
				unset( $instructions[ $key ] );
			}
		}
		$cmb->data_to_save['rank_math_snippet_recipe_instructions'] = $instructions;
	}

	/**
	 * Can save metadata.
	 *
	 * @param CMB2 $cmb CMB2 instance.
	 *
	 * @return boolean|array
	 */
	private function can_save_data( $cmb ) {
		if ( isset( $cmb->data_to_save['rank_math_snippet_recipe_instruction_type'] ) && 'HowToSection' !== $cmb->data_to_save['rank_math_snippet_recipe_instruction_type'] ) {
			return false;
		}

		return isset( $cmb->data_to_save['rank_math_snippet_recipe_instructions'] ) ? $cmb->data_to_save['rank_math_snippet_recipe_instructions'] : [];
	}
}
