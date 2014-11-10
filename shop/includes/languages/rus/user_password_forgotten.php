<?php
/* ----------------------------------------------------------------------
   $Id: user_password_forgotten.php,v 1.3 2007/06/12 17:03:33 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: password_forgotten.php,v 1.6 2002/11/19 01:48:08 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title_1'] = '��';
$aLang['navbar_title_2'] = '������� ���';
$aLang['heading_title'] = '���������!';
$aLang['text_no_email_address_found'] = '<font color="#ff0000"><b>NOTE:</b></font> ������ ������� E-Mail ����� ���, ����� � ��';
$aLang['email_password_reminder_subject'] = STORE_NAME . ' - ������';
$aLang['email_password_reminder_body'] = '������ ������ �' . oos_server_get_remote() . '.' . "\n\n" . '� ������ �\'' . STORE_NAME . '\':' . "\n\n" . '   %s' . "\n\n";
$aLang['text_password_sent'] = '������ ��� � � Email';
?>
