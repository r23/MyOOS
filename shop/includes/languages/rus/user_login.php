<?php
/* ----------------------------------------------------------------------
   $Id: user_login.php,v 1.3 2007/06/12 17:03:33 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: login.php,v 1.11 2002/11/12 00:45:21 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

if (isset($_GET['origin']) && ($_GET['origin'] == $aFilename['checkout_payment'])) {
  $aLang['navbar_title'] = '���;
  $aLang['heading_title'] = '���� ���� - �� ���.';
} else {
  $aLang['navbar_title'] = '��';
  $aLang['heading_title'] = '��������, ����;
}

$aLang['heading_new_customer'] = '��������';
$aLang['text_new_customer'] = '���������.';
$aLang['text_new_customer_introduction'] = '����������' . STORE_NAME . ' � ������������ ���� ���� ����� � ����� ������ ��������� �������.';

$aLang['heading_returning_customer'] = '���� �������;
$aLang['text_returning_customer'] = '������� �����������.';

$aLang['entry_remember_me'] = '���� ��<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:win_autologon(\'' . oos_href_link($aModules['main'], $aFilename['info_autologon']) . '\');"><b><u>�����!</u></b></a>';
$aLang['text_password_forgotten'] = '��� ���? Click here.';


$aLang['text_login_error'] = '<font color="#ff0000"><b>���:</b></font> ������\'E-Mail ���' ���\'���\'.';
$aLang['text_visitors_cart'] = '<font color="#ff0000"><b>����:</b></font> ����� �� &quot;���&quot;, �������� ��� �����������&quot;����� ����quot; ����� � ���. <a href="javascript:session_win(\'' . oos_href_link($aModules['main'], $aFilename['info_shopping_cart']) . '\');">[�����</a>';
?>
