<?php
/**
 * The Redirections Form
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Redirections
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Redirections;

use RankMath\Helper;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Param;
use RankMath\Monitor\DB as Monitor_DB;

/**
 * Form class.
 *
 * @codeCoverageIgnore
 */
class Form {

	use Hooker;

	/**
	 * The hooks.
	 *
	 * @codeCoverageIgnore
	 */
	public function hooks() {
		$this->action( 'cmb2_admin_init', 'register_form' );
		$this->filter( 'cmb2_override_option_get_rank-math-redirections', 'set_options' );
		$this->action( 'admin_post_rank_math_save_redirections', 'save' );
	}

	/**
	 * Display form.
	 *
	 * @codeCoverageIgnore
	 */
	public function display() {
		?>
		<h2><strong><?php echo ( $this->is_editing() ? esc_html__( 'Update', 'rank-math' ) : esc_html__( 'Add', 'rank-math' ) ) . ' ' . esc_html( get_admin_page_title() ); ?></strong></h2>

		<form class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="action" value="rank_math_save_redirections">
			<?php
				wp_nonce_field( 'rank-math-save-redirections', 'security' );
				$cmb = cmb2_get_metabox( 'rank-math-redirections', 'rank-math-redirections' );
				$cmb->show_form();
			?>
			<footer class="form-footer rank-math-ui">
				<button type="button" class="button button-link-delete button-xlarge alignleft"><?php esc_html_e( 'Cancel', 'rank-math' ); ?></button>
				<button type="submit" class="button button-primary button-xlarge"><?php echo $this->is_editing() ? esc_html__( 'Update Redirection', 'rank-math' ) : esc_html__( 'Add Redirection', 'rank-math' ); ?></button>
			</footer>
		</form>

		<?php
	}

	/**
	 * Register form for Add New Record.
	 */
	public function register_form() {

		$cmb = new_cmb2_box([
			'id'           => 'rank-math-redirections',
			'object_types' => [ 'options-page' ],
			'option_key'   => 'rank-math-redirections',
			'hookup'       => false,
			'save_fields'  => false,
		]);

		$cmb->add_field([
			'id'      => 'sources',
			'type'    => 'group',
			'name'    => esc_html__( 'Source URLs', 'rank-math' ),
			'options' => [
				'add_button'    => esc_html__( 'Add another', 'rank-math' ),
				'remove_button' => esc_html__( 'Remove', 'rank-math' ),
			],
			'classes' => 'cmb-group-text-only',
			'fields'  => [
				[
					'id'              => 'pattern',
					'type'            => 'text',
					'escape_cb'       => [ $this, 'stripslashes' ],
					'sanitization_cb' => false,
				],
				[
					'id'      => 'comparison',
					'type'    => 'select',
					'options' => Helper::choices_comparison_types(),
				],
			],
		]);

		$cmb->add_field([
			'id'   => 'url_to',
			'type' => 'text',
			'name' => esc_html__( 'Destination URL', 'rank-math' ),
		]);

		$cmb->add_field([
			'id'      => 'header_code',
			'type'    => 'radio_inline',
			'name'    => esc_html__( 'Redirection Type', 'rank-math' ),
			'options' => Helper::choices_redirection_types(),
			'default' => Helper::get_settings( 'general.redirections_header_code' ),
		]);

		$cmb->add_field([
			'id'      => 'status',
			'type'    => 'radio_inline',
			'name'    => esc_html__( 'Status', 'rank-math' ),
			'options' => [
				'active'   => esc_html__( 'Activate', 'rank-math' ),
				'inactive' => esc_html__( 'Deactivate', 'rank-math' ),
			],
			'default' => 'active',
		]);

		$cmb->add_field([
			'id'   => 'id',
			'type' => 'hidden',
		]);
	}

	/**
	 * Set option handler for form.
	 *
	 * @param array $opts Array of options.
	 */
	public function set_options( $opts ) {
		// If editing previous record.
		if ( $redirection_id = $this->is_editing() ) { // phpcs:ignore
			return DB::get_redirection_by_id( $redirection_id );
		}

		if ( $url = Param::get( 'url' ) ) { // phpcs:ignore
			return [ 'sources' => [ [ 'pattern' => $url ] ] ];
		}

		if ( ! empty( $_REQUEST['log'] ) && is_array( $_REQUEST['log'] ) ) {
			return [
				'sources' => $this->get_sources_for_log(),
				'url_to'  => esc_url( home_url( '/' ) ),
			];
		}

		return $opts;
	}

	/**
	 * Get sources for 404 log items
	 *
	 * @return array
	 */
	private function get_sources_for_log() {
		$logs = array_map( 'absint', $_REQUEST['log'] );
		$logs = Monitor_DB::get_logs([
			'ids'     => $logs,
			'orderby' => '',
		]);

		$sources = [];
		foreach ( $logs['logs'] as $log ) {
			if ( empty( $log['uri'] ) ) {
				continue;
			}
			$sources[] = [ 'pattern' => $log['uri'] ];
		}

		return $sources;
	}

	/**
	 * Save new record form submit handler.
	 */
	public function save() {
		// If no form submission, bail!
		if ( empty( $_POST ) ) {
			return false;
		}

		check_admin_referer( 'rank-math-save-redirections', 'security' );

		$cmb    = cmb2_get_metabox( 'rank-math-redirections' );
		$values = $cmb->get_sanitized_values( $_POST );

		$redirection = Redirection::from( $values );
		if ( false === $redirection->save() ) {
			Helper::add_notification( __( 'Please add at least one valid source URL.', 'rank-math' ), [ 'type' => 'error' ] );
			wp_safe_redirect( wp_unslash( $_POST['_wp_http_referer'] ) );
			exit;
		}

		wp_safe_redirect( Helper::get_admin_url( 'redirections' ) );
		exit;
	}

	/**
	 * Is editing a record.
	 *
	 * @return int|boolean
	 */
	public function is_editing() {

		if ( isset( $_GET['action'] ) && 'edit' !== $_GET['action'] ) {
			return false;
		}

		return isset( $_GET['redirection'] ) ? absint( $_GET['redirection'] ) : false;
	}

	/**
	 * Stripslashes
	 *
	 * @param  mixed      $value      The unescaped value from the database.
	 * @param  array      $field_args Array of field arguments.
	 * @param  CMB2_Field $field      The field object.
	 *
	 * @return mixed                  Escaped value to be displayed.
	 */
	public function stripslashes( $value, $field_args, $field ) {
		return \stripslashes( $value );
	}
}
