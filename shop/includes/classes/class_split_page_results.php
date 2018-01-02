<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: split_page_results.php,v 1.11 2003/02/13 04:23:23 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

/**
 * Page Navigation
 *
 * @package		kernel
 * @version		$Revision: 1.2 $ - changed by $Author: r23 $ on $Date: 2007/12/11 08:12:54 $
 */
class splitPageResults {
	/**
	 * Constructor
	 */
	public function __construct(&$current_page_number, $max_rows_per_page, &$sql_result, &$query_num_rows) {

		$dbconn =& oosDBGetConn();
		if (empty($current_page_number)) $current_page_number = 1;

		$pos_to = strlen($sql_result);
		$pos_from = strpos($sql_result, ' FROM', 0);

		$pos_group_by = strpos($sql_result, ' GROUP BY', $pos_from);
		if (($pos_group_by < $pos_to) && ($pos_group_by != FALSE)) $pos_to = $pos_group_by;

		$pos_having = strpos($sql_result, ' HAVING', $pos_from);
		if (($pos_having < $pos_to) && ($pos_having != FALSE)) $pos_to = $pos_having;

		$pos_order_by = strpos($sql_result, ' ORDER BY', $pos_from);
		if (($pos_order_by < $pos_to) && ($pos_order_by != FALSE)) $pos_to = $pos_order_by;

		$pos_limit = strpos($sql_result, ' LIMIT', $pos_from);
		if (($pos_limit < $pos_to) && ($pos_limit != FALSE)) $pos_to = $pos_limit;

		$pos_procedure = strpos($sql_result, ' PROCEDURE', $pos_from);
		if (($pos_procedure < $pos_to) && ($pos_procedure != FALSE)) $pos_to = $pos_procedure;

		$offset = ($max_rows_per_page * ($current_page_number - 1));
		if ($offset < 0) $offset = 0;
		$sql_result .= " LIMIT " . max($offset, 0) . ", " . $max_rows_per_page;

		$sql = "SELECT COUNT(*) AS total " . substr($sql_result, $pos_from, ($pos_to - $pos_from));
		$count = $dbconn->Execute($sql);	
		$query_num_rows = $count->fields['total'];
	}


	/**
     * display split-page-number-links
     *
     * @param $query_numrows
     * @param $max_rows_per_page
     * @param $max_page_links
     * @param $current_page_number
     * @param $parameters
     * @return string
     */
	public function display_links($query_numrows, $max_rows_per_page, $max_page_links, $current_page_number, $parameters = '') {
		global $aLang, $sContent;

		$display_link = '';

		if ( oos_is_not_null($parameters) && (substr($parameters, -5) != '&amp;') ) $parameters .= '&amp;';

		// calculate number of pages needing links 
		$num_pages = intval($query_numrows / $max_rows_per_page);

		// $num_pages now contains int of pages needed unless there is a remainder from division 
		if ($query_numrows % $max_rows_per_page) $num_pages++; // has remainder so add one page 

		// previous button - not displayed on first page
		if ($current_page_number > 1) {
			$display_link .= '<li class="page-item"><a class="page-link" href="' . oos_href_link($sContent, $parameters . 'page=' . ($current_page_number - 1)) . '" aria-label="' . $aLang['prevnext_button_prev'] . '"><span aria-hidden="true">&laquo;</span><span class="sr-only">' . $aLang['prevnext_button_prev'] . '</span></a></li>';	
		}
		
		// check if num_pages > $max_page_links
		$cur_window_num = intval($current_page_number / $max_page_links);
		if ($current_page_number % $max_page_links) $cur_window_num++;

		$max_window_num = intval($num_pages / $max_page_links);
		if ($num_pages % $max_page_links) $max_window_num++;

		// previous window of pages
		// if ($cur_window_num > 1) $display_link .= '<li class="page-item"><a class="page-link"' . oos_href_link($sContent, $parameters . 'page=' . (($cur_window_num - 1) * $max_page_links)) . '">...</a></li>';

		// page nn button
		for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $num_pages); $jump_to_page++) {
			if ($jump_to_page == $current_page_number) {
				// $display_link .= '<li class="page-item active"><a class="page-link" href="' . oos_href_link($sContent, $parameters . 'page=' . $jump_to_page) . '">' . $jump_to_page . '<span class="sr-only"></span></a></li>';
				$display_link .= '<li class="page-item active"><span class="page-link">' . $jump_to_page . '<span class="sr-only">(current)</span></span></li>';
			} else {
				$display_link .= '<li class="page-item"><a class="page-link" href="' . oos_href_link($sContent, $parameters . 'page=' . $jump_to_page) . '">' . $jump_to_page . '</a></li>';
			}
		}

		// next window of pages
		// if ($cur_window_num < $max_window_num) $display_link .= '<li class="page-item"><a class="page-link" href="' . oos_href_link($sContent, $parameters . 'page=' . (($cur_window_num) * $max_page_links + 1)) . '">...</a></li>';

		// next button
		if (($current_page_number < $num_pages) && ($num_pages != 1)) $display_link .= '<li class="page-item"><a class="page-link" href="' . oos_href_link($sContent, $parameters . 'page=' . ($current_page_number + 1)) . '"><span aria-hidden="true">&raquo;</span><span class="sr-only">' . $aLang['prevnext_button_next'] . '</span></a></li>';


		return $display_link;
	}


	/**
     * display number of total products found
     *
     * @param $query_numrows
     * @param $max_rows_per_page
     * @param $current_page_number
     * @param $text_output
     * @return string
     */
	public function display_count($query_numrows, $max_rows_per_page, $current_page_number, $text_output) {

		$to_num = ($max_rows_per_page * $current_page_number);
		if ($to_num > $query_numrows) $to_num = $query_numrows;

		$from_num = ($max_rows_per_page * ($current_page_number - 1));

		if ($to_num == 0) {
			$from_num = 0;
		} else {
			$from_num++;
		}

		return sprintf($text_output, $from_num, $to_num, $query_numrows);
	}
}
