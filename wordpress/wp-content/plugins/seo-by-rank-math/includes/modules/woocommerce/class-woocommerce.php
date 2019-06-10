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
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\Attachment;
use RankMath\OpenGraph\Image as OpenGraph_Image;

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce class.
 */
class WooCommerce {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {

		if ( is_admin() ) {
			new Admin;
		}

		// Permalink Manager.
		if ( ! is_admin() ) {
			if (
				Helper::get_settings( 'general.wc_remove_product_base' ) ||
				Helper::get_settings( 'general.wc_remove_category_base' ) ||
				Helper::get_settings( 'general.wc_remove_category_parent_slugs' )
			) {
				$this->action( 'request', 'request' );
			}

			if ( Helper::get_settings( 'general.wc_remove_generator' ) ) {
				remove_action( 'get_the_generator_html', 'wc_generator_tag', 10 );
				remove_action( 'get_the_generator_xhtml', 'wc_generator_tag', 10 );
			}

			// Add metadescription filter.
			$this->filter( 'rank_math/frontend/description', 'metadesc' );

			// Robots.
			$this->filter( 'rank_math/frontend/robots', 'robots' );

			// OpenGraph.
			$this->filter( 'language_attributes', 'og_product_namespace', 11 );
			$this->filter( 'rank_math/opengraph/desc', 'og_desc_product_taxonomy' );
			$this->action( 'rank_math/opengraph/facebook', 'og_enhancement', 50 );
			$this->action( 'rank_math/opengraph/facebook/add_additional_images', 'set_opengraph_image' );

			// Sitemap.
			$this->filter( 'rank_math/sitemap/exclude_post_type', 'sitemap_exclude_post_type', 10, 2 );
			$this->filter( 'rank_math/sitemap/post_type_archive_link', 'sitemap_taxonomies', 10, 2 );
			$this->filter( 'rank_math/sitemap/post_type_archive_link', 'sitemap_post_type_archive_link', 10, 2 );
			$this->filter( 'rank_math/sitemap/urlimages', 'add_product_images_to_xml_sitemap', 10, 2 );

		}

		if ( Helper::get_settings( 'general.wc_remove_product_base' ) ) {
			$this->filter( 'post_type_link', 'product_post_type_link', 1, 2 );
		}
		if ( Helper::get_settings( 'general.wc_remove_category_base' ) || Helper::get_settings( 'general.wc_remove_category_parent_slugs' ) ) {
			$this->filter( 'term_link', 'product_term_link', 1, 3 );
		}

		$this->action( 'rank_math/vars/register_extra_replacements', 'register_replacements' );
	}

	/**
	 * Replace request if product found.
	 *
	 * @param  array $request Current request.
	 * @return array
	 */
	public function request( $request ) {
		global $wp, $wpdb;
		$url = $wp->request;

		if ( ! empty( $url ) ) {
			$replace = [];
			$url     = explode( '/', $url );
			$slug    = array_pop( $url );

			if ( 'feed' === $slug ) {
				$replace['feed'] = $slug;
				$slug            = array_pop( $url );
			}

			if ( 'amp' === $slug ) {
				$replace['amp'] = $slug;
				$slug           = array_pop( $url );
			}

			if ( 0 === strpos( $slug, 'comment-page-' ) ) {
				$replace['cpage'] = substr( $slug, strlen( 'comment-page-' ) );
				$slug             = array_pop( $url );
			}

			$query = "SELECT COUNT(ID) as count_id FROM {$wpdb->posts} WHERE post_name = %s AND post_type = %s";
			$num   = intval( $wpdb->get_var( $wpdb->prepare( $query, array( $slug, 'product' ) ) ) ); // phpcs:ignore
			if ( $num > 0 ) {
				$replace['page']      = '';
				$replace['name']      = $slug;
				$replace['product']   = $slug;
				$replace['post_type'] = 'product';

				return $replace;
			}
		}

		return $request;
	}

	/**
	 * Replace product permalink according to settings.
	 *
	 * @param  string  $permalink The existing permalink URL.
	 * @param  WP_Post $post WP_Post object.
	 * @return string
	 */
	public function product_post_type_link( $permalink, $post ) {
		if ( 'product' !== $post->post_type ) {
			return $permalink;
		}

		if ( ! get_option( 'permalink_structure' ) ) {
			return $permalink;
		}

		$permalink_structure = wc_get_permalink_structure();
		$product_base        = $permalink_structure['product_rewrite_slug'];
		$product_base        = explode( '/', ltrim( $product_base, '/' ) );

		$link = $permalink;
		foreach ( $product_base as $remove ) {
			if ( '%product_cat%' === $remove ) {
				continue;
			}
			$link = preg_replace( "#{$remove}/#i", '', $link, 1 );
		}

		return $link;
	}

