<?php
/* ----------------------------------------------------------------------
   $Id: class_split_page_results.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: split_page_results.php,v 1.11 2002/11/11 21:12:19 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


class splitPageResults {

    public function splitPageResults(&$current_page_number, $max_rows_per_page, &$sql_result, &$query_num_rows) 
	{

		// Get database information
		$dbconn =& oosDBGetConn();

		if (empty($current_page_number)) $current_page_number = 1;

		$pos_to = strlen($sql_result);
		$pos_from = strpos($sql_result, ' FROM', 0);

		$pos_group_by = strpos($sql_result, ' GROUP BY', $pos_from);
		if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;

		$pos_having = strpos($sql_result, ' HAVING', $pos_from);
		if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

		$pos_order_by = strpos($sql_result, ' ORDER BY', $pos_from);
		if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;

		$pos_limit = strpos($sql_result, ' LIMIT', $pos_from);
		if (($pos_limit < $pos_to) && ($pos_limit != false)) $pos_to = $pos_limit;

		$pos_procedure = strpos($sql_result, ' PROCEDURE', $pos_from);
		if (($pos_procedure < $pos_to) && ($pos_procedure != false)) $pos_to = $pos_procedure;

		$offset = ($max_rows_per_page * ($current_page_number - 1));
		$sql_result .= " LIMIT " . max($offset, 0) . ", " . $max_rows_per_page;

		$sql = "SELECT count(*) AS total " . substr($sql_result, $pos_from, ($pos_to - $pos_from));
		$reviews_count_result = $dbconn->Execute($sql);
		$reviews_count = $reviews_count_result->fields;
		$query_num_rows = $reviews_count['total'];
    }

	// todo remove - use: no smarty template
	public function display_links($query_numrows, $max_rows_per_page, $max_page_links, $current_page_number, $parameters = '', $page_name = 'page')
	{

      if ( oos_is_not_null($parameters) && (substr($parameters, -1) != '&') ) $parameters .= '&';

      // calculate number of pages needing links
      $num_pages = intval($query_numrows / $max_rows_per_page);

      // $num_pages now contains int of pages needed unless there is a remainder from division
      if ($query_numrows % $max_rows_per_page) $num_pages++; // has remainder so add one page

      $pages_array = array();
      for ($i=1; $i<=$num_pages; $i++) {
        $pages_array[] = array('id' => $i, 'text' => $i);
      }

      if ($num_pages > 1) {
        $display_links = oos_draw_form('pages', basename($_SERVER['PHP_SELF']), '', 'get');

        if ($current_page_number > 1) {
          $display_links .= '<a href="' . oos_href_link_admin(basename($_SERVER['PHP_SELF']), $parameters . $page_name . '=' . ($current_page_number - 1), 'NONSSL') . '" class="splitPageLink">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';
        } else {
          $display_links .= PREVNEXT_BUTTON_PREV . '&nbsp;&nbsp;';
        }

        $display_links .= sprintf(TEXT_RESULT_PAGE, oos_draw_pull_down_menu($page_name, $pages_array, '', 'onChange="this.form.submit();"'), $num_pages);

        if (($current_page_number < $num_pages) && ($num_pages != 1)) {
          $display_links .= '&nbsp;&nbsp;<a href="' . oos_href_link_admin(basename($_SERVER['PHP_SELF']), $parameters . $page_name . '=' . ($current_page_number + 1), 'NONSSL') . '" class="splitPageLink">' . PREVNEXT_BUTTON_NEXT . '</a>';
        } else {
          $display_links .= '&nbsp;&nbsp;' . PREVNEXT_BUTTON_NEXT;
        }

        if ($parameters != '') {
          if (substr($parameters, -1) == '&') $parameters = substr($parameters, 0, -1);
          $pairs = explode('&', $parameters);
          while (list(, $pair) = each($pairs)) {
            list($key,$value) = explode('=', $pair);
            $display_links .= oos_draw_hidden_field(rawurldecode($key), rawurldecode($value));
          }
        }

        if (SID) $display_links .= oos_draw_hidden_field(oos_session_name(), oos_session_id());

        $display_links .= '</form>';
      } else {
        $display_links = sprintf(TEXT_RESULT_PAGE, $num_pages, $num_pages);
      }

      return $display_links;
    }
	
	
	public function display_pagination($query_numrows, $max_rows_per_page, $max_page_links, $current_page_number, $parameters = '', $sContent = 'page')
	{
		global $aLang;

		$display_pagination = '<ul class="pagination">';

		if ( !empty($parameters) && (substr($parameters, -5) != '&amp;') ) $parameters .= '&amp;';

		// calculate number of pages needing links 
		$num_pages = intval($query_numrows / $max_rows_per_page);

		// $num_pages now contains int of pages needed unless there is a remainder from division 
		if ($query_numrows % $max_rows_per_page) $num_pages++; // has remainder so add one page 
		// previous button - not displayed on first page
		if ($current_page_number > 1) $display_pagination .= '<li><a href="' . oos_href_link_admin($sContent, $parameters . 'page=' . ($current_page_number - 1)) . '"  title=" ' . $aLang['prevnext_title_previous_page'] . ' ">&laquo;</a></li>';

		// check if num_pages > $max_page_links
		$cur_window_num = intval($current_page_number / $max_page_links);
		if ($current_page_number % $max_page_links) $cur_window_num++;

		$max_window_num = intval($num_pages / $max_page_links);
		if ($num_pages % $max_page_links) $max_window_num++;

		// previous window of pages
		if ($cur_window_num > 1) $display_pagination .= '<li><a href="' . oos_href_link_admin($sContent, $parameters . 'page=' . (($cur_window_num - 1) * $max_page_links)) . '"  title=" ' . sprintf($aLang['prevnext_title_prev_set_of_no_page'], $max_page_links) . ' ">...</a>';

		// page nn button
		for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $num_pages); $jump_to_page++) {
			if ($jump_to_page == $current_page_number) {
				$display_pagination .= ' <li class="active"><a href="' . oos_href_link_admin($sContent, $parameters . 'page=' . $jump_to_page) . '">' . $jump_to_page . '</a></li>';
			} else {
				$display_pagination .= ' <li><a href="' . oos_href_link_admin($sContent, $parameters . 'page=' . $jump_to_page) . '"  title=" ' . sprintf($aLang['prevnext_title_page_no'], $jump_to_page) . ' ">' . $jump_to_page . '</a></li>';
			}
		}

		// next window of pages
		if ($cur_window_num < $max_window_num) $display_pagination .= '<li><a href="' . oos_href_link_admin($sContent, $parameters . 'page=' . (($cur_window_num) * $max_page_links + 1)) . '"  title=" ' . sprintf($aLang['prevnext_title_next_set_of_no_page'], $max_page_links) . ' ">...</a></li>';

		// next button
		if (($current_page_number < $num_pages) && ($num_pages != 1)) $display_pagination .= '<li><a href="' . oos_href_link_admin($sContent, $parameters . 'page=' . ($current_page_number + 1)) . '"  title=" ' . $aLang['prevnext_title_next_page'] . ' ">&raquo;</a><li>';

		$display_pagination .= '</ul>';
		return $display_pagination;

	}
	
	
	

    public function display_count($query_numrows, $max_rows_per_page, $current_page_number, $text_output)
	{
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

