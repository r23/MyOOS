<?php
/**
 * The Web Stories module.
 *
 * @since      1.0.45
 * @package    RankMath
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Web_Stories;

use RankMath\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Web_Stories class.
 */
class Web_Stories {

	use Hooker;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->action( 'web_stories_story_head', 'remove_web_stories_meta_tags', 0 );
		$this->action( 'web_stories_story_head', 'add_rank_math_tags' );
	}

	/**
	 * Remove all meta tags added by the Web Stories plugin.
	 */
	public function remove_web_stories_meta_tags() {
		add_filter( 'web_stories_enable_metadata', '__return_false' );
		add_filter( 'web_stories_enable_schemaorg_metadata', '__return_false' );
		add_filter( 'web_stories_enable_open_graph_metadata', '__return_false' );
		add_filter( 'web_stories_enable_twitter_metadata', '__return_false' );
		remove_action( 'web_stories_story_head', 'rel_canonical' );
	}

	/**
	 * Add Rank Math meta tags.
	 */
	public function add_rank_math_tags() {
		do_action( 'rank_math/head' );
	}
}
