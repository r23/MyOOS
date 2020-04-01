<?php
/**
 * SEO Analysis admin page contents.
 *
 * @package   MTS_SEO
 * @author    Rank Math <support@rankmath.com>
 * @license   GPL-2.0+
 * @link      https://rankmath.com/wordpress/plugin/seo-suite/
 * @copyright 2019 Rank Math
 */

use RankMath\KB;
use RankMath\Helper;

$assets   = plugin_dir_url( dirname( __FILE__ ) );
$analyzer = Helper::get_module( 'seo-analysis' )->admin->analyzer;
?>
<div class="wrap rank-math-seo-analysis-wrap">

	<span class="wp-header-end"></span>

	<h2>
		<?php echo esc_html( get_admin_page_title() ); ?>
		<a class="page-title-action" href="<?php KB::the( 'seo-analysis' ); ?>" target="_blank"><?php esc_html_e( 'What is this?', 'rank-math' ); ?></a>
	</h2>

	<?php if ( Helper::is_site_connected() ) : ?>
		<?php include dirname( __FILE__ ) . '/form.php'; ?>
	<?php // phpcs:disable ?>

	<?php if ( ! $analyzer->analyse_subpage ) : ?>
	<div class="rank-math-results-wrapper">
		<?php $analyzer->display(); ?>
	</div>
	<?php endif; ?>

<?php else : ?>
	<div class="rank-math-seo-analysis-header rank-math-ui">
		<h3><?php printf( __( 'Analyze your site by <a href="%1$s" target="_blank">linking your Rank Math account', 'rank-math' ), Helper::get_connect_url() ); ?></a>.</h3>
	</div>
	<?php // phpcs:enable ?>
<?php endif; ?>
</div>
