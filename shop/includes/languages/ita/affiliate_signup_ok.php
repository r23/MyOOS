<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_signup_ok.php,v 1.3 2007/06/12 17:25:13 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_signup_ok.php,v 1.3 2003/02/14 17:39:39 harley_vb 
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

$aLang['navbar_title'] = 'Affiliate Signup';
$aLang['heading_title'] = 'Congratulations!';
$aLang['text_main'] = 'Congratulations! Your new Affiliate account application has been submitted! You will shortly receive an email containing important information regarding your Affiliate Account, including you affiliate login details. If you have not received it within the hour, please <a href="' . oos_href_link($aModules['affiliate'], $aFilename['affiliate_contact']) . '">contact us</a>.<br /><br />If you have <small><b>ANY</b></small> questions about the affiliate program, please <a href="' . oos_href_link($aModules['affiliate'], $aFilename['affiliate_contact']) . '">contact us</a>.';

?>
