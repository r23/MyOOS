<?php
/**
 * The Optimization wizard step
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Wizard
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Wizard;

use RankMath\KB;
use RankMath\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Step class.
 */
class Optimization implements Wizard_Step {

	/**
	 * Render step body.
	 *
	 * @param object $wizard Wizard class instance.
	 *
	 * @return void
	 */
	public function render( $wizard ) {
		?>
		<header>
			<h1><?php esc_html_e( 'SEO Tweaks', 'rank-math' ); ?> </h1>
			<p>
				<?php
					/* translators: Link to How to Optimization KB article */
					printf( esc_html__( 'Automate some of your SEO tasks like making external links nofollow, redirecting attachment pages, etc.. %s', 'rank-math' ), '<a href="' . KB::get( 'seo-tweaks' ) . '" target="_blank">' . esc_html__( 'Learn More', 'rank-math' ) . '</a>' );
				?>
			</p>
		</header>

		<?php $wizard->cmb->show_form(); ?>

		<footer class="form-footer wp-core-ui rank-math-ui">
			<?php $wizard->get_skip_link(); ?>
			<button type="submit" class="button button-primary"><?php esc_html_e( 'Save and Continue', 'rank-math' ); ?></button>
		</footer>
		<?php
	}

	/**
	 * Render form for step.
	 *
	 * @param object $wizard Wizard class instance.
	 *
	 * @return void
	 */
	public function form( $wizard ) {
		$wizard->cmb->add_field([
			'id'      => 'noindex_empty_taxonomies',
			'type'    => 'switch',
			'name'    => esc_html__( 'Noindex Empty Category and Tag Archives', 'rank-math' ),
			'desc'    => wp_kses_post( __( 'Setting empty archives to <code>noindex</code> is useful for avoiding indexation of thin content pages and dilution of page rank. As soon as a post is added, the page is updated to <code>index</code>.', 'rank-math' ) ),
			'default' => Helper::get_settings( 'titles.noindex_empty_taxonomies' ) ? 'on' : 'off',
		]);

		$wizard->cmb->add_field([
			'id'      => 'nofollow_image_links',
			'type'    => 'switch',
			'name'    => esc_html__( 'Nofollow Image File Links', 'rank-math' ),
			'desc'    => wp_kses_post( __( 'Automatically add <code>rel="nofollow"</code> attribute for links pointing to external image files. The attribute is dynamically applied when the content is displayed, and the stored content is not changed.', 'rank-math' ) ),
			'default' => Helper::get_settings( 'general.nofollow_image_links' ) ? 'on' : 'off',
		]);

		$wizard->cmb->add_field([
			'id'      => 'nofollow_external_links',
			'type'    => 'switch',
			'name'    => esc_html__( 'Nofollow External Links', 'rank-math' ),
			'desc'    => wp_kses_post( __( 'Automatically add <code>rel="nofollow"</code> attribute for external links appearing in your posts, pages, and other post types. The attribute is dynamically applied when the content is displayed, and the stored content is not changed.', 'rank-math' ) ),
			'default' => Helper::get_settings( 'general.nofollow_external_links' ) ? 'on' : 'off',
		]);

		$wizard->cmb->add_field([
			'id'      => 'new_window_external_links',
			'type'    => 'switch',
			'name'    => esc_html__( 'Open External Links in New Tab/Window', 'rank-math' ),
			'desc'    => wp_kses_post( __( 'Automatically add a <code>target="_blank"</code> attribute to external links appearing in your posts, pages, and other post types. The attributes are applied when the content is displayed, which does not change the stored content.', 'rank-math' ) ),
			'default' => Helper::get_settings( 'general.new_window_external_links' ) ? 'on' : 'off',
		]);

		$wizard->cmb->add_field([
			'id'      => 'strip_category_base',
			'type'    => 'switch',
			'name'    => esc_html__( 'Strip Category Base', 'rank-math' ),
			/* translators: Link to kb article */
			'desc'    => sprintf( wp_kses_post( __( 'Remove /category/ from category archive URLs. <a href="%s" target="_blank">Why do this?</a><br>E.g. <code>example.com/category/my-category/</code> becomes <code>example.com/my-category</code>', 'rank-math' ) ), KB::get( 'remove-category-base' ) ),
			'default' => Helper::get_settings( 'general.strip_category_base' ) ? 'on' : 'off',
		]);
	}

	/**
	 * Save handler for step.
	 *
	 * @param array  $values Values to save.
	 * @param object $wizard Wizard class instance.
	 *
	 * @return bool
	 */
	public function save( $values, $wizard ) {
		$settings = rank_math()->settings->all_raw();

		$settings['general']['strip_category_base']     = $values['strip_category_base'];
		$settings['titles']['noindex_empty_taxonomies'] = $values['noindex_empty_taxonomies'];

		if ( isset( $values['attachment_redirect_urls'] ) && 'on' === $values['attachment_redirect_urls'] ) {
			$settings['general']['attachment_redirect_urls']    = $values['attachment_redirect_urls'];
			$settings['general']['attachment_redirect_default'] = $values['attachment_redirect_default'];
		}

		$settings['general']['nofollow_image_links']      = $values['nofollow_image_links'];
		$settings['general']['nofollow_external_links']   = $values['nofollow_external_links'];
		$settings['general']['new_window_external_links'] = $values['new_window_external_links'];

		Helper::is_configured( true );
		Helper::update_all_settings( $settings['general'], $settings['titles'], null );
		Helper::schedule_flush_rewrite();

		return true;
	}
}
