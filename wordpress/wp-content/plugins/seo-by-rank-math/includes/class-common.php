<?php
/**
 * The Global functionality of the plugin.
 *
 * Defines the functionality loaded both on admin and frontend.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath;

use RankMath\Paper\Paper;
use RankMath\Traits\Ajax;
use RankMath\Traits\Meta;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Arr;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\Url;
use MyThemeShop\Helpers\Param;

defined( 'ABSPATH' ) || exit;

/**
 * Common class.
 */
class Common {

	use Hooker, Ajax, Meta;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->action( 'admin_bar_menu', 'admin_bar_menu', 100 );
		$this->action( 'loginout', 'nofollow_link' );
		$this->filter( 'register', 'nofollow_link' );
		$this->filter( 'rank_math/excluded_taxonomies', 'default_excluded_taxonomies' );
		add_action( 'init', [ '\RankMath\Replace_Vars', 'setup' ], 99 );

		// Change Permalink for primary term.
		$this->filter( 'post_type_link', 'post_type_link', 9, 2 );
		$this->filter( 'post_link_category', 'post_link_category', 10, 3 );

		// Strip stopwords from slugs.
		if ( Helper::get_settings( 'general.url_strip_stopwords' ) ) {
			$this->filter( 'get_sample_permalink', 'stopwords_sample_permalink', 10, 3 );
		}

		$this->ajax( 'mark_page_as', 'mark_page_as' );

