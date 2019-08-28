<?php
/**
 * The Setup Wizard - configure the SEO settings in just a few steps.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Admin;

use RankMath\KB;
use RankMath\CMB2;
use RankMath\Helper;
use RankMath\Traits\Hooker;
use RankMath\Admin\Admin_Helper;
use MyThemeShop\Helpers\Param;

defined( 'ABSPATH' ) || exit;

/**
 * Registration class.
 */
class Registration {

	use Hooker;

	/**
	 * Page slug.
	 *
	 * @var string
	 */
	private $slug = 'rank-math-registration';

	/**
	 * The text string array.
	 *
	 * @var array
	 */
	protected $strings = null;

	/**
	 * Is registration invalid.
	 *
	 * @var bool
	 */
	public $invalid = false;

	/**
	 * CMB2 object
	 *
	 * @var \CMB2
	 */
	public $cmb = null;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		// Strings passed in from the config file.
		$this->strings = [
			'title'               => esc_html__( 'Rank Math Product Registration', 'rank-math' ),
			'return-to-dashboard' => esc_html__( 'Return to dashboard', 'rank-math' ),
		];

		$this->step      = 'register';
		$this->step_slug = 'register';
		$this->invalid   = Helper::is_invalid_registration();

		if ( $this->invalid ) {
			$this->action( 'admin_menu', 'admin_menu' );
			$this->action( 'admin_init', 'redirect_to_welcome' );
			$this->action( 'cmb2_admin_init', 'registration_form' );
			$this->action( 'admin_post_rank_math_save_registration', 'save_registration' );
			$this->action( 'admin_post_rank_math_skip_wizard', 'skip_wizard' );
			$this->action( 'admin_init', 'render_page', 30 );
		}
	}

	/**
	 * Redirect to welcome page.
	 *
	 * Redirect the user to the welcome page after plugin activation.
	 */
	public function redirect_to_welcome() {
		if ( ! $this->can_redirect() ) {
			return;
		}

		$url = '';
		if ( $this->invalid ) {
			$url = 'registration';
		} elseif ( ! get_option( 'rank_math_wizard_completed' ) ) {
			$url = 'wizard';
		}

		wp_redirect( Helper::get_admin_url( $url ) );
		exit;
	}

	/**
	 * Add menu items.
	 */
	public function admin_menu() {
		add_menu_page(
			esc_html__( 'Rank Math', 'rank-math' ),
			esc_html__( 'Rank Math', 'rank-math' ),
			'manage_options',
			$this->slug,
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Register registration form.
	 */
	public function registration_form() {
		$this->cmb = new_cmb2_box([
			'id'           => 'rank-math-wizard',
			'object_types' => [ 'options-page' ],
			'option_key'   => 'rank-math-wizard',
			'hookup'       => false,
			'save_fields'  => false,
		]);

		$this->cmb->add_field([
			'id'         => 'username',
			'type'       => 'text',
			'name'       => esc_html__( 'Username/Email', 'rank-math' ),
			'classes'    => 'nob nopb rank-math-validate-field',
			'attributes' => [
				'data-rule-required' => 'true',
				'required'           => '',
				'autocomplete'       => 'off',
			],
			'after'      => '<label id="username-error" class="invalid" for="username" style="display:none;">' . esc_html__( 'This field is required.', 'rank-math' ) . '</label>',
		]);

		$this->cmb->add_field([
			'id'         => 'validation_code',
			'type'       => 'text',
			'name'       => esc_html__( 'Password', 'rank-math' ),
			'classes'    => 'nob nopb rank-math-validate-field',
			'attributes' => [
				'data-rule-required' => 'true',
				'autocomplete'       => 'off',
				'required'           => '',
				'type'               => 'password',
			],
			'after'      => '<label id="validation_code-error" class="invalid" for="validation_code" style="display:none;">' . esc_html__( 'This field is required.', 'rank-math' ) . '</label>',
		]);

		$this->cmb->add_field([
			'id'      => 'rank-math-usage-tracking',
			'type'    => 'checkbox',
			/* translators: Link to Rank Math privay policy */
			'name'    => sprintf( __( 'Gathering usage data helps us make Rank Math SEO plugin better - for you. By understanding how you use Rank Math, we can introduce new features and find out if existing features are working well for you. If you don’t want us to collect data from your website, uncheck the tickbox. Please note that licensing information may still be sent back to us for authentication. We collect data anonymously, read more %s.', 'rank-math' ), '<a href="' . KB::get( 'rm-privacy' ) . '" target="_blank">here</a>' ),
			'classes' => 'nob nopb',
			'default' => Helper::get_settings( 'general.usage_tracking' ) ? 'on' : '',
		]);

		CMB2::pre_init( $this->cmb );
	}

	/**
	 * Output the admin page.
	 */
	public function render_page() {

		// Early bail if we're not on the right page.
		if ( Param::get( 'page' ) !== $this->slug ) {
			return;
		}

		if ( ob_get_length() ) {
			ob_end_clean();
		}

		$assets = new Assets;
		$assets->register();

		wp_styles()->done  = [];
		wp_scripts()->done = [];

		// Enqueue styles.
		\CMB2_hookup::enqueue_cmb_css();
		\CMB2_hookup::enqueue_cmb_js();

		// Wizard.
		wp_enqueue_style( 'rank-math-wizard', rank_math()->plugin_url() . 'assets/admin/css/setup-wizard.css', [ 'wp-admin', 'buttons', 'cmb2-styles', 'rank-math-common', 'rank-math-cmb2' ], rank_math()->version );
		wp_enqueue_script( 'rank-math-wizard', rank_math()->plugin_url() . 'assets/admin/js/wizard.js', [ 'jquery', 'rank-math-common', 'rank-math-validate' ], rank_math()->version, true );
		wp_localize_script( 'rank-math-wizard', 'wp', [] );

		$logo_url = '<a href="' . KB::get( 'logo' ) . '" target="_blank"><img src="' . esc_url( rank_math()->plugin_url() . 'assets/admin/img/logo.svg' ) . '"></a>';

		ob_start();

		/**
		 * Start the actual page content.
		 */
		include_once $this->get_view( 'header' );
		include_once $this->get_view( 'content' );
		include_once $this->get_view( 'footer' );
		exit;
	}

	/**
	 * Render page body.
	 */
	protected function body() {
		?>
		<header>
			<?php $this->header_content(); ?>
		</header>

		<span class="wp-header-end"></span>

		<?php rank_math()->notification->display(); ?>

		<?php $this->cmb->show_form(); ?>

		<footer class="form-footer wp-core-ui rank-math-ui">
			<button type="submit" class="button button-<?php echo $this->invalid ? 'primary alignright' : 'secondary'; ?>"><?php echo $this->invalid ? esc_html__( 'Activate Rank Math', 'rank-math' ) : esc_html__( 'Deactivate License', 'rank-math' ); ?></button>
			<button type="submit" class="button button-<?php echo $this->invalid ? 'secondary' : 'primary alignright'; ?>" formnovalidate id="skip-registration" style="margin-right:15px"><?php echo $this->invalid ? esc_html__( 'Skip Now', 'rank-math' ) : esc_html__( 'Next', 'rank-math' ); ?></button>
		</footer>

		<?php
		$this->print_script();
	}

	/**
	 * Header content.
	 */
	private function header_content() {
		if ( $this->invalid ) :
			?>
			<h1><?php esc_html_e( 'Connect FREE Account', 'rank-math' ); ?></h1>
			<div class="notice notice-warning rank-math-registration-notice inline">
				<p>
					<?php
					/* translators: Link to Rank Math signup page */
					printf( wp_kses_post( __( 'You need to connect with your <a href="%s" target="_blank"><strong>FREE Rank Math account</strong></a> to use Rank Math on this site.', 'rank-math' ) ), KB::get( 'free-account' ) );
					?>
				</p>
			</div>
			<?php
			return;
		endif;
		?>

		<h1><?php esc_html_e( 'Account Successfully Connected', 'rank-math' ); ?></h1>
		<h3 style="text-align: center; padding-top:15px;"><?php esc_html_e( 'You have successfully activated Rank Math.', 'rank-math' ); ?></h3>
		<?php
	}

	/**
	 * Execute save handler for current step.
	 */
	public function save_registration() {

		// If no form submission, bail.
		$referer = Param::post( '_wp_http_referer' );
		if ( Param::post( 'step' ) !== 'register' ) {
			return wp_safe_redirect( $referer );
		}

		check_admin_referer( 'rank-math-wizard', 'security' );

		Admin_Helper::allow_tracking();

		$show_content = $this->register_handler( $this->cmb->get_sanitized_values( $_POST ) );
		$redirect     = true === $show_content ? Helper::get_admin_url( 'wizard' ) : $referer;
		wp_safe_redirect( $redirect );
		exit;
	}

	/**
	 * Skip wizard handler.
	 */
	public function skip_wizard() {
		check_admin_referer( 'rank-math-wizard', 'security' );
		add_option( 'rank_math_registration_skip', true );
		Admin_Helper::allow_tracking();
		wp_safe_redirect( Helper::get_admin_url( 'wizard' ) );
		exit;
	}

	/**
	 * Authenticate registration.
	 *
	 * @param array $values Array of values for the step to process.
	 */
	private function register_handler( $values ) {

		if ( ! isset( $values['username'] ) ) {
			delete_option( 'rank_math_connect_data' );
			return;
		}

		$values = wp_parse_args(
			$values,
			[
				'username'        => '',
				'validation_code' => '',
			]
		);

		return Admin_Helper::register_product( $values['username'], $values['validation_code'] );
	}

	/**
	 * Can redirect to setup/registration page after install.
	 *
	 * @return bool
	 */
	private function can_redirect() {
		if ( ! get_transient( '_rank_math_activation_redirect' ) ) {
			return false;
		}

		delete_transient( '_rank_math_activation_redirect' );

		if ( ( ! empty( $_GET['page'] ) && in_array( $_GET['page'], [ 'rank-math-registration', 'rank-math-wizard' ], true ) ) || ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get view file to display.
	 *
	 * @param string $view View to display.
	 * @return string
	 */
	private function get_view( $view ) {
		if ( 'navigation' === $view ) {
			$view = 'no-navigation';
		}

		return rank_math()->admin_dir() . "wizard/views/{$view}.php";
	}

	/**
	 * Print Javascript.
	 */
	private function print_script() {
		?>
		<script>
		(function($){
			$(function() {
				$( '#skip-registration' ).on( 'click', function( event ) {
					$('[name="action"]').val( 'rank_math_skip_wizard' );
					$( this ).closest( '.cmb-form' );
				});

				$( '.cmb-form' ).on( 'keyup keypress', function( event ) {
					var isValid = event.currentTarget.checkValidity();

					var keyCode = event.keyCode || event.which;
					if ( ! isValid && 13 === keyCode ) {
						event.preventDefault();
						return false;
					}
				});

				// Required Field
				$( '.required, [required]' ).on( 'input invalid', function( event ) {
					event.preventDefault();

					var input = $( this );
					if ( ! event.target.validity.valid ) {
						input.addClass( 'invalid animated shake' );
					} else {
						input.removeClass( 'invalid animated shake' );
					}
				});

			});
		})(jQuery);
		</script>
		<?php
	}
}
