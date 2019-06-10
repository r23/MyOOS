<?php
/**
 * Redirection main view.
 *
 * @package    RankMath
 * @subpackage RankMath\Redirections
 */

use RankMath\KB;
use RankMath\Helper;

$is_new       = isset( $_GET['new'] );
$redirections = Helper::get_module( 'redirections' )->admin;
$is_editing   = ! empty( $_GET['url'] ) || ! empty( $_REQUEST['log'] ) || ! empty( $_REQUEST['redirect_uri'] ) || $redirections->form->is_editing();
?>
<div class="wrap rank-math-redirections-wrap">

	<h1 class="wp-heading-inline">
		<?php echo esc_html( get_admin_page_title() ); ?>
		<a class="rank-math-add-new-redirection<?php echo $is_editing ? '-refresh' : ''; ?> page-title-action" href="<?php echo Helper::get_admin_url( 'redirections', 'new=1' ); ?>"><?php esc_html_e( 'Add New', 'rank-math' ); ?></a>
		<a class="page-title-action" href="<?php echo Helper::get_admin_url( 'redirections', 'export=apache' ); ?>"><?php esc_html_e( 'Export to .htaccess', 'rank-math' ); ?></a>
		<a class="page-title-action" href="<?php echo Helper::get_admin_url( 'redirections', 'export=nginx' ); ?>"><?php esc_html_e( 'Export to Nginx config file', 'rank-math' ); ?></a>
		<a class="page-title-action" href="<?php KB::the( 'redirections' ); ?>" target="_blank"><?php esc_html_e( 'Learn More', 'rank-math' ); ?></a>
		<a class="page-title-action" href="<?php echo Helper::get_admin_url( 'options-general#setting-panel-redirections' ); ?>"><?php esc_html_e( 'Settings', 'rank-math' ); ?></a>
	</h1>

	<div class="clear"></div>

	<div class="rank-math-redirections-form<?php echo $is_editing || $is_new ? ' is-editing' : ''; ?>">

		<?php $redirections->form->display(); ?>

	</div>

	<form method="post">
	<?php
		$redirections->table->prepare_items();
		$redirections->table->views();
		$redirections->table->search_box( esc_html__( 'Search', 'rank-math' ), 's' );
		$redirections->table->display();
	?>
	</form>

</div>