	/**
	 * Replace category permalink according to settings.
	 *
	 * @param  string $link     Term link URL.
	 * @param  object $term     Term object.
	 * @param  string $taxonomy Taxonomy slug.
	 * @return string
	 */
	public function product_term_link( $link, $term, $taxonomy ) {
		if ( 'product_cat' !== $taxonomy ) {
			return $link;
		}

		if ( ! get_option( 'permalink_structure' ) ) {
			return $link;
		}

		$permalink_structure  = wc_get_permalink_structure();
		$category_base        = trailingslashit( $permalink_structure['category_rewrite_slug'] );
		$remove_category_base = Helper::get_settings( 'general.wc_remove_category_base' );
		$remove_parent_slugs  = Helper::get_settings( 'general.wc_remove_category_parent_slugs' );
		$is_language_switcher = ( class_exists( 'Sitepress' ) && strpos( $original_link, 'lang=' ) );

		if ( $remove_category_base ) {
			$link          = str_replace( $category_base, '', $link );
			$category_base = '';
		}

		if ( $remove_parent_slugs && ! $is_language_switcher ) {
			$link = home_url( trailingslashit( $category_base . $term->slug ) );
		}

		return $link;
	}

	/**
	 * Change robots for WooCommerce pages according to settings
	 *
	 * @param  array $robots Array of robots to sanitize.
	 * @return array Modified robots.
	 */
	public function robots( $robots ) {
		if ( is_cart() || is_checkout() || is_account_page() ) {
			remove_action( 'wp_head', 'wc_page_noindex' );
			return array(
				'index'  => 'noindex',
				'follow' => 'follow',
			);
		}

		return $robots;
	}

	/**
	 * Returns the meta description. Checks which value should be used when the given meta description is empty.
	 *
	 * It will use the short_description if that one is set. Otherwise it will use the full
	 * product description limited to 156 characters. If everything is empty, it will return an empty string.
	 *
	 * @param  string $metadesc The meta description to check.
	 * @return string The meta description.
	 */
	public function metadesc( $metadesc ) {

		if ( '' !== $metadesc || ! is_singular( 'product' ) ) {
			return $metadesc;
		}

		$product = $this->get_product_by_id( get_the_id() );
		if ( ! is_object( $product ) ) {
			return '';
		}

		$short_desc = $this->get_short_description( $product );
		if ( '' !== $short_desc ) {
			return $short_desc;
		}

		$long_desc = $this->get_long_description( $product );
		if ( '' !== $long_desc ) {
			return wp_html_excerpt( $long_desc, 156 );
		}

		return '';
	}

	/**
	 * Filter for the namespace, adding the OpenGraph namespace.
	 *
	 * @link https://developers.facebook.com/docs/reference/opengraph/object-type/product/
	 *
	 * @param  string $input The input namespace string.
	 * @return string
	 */
	public function og_product_namespace( $input ) {
		if ( is_singular( 'product' ) ) {
			$input = preg_replace( '/prefix="([^"]+)"/', 'prefix="$1 product: http://ogp.me/ns/product#"', $input );
		}

		return $input;
	}

	/**
	 * Make sure the OpenGraph description is put out.
	 *
	 * @param  string $desc The current description, will be overwritten if we're on a product page.
	 * @return string
	 */
	public function og_desc_product_taxonomy( $desc ) {
		if ( is_product_taxonomy() ) {
			$term_desc = term_description();
			if ( ! empty( $term_desc ) ) {
				$desc = wp_strip_all_tags( $term_desc, true );
				$desc = strip_shortcodes( $desc );
			}
		}

		return $desc;
	}

	/**
	 * Adds the other product images to the OpenGraph output.
	 *
	 * @param OpenGraph $opengraph The current opengraph network object.
	 */
	public function og_enhancement( $opengraph ) {
		$product = $this->get_product();
		if ( ! is_object( $product ) ) {
			return;
		}

		$brands = $this->get_brands( get_the_ID() );
		if ( ! empty( $brands ) ) {
			$opengraph->tag( 'product:brand', $brands[0]->name );
		}

		/**
		 * Allow developers to prevent the output of the price in the OpenGraph tags.
		 *
		 * @param bool unsigned Defaults to true.
		 */
		if ( $this->do_filter( 'woocommerce/og_price', true ) ) {
			$opengraph->tag( 'product:price:amount', $product->get_price() );
			$opengraph->tag( 'product:price:currency', get_woocommerce_currency() );
		}

		if ( $product->is_in_stock() ) {
			$opengraph->tag( 'product:availability', 'instock' );
		}
	}

