<?php
/**
 * The option center of the plugin.
 *
 * @since      1.0.9
 * @package    RankMath
 * @subpackage RankMath\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Admin;

use RankMath\KB;
use RankMath\CMB2;
use RankMath\Helper;
use RankMath\Runner;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Arr;
use MyThemeShop\Helpers\WordPress;

defined( 'ABSPATH' ) || exit;

/**
 * Option_Center class.
 */
class Option_Center implements Runner {

	use Hooker;

	/**
	 * Register hooks.
	 */
	public function hooks() {
		$this->action( 'init', 'register_general_settings', 125 );
		$this->action( 'init', 'register_title_settings', 125 );
		$this->filter( 'rank_math/settings/title', 'title_post_type_settings', 1 );
		$this->filter( 'rank_math/settings/title', 'title_taxonomy_settings', 1 );
		$this->filter( 'rank_math/settings/general', 'remove_unwanted_general_tabs', 1 );

		// Check for fields and act accordingly.
		$this->action( 'cmb2_save_options-page_fields_rank-math-options-general_options', 'check_updated_fields', 25, 2 );
		$this->action( 'cmb2_save_options-page_fields_rank-math-options-titles_options', 'check_updated_fields', 25, 2 );
	}

	/**
	 * General Settings.
	 */
	public function register_general_settings() {
		$tabs = [
			'links'       => [
				'icon'  => 'fa fa-link',
				'title' => esc_html__( 'Links', 'rank-math' ),
				/* translators: Link to kb article */
				'desc'  => sprintf( esc_html__( 'This tab contains the options related to links and URLs. %s.', 'rank-math' ), '<a href="' . KB::get( 'link-settings' ) . '" target="_blank">' . esc_html__( 'Learn More', 'rank-math' ) . '</a>' ),
			],
			'images'      => [
				'icon'  => 'dashicons dashicons-images-alt2',
				'title' => esc_html__( 'Images', 'rank-math' ),
				/* translators: Link to kb article */
				'desc'  => sprintf( esc_html__( 'SEO options related to featured images and media appearing in your post content. %s.', 'rank-math' ), '<a href="' . KB::get( 'image-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
			],
			'breadcrumbs' => [
				'icon'  => 'fa fa-angle-double-right',
				'title' => esc_html__( 'Breadcrumbs', 'rank-math' ),
				/* translators: Link to kb article */
				'desc'  => sprintf( esc_html__( 'Here you can set up the breadcrumbs function. %s Use the following code in your theme template files to display breadcrumbs:', 'rank-math' ), '<a href="' . KB::get( 'breadcrumbs' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>. <br/>' ) . '<br /><code>&lt;?php if (function_exists(\'rank_math_the_breadcrumbs\')) rank_math_the_breadcrumbs(); ?&gt;</code><br /> OR <br /><code>[rank_math_breadcrumb]</code>',
			],
			'webmaster'   => [
				'icon'  => 'fa fa-external-link',
				'title' => esc_html__( 'Webmaster Tools', 'rank-math' ),
				/* translators: Link to kb article */
				'desc'  => sprintf( esc_html__( 'Here you can enter verification codes for various third-party webmaster tools. %s You can safely paste the full HTML tags, or just the ID codes.', 'rank-math' ), '<a href="' . KB::get( 'webmaster-tools' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>.<br />' ),
			],
			'robots'      => [
				'icon'  => 'fa fa-simplybuilt',
				'title' => esc_html__( 'Edit robots.txt', 'rank-math' ),
				/* translators: Link to kb article */
				'desc'  => sprintf( esc_html__( 'Here you can edit the virtual robots.txt file. Leave the field empty to let WordPress handle the contents dynamically. If an actual robots.txt file is present in the root folder of your site, this option won\'t take effect and you have to edit the file directly, or delete it and then edit from here. %s.', 'rank-math' ), '<a href="' . KB::get( 'edit-robotstxt' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
			],
			'htaccess'    => [
				'icon'  => 'fa fa-file',
				'title' => esc_html__( 'Edit .htaccess', 'rank-math' ),
				/* translators: Link to kb article */
				'desc'  => sprintf( esc_html__( 'Here you can edit the contents of your .htaccess file. This file is mainly used by WordPress to control permalinks and redirections, but it may be useful to edit it in a number of cases. %s.', 'rank-math' ), '<a href="' . KB::get( 'edit-htaccess' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
			],
			'others'      => [
				'icon'  => 'fa fa-dot-circle-o',
				'title' => esc_html__( 'Others', 'rank-math' ),
				/* translators: Link to kb article */
				'desc'  => sprintf( esc_html__( 'Control how your plugin communicates with Rank Math and change how your RSS content looks. %s.', 'rank-math' ), '<a href="' . KB::get( 'other-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
			],
		];

		/**
		 * Allow developers to add new section into general setting option panel.
		 *
		 * @param array $tabs
		 */
		$tabs = $this->do_filter( 'settings/general', $tabs );

		new Options([
			'key'        => 'rank-math-options-general',
			'title'      => esc_html__( 'SEO Settings', 'rank-math' ),
			'menu_title' => esc_html__( 'General Settings', 'rank-math' ),
			'capability' => 'rank_math_general',
			'folder'     => 'general',
			'tabs'       => $tabs,
		]);
	}

	/**
	 * Remove general tabs.
	 *
	 * @param  array $tabs Hold tabs for optional panel.
	 * @return array
	 */
	public function remove_unwanted_general_tabs( $tabs ) {
		if ( is_multisite() ) {
			unset( $tabs['robots'] );
		}

		if ( ! Helper::has_cap( 'edit_htaccess' ) && is_multisite() ) {
			unset( $tabs['htaccess'] );
		}

		return $tabs;
	}

	/**
	 * Register SEO Titles & Meta Settings.
	 */
	public function register_title_settings() {
		$tabs = [
			'global'   => [
				'icon'  => 'fa fa-cogs',
				'title' => esc_html__( 'Global Meta', 'rank-math' ),
				/* translators: Link to KB article */
				'desc'  => sprintf( esc_html__( 'This tab contains SEO titles and meta options related to all the pages of your site. %s.', 'rank-math' ), '<a href="' . KB::get( 'titles-meta' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
			],
			'local'    => [
				'icon'  => 'fa fa-map-marker',
				'title' => esc_html__( 'Local SEO', 'rank-math' ),
				/* translators: Redirection page url */
				'desc'  => sprintf( wp_kses_post( __( 'This tab contains settings related to contact information & opening hours of your local business. Use the <code>[rank_math_contact_info]</code> shortcode to display contact information in a nicely formatted way. %s. You should also claim your business on Google if you have not already.', 'rank-math' ) ), '<a href="' . KB::get( 'local-seo-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
			],
			'social'   => [
				'icon'  => 'fa fa-retweet',
				'title' => esc_html__( 'Social Meta', 'rank-math' ),
				/* translators: Link to social setting KB article */
				'desc'  => sprintf( esc_html__( 'This tab contains settings related to social networks and feeds. Social page URLs will be displayed in the contact shortcode and added to the pages as metadata to be displayed in Knowledge Graph cards. Unable to find the details? %s for the tutorial.', 'rank-math' ), '<a href="' . KB::get( 'social-meta-settings' ) . '" target="_blank">' . esc_html__( 'Click here', 'rank-math' ) . '</a>' ),
			],
			'homepage' => [
				'icon'  => 'fa fa-home',
				'title' => esc_html__( 'Homepage', 'rank-math' ),
				'desc'  => 'page' == get_option( 'show_on_front' ) ?
					/* translators: something */
					sprintf( wp_kses_post( __( 'A static page is used as front page (as set in Settings &gt; Reading). To set up the title, description, and meta of the homepage, use the meta box in the page editor.<br><br><a href="%1$s">Edit Page: %2$s</a>', 'rank-math' ) ), admin_url( 'post.php?post=' . get_option( 'page_on_front' ) ) . '&action=edit', get_the_title( get_option( 'page_on_front' ) ) ) :
					/* translators: Link to KB article */
					sprintf( esc_html__( 'This tab contains SEO options for your website\'s homepage. Change options like homepage title and homepage meta description here. %s.', 'rank-math' ), '<a href="' . KB::get( 'homepage-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
			],
			'author'   => [
				'icon'  => 'fa fa-users',
				'title' => esc_html__( 'Authors', 'rank-math' ),
				/* translators: Link to KB article */
				'desc'  => sprintf( esc_html__( 'Change SEO options related to the author archive pages. Author archives list the posts from a particular author in chronological order. %s.', 'rank-math' ), '<a href="' . KB::get( 'author-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
			],
			'misc'     => [
				'icon'  => 'fa fa-map-signs',
				'title' => esc_html__( 'Misc Pages', 'rank-math' ),
				/* translators: Link to KB article */
				'desc'  => sprintf( esc_html__( 'This tab contains meta data settings related to pages like search results, 404 error pages etc. %s.', 'rank-math' ), '<a href="' . KB::get( 'misc-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
			],
		];

		/**
		 * Allow developers to add new section into title setting option panel.
		 *
		 * @param array $tabs
		 */
		$tabs = $this->do_filter( 'settings/title', $tabs );

		new Options([
			'key'        => 'rank-math-options-titles',
			'title'      => esc_html__( 'SEO Titles &amp; Meta', 'rank-math' ),
			'menu_title' => esc_html__( 'Titles &amp; Meta', 'rank-math' ),
			'capability' => 'rank_math_titles',
			'folder'     => 'titles',
			'tabs'       => $tabs,
		]);

		if ( is_admin() ) {
			Helper::add_json( 'postTitle', 'Post Title' );
			Helper::add_json( 'postUri', home_url( '/post-title' ) );
			Helper::add_json( 'blogName', get_bloginfo( 'name' ) );
		}
	}

	/**
	 * Add post type tabs into title option panel
	 *
	 * @param  array $tabs Hold tabs for optional panel.
	 * @return array
	 */
	public function title_post_type_settings( $tabs ) {
		$icons = Helper::choices_post_type_icons();
		$links = [
			'post'       => '<a href="' . KB::get( 'post-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>.',
			'page'       => '<a href="' . KB::get( 'page-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>.',
			'product'    => '<a href="' . KB::get( 'product-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>.',
			'attachment' => '<a href="' . KB::get( 'media-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>.',
		];

		$names = [
			'post'       => 'single %s',
			'page'       => 'single %s',
			'product'    => 'product pages',
			'attachment' => 'media %s',
		];

		$tabs['p_types'] = [
			'title' => esc_html__( 'Post Types:', 'rank-math' ),
			'type'  => 'seprator',
		];

		foreach ( Helper::get_accessible_post_types() as $post_type ) {
			$obj      = get_post_type_object( $post_type );
			$link     = isset( $links[ $obj->name ] ) ? $links[ $obj->name ] : '';
			$obj_name = isset( $names[ $obj->name ] ) ? sprintf( $names[ $obj->name ], $obj->name ) : $obj->name;

			$tabs[ 'post-type-' . $obj->name ] = [
				'title'     => $obj->label,
				'icon'      => isset( $icons[ $obj->name ] ) ? $icons[ $obj->name ] : $icons['default'],
				/* translators: 1. post type name 2. link */
				'desc'      => sprintf( esc_html__( 'This tab contains SEO options for %1$s. %2$s', 'rank-math' ), $obj_name, $link ),
				'post_type' => $obj->name,
				'file'      => rank_math()->includes_dir() . 'settings/titles/post-types.php',
			];
		}

		return $tabs;
	}

	/**
	 * Add taxonomy tabs into title option panel
	 *
	 * @param  array $tabs Hold tabs for optional panel.
	 * @return array
	 */
	public function title_taxonomy_settings( $tabs ) {
		$icons = Helper::choices_taxonomy_icons();

		$hash_name = [
			'category'    => 'category archive pages',
			'product_cat' => 'Product category pages',
			'product_tag' => 'Product tag pages',
		];

		$hash_link = [
			'category'    => '<a href="' . KB::get( 'category-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>.',
			'post_tag'    => '<a href="' . KB::get( 'tag-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>.',
			'product_cat' => '<a href="' . KB::get( 'product-categories-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>.',
			'product_tag' => '<a href="' . KB::get( 'product-tags-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>.',
		];

		foreach ( Helper::get_accessible_taxonomies() as $taxonomy ) {
			$attached = implode( ' + ', $taxonomy->object_type );

			// Seprator.
			$tabs[ $attached ] = [
				'title' => ucwords( $attached ) . ':',
				'type'  => 'seprator',
			];

			$link          = isset( $hash_link[ $taxonomy->name ] ) ? $hash_link[ $taxonomy->name ] : '';
			$taxonomy_name = isset( $hash_name[ $taxonomy->name ] ) ? $hash_name[ $taxonomy->name ] : $taxonomy->label;

			$tabs[ 'taxonomy-' . $taxonomy->name ] = [
				'icon'     => isset( $icons[ $taxonomy->name ] ) ? $icons[ $taxonomy->name ] : $icons['default'],
				'title'    => $taxonomy->label,
				/* translators: 1. taxonomy name 2. link */
				'desc'     => sprintf( esc_html__( 'This tab contains SEO options for %1$s. %2$s', 'rank-math' ), $taxonomy_name, $link ),
				'taxonomy' => $taxonomy->name,
				'file'     => rank_math()->includes_dir() . 'settings/titles/taxonomies.php',
			];
		}

		if ( isset( $tabs['taxonomy-post_format'] ) ) {
			$tab = $tabs['taxonomy-post_format'];
			unset( $tabs['taxonomy-post_format'] );
			$tab['title']      = esc_html__( 'Post Formats', 'rank-math' );
			$tab['page_title'] = esc_html__( 'Post Formats Archive', 'rank-math' );
			Arr::insert( $tabs, [ 'taxonomy-post_format' => $tab ], 5 );
		}

		return $tabs;
	}

	/**
	 * Check if certain fields got updated.
	 *
	 * @param int   $object_id The ID of the current object.
	 * @param array $updated   Array of field ids that were updated.
	 *                         Will only include field ids that had values change.
	 */
	public function check_updated_fields( $object_id, $updated ) {

		/**
		 * Allow developers to add option fields to check against updatation.
		 * And if updated flush the rewrite rules.
		 *
		 * @param array $flush_fields Array of fields id for which we need to flush.
		 */
		$flush_fields = $this->do_filter(
			'flush_fields',
			[
				'strip_category_base',
				'disable_author_archives',
				'url_author_base',
				'attachment_redirect_urls',
				'attachment_redirect_default',
				'url_strip_stopwords',
				'nofollow_external_links',
				'nofollow_image_links',
				'nofollow_domains',
				'nofollow_exclude_domains',
				'new_window_external_links',
				'redirections_header_code',
				'redirections_post_redirect',
				'redirections_debug',
			]
		);

		foreach ( $flush_fields as $field_id ) {
			if ( in_array( $field_id, $updated, true ) ) {
				Helper::schedule_flush_rewrite();
				break;
			}
		}

		$this->update_htaccess();
	}

	/**
	 * Update .htaccess.
	 */
	private function update_htaccess() {
		if ( ! isset( $_POST['htaccess_accept_changes'] ) || ! isset( $_POST['htaccess_content'] ) ) {
			return;
		}

		$content = stripslashes( $_POST['htaccess_content'] );

		if ( ! $this->do_htaccess_backup() ) {
			Helper::add_notification(
				esc_html__( 'Failed to backup .htaccess file. Please check file permissions.', 'rank-math' ),
				[ 'type' => 'error' ]
			);
			return;
		}
		if ( ! $this->do_htaccess_update( $content ) ) {
			Helper::add_notification(
				esc_html__( 'Failed to update .htaccess file. Please check file permissions.', 'rank-math' ),
				[ 'type' => 'error' ]
			);
			return;
		}

		Helper::add_notification( esc_html__( '.htaccess file updated successfully.', 'rank-math' ) );
	}

	/**
	 * Create htaccess backup.
	 *
	 * @return bool
	 */
	private function do_htaccess_backup() {
		$wp_filesystem = WordPress::get_filesystem();

		$path = get_home_path();
		$file = $path . '.htaccess';
		if ( ! $wp_filesystem->is_writable( $path ) || ! $wp_filesystem->exists( $file ) ) {
			return false;
		}

		$backup = $path . uniqid( '.htaccess_back_' );
		return $wp_filesystem->copy( $file, $backup, true );
	}

	/**
	 * Update htaccess file.
	 *
	 * @param string $content Htaccess content.
	 * @return string|bool
	 */
	private function do_htaccess_update( $content ) {
		if ( empty( $content ) ) {
			return false;
		}

		$wp_filesystem = WordPress::get_filesystem();
		$htaccess_file = get_home_path() . '.htaccess';

		return ! $wp_filesystem->is_writable( $htaccess_file ) ? false : $wp_filesystem->put_contents( $htaccess_file, $content );
	}
}
