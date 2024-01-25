<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: split_page_results.php,v 1.11 2003/02/13 04:23:23 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

/**
 * Page Navigation
 *
 * @package kernel
 * @version $Revision: 1.2 $ - changed by $Author: r23 $ on $Date: 2007/12/11 08:12:54 $
 */
class splitPageResults
{
    public $sql_query;
    public $number_of_rows;
    public $current_page_number;
    public $number_of_pages;
    public $number_of_rows_per_page;

    /**
     * Constructor
     */
    public function __construct($query, $max_rows, $count_key = '*', public $page_name = 'page')
    {
        $max_rows = ($max_rows == '' || $max_rows == 0) ? 20 : $max_rows;

        $this->sql_query = preg_replace("/\n\r|\r\n|\n|\r/", " ", (string) $query);

        if (isset($_GET[$page_name])) {
            $page = filter_input(INPUT_GET, $page_name, FILTER_VALIDATE_INT);
        } elseif (isset($_POST[$page_name])) {
            $page = filter_input(INPUT_POST, $page_name, FILTER_VALIDATE_INT);
        }

        if ($page === null || $page === false) {
            $page = 1;
        }


        $this->current_page_number = $page;

        $this->number_of_rows_per_page = $max_rows;

        $pos_to = strlen($this->sql_query ?? '');
        $pos_from = strpos($this->sql_query, 'FROM', 0);

        $pos_group_by = strpos($this->sql_query, ' GROUP BY', $pos_from);
        if (($pos_group_by < $pos_to) && ($pos_group_by != false)) {
            $pos_to = $pos_group_by;
        }

        $pos_having = strpos($this->sql_query, ' HAVING', $pos_from);
        if (($pos_having < $pos_to) && ($pos_having != false)) {
            $pos_to = $pos_having;
        }

        $pos_order_by = strpos($this->sql_query, ' ORDER BY', $pos_from);
        if (($pos_order_by < $pos_to) && ($pos_order_by != false)) {
            $pos_to = $pos_order_by;
        }

        $dbconn = & oosDBGetConn();
        $sql = "SELECT COUNT(" . oos_db_input($count_key) . ") AS total " . substr($this->sql_query, $pos_from, ($pos_to - $pos_from));
        $count = $dbconn->Execute($sql);

        $this->number_of_rows = $count->fields['total'];

        if ($this->number_of_rows_per_page > 0) {
            $this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);
        } else {
            $this->number_of_pages = 0;
        }

        if ($this->current_page_number > $this->number_of_pages) {
            $this->current_page_number = $this->number_of_pages;
        }

        $offset = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

        if ($offset <= 0) {
            $offset = 0;
        }
        if ($this->current_page_number <= 0) {
            $this->current_page_number = 1;
        }

        // $this->sql_query .= " LIMIT " . ($offset > 0 ? $offset . ", " : '') . $this->number_of_rows_per_page;
        $this->sql_query .= " LIMIT " . max($offset, 0) . ", " . $this->number_of_rows_per_page;
    }


    /**
     * display split-page-number-links
     *
     * @param  $this->number_of_rows
     * @param  $this->number_of_rows_per_page
     * @param  $max_page_links
     * @param  $current_page_number
     * @param  $parameters
     * @return string
     */
    public function display_links($max_page_links, $parameters = '')
    {
        global $aLang, $sContent;


        $display_link = '';

        if (oos_is_not_null($parameters) && (!str_ends_with((string) $parameters, '&amp;'))) {
            $parameters .= '&amp;';
        }

        // previous button - not displayed on first page
        if ($this->current_page_number > 1) {
            $display_link .= '<li class="page-item"><a class="page-link" href="' . oos_href_link($sContent, $parameters . $this->page_name . '=' . ($this->current_page_number - 1)) . '" aria-label="' . $aLang['prevnext_button_prev'] . '"><span aria-hidden="true">&laquo;</span><span class="sr-only">' . $aLang['prevnext_button_prev'] . '</span></a></li>';
        }

        // check if num_pages > $max_page_links
        $cur_window_num = intval($this->current_page_number / $max_page_links);
        if ($this->current_page_number % $max_page_links) {
            $cur_window_num++;
        }

        $max_window_num = intval($this->number_of_pages / $max_page_links);
        if ($this->number_of_pages % $max_page_links) {
            $max_window_num++;
        }

        // previous window of pages
        if ($cur_window_num > 1) {
            $display_link .= '<li class="page-item"><a class="page-link"' . oos_href_link($sContent, $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links)) . '">...</a></li>';
        }

        // page nn button
        for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
            if ($jump_to_page == $this->current_page_number) {
                // $display_link .= '<li class="page-item active"><a class="page-link" href="' . oos_href_link($sContent, $parameters . $this->page_name . '=' . $jump_to_page) . '">' . $jump_to_page . '<span class="sr-only"></span></a></li>';
                $display_link .= '<li class="page-item active"><span class="page-link">' . $jump_to_page . '<span class="sr-only">(current)</span></span></li>';
            } else {
                $display_link .= '<li class="page-item"><a class="page-link" href="' . oos_href_link($sContent, $parameters . $this->page_name . '=' . $jump_to_page) . '">' . $jump_to_page . '</a></li>';
            }
        }

        // next window of pages
        if ($cur_window_num < $max_window_num) {
            $display_link .= '<li class="page-item"><a class="page-link" href="' . oos_href_link($sContent, $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1)) . '">...</a></li>';
        }

        // next button
        if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) {
            $display_link .= '<li class="page-item"><a class="page-link" href="' . oos_href_link($sContent, $parameters . $this->page_name . '=' . ($this->current_page_number + 1)) . '"><span aria-hidden="true">&raquo;</span><span class="sr-only">' . $aLang['prevnext_button_next'] . '</span></a></li>';
        }

        return $display_link;
    }


    /**
     * display number of total products found
     *
     * @return string
     */
    public function display_count($text_output)
    {
        $to_num = ($this->number_of_rows_per_page * $this->current_page_number);
        if ($to_num > $this->number_of_rows) {
            $to_num = $this->number_of_rows;
        }

        $from_num = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

        if ($to_num == 0) {
            $from_num = 0;
        } else {
            $from_num++;
        }

        return sprintf($text_output, $from_num, $to_num, $this->number_of_rows);
    }
}
