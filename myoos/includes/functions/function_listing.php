<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.212 2003/02/17 07:55:54 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * listing
 *
 * @link    https://www.oos-shop.de
 * @package listing
 * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/12 16:49:27 $
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

/**
 * Return table heading with sorting capabilities
 *
 * @param  $sortby
 * @param  $colnum,
 * @param  $heading
 * @return string
 */
function oos_create_sort_heading($sortby, $colnum, $heading)
{
    global $sContent, $aLang;

    $sort_prefix = '';
    $sort_suffix = '';

    if ($sortby) {
        $sort_prefix = '<a href="' . oos_href_link($sContent, oos_get_all_get_parameters(['page', 'info', 'sort']) . 'page=1&amp;sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a')) . '" title="' . $aLang['text_sort_products'] . ($sortby == $colnum . 'd' || substr((string) $sortby, 0, 1) != $colnum ? $aLang['text_ascendingly'] : $aLang['text_descendingly']) . $aLang['text_by'] . $heading . '">' ;
        $sort_suffix = (substr((string) $sortby, 0, 1) == $colnum ? (substr((string) $sortby, 1, 1) == 'a' ? '+' : '-') : '') . '</a>';
    }

    return $sort_prefix . $heading . $sort_suffix;
}
