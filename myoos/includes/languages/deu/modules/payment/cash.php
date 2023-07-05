<?php
/**
   ----------------------------------------------------------------------
   $Id: cash.php,v 1.5 2008/08/25 14:28:07 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: cash.php,v 1.01 2003/02/19 01:53:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Copyright (C) 2001 - 2003 TheMedia, Dipl.-Ing Thomas Pl�nkers
       http://www.themedia.at & http://www.oscommerce.at

                    All rights reserved.

   This program is free software licensed under the GNU General Public License (GPL).

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
   USA
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_PAYMENT_CASH_STATUS_TITLE', 'Barzahlungsmodul aktivieren');
define('MODULE_PAYMENT_CASH_STATUS_DESC', 'Möchten Sie Zahlungen per Barzahlung akzeptieren?');

define('MODULE_PAYMENT_CASH_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_CASH_ZONE_DESC', 'Wenn eine Zone ausgewählt ist, gilt die Zahlungsmethode nur für diese Zone.');

define('MODULE_PAYMENT_CASH_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_CASH_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_CASH_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_CASH_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');


$aLang['module_payment_cash_text_description'] = 'Barzahlung';
$aLang['module_payment_cash_text_title'] = 'Barzahlung';
