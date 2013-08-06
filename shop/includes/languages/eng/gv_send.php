<?php
/* ----------------------------------------------------------------------
   $Id: gv_send.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_send.php,v 1.1.2.1 2003/04/18 17:25:44 wilt 
   ----------------------------------------------------------------------
   The Exchange Project - Community Made Shopping!
   http://www.theexchangeproject.org

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['heading_title'] = 'Send Gift Voucher';
$aLang['navbar_title'] = 'Send Gift Voucher';
$aLang['email_subject'] = 'Enquiry from ' . STORE_NAME;
$aLang['heading_text'] = '<br />Please enter below the details of the Gift Voucher you wish to send. For more information, please see our <a href="' . oos_href_link($aContents['gv_faq']).'">'.GV_FAQ.'.</a><br />';
$aLang['entry_name'] = 'Recipients Name:';
$aLang['entry_email'] = 'Recipients E-Mail Address:';
$aLang['entry_message'] = 'Message to Recipients:';
$aLang['entry_amount'] = 'Amount of Gift Voucher:';
$aLang['error_entry_amount_check'] = '&nbsp;&nbsp;<span class="errorText">Invalid Amount</span>';
$aLang['error_entry_email_address_check'] = '&nbsp;&nbsp;<span class="errorText">Invalid Email Address</span>';
$aLang['main_message'] = 'You have decided to post a gift voucher worth %s to %s who\'s email address is %s<br /><br />The text accompanying the email will read<br /><br />Dear %s<br /><br />
                        You have been sent a Gift Voucher worth %s by %s';

$aLang['personal_message'] = '%s says';
$aLang['text_success'] = 'Congratulations, your Gift Voucher has successfully been sent';


$aLang['email_separator'] = '----------------------------------------------------------------------------------------';
$aLang['email_gv_text_header'] = 'Congratulations, You have received a gift voucher worth %s';
$aLang['email_gv_text_subject'] = 'A gift from %s';
$aLang['email_gv_from'] = 'This Gift Voucher has been sent to you by %s';
$aLang['email_gv_message'] = 'With a message saying ';
$aLang['email_gv_send_to'] = 'Hi, %s';
$aLang['email_gv_redeem'] = 'To redeem this Gift Voucher, please click on the link below. Please also write down the redemption code which is %s. In case you have problems.';
$aLang['email_gv_link'] = 'To redeem please click ';
$aLang['email_gv_visit'] = ' or visit ';
$aLang['email_gv_enter'] = ' and enter the code ';
$aLang['email_gv_fixed_footer'] = 'If you are have problems redeeming the Gift Voucher using the automated link above, ' . "\n" . 
                                'you can also enter the Gift Voucher code during the checkout process at our store.' . "\n\n";

$aLang['email_gv_shop_footer'] = '';

