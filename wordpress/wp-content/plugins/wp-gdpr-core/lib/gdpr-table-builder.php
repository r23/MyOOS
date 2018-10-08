<?php


namespace wp_gdpr\lib;

/**
 * Class Appsaloon_Table_Builder
 * @package wp_gdpr\lib
 *
 * allows to build simple table
 */
class Gdpr_Table_Builder {

	/**
	 * @var null|string
	 */
	public $table_class;
	public $table_id;

	/**
	 * @var array
	 */
	public $head;
	public $data;
	public $footer;

	/**
	 * Appsaloon_Table_Builder constructor.
	 */
	public function __construct( array $head, array $data, array $footer, $table_class = null, $table_id = null ) {
		if ( $table_class == null ) {
			$table_class = 'wp-list-table widefat fixed striped';
		}
		$this->table_class = $table_class;
		$this->head        = $head;
		$this->data        = $data;
		$this->footer      = $footer;
		$this->table_id    = $table_id;
	}

	/**
	 * show table
	 */
	public function print_table() {
		$this->open_table();
		$this->build_head();
		$this->build_body();
		$this->build_footer();
		$this->close_table();
	}

	/**
	 * table open tab
	 */
	public function open_table() {
		?><table id="<?php echo $this->table_id; ?>" class="<?php echo $this->table_class; ?>"><?php
	}

	/**
	 * build head
	 */
	public function build_head() {
		if ( empty( $this->head ) ) {
			return;
		}
		?>
        <thead>
        <tr>
			<?php foreach ( $this->head as $header ) : ?>
                <th><?php echo $header; ?></th>
			<?php endforeach; ?>
        </tr>
        </thead>
		<?php
	}

	/**
	 * show body
	 */
	public function build_body() {
		?>
        <tbody>
		<?php foreach ( $this->data as $rows ) : ?>
            <tr>
				<?php foreach ( $rows as $single_row ) : ?>
                    <td><?php echo $single_row; ?></td>
				<?php endforeach; ?>
            </tr>
		<?php endforeach; ?>
        </tbody>
		<?php
	}

	/**
	 * simple footer
	 */
	public function build_footer() {
		if ( empty( $this->footer ) ) {
			return;
		}
		?>

		<?php
	}

	/**
	 * close tag of table
	 */
	public function close_table() {
		?></table>
					<?php foreach ( $this->footer as $footer ) : ?>
               <?php echo $footer; ?>
			<?php endforeach; ?>
		
		<?php
	}
}