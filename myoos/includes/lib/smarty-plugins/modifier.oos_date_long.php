<?php
/**
   ----------------------------------------------------------------------
   $Id: modifier.oos_date_long.php,v 1.1 2007/06/08 13:34:16 r23 Exp $

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
    if (($raw_date == '0000-00-00 00:00:00') || ($raw_date == '')) {
        return false;
    }

    $locale = THE_LOCALE;
    $dateType = IntlDateFormatter::FULL;//type of date formatting
    $timeType = IntlDateFormatter::NONE;//type of time formatting setting to none, will give you date itself
    $formatter =new IntlDateFormatter($locale, $dateType, $timeType);
    $dateTime = new DateTime($raw_date);

    return $formatter->format($dateTime);
}