	/**
	 * Adds the opengraph images.
	 *
	 * @param OpenGraph_Image $opengraph_image The OpenGraph image to use.
	 */
	public function set_opengraph_image( OpenGraph_Image $opengraph_image ) {

		if ( ! function_exists( 'is_product_category' ) || is_product_category() ) {
			global $wp_query;
			$cat          = $wp_query->get_queried_object();
			$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$opengraph_image->add_image_by_id( $thumbnail_id );
		}

		$product = $this->get_product();
		if ( ! is_object( $product ) ) {
			return;
		}

		$img_ids = $this->get_image_ids( $product );
		if ( is_array( $img_ids ) && ! empty( $img_ids ) ) {
			foreach ( $img_ids as $img_id ) {
				$opengraph_image->add_image_by_id( $img_id );
			}
		}
	}

	/**
	 * Make sure product variations and shop coupons are not included in the XML sitemap.
	 *
	 * @param  bool   $bool      Whether or not to include this post type in the XML sitemap.
	 * @param  string $post_type The post type of the post.
	 * @return bool
	 */
	public function sitemap_exclude_post_type( $bool, $post_type ) {
		if ( in_array( $post_type, array( 'product_variation', 'shop_coupon' ), true ) ) {
			return true;
		}

		return $bool;
	}

	/**
	 * Make sure product attribute taxonomies are not included in the XML sitemap.
	 *
	 * @param  bool   $bool     Whether or not to include this post type in the XML sitemap.
	 * @param  string $taxonomy The taxonomy to check against.
	 * @return bool
	 */
	public function sitemap_taxonomies( $bool, $taxonomy ) {
		if ( in_array( $taxonomy, array( 'product_type', 'product_shipping_class', 'shop_order_status' ), true ) ) {
			return true;
		}

		if ( Str::starts_with( 'pa_', $taxonomy ) ) {
			return true;
		}

		return $bool;
	}

	/**
	 * Filters the archive link on the product sitemap.
	 *
	 * @param  string $link      The archive link.
	 * @param  string $post_type The post type to check against.
	 * @return bool
	 */
	public function sitemap_post_type_archive_link( $link, $post_type ) {
		if ( 'product' !== $post_type || ! function_exists( 'wc_get_page_id' ) ) {
			return $link;
		}

		$shop_page_id = wc_get_page_id( 'shop' );
		$home_page_id = (int) get_option( 'page_on_front' );
		if ( 1 > $shop_page_id || 'publish' !== get_post_status( $shop_page_id ) || $home_page_id === $shop_page_id ) {
			return false;
		}

		$robots = Helper::get_post_meta( 'robots', $shop_page_id );
		if ( ! empty( $robots ) && is_array( $robots ) && in_array( 'noindex', $robots, true ) ) {
			return false;
		}

		return $link;
	}

	/**
	 * Add the product gallery images to the XML sitemap.
	 *
	 * @param  array $images  The array of images for the post.
	 * @param  int   $post_id The ID of the post object.
	 * @return array
	 */
	public function add_product_images_to_xml_sitemap( $images, $post_id ) {
		if ( metadata_exists( 'post', $post_id, '_product_image_gallery' ) ) {
			$product_gallery = get_post_meta( $post_id, '_product_image_gallery', true );
			$attachments     = array_filter( explode( ',', $product_gallery ) );
			foreach ( $attachments as $attachment_id ) {
				$image_src = wp_get_attachment_image_src( $attachment_id, 'full' );
				$image     = array(
					'src'   => $this->do_filter( 'sitemap/xml_img_src', $image_src[0], $post_id ),
					'title' => get_the_title( $attachment_id ),
					'alt'   => Attachment::get_alt_tag( $attachment_id ),
				);
				$images[]  = $image;

				unset( $image, $image_src );
			}
		}

		return $images;
	}

