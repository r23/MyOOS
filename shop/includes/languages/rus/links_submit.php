<?php
/* ----------------------------------------------------------------------
   $Id: links_submit.php,v 1.3 2007/06/12 17:03:32 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: links_submit.php,v 1.00 2003/10/03 
   ----------------------------------------------------------------------
   Links Manager
   
   Contribution based on:
   
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title_1'] = '���;
$aLang['navbar_title_2'] = '���� ���;

$aLang['heading_title'] = '����� ����;

$aLang['text_main'] = '������� �� � ����� ����� ��� ���';

$aLang['email_subject'] = '�������� ��������' . STORE_NAME . '.';
$aLang['email_greet_none'] = '�����-�) %s' . "\n\n";
$aLang['email_welcome'] = '�������� ��������<b>' . STORE_NAME . '</b>.' . "\n\n";
$aLang['email_text'] = '�������� �������������' . STORE_NAME . '. It will be added to our listing as soon as we approve it. You will receive an email about the status of your submittal. If you have not received it within the next 48 hours, please contact us before submitting your link again.' . "\n\n";
$aLang['email_contact'] = '� ���� ��� ���� ����� �������� ���� � email: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n";

$aLang['email_warning'] = '<b>����:</b> �� email �������� ����� ����� ��� �� � � ���� ��� ���� ���� ���� ���� � email: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n";
$aLang['email_owner_subject'] = '����������������' . STORE_NAME;
$aLang['email_owner_text'] = '�� ����������������' . STORE_NAME . '. ��� � ������. ���� �����������' . "\n\n";


$aLang['text_links_help_link'] = '&nbsp;��&nbsp;[?]';

$aLang['heading_links_help'] = '���- ��';
$aLang['text_links_help'] = '<b>���� ���</b> ���� ������<br><br><b>URL:</b> ������������ ��� \'http://\'.<br><br><b>���:</b> ���� ���� ��� � ������<br><br><b>����:</b> �������� ������<br><br><b>URL ����</b> ���������� �� � ��� ���� ������ ��� \'http://\'.<br>����: http://your-domain.ru/path/to/your/image.gif <br><br><b>����� ��:</b> ����� �.<br><br><b>Email:</b> � email ��� ����������email ���<br><br><b>URL ���� ����� ���:</b> ��� �������, � ����� ����� ���� � ��.<br>����: http://your-domain.ru/path/to/your/links_page.php';
$aLang['text_close_window'] = '<u>��� ��</u> [x]';

// VJ todo - move to common language file
$aLang['category_website'] = '����� ����;
$aLang['category_reciprocal'] = '����� ����� ����� ���';

$aLang['entry_links_title'] = '���� ���';
$aLang['entry_links_title_error'] = '�� \'���� ���' ��� ���� � ���' . ENTRY_LINKS_TITLE_MIN_LENGTH . ' ����.';
$aLang['entry_links_title_text'] = '*';
$aLang['entry_links_url'] = 'URL:';
$aLang['entry_links_url_error'] = '�� \'URL\' ��� ���� � ���' . ENTRY_LINKS_URL_MIN_LENGTH . ' ����.';
$aLang['entry_links_url_text'] = '*';
$aLang['entry_links_category'] = '���:';
$aLang['entry_links_category_text'] = '*';
$aLang['entry_links_description'] = '����:';
$aLang['entry_links_description_error'] = '�� \'����\' ��� ���� � ���' . ENTRY_LINKS_DESCRIPTION_MIN_LENGTH . ' ����.';
$aLang['entry_links_description_text'] = '*';
$aLang['entry_links_image'] = 'URL ����';
$aLang['entry_links_image_text'] = '';
$aLang['entry_links_contact_name'] = '����� ��:';
$aLang['entry_links_contact_name_error'] = '�� \'����� ��\' ��� ���� � ���' . ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH . ' ����.';
$aLang['entry_links_contact_name_text'] = '*';
$aLang['entry_links_reciprocal_url'] = 'URL ���� ����� ���:';

$aLang['entry_links_reciprocal_url_error'] = '�� \'URL ���� ����� ���\' ��� ���� � ���' . ENTRY_LINKS_URL_MIN_LENGTH . ' ����.';
$aLang['entry_links_reciprocal_url_text'] = '*';
?>
