<?php

namespace wp_gdpr\lib;

use wp_gdpr\model\Data_Register_Model;

class Gdpr_Data_Register_List_Table extends \WP_List_Table {

	/**
	 * @var \wp_gdpr\model\Data_Register_Model
	 */
	private $data_register_model;

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @param $args array
	 * @param $data_register_model  \wp_gdpr\model\Data_Register_Model
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = array(), Data_Register_Model $data_register_model ) {
		parent::__construct( array(
			'plural'   => 'data-registers',
			'singular' => 'data-register',
			'ajax'     => true,
			'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
		) );

		$this->data_register_model = $data_register_model;
	}

	/**
	 * @return bool
	 */
	public function ajax_user_can() {
		return current_user_can( 'manage_options' );
	}

	/**
	 *
	 * @global int $post_id
	 * @global string $comment_status
	 * @global string $search
	 * @global string $comment_type
	 */
	public function prepare_items() {
		$this->process_action();

		$search = ( isset( $_REQUEST['email'] ) ) ? $_REQUEST['email'] : '';

		$per_page = 20;

		$page = $this->get_pagenum();

		if ( isset( $_REQUEST['start'] ) ) {
			$start = $_REQUEST['start'];
		} else {
			$start = ( $page - 1 ) * $per_page;
		}

		if ( ! empty( $search ) ) {
			$this->items = $this->data_register_model->search_by_email( $search, $start, $per_page )->get_data();
			$total_items = $this->data_register_model->search_by_email( $search )->max_data();
		} else {
			$this->items = $this->data_register_model->get_all( $start, $per_page )->get_data();
			$total_items = $this->data_register_model->get_max_all_data();
		}

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
		) );
	}

	/**
	 *
	 * @global string $comment_status
	 */
	public function no_items() {
		if ( isset( $_REQUEST['s'] ) ) {
			_e( 'No data found for given email address.', 'wp_gdpr' );
		} else {
			_e( 'No data found in data register.', 'wp_gdpr' );
		}
	}

	/**
	 * @return string|false
	 */
	public function current_action() {
		if ( isset( $_REQUEST['export_csv'] ) || isset( $_REQUEST['export_csv'] ) ) {
			return 'export_csv';
		}

		return parent::current_action();
	}

	/**
	 *
	 * @global int $post_id
	 *
	 * @return array
     * TODO when no data, use column headers as column col of no data.
	 */
	public function get_columns() {
		$columns = array();

		$columns['id']        = __( 'ID', 'wp_gdpr' );
		$columns['email']     = __( 'Email', 'wp_gdpr' );
		$columns['message']   = __( 'Message', 'wp_gdpr' );
		$columns['ref_id']    = __( 'Reference ID', 'wp_gdpr' );
		$columns['ref']       = __( 'Reference', 'wp_gdpr' );
		$columns['timestamp'] = __( 'Submitted On', 'wp_gdpr' );

		return $columns;
	}

	/**
	 */
	public function display() {
		wp_nonce_field( "fetch-list-" . get_class( $this ), '_ajax_fetch_list_nonce' );

		$this->display_tablenav( 'top' );

		$this->screen->render_screen_reader_content( 'heading_list' );

		?>
        <table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
            <thead>
            <tr>
				<?php $this->print_column_headers(); ?>
            </tr>
            </thead>

            <tbody id="the-data-register-list">
			<?php $this->display_rows_or_placeholder(); ?>
            </tbody>

            <tfoot>
            <tr>
				<?php $this->print_column_headers( false ); ?>
            </tr>
            </tfoot>

        </table>
		<?php

		$this->display_tablenav( 'bottom' );
	}

	/**
	 * $item    object
	 */
	public function single_row( $item ) {
		echo "<tr id='data_register-$item->id' class='data-register'>";
		$this->single_row_columns( $item );
		echo "</tr>\n";
	}

	/**
	 * Generate the table navigation above or below the table
	 *
	 * @since 3.1.0
	 *
	 * @param string $which
	 */
	protected function display_tablenav( $which ) {
		if ( 'top' === $which ) {
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		}
		?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">

			<?php if ( $this->has_items() ): ?>
                <form id="form_list_form" method="post">
                    <div class="alignleft actions bulkactions">
						<?php $this->bulk_actions( $which ); ?>
						<?php $this->extra_tablenav( $which ); ?>
                    </div>
                </form>
			<?php endif;
			$this->pagination( $which );
			?>

            <br class="clear"/>
        </div>
		<?php
	}

	/**
	 * Extra controls to be displayed between bulk actions and pagination
	 *
	 * @since 3.1.0
	 *
	 * @param string $which
	 */
	protected function extra_tablenav( $which ) {
		if ( $which == 'top' && ( isset( $_REQUEST['email'] ) || isset( $_REQUEST['paged'] ) ) ) {
			echo '<a class="button-primary" href="' . admin_url( 'admin.php?page=datareg' ) . '">Reset filter & pagination</a>';
		}
	}

	/**
	 * Get an associative array ( option_name => option_title ) with the list
	 * of bulk actions available on this table.
	 *
	 * @since 3.1.0
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		return array(
			'export_csv' => __( 'Export found data to csv', 'wp_gdpr' )
		);
	}

	/**
	 * Generates the columns for a single row of the table
	 *
	 * @since 3.1.0
	 *
	 * @param object $item The current item
	 */
	protected function single_row_columns( $item ) {
		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		$columns = $this->get_columns();

		foreach ( $columns as $column_name => $column_display_name ) {
			$classes = "$column_name column-$column_name";
			if ( $primary === $column_name ) {
				$classes .= ' has-row-actions column-primary';
			}

			if ( in_array( $column_name, $hidden ) ) {
				$classes .= ' hidden';
			}

			// Comments column uses HTML in the display name with screen reader text.
			// Instead of using esc_attr(), we strip tags to get closer to a user-friendly string.
			$data = 'data-colname="' . wp_strip_all_tags( $column_display_name ) . '"';

			$attributes = "class='$classes' $data";

			if ( 'cb' === $column_name ) {
				echo '<th scope="row" class="check-column">';
				echo $this->column_cb( $item );
				echo '</th>';
			} elseif ( method_exists( $this, '_column_' . $column_name ) ) {
				echo call_user_func(
					array( $this, '_column_' . $column_name ),
					$item,
					$classes,
					$data,
					$primary
				);
			} elseif ( method_exists( $this, 'column_' . $column_name ) ) {
				echo "<td $attributes>";
				echo call_user_func( array( $this, 'column_' . $column_name ), $item );
				echo $this->handle_row_actions( $item, $column_name, $primary );
				echo "</td>";
			} else {
				echo "<td $attributes>";
				echo $this->column_default( $item, $column_name );
				echo $this->handle_row_actions( $item, $column_name, $primary );
				echo "</td>";
			}
		}
	}

	/**
	 * Print column headers, accounting for hidden and sortable columns.
	 *
	 * @since 3.1.0
	 *
	 * @staticvar int $cb_counter
	 *
	 * @param bool $with_id Whether to set the id attribute or not
	 */
	public function print_column_headers( $with_id = true ) {
		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		$current_url = remove_query_arg( 'paged', $current_url );

		if ( isset( $_GET['orderby'] ) ) {
			$current_orderby = $_GET['orderby'];
		} else {
			$current_orderby = '';
		}

		if ( isset( $_GET['order'] ) && 'desc' === $_GET['order'] ) {
			$current_order = 'desc';
		} else {
			$current_order = 'asc';
		}

		if ( ! empty( $columns['cb'] ) ) {
			static $cb_counter = 1;
			$columns['cb'] = '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' . __( 'Select All' ) . '</label>'
			                 . '<input id="cb-select-all-' . $cb_counter . '" type="checkbox" />';
			$cb_counter ++;
		}

		$columns = $this->get_columns();

		foreach ( $columns as $column_key => $column_display_name ) {
			$class = array( 'manage-column', "column-$column_key" );

			if ( in_array( $column_key, $hidden ) ) {
				$class[] = 'hidden';
			}

			if ( 'cb' === $column_key ) {
				$class[] = 'check-column';
			} elseif ( in_array( $column_key, array( 'posts', 'comments', 'links' ) ) ) {
				$class[] = 'num';
			}

			if ( $column_key === $primary ) {
				$class[] = 'column-primary';
			}

			if ( isset( $sortable[ $column_key ] ) ) {
				list( $orderby, $desc_first ) = $sortable[ $column_key ];

				if ( $current_orderby === $orderby ) {
					$order   = 'asc' === $current_order ? 'desc' : 'asc';
					$class[] = 'sorted';
					$class[] = $current_order;
				} else {
					$order   = $desc_first ? 'desc' : 'asc';
					$class[] = 'sortable';
					$class[] = $desc_first ? 'asc' : 'desc';
				}

				$column_display_name = '<a href="' . esc_url( add_query_arg( compact( 'orderby', 'order' ), $current_url ) ) . '"><span>' . $column_display_name . '</span><span class="sorting-indicator"></span></a>';
			}

			$tag   = ( 'cb' === $column_key ) ? 'td' : 'th';
			$scope = ( 'th' === $tag ) ? 'scope="col"' : '';
			$id    = $with_id ? "id='$column_key'" : '';

			if ( ! empty( $class ) ) {
				$class = "class='" . join( ' ', $class ) . "'";
			}

			echo "<$tag $scope $id $class>$column_display_name</$tag>";
		}
	}

	/**
	 *
	 * @param $item $comment The comment object.
	 * @param string $column_name The custom column's name.
	 */
	public function column_default( $item, $column_name ) {
		echo $item->{$column_name};
	}

	/**
	 * Run bulk action
	 */
	protected function process_action() {
		$bulk_action = $this->current_action();

		if ( ! $bulk_action ) {
			return;
		}

		switch ( $bulk_action ) {
			case 'export_csv':
				ob_clean();
				$headers = array( 'id', 'email', 'hashed_email', 'message', 'ref', 'ref_id', 'timestamp' );
				$data    = $this->get_item_data();
				export_data_to_csv( $headers, $data, 'data-register-' . date( 'Y-m-d-H-i-s' ) );
				break;
		}
	}

	/**
	 * Returns item data by email REQUEST
	 *
	 * @return array
	 */
	private function get_item_data() {
		$email = ( isset( $_REQUEST['email'] ) ) ? $_REQUEST['email'] : '';

		if ( ! empty( $email ) ) {
			$data = $this->data_register_model->search_by_email( $email )->get_data();
		} else {
			$data = $this->data_register_model->get_all()->get_data();
		}

		return $data;
	}
}