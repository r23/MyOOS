<?php
/* ----------------------------------------------------------------------
   $Id: gv_send.php,v 1.3 2007/06/12 17:03:32 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2005 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_send.php,v 1.1.2.1 2003/05/15 23:04:32 wilt
   ----------------------------------------------------------------------
   The Exchange Project - Community Made Shopping!
   http://www.theexchangeproject.org

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['heading_title'] = '��������;
$aLang['navbar_title'] = '���� ���;
$aLang['email_subject'] = '���' . STORE_NAME;
$aLang['heading_text'] = '<br />�� � ��� ��� �������������� �����' . STORE_NAME . '.<br />����� �e-Mail ��������������� � ��� ���� ����� ����������
��� � ��� ���� �����<b><tt>���/tt></b> ���� ����� �����<a href="' . oos_href_link($aModules['gv'], $aFilename['gv_faq']).'">���FAQ</a><br />';
$aLang['entry_name'] = '� �����:';
$aLang['entry_email'] = 'E-Mail �����:';
$aLang['entry_message'] = '������ (��������������:';
$aLang['entry_amount'] = '���� (���:';
$aLang['error_entry_amount_check'] = '&nbsp;&nbsp;<span class="errorText">������ ���/span>';
$aLang['error_entry_email_address_check'] = '&nbsp;&nbsp;<span class="errorText"> ������E-Mail ���/span>';
$aLang['main_message'] = '� ��� ��� ������� %s �: %s  E-Mail-��� %s .<br /><br />���� ��������� ����: <br /><br />������ %s<br /><br /> ����� ������� %s � %s.';

$aLang['personal_message'] = '%s �� �;
$aLang['text_success'] = '����� � ������ �����';

$aLang['email_separator'] = '----------------------------------------------------------------------------------------';
$aLang['email_gv_text_header'] = '����� � ���� ������� %s';
$aLang['email_gv_text_subject'] = '������ %s';
$aLang['email_gv_from'] = '�� ������ ��� %s';
$aLang['email_gv_message'] = '� ���� �����';
$aLang['email_gv_send_to'] = '������, %s';
$aLang['email_gv_redeem'] = '�� ������ ����� ����� �� ��� ����� ���������: % s . ���� ������ ����.';
$aLang['email_gv_link'] = '��� ������ ����� ������';
$aLang['email_gv_visit'] = ' ������ ';
$aLang['email_gv_enter'] = ' ���������� ';
$aLang['email_gv_fixed_footer'] = '�� ������� ���� �������� ���, ' . "\n" . 
                                ' � � ��� ��� ����� ������ ����������������' . "\n\n";
$aLang['email_gv_shop_footer'] = '';
?>