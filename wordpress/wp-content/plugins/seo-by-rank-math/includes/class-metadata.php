<?php
/**
 * The Metadata Class
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath;

defined( 'ABSPATH' ) || exit;

/**
 * Metadata class.
 */
abstract class Metadata {

	/**
	 * Type of object metadata is for
	 *
	 * @var string
	 */
	protected $meta_type = 'post';

	/**
	 * Hold object.
	 *
	 * @var WP_Post|WP_Term|WP_User
	 */
	protected $object = null;

	/**
	 * Hold object id.
	 *
	 * @var int
	 */
	protected $object_id = null;

	/**
	 * Hold objects.
	 *
	 * @var array
	 */
	protected static $objects = [];

	/**
	 * Getter.
	 *
	 * @param string $property Key to get.
	 * @return mixed
	 */
	public function __get( $property ) {

		if ( \property_exists( $this, $property ) ) {
			return $this->$property;
		}

		if ( isset( $this->object->$property ) ) {
			return $this->object->$property;
		}

		return get_metadata( $this->meta_type, $this->object_id, $property, true );
	}

	/**
	 * Constructor.
	 *
	 * @param WP_Post|WP_Term|WP_User $object Current object.
	 */
	public function __construct( $object ) {
		$this->object = $object;
	}

	/**
	 * If object found
	 *
	 * @return bool
	 */
	public function is_found() {
		return ! is_null( $this->object );
	}

	/**
	 * Get object attached
	 *
	 * @return object
	 */
	public function get_object() {
		return $this->object;
	}

	/**
	 * Get metadata for the object
	 *
	 * @param  string $key Internal key of the value to get (without prefix).
	 * @return mixed
	 */
	public function get_metadata( $key ) {
		$meta_key = 'rank_math_' . $key;
		if ( isset( $this->$meta_key ) ) {
			return $this->$meta_key;
		}

		$value    = $this->$meta_key;
		$replaced = $this->may_replace_vars( $key, $value, $this->object );
		if ( false !== $replaced ) {
			$this->$meta_key = $replaced;
			return $this->$meta_key;
		}

		if ( ! $value ) {
			return '';
		}

		$this->$meta_key = Helper::normalize_data( $value );
		return $this->$meta_key;
	}

	/**
	 * Maybe we need to replace vars for this meta data
	 *
	 * @param  string $key    Key to check whether it contains variables.
	 * @param  mixed  $value  Value used to replace variables in.
	 * @param  object $object Object used for replacements.
	 * @return string|bool False if replacement not needed. Replaced variable string.
	 */
	public function may_replace_vars( $key, $value, $object ) {
		$need_replacements = array( 'title', 'description', 'facebook_title', 'twitter_title', 'facebook_description', 'twitter_description', 'snippet_name', 'snippet_desc' );

		// Early Bail!
		if ( ! in_array( $key, $need_replacements, true ) || ! is_string( $value ) || '' === $value ) {
			return false;
		}

		return Helper::replace_vars( $value, $object );
	}
}
