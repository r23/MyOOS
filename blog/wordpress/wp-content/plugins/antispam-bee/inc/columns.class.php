<?php


/* Quit */
defined('ABSPATH') OR exit;


/**
* Antispam_Bee_Columns
*/

final class Antispam_Bee_Columns {


	/**
	* Register plugin columns on comments screen
	*
	* @since   2.6.0
	* @change  2.6.0
	*
	* @param   array  $columns  Array with existing columns
	* @return  array            Array with extended columns
	*/

	public static function register_plugin_columns($columns)
	{
		return array_merge(
			$columns,
			array(
				'antispam_bee_reason' => esc_html__('Spam Reason', 'antispam_bee')
			)
		);
	}


	/**
	* Display plugin column values on comments screen
	*
	* @since   2.6.0
	* @change  2.6.0
	*
	* @param   string   $column      Currently selected column
	* @param   integer  $comment_id  Comment ID
	*/

	public static function print_plugin_column($column, $comment_id)
	{
		/* Only Antispam Bee column */
		if ( $column !== 'antispam_bee_reason' ) {
			return;
		}

		/* Init data */
		$spam_reason = get_comment_meta($comment_id, $column , true);
		$spam_reasons = Antispam_Bee::$defaults['reasons'];

		/* Empty values? */
		if ( empty($spam_reason) OR empty($spam_reasons[$spam_reason]) ) {
			return;
		}

		/* Escape & Print */
		echo esc_html__(
			$spam_reasons[$spam_reason],
			'antispam_bee'
		);
	}
}