<?php
/**
 * The SEO Link.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Links
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Links;

defined( 'ABSPATH' ) || exit;

/**
 * Link class.
 */
class Link {

	/**
	 * Link url.
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * Link post id.
	 *
	 * @var int
	 */
	protected $target_post_id;

	/**
	 * Link type.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Sets the properties for the object.
	 *
	 * @param string $url            The url.
	 * @param int    $target_post_id ID to the post where the link refers to.
	 * @param string $type           The url type: internal or outbound.
	 */
	public function __construct( $url, $target_post_id, $type ) {
		$this->url            = $url;
		$this->target_post_id = $target_post_id;
		$this->type           = $type;
	}

	/**
	 * Returns the set URL.
	 *
	 * @return string The set url.
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * Returns the set target post id.
	 *
	 * @return int The set target post id.
	 */
	public function get_target_post_id() {
		return (int) $this->target_post_id;
	}

	/**
	 * Return the set link type.
	 *
	 * @return string The set link type.
	 */
	public function get_type() {
		return $this->type;
	}
}
