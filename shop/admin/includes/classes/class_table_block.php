<?php
/* ----------------------------------------------------------------------
   $Id: class_table_block.php,v 1.1 2007/06/08 14:58:10 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: table_block.php,v 1.2 2002/11/22 18:45:46 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

class tableBlock {
	var $table_border = '0';
	var $table_width = '100%';
	var $table_cellspacing = '0';
	var $table_cellpadding = '2';
	var $table_parameters = '';
	var $table_row_parameters = '';
	var $table_data_parameters = '';
	
	public function __construct() {
    }
	function tableBlock($contents) {
		$tableBox_string = '';

		$form_set = false;
		if (isset($contents['form'])) {
			$tableBox_string .= $contents['form'] . "\n";
			$form_set = true;
			array_shift($contents);
		}		

		$tableBox_string .= '<table border="' . $this->table_border . '" width="' . $this->table_width . '" cellspacing="' . $this->table_cellspacing . '" cellpadding="' . $this->table_cellpadding . '"';
		if (oos_is_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
		$tableBox_string .= '>' . "\n";

		
		for ($i = 0, $n = count($contents); $i < $n; $i++) {
			$tableBox_string .= '  <tr';
			if (oos_is_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
			if (isset($contents[$i]['params']) && oos_is_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
			$tableBox_string .= '>' . "\n";		
			
			if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
				for ($x = 0, $y = count($contents[$i]); $x < $y; $x++) {
					if (isset($contents[$i][$x]['text']) && oos_is_not_null($contents[$i][$x]['text'])) {
						$tableBox_string .= '    <td';
						if (isset($contents[$i][$x]['align']) && oos_is_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . $contents[$i][$x]['align'] . '"';
						if (isset($contents[$i][$x]['params']) && oos_is_not_null($contents[$i][$x]['params'])) {
							$tableBox_string .= ' ' . $contents[$i][$x]['params'];
						} elseif (oos_is_not_null($this->table_data_parameters)) {
							$tableBox_string .= ' ' . $this->table_data_parameters;
						}
						$tableBox_string .= '>';
						if (isset($contents[$i][$x]['form']) && oos_is_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
						$tableBox_string .= $contents[$i][$x]['text'];
						if (isset($contents[$i][$x]['form']) && oos_is_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
						$tableBox_string .= '</td>' . "\n";
					}
				}
			} else {
				$tableBox_string .= '    <td';
				if (isset($contents[$i]['align']) && oos_is_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . $contents[$i]['align'] . '"';
				if (isset($contents[$i]['params']) && oos_is_not_null($contents[$i]['params'])) {
					$tableBox_string .= ' ' . $contents[$i]['params'];
				} elseif (oos_is_not_null($this->table_data_parameters)) {
					$tableBox_string .= ' ' . $this->table_data_parameters;
				}
				$tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
			}		

			$tableBox_string .= '  </tr>' . "\n";
		}
		
		$tableBox_string .= '</table>' . "\n";

		if ($form_set == true) $tableBox_string .= '</form>' . "\n";

		return $tableBox_string;
	}
}

