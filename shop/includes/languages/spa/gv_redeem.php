<?php
/* ----------------------------------------------------------------------
   $Id: gv_redeem.php,v 1.1 2007/06/13 15:54:25 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_redeem.php,v 1.1.2.1 2003/05/15 23:02:28 wilt 
   ----------------------------------------------------------------------
   The Exchange Project - Community Made Shopping!
   http://www.theexchangeproject.org

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title'] = 'Redeem Gift Voucher';
$aLang['heading_title'] = 'Redeem Gift Voucher';
$aLang['text_information'] = 'For more information regarding Gifr Vouchers, please see our <a href="' . oos_href_link($aModules['gv'], $aFilename['gv_faq']).'">'.GV_FAQ.'.</a>';
$aLang['text_invalid_gv'] = 'The Gift Voucher number may be invalid or has already been redeemed. To contact the shop <a href="' . oos_href_link($aModules['main'], $aFilename['contact_us']) . '">owner please use the Contact Page</a>';
$aLang['text_valid_gv'] = 'Congratulations, you have redeemed a Gift Voucher worth %s';
?>
