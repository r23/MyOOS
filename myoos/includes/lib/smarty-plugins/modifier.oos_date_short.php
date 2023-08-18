<?php
/**
   ----------------------------------------------------------------------
   $Id: modifier.oos_date_short.php,v 1.1 2007/06/08 13:34:16 r23 Exp $

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
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */

/**
 * Smarty oos_date_long modifier plugin
 *
 * Type:     modifier<br>
 * Name:     oos_date_short<br>
 * Version:  0.1
 * Date:     September 12, 2003
 * Install:  Drop into the plugin directory
 *
 * Examples: {$raw_date|oos_date_short}
 * Author:   r23 <info at r23 dot de>
 * -------------------------------------------------------------
 */

function smarty_modifier_oos_date_short($raw_date)
{
    if (($raw_date == '0000-00-00 00:00:00') || ($raw_date == '')) {
        return false;
    }

    $year = substr((string) $raw_date, 0, 4);
    $month = (int)substr((string) $raw_date, 5, 2);
    $day = (int)substr((string) $raw_date, 8, 2);
    $hour = (int)substr((string) $raw_date, 11, 2);
    $minute = (int)substr((string) $raw_date, 14, 2);
    $second = (int)substr((string) $raw_date, 17, 2);

    if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
        return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
        return preg_match('/2037' . '$/', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    }
}
