<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: split_page_results.php,v 1.11 2002/11/11 21:12:19 hpdl
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


class splitPageResults
{
    public function __construct(&$current_page_number, $max_rows_per_page, &$sql_query, &$query_num_rows)
    {
        if ($max_rows_per_page == 0) {
            $max_rows_per_page = 20;
        }
        $sql_query = preg_replace("/\n\r|\r\n|\n|\r/", " ", (string) $sql_query);

        if (empty($current_page_number)) {
            $current_page_number = 1;
        }
        $current_page_number = (int)$current_page_number;

        $pos_to = strlen($sql_query);
        $pos_from = strpos(strtoupper($sql_query), ' FROM', 0);

        $pos_group_by = strpos(strtoupper($sql_query), ' GROUP BY', $pos_from);
        if (($pos_group_by < $pos_to) && ($pos_group_by !== false)) {
            $pos_to = $pos_group_by;
        }

        $pos_having = strpos(strtoupper($sql_query), ' HAVING', $pos_from);
        if (($pos_having < $pos_to) && ($pos_having !== false)) {
            $pos_to = $pos_having;
        }

        $pos_order_by = strpos(strtoupper($sql_query), ' ORDER BY', $pos_from);
        if (($pos_order_by < $pos_to) && ($pos_order_by !== false)) {
            $pos_to = $pos_order_by;
        }

        $sql = "SELECT count(*) AS total " . substr($sql_query, $pos_from, ($pos_to - $pos_from));

        // Get database information
        $dbconn =& oosDBGetConn();

        $reviews_count = $dbconn->Execute($sql);
        $query_num_rows = $reviews_count->fields['total'];

        $num_pages = ceil($query_num_rows / $max_rows_per_page);
        if ($current_page_number > $num_pages) {
            $current_page_number = $num_pages;
        }
        $offset = ($max_rows_per_page * ($current_page_number - 1));
        $sql_query .= " limit " . max($offset, 0) . ", " . $max_rows_per_page;
    }


    public function display_links($query_numrows, $max_rows_per_page, $max_page_links, $current_page_number, $parameters = '', $page_name = 'page')
    {
        global $session;

        if (oos_is_not_null($parameters) && (!str_ends_with((string) $parameters, '&'))) {
            $parameters .= '&';
        }

        // calculate number of pages needing links
        $num_pages = intval($query_numrows / $max_rows_per_page);

        // $num_pages now contains int of pages needed unless there is a remainder from division
        if ($query_numrows % $max_rows_per_page) {
            $num_pages++;
        } // has remainder so add one page

        $pages_array = [];
        for ($i=1; $i<=$num_pages; $i++) {
            $pages_array[] = ['id' => $i, 'text' => $i];
        }

        $php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
        if ($num_pages > 1) {
            $display_links = oos_draw_form('id', 'pages', basename($php_self), '', 'get', false);

            if ($current_page_number > 1) {
                $display_links .= '<a href="' . oos_href_link_admin(basename($php_self), $parameters . $page_name . '=' . ($current_page_number - 1)) . '" class="splitPageLink">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';
            } else {
                $display_links .= PREVNEXT_BUTTON_PREV . '&nbsp;&nbsp;';
            }

            $display_links .= sprintf(TEXT_RESULT_PAGE, oos_draw_pull_down_menu($page_name, '', $pages_array, '', 'onChange="this.form.submit();"'), $num_pages);

            if (($current_page_number < $num_pages) && ($num_pages != 1)) {
                $display_links .= '&nbsp;&nbsp;<a href="' . oos_href_link_admin(basename($php_self), $parameters . $page_name . '=' . ($current_page_number + 1)) . '" class="splitPageLink">' . PREVNEXT_BUTTON_NEXT . '</a>';
            } else {
                $display_links .= '&nbsp;&nbsp;' . PREVNEXT_BUTTON_NEXT;
            }

            if ($parameters != '') {
                if (str_ends_with((string) $parameters, '&')) {
                    $parameters = substr((string) $parameters, 0, -1);
                }
                $pairs = explode('&', (string) $parameters);
                foreach ($pairs as $pair) {
                    [$key, $value] = explode('=', $pair);
                    $display_links .= oos_draw_hidden_field(rawurldecode($key), rawurldecode($value));
                }
            }

            if (SID) {
                $display_links .= oos_draw_hidden_field($session->getName(), $session->getId());
            }

            $display_links .= '</form>';
        } else {
            $display_links = sprintf(TEXT_RESULT_PAGE, $num_pages, $num_pages);
        }

        return $display_links;
    }


    public function display_count($query_numrows, $max_rows_per_page, $current_page_number, $text_output)
    {
        if (empty($current_page_number)) {
            $current_page_number = 1;
        }
        $current_page_number = (int)$current_page_number;

        if ($max_rows_per_page == 0) {
            $max_rows_per_page = 20;
        }
        if ($max_rows_per_page == '') {
            $max_rows_per_page = $query_numrows;
        }

        $to_num = ($max_rows_per_page * $current_page_number);
        if ($to_num > $query_numrows) {
            $to_num = $query_numrows;
        }
        $from_num = ($max_rows_per_page * ($current_page_number - 1));
        if ($to_num == 0) {
            $from_num = 0;
        } else {
            $from_num++;
        }

        return sprintf($text_output, $from_num, $to_num, $query_numrows);
    }
}
