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

use RankMath\Helper;
use MyThemeShop\Helpers\Param;

$default_tab = apply_filters( 'rank_math/tools/default_tab', 'tools' );
$module      = Helper::get_module( 'status' );
$current     = Param::get( 'view', $default_tab );
?>
<div class='wrap rank-math-wrap rank-math-status-wrap'>

	<span class='wp-header-end'></span>

	<h1 class="page-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php $module->display_nav(); ?>

	<?php $module->display_body( $current ); ?>

</div>
