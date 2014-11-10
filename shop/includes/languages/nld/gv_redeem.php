<?php
/* ----------------------------------------------------------------------
   $Id: gv_redeem.php,v 1.3 2007/06/12 17:09:44 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_redeem.php,v 1.1.2.1 2003/05/15 23:04:32 wilt
   ----------------------------------------------------------------------
   The Exchange Project - Community Made Shopping!
   http://www.theexchangeproject.org

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title'] = 'Tegoedbon gebruiken';
$aLang['heading_title'] = 'Tegoedbon gebruiken';
$aLang['text_information'] = 'Voor verdere informatie over tegoedbonnen lees a.u.b. onze <a href="' . oos_href_link($aModules['gv'], $aFilename['gv_faq']).'">'.GV_FAQ.'.</a>';
$aLang['text_invalid_gv'] = 'De tegoedboncode kan ongeldig zijn of is al gebruikt. Zijn er nog vragen, richt u dan aan onze <a href="' . oos_href_link($aModules['main'], $aFilename['contact_us']) . '">winkel via onze contactpagina</a>.';
$aLang['text_valid_gv'] = 'Gefeliciteerd, u heeft een tegoedbon ter waarde van %s ingewisseld.';
?>
