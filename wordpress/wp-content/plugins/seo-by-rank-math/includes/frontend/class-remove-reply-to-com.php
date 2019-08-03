<?php
/**
 * The class handles the removal of replytocom.
 *
 * @since      1.0.15
 * @package    RankMath
 * @subpackage RankMath\Frontend
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Frontend;

use RankMath\Helper;
use RankMath\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Remove Reply To Com class.
 */
class Remove_Reply_To_Com {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		/**
		 * Enable or disable the feature that removes the ?replytocom parameters.
		 *
		 * @param bool $remove Whether to remove the parameters.
		 */
		if ( $this->do_filter( 'frontend/remove_reply_to_com', true ) ) {
			$this->filter( 'comment_reply_link', 'remove_reply_to_com' );
			$this->action( 'template_redirect', 'replytocom_redirect', 1 );
		}
	}

	/**
	 * Replace the ?replytocom with #comment-[number] in a link.
	 *
	 * @param  string $link The comment link as a string.
	 * @return string The new link.
	 */
	public function remove_reply_to_com( $link ) {
		return preg_replace( '`href=(["\'])(?:.*(?:\?|&|&#038;)replytocom=(\d+)#respond)`', 'href=$1#comment-$2', $link );
	}

	/**
	 * Redirect the ?replytocom URLs.
	 *
	 * @return bool True when redirect has been done.
	 */
	public function replytocom_redirect() {

		if ( isset( $_GET['replytocom'] ) && is_singular() ) {
			$url          = get_permalink( $GLOBALS['post']->ID );
			$query_string = remove_query_arg( 'replytocom', sanitize_text_field( $_SERVER['QUERY_STRING'] ) );
			if ( ! empty( $query_string ) ) {
				$url .= '?' . $query_string;
			}
			$url .= '#comment-' . sanitize_text_field( $_GET['replytocom'] );
			Helper::redirect( $url, 301 );
			return true;
		}

		return false;
	}

}
