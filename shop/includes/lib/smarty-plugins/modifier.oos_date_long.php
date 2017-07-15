<?php
/* ----------------------------------------------------------------------
   $Id: modifier.oos_date_long.php,v 1.1 2007/06/08 13:34:16 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.212 2003/02/17 07:55:54 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
 
/**
 * Smarty oos_date_long modifier plugin
 *
 * Type:     modifier<br>
 * Name:     oos_date_long<br>
 * Version:  0.1
 * Date:     September 12, 2003
 * Install:  Drop into the plugin directory
 *
 * Examples: {$raw_date|oos_date_long}
 * Author:   r23 <info at r23 dot de>
 * -------------------------------------------------------------
 */
 
function smarty_modifier_oos_date_long($raw_date)
{
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return FALSE;

    $year = (int)substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    return strftime(DATE_FORMAT_LONG, mktime($hour,$minute,$second,$month,$day,$year));
}

?>