	/**
	 * Registers variable replacements for WooCommerce products.
	 */
	public function register_replacements() {
		Helper::register_var_replacement(
			'wc_price',
			array( $this, 'get_product_var_price' ),
			array(
				'name'    => esc_html__( 'Product\'s price.', 'rank-math' ),
				'desc'    => esc_html__( 'Product\'s price of the current product', 'rank-math' ),
				'example' => $this->get_product_var_price(),
			)
		);

		Helper::register_var_replacement(
			'wc_sku',
			array( $this, 'get_product_var_sku' ),
			array(
				'name'    => esc_html__( 'Product\'s SKU.', 'rank-math' ),
				'desc'    => esc_html__( 'Product\'s SKU of the current product', 'rank-math' ),
				'example' => $this->get_product_var_sku(),
			)
		);

		Helper::register_var_replacement(
			'wc_shortdesc',
			array( $this, 'get_short_description' ),
			array(
				'name'    => esc_html__( 'Product\'s short description.', 'rank-math' ),
				'desc'    => esc_html__( 'Product\'s short description of the current product', 'rank-math' ),
				'example' => $this->get_short_description(),
			)
		);

		Helper::register_var_replacement(
			'wc_brand',
			array( $this, 'get_product_var_brand' ),
			array(
				'name'    => esc_html__( 'Product\'s brand.', 'rank-math' ),
				'desc'    => esc_html__( 'Product\'s brand of the current product', 'rank-math' ),
				'example' => $this->get_product_var_brand(),
			)
		);
	}

	/**
	 * Retrieves the product price.
	 *
	 * @return string
	 */
	public function get_product_var_price() {

		$product = $this->get_product();
		if ( ! is_object( $product ) ) {
			return '';
		}

		if ( method_exists( $product, 'get_price' ) ) {
			return wp_strip_all_tags( wc_price( $product->get_price() ), true );
		}

		return '';
	}

	/**
	 * Retrieves the product SKU.
	 *
	 * @return string
	 */
	public function get_product_var_sku() {
		$product = $this->get_product();
		if ( ! is_object( $product ) ) {
			return '';
		}

		if ( method_exists( $product, 'get_sku' ) ) {
			return $product->get_sku();
		}

		return '';
	}

	/**
	 * Retrieves the product brand.
	 *
	 * @return string
	 */
	public function get_product_var_brand() {
		$product = $this->get_product();
		if ( ! is_object( $product ) ) {
			return '';
		}

		$brands = $this->get_brands( $product->get_id() );
		if ( ! empty( $brands ) ) {
			return $brands[0]->name;
		}

		return '';
	}

	/**
	 * Returns the product object when the current page is the product page.
	 *
	 * @return null|WC_Product
	 */
	protected function get_product() {
		$product_id = isset( $_GET['post'] ) ? $_GET['post'] : get_queried_object_id();
		if ( ! $product_id && ( ! is_singular( 'product' ) || ! function_exists( 'wc_get_product' ) ) ) {
			return null;
		}
		return wc_get_product( $product_id );
	}


	/**
	 * Returns the product for given product_id.
	 *
	 * @param  int $product_id The id to get the product for.
	 * @return null|WC_Product
	 */
	protected function get_product_by_id( $product_id ) {
		if ( function_exists( 'wc_get_product' ) ) {
			return wc_get_product( $product_id );
		}

		if ( function_exists( 'get_product' ) ) {
			return get_product( $product_id );
		}

		return null;
	}

	/**
	 * Checks if product class has a short description method.
	 * Otherwise it returns the value of the post_excerpt from the post attribute.
	 *
	 * @param  WC_Product $product The product.
	 * @return string
	 */
	public function get_short_description( $product = null ) {
		if ( is_null( $product ) ) {
			$product = $this->get_product();
		}

		if ( ! is_object( $product ) ) {
			return '';
		}

		if ( method_exists( $product, 'get_short_description' ) ) {
			return $product->get_short_description();
		}
		return $product->post->post_excerpt;
	}

	/**
	 * Checks if product class has a description method.
	 * Otherwise it returns the value of the post_content.
	 *
	 * @param  WC_Product $product The product.
	 * @return string
	 */
	protected function get_long_description( $product ) {
		if ( method_exists( $product, 'get_description' ) ) {
			return $product->get_description();
		}

		return $product->post->post_content;
	}

	/**
	 * Returns the set image ids for the given product.
	 *
	 * @param  WC_Product $product The product to get the image ids for.
	 * @return array
	 */
	protected function get_image_ids( $product ) {
		if ( method_exists( $product, 'get_gallery_image_ids' ) ) {
			return $product->get_gallery_image_ids();
		}

		// Backwards compatibility.
		return $product->get_gallery_attachment_ids();
	}

	/**
	 * Returns the array of brand taxonomy.
	 *
	 * @param  int $product_id The id to get the product brands for.
	 * @return bool|array
	 */
	protected function get_brands( $product_id ) {
		$taxonomy = Helper::get_settings( 'general.product_brand' );
		if ( ! $taxonomy || ! taxonomy_exists( $taxonomy ) ) {
			return false;
		}

		$brands = wp_get_post_terms( $product_id, $taxonomy );
		return empty( $brands ) || is_wp_error( $brands ) ? false : $brands;
	}
}