		add_action( 'wp_ajax_nopriv_rank_math_overlay_thumb', [ $this, 'generate_overlay_thumbnail' ] );
	}

	/**
	 * Removes stopword from the sample permalink that is generated in an AJAX request.
	 *
	 * @param array  $permalink The permalink generated for this post by WordPress.
	 * @param int    $post_id   The ID of the post.
	 * @param string $title     The title for the post that the user used.
	 *
	 * @return array
	 */
	public function stopwords_sample_permalink( $permalink, $post_id, $title ) {

		// Early Bail!
		if ( empty( $title ) ) {
			return $permalink;
		}

		// The second element is the slug.
		// Turn it to an array and strip stop words by comparing against an array of stopwords.
		$new_slug_parts = array_diff( explode( '-', $permalink[1] ), $this->get_stopwords() );

		// Don't change the slug if there are less than 3 words left.
		if ( count( $new_slug_parts ) > 2 ) {
			$permalink[1] = join( '-', $new_slug_parts );
		}

		return $permalink;
	}

	/**
	 * Get stop words.
	 *
	 * @return array List of stop words.
	 */
	private function get_stopwords() {

		/* translators: this should be an array of stop words for your language, separated by comma's. */
		$stopwords = explode( ',', esc_html__( "a,about,above,after,again,against,all,am,an,and,any,are,as,at,be,because,been,before,being,below,between,both,but,by,could,did,do,does,doing,down,during,each,few,for,from,further,had,has,have,having,he,he'd,he'll,he's,her,here,here's,hers,herself,him,himself,his,how,how's,i,i'd,i'll,i'm,i've,if,in,into,is,it,it's,its,itself,let's,me,more,most,my,myself,nor,of,on,once,only,or,other,ought,our,ours,ourselves,out,over,own,same,she,she'd,she'll,she's,should,so,some,such,than,that,that's,the,their,theirs,them,themselves,then,there,there's,these,they,they'd,they'll,they're,they've,this,those,through,to,too,under,until,up,very,was,we,we'd,we'll,we're,we've,were,what,what's,when,when's,where,where's,which,while,who,who's,whom,why,why's,with,would,you,you'd,you'll,you're,you've,your,yours,yourself,yourselves", 'rank-math' ) );

		$custom = Helper::get_settings( 'general.stopwords' );
		$custom = Str::to_arr_no_empty( $custom );

		return array_unique( array_merge( $stopwords, $custom ) );
	}

	/**
	 * Filters the category that gets used in the %category% permalink token.
	 *
	 * @param WP_Term $term  The category to use in the permalink.
	 * @param array   $terms Array of all categories (WP_Term objects) associated with the post.
	 * @param WP_Post $post  The post in question.
	 *
	 * @return WP_Term
	 */
	public function post_link_category( $term, $terms, $post ) {
		$primary_term = $this->get_primary_term( $term->taxonomy, $post->ID );

		return false === $primary_term ? $term : $primary_term;
	}

	/**
	 * Filters the permalink for a post of a custom post type.
	 *
	 * @param string  $post_link The post's permalink.
	 * @param WP_Post $post      The post in question.
	 *
	 * @return string
	 */
	public function post_type_link( $post_link, $post ) {
		$taxonomies = Helper::get_object_taxonomies( $post->post_type, 'objects' );
		$taxonomies = wp_filter_object_list( $taxonomies, [ 'hierarchical' => true ], 'and', 'name' );

		foreach ( $taxonomies as $taxonomy ) {
			$find = "%{$taxonomy}%";
			if ( ! Str::contains( $find, $post_link ) ) {
				continue;
			}

			$primary_term = $this->get_primary_term( $taxonomy, $post->ID );
			if ( false !== $primary_term ) {
				// Get the hierachical terms.
				$parents = $this->get_hierarchical_link( $primary_term );

				// Replace the placeholder rewrite tag with hierachical terms.
				$post_link = str_replace( $find, $parents, $post_link );
			}
		}

		return $post_link;
	}

	/**
	 * Get chain of hierarchical link.
	 *
	 * @param WP_Term $term The term in question.
	 *
	 * @return string
	 */
	private function get_hierarchical_link( $term ) {
		if ( is_wp_error( $term ) ) {
			return $term->slug;
		}

		$chain = [];
		$name  = $term->slug;
		if ( $term->parent && ( $term->parent !== $term->term_id ) ) {
			$chain[] = $this->get_hierarchical_link( get_term( $term->parent, $term->taxonomy ) );
		}

		$chain[] = $name;
		return implode( '/', $chain );
	}

	/**
	 * Get primary term of the post.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $post_id  Post ID.
	 *
	 * @return object|false Primary term on success, false if there are no terms, WP_Error on failure.
	 */
	private function get_primary_term( $taxonomy, $post_id ) {
		$primary = Helper::get_post_meta( "primary_{$taxonomy}", $post_id );
		if ( ! $primary ) {
			return false;
		}

		$primary = get_term( $primary, $taxonomy );
		return is_wp_error( $primary ) ? false : $primary;
	}

	/**
	 * Exclude taxonomies.
	 *
	 * @param array $taxonomies Excluded taxonomies.
	 *
	 * @return array
	 */
	public function default_excluded_taxonomies( $taxonomies ) {
		if ( ! current_theme_supports( 'post-formats' ) ) {
			unset( $taxonomies['post_format'] );
		}
		unset( $taxonomies['product_shipping_class'] );

		return $taxonomies;
	}

	/**
	 * Adds rel="nofollow" to a link, only used for login / registration links.
	 *
	 * @param string $link The link element as a string.
	 *
	 * @return string
	 */
	public function nofollow_link( $link ) {
		return str_replace( '<a ', '<a rel="nofollow" ', $link );
	}

	/**
	 * Add SEO item to admin bar with context-specific submenu items.
	 *
	 * @param WP_Admin_Bar $wp_admin_bar Show/Hide admin bar.
	 */
	public function admin_bar_menu( $wp_admin_bar ) {

		if ( ! Helper::has_cap( 'admin_bar' ) ) {
			return;
		}

		$items      = [];
		$mark_me    = false;
		$post_type  = get_post_type();
		$first_menu = get_transient( 'rank_math_first_submenu_id' );
		$first_menu = $first_menu && 'rank-math' !== $first_menu ? str_replace( 'rank-math-', '', $first_menu ) : '';

		$items['main'] = [
			'id'        => 'rank-math',
			'title'     => '<span class="rank-math-icon">' . $this->get_icon() . '</span><span class="rank-math-text">' . esc_html( 'Rank Math SEO', 'rank-math' ) . '</span>',
			'href'      => Helper::get_admin_url( $first_menu ),
			'meta'      => [ 'title' => esc_html__( 'Rank Math Dashboard', 'rank-math' ) ],
			'_priority' => 10,
		];

		if ( current_user_can( 'manage_options' ) ) {
			$items['dashboard'] = [
				'id'        => 'rank-math-dashboard',
				'title'     => esc_html__( 'Dashboard', 'rank-math' ),
				'href'      => $items['main']['href'],
				'parent'    => 'rank-math',
				'meta'      => [ 'title' => esc_html__( 'Dashboard', 'rank-math' ) ],
				'_priority' => 20,
			];
		}

		if ( ! is_admin() ) {
			$this->seo_tools_menu_item( $items );
		}

		if ( Helper::has_cap( 'titles' ) ) {
			if ( is_home() ) {
				$items['local'] = [
					'id'        => 'rank-math-home',
					'title'     => esc_html__( 'Homepage SEO', 'rank-math' ),
					'href'      => Helper::get_admin_url( 'options-titles#setting-panel-homepage' ),
					'parent'    => 'rank-math',
					'meta'      => [ 'title' => esc_html__( 'Edit Homepage SEO Settings', 'rank-math' ) ],
					'_priority' => 35,
				];
			} elseif ( is_singular( Helper::get_accessible_post_types() ) ) {
				$mark_me        = true;
				$object         = get_post_type_object( $post_type );
				$object_type    = 'post';
				$items['local'] = [
					'id'        => 'rank-math-posttype',
					/* translators: Post Type Singular Name */
					'title'     => sprintf( esc_html__( 'SEO Settings for %s', 'rank-math' ), $object->labels->name ),
					'href'      => Helper::get_admin_url( 'options-titles#setting-panel-post-type-' . $post_type ),
					'parent'    => 'rank-math',
					'meta'      => [ 'title' => esc_html__( 'Edit default SEO settings for this post type', 'rank-math' ) ],
					'_priority' => 35,
				];
			} elseif ( ( is_category() || is_tag() || is_tax() ) && in_array( $post_type, Helper::get_accessible_post_types(), true ) ) {
				$mark_me        = true;
				$term           = get_queried_object();
				$labels         = get_taxonomy_labels( get_taxonomy( $term->taxonomy ) );
				$object_type    = 'term';
				$items['local'] = [
					'id'        => 'rank-math-tax',
					/* translators: Taxonomy Singular Name */
					'title'     => sprintf( esc_html__( 'SEO Settings for %s', 'rank-math' ), $labels->name ),
					'href'      => Helper::get_admin_url( 'options-titles#setting-panel-taxonomy-' . $term->taxonomy ),
					'parent'    => 'rank-math',
					'meta'      => [ 'title' => esc_html__( 'Edit SEO settings for this archive page', 'rank-math' ) ],
					'_priority' => 35,
				];
			} elseif ( is_date() ) {
				$items['local'] = [
					'id'        => 'rank-math-date',
					'title'     => esc_html__( 'SEO Settings for Date Archives', 'rank-math' ),
					'href'      => Helper::get_admin_url( 'options-titles#setting-panel-global' ),
					'parent'    => 'rank-math',
					'meta'      => [ 'title' => esc_html__( 'Edit SEO settings for this archive page', 'rank-math' ) ],
					'_priority' => 35,
				];
			} elseif ( is_search() ) {
				$items['local'] = [
					'id'        => 'rank-math-search',
					'title'     => esc_html__( 'SEO Settings for Search Page', 'rank-math' ),
					'href'      => Helper::get_admin_url( 'options-titles#setting-panel-global' ),
					'parent'    => 'rank-math',
					'meta'      => [ 'title' => esc_html__( 'Edit SEO settings for the search results page', 'rank-math' ) ],
					'_priority' => 35,
				];
			}
		}

		if ( is_author() ) {
			$mark_me     = true;
			$object_type = 'user';
		}

		if ( $mark_me && Helper::has_cap( 'onpage_general' ) ) {
			$items['mark'] = [
				'id'        => 'rank-math-mark-me',
				'title'     => esc_html__( 'Mark this page', 'rank-math' ),
				'href'      => '#',
				'parent'    => 'rank-math',
				'_priority' => 100,
			];

			$dashicon_format = '<span class="dashicons dashicons-%s" style="font-family: dashicons; font-size: 19px;"></span>';
			$ispillar_check  = '';
			if ( is_singular( Helper::get_accessible_post_types() ) ) {
				if ( get_post_meta( get_the_ID(), 'rank_math_pillar_content', true ) === 'on' ) {
					$ispillar_check = sprintf( $dashicon_format, 'yes' );
				}
				$items['pillar-content'] = [
					'id'        => 'rank-math-pillar-content',
					'title'     => $ispillar_check . esc_html__( 'As Pillar Content', 'rank-math' ),
					'href'      => '#pillar_content',
					'parent'    => 'rank-math-mark-me',
					'meta'      => [ 'class' => 'mark-page-as' ],
					'_priority' => 110,
				];
			}

			if ( ! is_admin() && Paper::get() ) {
				$robots            = Paper::get()->get_robots();
				$noindex_check     = in_array( 'noindex', $robots, true ) ? sprintf( $dashicon_format, 'yes' ) : '';
				$items['no-index'] = [
					'id'        => 'rank-math-no-index',
					'title'     => $noindex_check . esc_html__( 'As NoIndex', 'rank-math' ),
					'href'      => '#noindex',
					'parent'    => 'rank-math-mark-me',
					'meta'      => [ 'class' => 'mark-page-as' ],
					'_priority' => 120,
				];

				$nofollow_check     = in_array( 'nofollow', $robots, true ) ? sprintf( $dashicon_format, 'yes' ) : '';
				$items['no-follow'] = [
					'id'        => 'rank-math-no-follow',
					'title'     => $nofollow_check . esc_html__( 'As NoFollow', 'rank-math' ),
					'href'      => '#nofollow',
					'parent'    => 'rank-math-mark-me',
					'meta'      => [ 'class' => 'mark-page-as' ],
					'_priority' => 130,
				];
			}
		}

		/**
		 * Add item to rank math admin bar node.
		 *
		 * @param array $items Array of nodes for rank math menu.
		 */
		$items = $this->do_filter( 'admin_bar/items', $items );

		// Keep original order when uasort() deals with equal "_priority" values.
		$orig_order_counter = 0;
		foreach ( $items as &$item ) {
			$item['_original_order'] = $orig_order_counter++;
		}
		uasort( $items, [ $this, 'sort_admin_bar_items' ] );

		foreach ( $items as $item ) {
			$wp_admin_bar->add_node( $item );
		}
	}

	/**
	 * Third party SEO Tools
	 *
	 * @param array $items Array to add menu into.
	 */
	private function seo_tools_menu_item( &$items ) {
		$link_urlencoded = ( ! is_admin() && ! is_preview() ) ? urlencode( Url::get_current_url() ) : '';

		$items['third-party'] = [
			'id'        => 'rank-math-third-party',
			'title'     => esc_html__( 'External Tools', 'rank-math' ),
			'href'      => '#',
			'parent'    => 'rank-math',
			'meta'      => [ 'title' => esc_html__( 'Third Party SEO Tools', 'rank-math' ) ],
			'_priority' => 200,
		];

		$items['third-party-google-structured-data'] = [
			'id'        => 'rank-math-google-structured-data',
			'title'     => esc_html__( 'Google Structured Data', 'rank-math' ),
			'href'      => 'https://search.google.com/structured-data/testing-tool/?url=' . $link_urlencoded,
			'parent'    => 'rank-math-third-party',
			'meta'      => [
				'title'  => esc_html__( 'Google Structured Data Testing Tool', 'rank-math' ),
				'target' => '_blank',
			],
			'_priority' => 210,
		];

		$items['third-party-google-pagespeed'] = [
			'id'        => 'rank-math-google-pagespeed',
			'title'     => esc_html__( 'Google PageSpeed', 'rank-math' ),
			'href'      => 'https://developers.google.com/speed/pagespeed/insights/?url=' . $link_urlencoded,
			'parent'    => 'rank-math-third-party',
			'meta'      => [
				'title'  => esc_html__( 'Google PageSpeed Insights', 'rank-math' ),
				'target' => '_blank',
			],
			'_priority' => 220,
		];

		$items['third-party-google-mobilefriendly'] = [
			'id'        => 'rank-math-google-mobilefriendly',
			'title'     => esc_html__( 'Google Mobile-Friendly', 'rank-math' ),
			'href'      => 'https://search.google.com/test/mobile-friendly?url=' . $link_urlencoded,
			'parent'    => 'rank-math-third-party',
			'meta'      => [
				'title'  => esc_html__( 'Google Mobile-Friendly Test', 'rank-math' ),
				'target' => '_blank',
			],
			'_priority' => 230,
		];

		if ( ! is_admin() && ! is_preview() ) {
			$items['third-party-google-cache'] = [
				'id'        => 'rank-math-google-cache',
				'title'     => esc_html__( 'Google Cache', 'rank-math' ),
				'href'      => 'https://webcache.googleusercontent.com/search?q=cache:' . $link_urlencoded,
				'parent'    => 'rank-math-third-party',
				'meta'      => [
					'title'  => esc_html__( 'See Google\'s cached version of your site', 'rank-math' ),
					'target' => '_blank',
				],
				'_priority' => 240,
			];
		}

		$items['third-party-fb-debugger'] = [
			'id'        => 'rank-math-fb-debugger',
			'title'     => esc_html__( 'Facebook Debugger', 'rank-math' ),
			'href'      => 'https://developers.facebook.com/tools/debug/sharing/?q=' . $link_urlencoded,
			'parent'    => 'rank-math-third-party',
			'meta'      => [
				'title'  => esc_html__( 'Facebook Sharing Debugger', 'rank-math' ),
				'target' => '_blank',
			],
			'_priority' => 250,
		];
	}

	/**
	 * Sort admin bar items callback.
	 *
	 * @param array $item1 Item A to compare.
	 * @param array $item2 Item B to compare.
	 *
	 * @return integer
	 */
	public function sort_admin_bar_items( $item1, $item2 ) {
		if ( ! isset( $item1['_priority'] ) || ! isset( $item2['_priority'] ) || $item1['_priority'] === $item2['_priority'] ) {
			return $item1['_original_order'] < $item2['_original_order'] ? -1 : 1;
		}
		return $item1['_priority'] < $item2['_priority'] ? -1 : 1;
	}

	/**
	 * AJAX function to mark page for different thing.
	 */
	public function mark_page_as() {
		check_ajax_referer( 'rank-math-ajax-nonce', 'security' );
		$this->has_cap_ajax( 'onpage_general' );

		$what        = Param::post( 'what' );
		$object_id   = Param::post( 'objectID' );
		$object_type = Param::post( 'objectType' );

		if ( ! $what || ! $object_id || ! $object_type || ! in_array( $what, [ 'pillar_content', 'noindex', 'nofollow' ], true ) ) {
			return 0;
		}

		if ( 'pillar_content' === $what ) {
			$current = $this->get_meta( $object_type, $object_id, 'rank_math_pillar_content' );
			$updated = 'on' === $current ? 'off' : 'on';
			$this->update_meta( $object_type, $object_id, 'rank_math_pillar_content', $updated );
			die( '1' );
		}

		if ( 'noindex' === $what || 'nofollow' === $what ) {
			$robots = (array) $this->get_meta( $object_type, $object_id, 'rank_math_robots' );
			$robots = array_filter( $robots );

			Arr::add_delete_value( $robots, $what );
			$robots = array_unique( $robots );

			$this->update_meta( $object_type, $object_id, 'rank_math_robots', $robots );
			die( '1' );
		}

		die();
	}

	/**
	 * AJAX function to generate overlay image.
	 */
	public function generate_overlay_thumbnail() {
		$thumbnail_id  = Param::request( 'id', 0, FILTER_VALIDATE_INT );
		$type          = Param::request( 'type', 'play' );
		$overlay_image = Helper::choices_overlay_images()[ $type ]['url'];
		$image         = wp_get_attachment_image_src( $thumbnail_id, 'full' );

		if ( ! empty( $image ) ) {
			$this->create_overlay_image( $image[0], $overlay_image );
		}
		die();
	}

	/**
	 * Create Overlay Image.
	 *
	 * @param string $image_file    The permalink generated for this post by WordPress.
	 * @param string $overlay_image The ID of the post.
	 */
	public function create_overlay_image( $image_file, $overlay_image ) {
		$image_format = pathinfo( $image_file, PATHINFO_EXTENSION );
		if ( ! in_array( $image_format, [ 'jpg', 'jpeg', 'gif', 'png' ], true ) ) {
			return;
		}
		if ( 'jpg' === $image_format ) {
			$image_format = 'jpeg';
		}

		$imagecreatef = 'imagecreatefrom' . $image_format;
		$stamp        = imagecreatefrompng( $overlay_image );
		$image        = $imagecreatef( $image_file );

		// Set the margins for the stamp and get the height/width of the stamp image.
		$img_width     = imagesx( $stamp );
		$img_height    = imagesy( $stamp );
		$margin_right  = round( abs( imagesx( $image ) - $img_width ) / 2 );
		$margin_bottom = round( abs( imagesy( $image ) - $img_height ) / 2 );

		// Copy the stamp image onto our photo using the margin offsets and the photo width to calculate positioning of the stamp.
		imagecopy( $image, $stamp, $margin_right, $margin_bottom, 0, 0, $img_width, $img_height );

		// Output and free memory.
		header( 'Content-type: image/png' );
		imagepng( $image );
		imagedestroy( $image );
	}

	/**
	 * Get Rank Math icon.
	 *
	 * @param integer $width Width of icon.
	 *
	 * @return string
	 */
	private function get_icon( $width = 20 ) {
		return '<svg viewBox="0 0 462.03 462.03" xmlns="http://www.w3.org/2000/svg" width="' . $width . '"><g fill="#fff"><path d="m462 234.84-76.17 3.43 13.43 21-127 81.18-126-52.93-146.26 60.97 10.14 24.34 136.1-56.71 128.57 54 138.69-88.61 13.43 21z"/><path d="m54.1 312.78 92.18-38.41 4.49 1.89v-54.58h-96.67zm210.9-223.57v235.05l7.26 3 89.43-57.05v-181zm-105.44 190.79 96.67 40.62v-165.19h-96.67z"/></g></svg>';
	}
}
