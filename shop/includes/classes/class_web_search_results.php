<?php
/* ----------------------------------------------------------------------
   $Id: class_web_search_results.php,v 1.1 2007/06/07 16:06:31 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: web_search_results.php,v 1.1 2004/07/02 22:27:20 chaicka
   ----------------------------------------------------------------------
   WebSearch

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2004 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  /**
   * Page Navigation
   *
   * @package		WebSearch
   * @version		$Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/07 16:06:31 $
   */
  class webSearchResults {
    var $search_string, $number_of_rows, $current_page_number, $number_of_pages, $number_of_rows_per_page, $page_name;
    var $gs, $license_key, $start_index, $result_accuracy;

   /**
    * Constructor
    */
    function webSearchResults($query, $max_rows, $google_key = '', $page_holder = 'page') {
      global $aLang;

      $this->gs = new GoogleSearch();

      $this->search_string = $query;
      $this->license_key = $google_key;
      $this->page_name = $page_holder;

      if (isset($_GET[$page_holder])) {
        $page = $_GET[$page_holder];
      } elseif (isset($_POST[$page_holder])) {
        $page = $_POST[$page_holder];
      } else {
        $page = '';
      }

      if (empty($page) || !is_numeric($page)) $page = 1;
      $this->current_page_number = $page;

      $this->number_of_rows_per_page = $max_rows;

      //set Google licensing key
      $this->gs->setKey($this->license_key);
      $this->gs->setQueryString($this->search_string);	

      $rs = $this->gs->doSearch();
      $this->number_of_rows = $rs->getEstimatedTotalResultsCount();

      //result accuracy
      $this->result_accuracy = $rs->getEstimateIsExact() ? $aLang['text_display_web_search_result_exact'] : $aLang['text_display_web_search_result_approx'] ;

      $this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);

      if ($this->current_page_number > $this->number_of_pages) {
        $this->current_page_number = $this->number_of_pages;
      }

      $this->start_index = ($this->number_of_rows_per_page * ($this->current_page_number - 1));
    }



   /**
    * display split-page-number-links
    *
    * @return string
    */
    function display_links($max_page_links, $parameters = '') {
      global $aLang, $sMp, $sFile, $request_type;

      $display_links_string = '';
      $class = 'class="pageResults"';


      if (oos_is_not_null($parameters) && (substr($parameters, -5) != '&amp;') ) $parameters .= '&amp;';

      // previous button - not displayed on first page
      if ($this->current_page_number > 1) $display_links_string .= '<a href="' . oos_href_link($sMp, $sFile, $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type) . '" ' . $class . ' title=" ' . $aLang['prevnext_title_previous_page'] . ' ">[<u>' . $aLang['prevnext_button_prev'] . '</u>]</a>&nbsp;&nbsp;';

      // check if number_of_pages > $max_page_links
      $cur_window_num = intval($this->current_page_number / $max_page_links);
      if ($this->current_page_number % $max_page_links) $cur_window_num++;

      $max_window_num = intval($this->number_of_pages / $max_page_links);
      if ($this->number_of_pages % $max_page_links) $max_window_num++;

      // previous window of pages
      if ($cur_window_num > 1) $display_links_string .= '<a href="' . oos_href_link($sMp, $sFile, $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" ' . $class . ' title=" ' . sprintf($aLang['prevnext_title_prev_set_of_no_page'], $max_page_links) . ' ">...</a>';

      // page nn button
      for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
        if ($jump_to_page == $this->current_page_number) {
          $display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;';
        } else {
          $display_links_string .= '&nbsp;<a href="' . oos_href_link($sMp, $sFile, $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '" ' . $class . ' title=" ' . sprintf($aLang['prevnext_title_page_no'], $jump_to_page) . ' "><u>' . $jump_to_page . '</u></a>&nbsp;';
        }
      }

      // next window of pages
      if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="' . oos_href_link($sMp, $sFile, $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" ' . $class . ' title=" ' . sprintf($aLang['prevnext_title_next_set_of_no_page'], $max_page_links) . ' ">...</a>&nbsp;';

      // next button
      if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a href="' . oos_href_link($sMp, $sFile, $parameters . 'page=' . ($this->current_page_number + 1), $request_type) . '" ' . $class . ' title=" ' . $aLang['prevnext_title_next_page'] . ' ">[<u>' . $aLang['prevnext_button_next'] . '</u>]</a>&nbsp;';

      return $display_links_string;
    }


   /**
    * display number of results found
    *
    * @return string
    */
    function display_count($text_output) {
      $to_num = ($this->number_of_rows_per_page * $this->current_page_number);
      if ($to_num > $this->number_of_rows) $to_num = $this->number_of_rows;

      $from_num = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

      if ($to_num == 0) {
        $from_num = 0;
      } else {
        $from_num++;
      }

      return sprintf($text_output, $from_num, $to_num, $this->result_accuracy, $this->number_of_rows, oos_output_string($this->search_string));
    }


   /**
    * Return result element set
    *
    * @return string
    */
    function do_search() {
      $this->gs->setMaxResults($this->number_of_rows_per_page);	
      $this->gs->setStartResult($this->start_index);

      $rs = $this->gs->doSearch();
      $re = $rs->getResultElements();

      return $re;
    }
  }

?>
