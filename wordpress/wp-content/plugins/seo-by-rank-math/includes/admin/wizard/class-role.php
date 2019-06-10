<?php
/**
 * The Role wizard step
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Wizard
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Wizard;

use RankMath\Helper;
use MyThemeShop\Helpers\WordPress;

defined( 'ABSPATH' ) || exit;

/**
 * Step class.
 */
class Role implements Wizard_Step {

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
			<h1><?php esc_html_e( 'Role Manager', 'rank-math' ); ?></h1>
			<p><?php esc_html_e( 'Set capabilities here.', 'rank-math' ); ?></p>
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
		$defaults  = Helper::get_roles_capabilities();
		$cap_count = count( Helper::get_capabilities() );

		foreach ( WordPress::get_roles() as $role => $label ) {
			$default = isset( $defaults[ $role ] ) ? $defaults[ $role ] : [];
			$wizard->cmb->add_field([
				'id'      => esc_attr( $role ),
				'type'    => 'multicheck_inline',
				'name'    => translate_user_role( $label ),
				'options' => Helper::get_capabilities(),
				'default' => $default,
				'classes' => 'cmb-big-labels' . ( count( $default ) === $cap_count ? ' multicheck-checked' : '' ),
			]);
		}
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
		if ( empty( $values ) ) {
			return false;
		}

		Helper::set_capabilities( $values );
		return true;
	}
}
